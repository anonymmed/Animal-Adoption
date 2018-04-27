<?php

namespace soinBundle\Controller;

use soinBundle\Form\centreToilettageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use soinBundle\Entity\centreToilettage;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class centreToilettageController extends Controller
{
    public function ajouterCentreToilettageAction(Request $request)
    {
        $centreT = new centreToilettage();
        $form = $this->createForm(centreToilettageType::class, $centreT);
        $form->handleRequest($request);
        if ($form->isValid()) {

            // $file stores the uploaded PDF file

            $file = $centreT->getImage();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move("telechargements/", $fileName);

            $centreT->setImage("telechargements/" . $fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($centreT);
            $em->flush();
            return $this->redirectToRoute("afficher_centre_toilettage");
        }

        return $this->render('soinBundle:centreToilettage:ajouter_centre_toilettage.html.twig', array(
            "form" => $form->createView()
        ));
    }

        public function modifierCentreToilettageAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $centreT = $em->getRepository("soinBundle:centreToilettage")->find($id);


        $file = new File($centreT->getImage());

        $centreT->setImage($file);

        $form = $this->createForm(centreToilettageType::class, $centreT);

        $form->handleRequest($request);
        if (($form->isValid()) && ($form->isSubmitted())) {

            $file = $centreT->getImage();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move("telechargements/", $fileName);

            $centreT->setImage("telechargements/" . $fileName);
            $em->flush();
            return $this->redirectToRoute('afficher_centre_toilettage');
        }
        return $this->render('soinBundle:centreToilettage:modifier_centre_toilettage.html.twig', array
        ('form' => $form->createView(),"centreT" => $centreT));
    }

    public function supprimerCentreToilettageAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $centreT = $em->getRepository(CentreToilettage::class)->find($id);
        $em->remove($centreT);
        $em->flush();
        return $this->redirectToRoute("afficher_centre_toilettage");
    }

    public function afficherCentreToilettageAction()
    {

        $em = $this->getDoctrine()->getManager();
        $centresT = $em->getRepository("soinBundle:centreToilettage")->findAll();
        return $this->render('soinBundle:CentreToilettage:afficher_centre_Toilettage.html.twig', array(
            "centresT" => $centresT
        ));
    }
    public function  info_centreAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $centreT=$em->getRepository('soinBundle:centreToilettage')->find($id);
        return $this->render('soinBundle:centreToilettage:info_centre.html.twig' , array(
            "centreT"=>$centreT
        ));
    }


    public function  info_VeterinaireAction()
    {

    }

    public function centreToilettagePageAction(Request $request)
    {
        $em= $this->getDoctrine()->getManager();
        $centresT=$em->getRepository("soinBundle:centreToilettage")->findAll();

        $paginator = $this->get('knp_paginator');
        $result= $paginator->paginate(
            $centresT,
            $request->query->getInt('page',1),2);


        return $this->render('soinBundle:centreToilettage:centreToilettage.html.twig', array(
            "centresT"=>$result
        ));
    }

    public function searchAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $mot=$request->get('mot');
            $em=$this->getDoctrine();
            $centreD=$em->getRepository("soinBundle:centreToilettage")->rechercheAjax($mot);
            //etape 1: initialiser le serializer
            $serializer=new Serializer(array(new ObjectNormalizer()));
            //etape 2 : transformation liste des objets
            $data=$serializer->normalize($centreD);
            //etape 3 : encodage format JSON
            return new JsonResponse($data);
        }
        return $this->render('soinBundle:centreToilettage:afficher_centre_toilettage.html.twig', array(

        ));
    }









}
