<?php

namespace AdoptionBundle\Controller;

use AdoptionBundle\Entity\Refuge;
use AdoptionBundle\Form\RefugeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class RefugeController extends Controller
{
    public function ajouterAction(Request $request)
    {
        $refuge = new Refuge();
        $form=$this->createForm(RefugeType::class,$refuge);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($refuge);
            $em->flush();
            return $this->redirectToRoute("afficher_refuge");
        }
        return $this->render('AdoptionBundle:Refuge:ajouter.html.twig', array(
            "form"=>$form->createView()
        ));
    }

    public function supprimerAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $refuge= $em->getRepository(refuge::class)->find($id);
        $em->remove($refuge);
        $em->flush();
        return $this->redirectToRoute("afficher_refuge");
    }

    public function modifierAction($id,Request $request)
    {
        $em= $this->getDoctrine()->getManager();
        $refuge=$em->getRepository("AdoptionBundle:Refuge")->find($id);
        $form=$this->createForm(RefugeType::class,$refuge);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute("afficher_refuge");
        }
        return $this->render('@Adoption/Refuge/ajouter.html.twig', array(
            "form"=>$form->createView()

        ));
    }

    public function afficherAction()
    {
        $em= $this->getDoctrine()->getManager();
        $refuges=$em->getRepository("AdoptionBundle:Refuge")->findAll();
        return $this->render('AdoptionBundle:Refuge:afficher.html.twig', array(
            "refuges"=>$refuges

        ));
    }
    public function rechercherAction(Request $r)
    {
        $refuge = new Refuge();
        $refuges=[];
        $form=$this->createFormBuilder($refuge)
            ->add('libelle')
            ->add('recherche',SubmitType::class)
            ->getForm();


        $form->handleRequest($r);
        if($form->isValid()){
            $nom=$refuge->getLibelle();
            $em=$this->getDoctrine();
            $refuges=$em->getRepository("AdoptionBundle:Refuge")->rechercheRefuge($nom);
        }

        return $this->render('AdoptionBundle:Refuge:recherche.html.twig', array(
            "form"=>$form->createView(),'refuges'=>$refuges

        ));
    }
    public function afficherRefugeDetailAction($id)
    {
        $em= $this->getDoctrine()->getManager();
        $refuges=$em->getRepository("AdoptionBundle:Refuge")->rechercheById($id);
        return $this->render('AdoptionBundle:Animal:refuge_detail.html.twig',array('refuges'=>$refuges));
    }
}
