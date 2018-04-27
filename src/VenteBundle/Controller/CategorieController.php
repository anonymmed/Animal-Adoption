<?php

namespace VenteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use VenteBundle\Entity\Categorie;
use VenteBundle\Form\CategorieType;

class CategorieController extends Controller
{
    public function ajoutercategorieAction(Request $request)
    {
        $categorie = new Categorie();
        $form=$this->createForm(CategorieType::class,$categorie);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('afficher_categorie');
        }
        return $this->render('@Vente/Categorie/ajouter_categorie.html.twig', array(
            "form"=>$form->createView()
        ));
    }

    public function modifiercategorieAction(Request $request,$id)
    {

        $em=$this->getDoctrine()->getManager();
        $categorie=$em->getRepository("VenteBundle:Categorie")->find($id);
        $form=$this->createForm(CategorieType::class,$categorie);
        $form->handleRequest($request);
        if(($form->isValid())&&($form->isSubmitted()))
        {
            $em->flush();
            return $this->redirectToRoute('afficher_categorie');
        }
        return $this->render('@Vente/Categorie/ajouter_categorie.html.twig', array(
            "form"=>$form->createView()
        ));
    }

    public function supprimerCategorieAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $categorie= $em->getRepository(Categorie::class)->find($id);
        $em->remove($categorie);
        $em->flush();
        return $this->redirectToRoute('afficher_categorie');
    }

    public function afficherCategorieAction()
    {
        $em= $this->getDoctrine()->getManager();
        $categories=$em->getRepository("VenteBundle:Categorie")->affichageCategorie();
        return $this->render('@Vente/Categorie/afficher_categories.html.twig', array(
            "categories"=>$categories
        ));
    }

}
