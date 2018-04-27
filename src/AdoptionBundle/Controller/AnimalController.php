<?php

namespace AdoptionBundle\Controller;

use AdoptionBundle\AdoptionBundle;
use AdoptionBundle\Entity\Animal;
use AdoptionBundle\Form\AnimalType;
use ParcBundle\Form\Recherche;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Field\TextareaFormField;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AnimalController extends Controller
{
    public function ajouterAction(Request $request)
    {
        $animal = new Animal();
        $form=$this->createForm(AnimalType::class,$animal);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($animal);
            $em->flush();
            return $this->redirectToRoute("afficher_animal");
        }
        return $this->render('AdoptionBundle:Animal:ajouter.html.twig', array(
            "form"=>$form->createView()
        ));

    }

    public function supprimerAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $animal= $em->getRepository(animal::class)->find($id);
        $em->remove($animal);
        $em->flush();
        return $this->redirectToRoute("afficher_animal");
    }

    public function afficherAction(Request $request)
    {
        $em= $this->getDoctrine()->getManager();
        $animals=$em->getRepository("AdoptionBundle:Animal")->findAll();
        return $this->render('AdoptionBundle:Animal:afficher.html.twig', array(
            "animals"=>$animals

        ));
    }

    public function modifierAction($id, Request $request)
    {
        $em= $this->getDoctrine()->getManager();
        $animal=$em->getRepository("AdoptionBundle:Animal")->find($id);
        if ($request->isMethod('POST'))
        {
            $animal->setNom($request->get('Nom'));
            $animal->setRace($request->get('Race'));
            $animal->setEspece($request->get('Espece'));
            $animal->setSexe($request->get('Sexe'));
            $animal->setAge($request->get('Age'));
            $animal->setTaille($request->get('Taille'));
            $animal->setRegion($request->get('Region'));
            $animal->setDescription($request->get('Description'));
            $em->persist($animal);
            $em->flush();
            return $this->redirectToRoute('afficher_animal');
        }
        return $this->render('AdoptionBundle:Animal:modifier.html.twig', array(
            'animal' => $animal));
    }

    public function rechercherAction(Request $r)
    {
        $animal = new Animal();
        $animals=[];
        $form=$this->createFormBuilder($animal)
            ->add('nom')
            ->add('recherche',SubmitType::class)
            ->getForm();

        $form->handleRequest($r);
        if($form->isValid()){
            $nom=$animal->getNom();
            $em=$this->getDoctrine();
            $animals=$em->getRepository("AdoptionBundle:Animal")->rechercheAnimal($nom);
        }

        return $this->render('AdoptionBundle:Animal:recherche.html.twig', array(
            "form"=>$form->createView(),'animals'=>$animals

        ));
    }

    public function afficherFrontAction(Request $request)
{
    $em= $this->getDoctrine()->getManager();
    $animals=$em->getRepository("AdoptionBundle:Animal")->rechercheNonAdopte();
    return $this->render('AdoptionBundle:Animal:afficher_front.html.twig', array(
        "animals"=>$animals

    ));
}

    public function afficherAnimalDetailAction($id)
    {
        $em= $this->getDoctrine()->getManager();
        $animals=$em->getRepository("AdoptionBundle:Animal")->rechercheById($id);
        return $this->render('AdoptionBundle:Animal:animal_details.html.twig',array('animals'=>$animals));
    }

    public function adopterAction($id, Request $request)
    {

        $form= $this->createFormBuilder()
            ->add('Nom')
            ->add('Prenom')
            ->add('Email')
            ->add('Age')
            ->add('Adresse')
            ->add('Motivation', TextareaType::class)
            ->add('Envoyer', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $animal= $em->getRepository(Animal::class)->find($id);
            $animal->setDemande($animal->getDemande() + 1);
            $em->flush();
            $request = Request::createFromGlobals();

            $nom = $form["Nom"]->getData();
            $prenom = $form["Prenom"]->getData();
            $email = $form["Email"]->getData();
            $age = $form["Age"]->getData();
            $adresse = $form["Adresse"]->getData();
            $motivation = $form["Motivation"]->getData();

            $message = (new \Swift_Message('Hello Email'))
                ->setSubject("Demande d'adoption")
                ->setFrom(array($email))
                ->setTo('f5913a00ff-fe2c41@inbox.mailtrap.io')
                ->setCharset('utf-8')
                ->setBody($this->renderView('AdoptionBundle:Email:email.html.twig', array('name' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'age' => $age,
                    'adresse' => $adresse,
                    'motivation' => $motivation)),
                    'text/html');
            $this->get('mailer')->send($message);
        }

        return $this->render('AdoptionBundle:Animal:formulaire.html.twig', array(
            "form"=>$form->createView()));
    }

    public function imprimerPDFAction()
    {
        $snappy = $this->get('knp_snappy.pdf');
        $em= $this->getDoctrine()->getManager();
        $animals=$em->getRepository("AdoptionBundle:Animal")->findAll();
        $html = $this->renderView('AdoptionBundle:Animal:imprim_animal.html.twig', array(
            'animals'=>$animals
        ));

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
    public function rechercheAjaxAction(Request $request)
    {

        if($request->isXmlHttpRequest())
        {
            $mot=$request->get('nom');
            $em=$this->getDoctrine();
            $animals=$em->getRepository("AdoptionBundle:Animal")->rechercheAjax($mot);
            //etape 1: initialiser le serializer
            $serializer=new Serializer(array(new ObjectNormalizer()));
            //etape 2 : transformation liste des objets
            $data=$serializer->normalize($animals);
            //etape 3 : encodage format JSON
            return new JsonResponse($data);
        }
        return $this->render('AdoptionBundle:Animal:recherche.html.twig', array(

        ));
    }
    public function recherchefrontAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $espece=$request->get('espece');
        $age_animal=$request->get('age');
        $sexe_animal=$request->get('sexe');
        $region=$request->get('region');
        $taille=$request->get('taille');

        $animals= $em->getRepository('AdoptionBundle:Animal')->findAnimal($espece, $age_animal,$sexe_animal, $region, $taille );

        return $this->render('AdoptionBundle:Animal:afficher_front.html.twig', array(
            'animals' => $animals,
        ));
    }

    public function changerEtatAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $animal= $em->getRepository(Animal::class)->find($id);
        $animal->setEtat("adopte");
        $em->flush();
        return $this->redirectToRoute('afficher_animal');
    }

    public function setnonadopterAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $animal= $em->getRepository(Animal::class)->find($id);
        $animal->setEtat("nonadopte");
        $em->flush();
        return $this->redirectToRoute('afficher_animal');
    }

}
