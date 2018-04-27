<?php

namespace soinBundle\Controller;

use DateTime;
use soinBundle\Entity\reservationVeterinaire;
use soinBundle\Form\reservationVeterinaireType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use UserBundle\Form\veterinaireType;
use Symfony\Component\Security\Core\User\UserInterface;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class reservationVeterinaireController extends Controller
{
    public function reserverVeterinairerAction(Request $request,$id)
    {
        $reservationV = new reservationVeterinaire();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(reservationVeterinaireType::class, $reservationV);
        $form->handleRequest($request);
        $veterinaire = $em->getRepository('UserBundle:User')->find($id);
        $reservationV->setIdVeterinaire($veterinaire);
        $reservationV->setIdUser($this->getUser());

        if (($form->isValid()) && ($form->isSubmitted())) {

            $dated=$reservationV->getDateDebut();
            //var_dump($dated);

            $datef=$reservationV->getDateFin();

            if ($reservationV->getDescription()== "consultation")
            {
                //$var = new DateTime();
                $var=clone $dated;
                $var->modify('+30 minutes');
                $reservationV->setDateFin($var);
                //var_dump($reservationV->getDateFin());
                //var_dump($dated);die;

            }
            else
            {
                //$var = new DateTime();
                $var=clone $dated;
                $var->modify('+90 minutes');
                $reservationV->setDateFin($var);
                //var_dump($reservationV->getDateFin());
                //var_dump($dated);die;

            }


            $rslt =  $em->getRepository('soinBundle:reservationVeterinaire')->existance($dated,$id) ;
            //var_dump($rslt);
            //die();


            if ($rslt == NULL )
            {
                $em->persist($reservationV);
                $em->flush();

            }
            else
            {
               $this->addFlash("error", "cette date est deja prise , veuillez choisir une autre ");


            }
//            $em->persist($reservationV);
//            $em->flush();

        }
        return $this->render('soinBundle:reservationVeterinaire:reservation_veterinaire.html.twig', array
        ('form' => $form->createView()));
    }

    public  function statistiqueAction()
    {
        $pieChart = new PieChart();
        $em= $this->getDoctrine();
        $rdvs = $em->getRepository(reservationVeterinaire::class)->findAll();
        $totalRDV=0;
        foreach($rdvs as $rdv) {
            $totalRDV=$totalRDV+$rdv->getIdVeterinaire()->getId();
        }
        $data= array();
        $stat=['reservatonVeterinaire', 'id_veterinaire_id'];
        $nb=0;
        array_push($data,$stat);
        foreach($rdvs as $rdv) {
            $stat=array();
            array_push($stat,$rdv->getIdVeterinaire()->getNom(),(($rdv->getIdVeterinaire()->getId()) *100)/$totalRDV);
            $nb=($rdv->getIdVeterinaire()->getId() *100)/$totalRDV;
            $stat=[$rdv->getIdVeterinaire()->getNom(),$nb];
            array_push($data,$stat);
        }
        $pieChart->getData()->setArrayToDataTable(
            $data
        );
        $pieChart->getOptions()->setTitle('statistiques des rendez-vous ');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
        return $this->render('@soin/reservationVeterinaire/statistique.html.twig', array('piechart' =>
            $pieChart));
    }

        public function singleVeterinaireAction($id)
        {
            $em = $this->getDoctrine()->getManager();
            $veterinaire=$em->getRepository("UserBundle:User")->find($id);
            return $this->render("soinBundle:reservationVeterinaire:single_veterinaire.html.twig",array(
                "veterinaire"=>$veterinaire
            ));
        }











    public function afficher_veterinaireAction()
    {
        $em = $this->getDoctrine()->getManager();
        $veterinaire = $em->getRepository("soinBundle:reservationVeterinaire")->findByRole("ROLE_VETERINAIRE");
        return $this->render('soinBundle:reservationVeterinaire:afficher_veterinaire.html.twig', array(
            "veterinaires" => $veterinaire


        ));
    }

    public function supprimerVeterinaireAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $veterinaire = $em->getRepository(user::class)->find($id);
        $em->remove($veterinaire);
        $em->flush();
        return $this->redirectToRoute("afficher_veterinaire");
    }
    /* afficher la liste des vetrinaire inscrits ds ce site */

    public function afficherVeterinairesFrontAction()
    {
        $em = $this->getDoctrine()->getManager();
        $veterinaires = $em->getRepository("soinBundle:reservationVeterinaire")->findByRole("ROLE_VETERINAIRE");

        return $this->render('soinBundle:reservationVeterinaire:afficher_veterinaire_front.html.twig', array("veterinaires" => $veterinaires));


    }

    /* le liste des rdv d'un veterinaire */

    public function afficher_rdv_veterinaireAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $rdv = $em->getRepository("soinBundle:reservationVeterinaire")->aficher_rdv($id);
        return $this->render('soinBundle:reservationVeterinaire:liste_rdv_veterinaire.html.twig', array(
            "rdv" => $rdv
        ));
    }

    /* supp un rdv d'un veterinaire */
    public function supprimerRDVAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $rdv = $em->getRepository(reservationVeterinaire::class)->find($id);
        $em->remove($rdv);
        $em->flush();
        return $this->redirectToRoute("afficher_rdv");
    }

    public function PDFListeClientAction(Request $request)
    {
        $snappy = $this->get('knp_snappy.pdf');
        $filename = 'Veterinaire';
        $pageUrl = $this->generateUrl('afficher_veterinaire_pdf', array(), UrlGeneratorInterface::ABSOLUTE_URL);

        return new Response(
            $snappy->getOutput($pageUrl),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '.pdf"'
            )
        );
    }

    public function GeneratePDFAction()
    {
        $em = $this->getDoctrine()->getManager();
        $veterinaire = $em->getRepository("soinBundle:reservationVeterinaire")->findByRole("ROLE_VETERINAIRE");
        return $this->render('soinBundle:reservationVeterinaire:afficher_veterinairePDF.html.twig', array(
            "veterinaires" => $veterinaire


        ));
    }

    /* les rendez-vous d'un membre */

    public  function rdv_prisAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $rdv = $em->getRepository("soinBundle:reservationVeterinaire")->aficher_rdv_pris($id);

        return $this->render('soinBundle:reservationVeterinaire:liste_rdv_pris.html.twig', array(
            "rdv" => $rdv
        ));
    }
    public function modifierMonRDVAction($id,Request $request,$id_user)
    {
        $em= $this->getDoctrine()->getManager();
        $rdv=$em->getRepository("soinBundle:reservationVeterinaire")->find($id);
        $form=$this->createForm(reservationVeterinaireType::class,$rdv);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->flush();
            $liste = $em->getRepository("soinBundle:reservationVeterinaire")->findAll();
            return $this->redirectToRoute("afficher_rdv_pris",array('id'=>$id_user) );

        }
        return $this->render('soinBundle:reservationVeterinaire:reservation_veterinaire.html.twig', array(
            "form"=>$form->createView()

        ));
    }
    public function supprimerMonRdvAction($id,$id_user)
    {
        $em = $this->getDoctrine()->getManager();
        $rdv= $em->getRepository(reservationVeterinaire::class)->find($id);
        $em->remove($rdv);
        $em->flush();
        return $this->redirectToRoute("afficher_rdv_pris",array('id'=>$id_user) );
    }



}
