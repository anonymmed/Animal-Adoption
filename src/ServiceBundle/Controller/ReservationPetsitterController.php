<?php

namespace ServiceBundle\Controller;

use ServiceBundle\Entity\ReservationPetsitter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ServiceBundle\Repository\ReservationPetsitterRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ReservationPetsitterController extends Controller
{
    public function reserverPetsitterAction(Request $request)
    {

        $reservationP = new ReservationPetsitter();
        $em = $this->getDoctrine()->getManager();
        $prix=$request->get('prix');
        $dateD=new \DateTime($request->get('dateD'));
        $dateF=new \DateTime($request->get('dateF'));

        $pet_sitter = $em->getRepository("UserBundle:User")->find($request->get('idP'));
        $user = $em->getRepository("UserBundle:User")->find($request->get('idU'));

        $reservationP->setIdPetsitter($pet_sitter);
        $reservationP->setIdUser($user);
        $reservationP->setDateDebut($dateD);
        $reservationP->setDateFin($dateF);
        $reservationP->setPrix($prix);
        $reservationP->setEncaisser(0.2*$prix);
        $dated=$reservationP->getDateDebut();
        $em = $this->getDoctrine()->getManager();
        $rslt=$em->getRepository('ServiceBundle:ReservationPetsitter')->existance($dated,$pet_sitter->getId());
        if ($rslt == NULL )
        {
            $em->persist($reservationP);
            $em->flush();
        }
        else
        {
            $this->addFlash("error", "cette date est deja prise , veuillez choisir une autre ");
        }

        return $this->redirectToRoute('petsitterPage');
    }

    public function afficherReservationAction($idU)
    {
        $em= $this->getDoctrine()->getManager();
        $reservations=$em->getRepository("ServiceBundle:ReservationPetsitter")->afficherReservation($idU);
        return $this->render('ServiceBundle:ReservationPetsitter:mesReservations_petsitter.html.twig', array(
            "reservations"=>$reservations
        ));
    }

    public function afficherBackAction()
    {
        $em= $this->getDoctrine()->getManager();
        $reservations=$em->getRepository("ServiceBundle:ReservationPetsitter")->findAll();
        return $this->render('ServiceBundle:ReservationPetsitter:ReservationBack.html.twig', array(
            "reservations"=>$reservations
        ));
    }
    public function annulerReservationAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $centreD= $em->getRepository(ReservationPetsitter::class)->find($id);
        $em->remove($centreD);
        $em->flush();
        return $this->redirectToRoute("home");
    }
    public function petsitterPageAction()
    {
        $em= $this->getDoctrine()->getManager();
        $petsitters=$em->getRepository("ServiceBundle:ReservationPetsitter")->Petsitter();
        return $this->render('ServiceBundle:ReservationPetsitter:petsitter.html.twig', array(
            "petsitters"=>$petsitters
        ));
    }

    public function petsitterBackPageAction()
    {
        $em= $this->getDoctrine()->getManager();
        $petsitters=$em->getRepository("UserBundle:User")->afficherPetsitter();
        return $this->render('ServiceBundle:ReservationPetsitter:petsitter.html.twig', array(
            "petsitters"=>$petsitters
        ));
    }

    public function afficher_single_petsitterAction($id)
    {
        $em= $this->getDoctrine()->getManager();
        $petsitter=$em->getRepository("UserBundle:User")->find($id);
        return $this->render('ServiceBundle:ReservationPetsitter:single_petsitter.html.twig', array(
            "petsitter"=>$petsitter
        ));
    }

    public function argent_encaisserAction()
    {
        $em= $this->getDoctrine()->getManager();
        $apports=$em->getRepository("ServiceBundle:ReservationPetsitter")->apportPetsitter();
        $total=$em->getRepository("ServiceBundle:ReservationPetsitter")->TotalPetsitter();
        return $this->render('ServiceBundle:ReservationPetsitter:apportPetsitter.html.twig', array(
            "apports"=>$apports,"total"=>$total
        ));
    }


    public function rechercheAjaxReservationAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $mot=$request->get('mot');
            $em=$this->getDoctrine();
            $reservation=$em->getRepository("ServiceBundle:ReservationPetsitter")->rechercheAjax($mot);
            //etape 1: initialiser le serializer
            $serializer=new Serializer(array(new ObjectNormalizer()));
            //etape 2 : transformation liste des objets
            $data=$serializer->normalize($reservation);
            //etape 3 : encodage format JSON
            return new JsonResponse($data);
        }
        return $this->render('ServiceBundle:ReservationPetsitter:ReservationBack.html.twig', array(

        ));
    }

}
