<?php

namespace eventsBundle\Controller;

use eventsBundle\Entity\conseils;

use eventsBundle\Form\conseilsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class conseilsController extends Controller
{

    public function createAction(Request $request)
    {

        $conseils= new conseils();
        $form=$this->createForm(conseilsType::class,$conseils);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($conseils);
            $em->flush();
            return $this->redirectToRoute("readconseils");
        }
        return $this->render('eventsBundle:Default:createconseils.html.twig', array(
            "form"=>$form->createView()
        ));




    }



    public function readAction()
    {
        $em= $this->getDoctrine()->getManager();
        $conseils=$em->getRepository("eventsBundle:conseils")->findAll();
        return $this->render('eventsBundle:Default:readconseils.html.twig', array(
            "conseils"=>$conseils

        ));
    }


    public function readfrontconseilAction()
    {
        $em= $this->getDoctrine()->getManager();
        $conseils=$em->getRepository("eventsBundle:conseils")->findAll();
        return $this->render('eventsBundle:Default:readfrontconseil.html.twig', array(
            "conseils"=>$conseils

        ));
    }

    public function deleteAction($id)
    {
        $em= $this->getDoctrine()->getManager();
        $conseil=$em->getRepository("eventsBundle:conseils")->find($id);
        $em->remove($conseil);
        $em->flush();
        return $this->redirectToRoute("readconseils");
    }


    public function updateeAction ($id,Request $request)
    {
        $em= $this->getDoctrine()->getManager();
        $conseil=$em->getRepository("eventsBundle:conseils")->find($id);
        $form=$this->createForm(conseilsType::class,$conseil);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute("readconseilsfront");
        }
        return $this->render('eventsBundle:Default:updateconseil.html.twig', array(
            "form"=>$form->createView()

        ));
    }

}
