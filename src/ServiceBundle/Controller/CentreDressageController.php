<?php

namespace ServiceBundle\Controller;

use ServiceBundle\Entity\CentreDressage;
use ServiceBundle\Form\CentreDressageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CentreDressageController extends Controller
{
    public function ajouterCentreDressageAction(Request $request)
    {
        $centreD = new CentreDressage();
        $form=$this->createForm(CentreDressageType::class,$centreD);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($centreD);
            $em->flush();
            return $this->redirectToRoute("afficher_centre_dressage");
        }
        return $this->render('ServiceBundle:CentreDressage:ajouter_centre_dressage.html.twig', array(
            "form"=>$form->createView()
        ));
    }

    public function afficherCentreDressageAction()
    {
        $em= $this->getDoctrine()->getManager();
        $centresD=$em->getRepository("ServiceBundle:CentreDressage")->findAll();
        return $this->render('ServiceBundle:CentreDressage:afficher_centre_dressage.html.twig', array(
            "centresD"=>$centresD
        ));
    }

    public function modifierCentreDressageAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $centreD = $em->getRepository(CentreDressage::class)->find($id);

        if ($request->isMethod('POST'))
        {
            $centreD->setLibelle($request->get('libelle'));
            $centreD->setAdresse($request->get('adresse'));
            $centreD->setTel($request->get('tel'));
            $centreD->setDescription($request->get('description'));
            $em->persist($centreD);
            $em->flush();
            return $this->redirectToRoute('afficher_centre_dressage');
        }
        return $this->render('ServiceBundle:CentreDressage:modifier_centre_dressage.html.twig', array(
            'centreD' => $centreD));
        }

    public function supprimerCentreDressageAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $centreD= $em->getRepository(CentreDressage::class)->find($id);
        $em->remove($centreD);
        $em->flush();
        return $this->redirectToRoute("afficher_centre_dressage");
    }

    public function centreDressagePageAction()
    {
        $em= $this->getDoctrine()->getManager();
        $centresD=$em->getRepository("ServiceBundle:CentreDressage")->findAll();
        return $this->render('ServiceBundle:CentreDressage:centreDressage.html.twig', array(
            "centresD"=>$centresD
        ));
    }

    public function centreDressageBackPageAction()
    {
        return $this->render('ServiceBundle:CentreDressage:centreDressage.html.twig');
    }

    public function afficher_single_centreDressageAction($id)
    {
        $em= $this->getDoctrine()->getManager();
        $centreD=$em->getRepository("ServiceBundle:CentreDressage")->find($id);
        return $this->render('ServiceBundle:CentreDressage:single_centreDressage.html.twig', array(
            "centreD"=>$centreD
        ));
    }
    public function rechercheAjaxCentreAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $mot=$request->get('mot');
            $em=$this->getDoctrine();
            $centreD=$em->getRepository("ServiceBundle:CentreDressage")->rechercheAjax($mot);
            //etape 1: initialiser le serializer
            $serializer=new Serializer(array(new ObjectNormalizer()));
            //etape 2 : transformation liste des objets
            $data=$serializer->normalize($centreD);
            //etape 3 : encodage format JSON
            return new JsonResponse($data);
        }
        return $this->render('ServiceBundle:CentreDressage:afficher_centre_dressage.html.twig', array(

        ));
    }

        public function pdfAction()
        {
            $em= $this->getDoctrine()->getManager();
            $centresD=$em->getRepository("ServiceBundle:CentreDressage")->findAll();
            $snappy = $this->get('knp_snappy.pdf');

            $html = $this->renderView('ServiceBundle:CentreDressage:pdf.html.twig', array(
                "centresD"=>$centresD            ));

            $filename = 'myFirstSnappyPDF';

            return new Response(
                $snappy->getOutputFromHtml($html),
                200,
                array(
                    'Content-Type'          => 'application/pdf',
                    'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
                )
            );
        }
}



