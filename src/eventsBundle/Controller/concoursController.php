<?php

namespace eventsBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Doctrine\DBAL\Types\TextType;
use eventsBundle\Entity\concours;
use eventsBundle\Entity\participation;
use eventsBundle\Form\concoursType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Common\Collections\ArrayCollection;

class concoursController extends Controller
{
    public function createAction(Request $request)
    {

        $concours = new concours();
        $form=$this->createForm(concoursType::class,$concours);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($concours);
            $em->flush();
            return $this->redirectToRoute("read");
        }
        return $this->render('eventsBundle:Default:createconcours.html.twig', array(
            "form"=>$form->createView()
        ));




    }

    public function readAction()
    {
        $em= $this->getDoctrine()->getManager();
        $concours=$em->getRepository("eventsBundle:concours")->findAll();
        return $this->render('eventsBundle:Default:read.html.twig', array(
            "concours"=>$concours

        ));
    }


    public function readfrontAction()
    {
        $em= $this->getDoctrine()->getManager();
        $concours=$em->getRepository("eventsBundle:concours")->findAll();
        return $this->render('eventsBundle:Default:readfront.html.twig', array(
            "concours"=>$concours

        ));
    }




    public function redirectfrontAction($id)
    {

        $em= $this->getDoctrine()->getManager();
        $concour=$em->getRepository("eventsBundle:concours")->find($id);
        return $this->render('eventsBundle:Default:participer1.html.twig', array(
            "concour"=>$concour

        ));
    }


    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $concours= $em->getRepository(concours::class)->find($id);
        $em->remove($concours);
        $em->flush();
        return $this->redirectToRoute("read");
    }


    public function updateAction ($id,Request $request)
    {
        $em= $this->getDoctrine()->getManager();
        $concours=$em->getRepository("eventsBundle:concours")->find($id);
        $form=$this->createForm(concoursType::class,$concours);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute("read");
        }
        return $this->render('eventsBundle:Default:update.html.twig', array(
            "form"=>$form->createView()

        ));
    }



    public function particiaptionAction( Request $request)
    {


        $participation = new participation();
        $em = $this->getDoctrine()->getManager();

        $concour= $em->getRepository("eventsBundle:concours")->find($request->get('idc'));
        $user= $em->getRepository("UserBundle:User")->find($request->get('idp'));
        $nbp=$em->getRepository("eventsBundle:participation")->calculerPart();
        $x=new ArrayCollection();
        foreach ($nbp as $c)
        {
            $x->set($c[1],$c[2]);
        }

        if($x->get($request->get('idc'))<$concour->getNbredeplaces()) {
            $participation->setIdc($concour);
            $participation->setIdp($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($participation);
            $em->flush();
        }
        else
        {
            return $this->render('eventsBundle:Default:nbredesparticipants.html.twig', array(

            ));

        }
        return $this->redirectToRoute('readfront');

    }



        public function rechercheAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $concour=$request->get('description');
            $em=$this->getDoctrine();
            $concours=$em->getRepository("eventsBundle:concours")->rechercheAjax($concour);
            //etape 1: initialiser le serializer
            $serializer=new Serializer(array(new ObjectNormalizer()));
            //etape 2 : transformation liste des objets
            $data=$serializer->normalize($concours);
            //etape 3 : encodage format JSON
            return new JsonResponse($data);
        }
        return $this->render('eventsBundle:Default:searchajax.html.twig', array(

        ));
    }

    public function pdfAction(Request $request)
    {  $em= $this->getDoctrine()->getManager();
        $concour=$em->getRepository("eventsBundle:concours")->findAll();
        $snappy=$this->get("knp_snappy.pdf");
        $html=$this->renderView("eventsBundle:Default:read.html.twig",array("concours"=>$concour));
        $filename="twig en pdf";

        return new Response (
            $snappy->getOutputFromHtml($html),200,array(
                'content-type'=>'pdf/pdf',
                'conctent-disposition'=>'inline;filename="'.$filename.'.pdf"'
            )
        );
    }




    public function statiAction()
    {
        $pieChart = new PieChart();
        $em= $this->getDoctrine();
        $classes = $em->getRepository(concours::class)->findAll();
        $totalEtudiant=0;



        foreach($classes as $classe) {
            $totalEtudiant=$totalEtudiant+$classe->getNbredeplaces();
        }
        $data= array();
        $stat=['classe', 'nbEtudiant'];
        $nb=0;
        array_push($data,$stat);
        foreach($classes as $classe) {
            $stat=array();
            array_push($stat,$classe->getDescription(),(($classe->getNbredeplaces()) *100)/$totalEtudiant);
            $nb=($classe->getNbredeplaces() *100)/$totalEtudiant;
            $stat=[$classe->getDescription(),$nb];
            array_push($data,$stat);
        }
        $pieChart->getData()->setArrayToDataTable(
            $data
        );
        $pieChart->getOptions()->setTitle('participation par concour');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
        return $this->render('eventsBundle:Default:graph.html.twig', array('piechart' =>
            $pieChart));
    }

    public function recherchedateAction(){
        $em=$this->getDoctrine();
        $concours =$em->getRepository('eventsBundle:concours')->recherchedate();
        return $this->render('eventsBundle:Default:recherchedate.html.twig', array(
            'concours' => $concours
            // ...
        ));

    }
}
