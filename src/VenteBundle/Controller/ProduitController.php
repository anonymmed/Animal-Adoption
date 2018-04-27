<?php

namespace VenteBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Histogram;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Builder\ParentNodeDefinitionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use VenteBundle\Entity\Commande;
use VenteBundle\Entity\Ligne;
use VenteBundle\Entity\Produit;
use VenteBundle\Form\ProduitType;

class ProduitController extends Controller
{
    public function ajouterProduitAction(Request $request)
    {
        $produit = new Produit();
        $form=$this->createForm(ProduitType::class,$produit);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('afficher_produits');
        }
        return $this->render('@Vente/Produit/ajouter_produit.html.twig', array(
            "form"=>$form->createView()
        ));
    }

    public function modifierProduitAction(Request $request,$id)
    {

        $em=$this->getDoctrine()->getManager();
        $produit=$em->getRepository("VenteBundle:Produit")->find($id);
        if ($request->isMethod('POST'))
        {

            $produit->setLibelle($request->get('libelle'));
            $produit->setQuantite($request->get('quantite'));
            $produit->setAnimal($request->get('animal'));
            $produit->setPrix($request->get('prix'));
            $produit->setDescription($request->get('description'));
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('afficher_produits');
        }
        return $this->render('@Vente/Produit/modifier_produit.html.twig', array(
            'produit' => $produit));
    }


    public function supprimerProduitAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $produit= $em->getRepository(Produit::class)->find($id);
        $em->remove($produit);
        $em->flush();
        return $this->redirectToRoute('afficher_produits');
    }

    public function afficherProduitAction()
    {
        $em= $this->getDoctrine()->getManager();
        $produits=$em->getRepository("VenteBundle:Produit")->affichage();
        return $this->render('VenteBundle:Produit:afficher_produits.html.twig', array(
            "produits"=>$produits
        ));
    }

    public function afficherProduitjsonAction()
    {

        $em=$this->getDoctrine();
        $users=$em->getRepository("VenteBundle:Produit")->findAll();
        $normaliser=new ObjectNormalizer();
        //etape 1: initialiser le serializer
        $serializer=new Serializer(array($normaliser));
        //etape 2 : transformation liste des objets
        $data=$serializer->normalize($users);

        //etape 3 : encodage format JSON
        return new JsonResponse($data);
    }

    public function totalpanierjsonAction(Request $request)
    {

        $em=$this->getDoctrine();
        $total=$em->getRepository("VenteBundle:Ligne")->totalpanier($request->get("id"));
        $normaliser=new ObjectNormalizer();
        //etape 1: initialiser le serializer
        $serializer=new Serializer(array($normaliser));
        //etape 2 : transformation liste des objets
        $data=$serializer->normalize($total);

        //etape 3 : encodage format JSON
        return new JsonResponse($data);
    }

    public function descriptionpanierjsonAction(Request $request)
    {

        $em=$this->getDoctrine();
        $description=$em->getRepository("VenteBundle:Ligne")->passerCommander($request->get("id"));
        $normaliser=new ObjectNormalizer();
        //etape 1: initialiser le serializer
        $serializer=new Serializer(array($normaliser));
        //etape 2 : transformation liste des objets
        $data=$serializer->normalize($description);

        //etape 3 : encodage format JSON
        return new JsonResponse($data);
    }

    public function commanderjsonAction(Request $request)
    {
        $x=new ArrayCollection();
        $amount=$request->get("amount");
        $time = new \DateTime();
        $time->format(' Y-m-d');
        $em = $this->getDoctrine()->getManager();
        $array=$em->getRepository("VenteBundle:Ligne")->passerCommander($request->get("id"));
        foreach ($array as $a)
        {
            $x->add($a['description']);
        }
        $description=implode("+",$x->getValues());
        $order = new Commande($amount,$description,$time);
        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();
        $normaliser=new ObjectNormalizer();
        //etape 1: initialiser le serializer
        $serializer=new Serializer(array($normaliser));
        //etape 2 : transformation liste des objets
        $data=$serializer->normalize("Success");

        //etape 3 : encodage format JSON
        return new JsonResponse($data);

    }


    public function afficherFrontProduitAction()
    {
        $em= $this->getDoctrine()->getManager();
        $produits=$em->getRepository("VenteBundle:Produit")->affichage();
        return $this->render('@Vente/Produit/front_produits.html.twig', array(
            "produits"=>$produits
        ));
    }

    public function afficherProduitDetailAction($id)
    {
        $em= $this->getDoctrine()->getManager();
        $produit=$em->getRepository("VenteBundle:Produit")->Details($id);
        return $this->render('@Vente/Produit/produits_details.html.twig', array(
            "produit"=>$produit
        ));
    }

    public function statistiqueAction()
    {
        $x=new ArrayCollection();
        $time = new \DateTime();
        $em=$this->getDoctrine()->getManager();
        $produit=$em->getRepository("VenteBundle:Produit")->affichage();
        $totalproduit=0;
        foreach($produit as $p)
        {
            $totalproduit+=$p->getQuantite();
        }
        $data= array();
        $stat=['Produit', 'Quantite'];
        $nb=0;
        array_push($data,$stat);
        foreach($produit as $p)
        {
            $stat=array();
            array_push($stat,$p->getlibelle(),(($p->getQuantite()) *100)/$totalproduit);
            $nb=($p->getQuantite() *100)/$totalproduit;
            $stat=[$p->getLibelle(),$nb];
            array_push($data,$stat);
        }
        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable($data);
        $pieChart->getOptions()->setPieSliceText('label');
        $pieChart->getOptions()->setTitle('Quantite/Tous les Produits');
        $pieChart->getOptions()->setPieStartAngle(100);
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getLegend()->setPosition('none');

        return $this->render('@Vente/Produit/statistique.html.twig', array('piechart' => $pieChart));
    }

    public function ajouterPanierAction(Request $request)
    {

        $ligne=new Ligne();
        $quantite=$request->get('quantite');
        $em= $this->getDoctrine()->getManager();
        $ligne1=$em->getRepository("VenteBundle:Ligne")->produitExiste($request->get('idProduit'));
        $produit=$em->getRepository("VenteBundle:Produit")->find($request->get('idProduit'));
        if($ligne1==null)
        {
            $ligne->setProduit($produit);
            $ligne->setPrix($produit->getPrix());
            $ligne->setImage($produit->getImage());
            $ligne->setIdClient(1);
            $ligne->setQuantite($quantite);
            $em->persist($ligne);
            $em->flush();
        }
        else{
                $ligne1->setQuantite($ligne1->getQuantite()+$quantite);
                $ligne1->setPrix($ligne1->getQuantite()*$produit->getPrix());
                $em->flush();
            }
        return $this->redirectToRoute('afficher_front_produit');
    }

    public function ajouterPanierjsonAction(Request $request)
    {

        $ligne=new Ligne();
        $quantite=$request->get('quantite');
        $em= $this->getDoctrine()->getManager();
        $ligne1=$em->getRepository("VenteBundle:Ligne")->produitExiste($request->get('id_produit'));
        $produit=$em->getRepository("VenteBundle:Produit")->find($request->get('id_produit'));
        if($ligne1==null)
        {
            $ligne->setProduit($produit);
            $ligne->setPrix($produit->getPrix());
            $ligne->setImage($produit->getImage());
            $ligne->setIdClient($request->get('id_client'));
            $ligne->setQuantite($quantite);
            $em->persist($ligne);
            $em->flush();
        }
        else{
            $ligne1->setQuantite($ligne1->getQuantite()+$quantite);
            $ligne1->setPrix($ligne1->getQuantite()*$produit->getPrix());
            $em->flush();
        }

        $normaliser=new ObjectNormalizer();
        //etape 1: initialiser le serializer
        $serializer=new Serializer(array($normaliser));
        //etape 2 : transformation liste des objets
        $data=$serializer->normalize("c bn");

        //etape 3 : encodage format JSON
        return new JsonResponse($data);

    }

    public function AfficherPanierAction()
    {
        $em= $this->getDoctrine()->getManager();
        $lignes=$em->getRepository("VenteBundle:Ligne")->findAll();
        return $this->render('@Vente/Produit/panier.html.twig', array(
            "lignes"=>$lignes
        ));

    }

    public function AfficherPanierjsonAction()
    {
        $em= $this->getDoctrine()->getManager();
        $lignes=$em->getRepository("VenteBundle:Ligne")->findAll();
        $normaliser=new ObjectNormalizer();
        //etape 1: initialiser le serializer
        $serializer=new Serializer(array($normaliser));
        //etape 2 : transformation liste des objets
        $data=$serializer->normalize($lignes);
        //etape 3 : encodage format JSON
        return new JsonResponse($data);
    }

    public function supprimerPanierAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $produit= $em->getRepository(Ligne::class)->find($id);
        $em->remove($produit);
        $em->flush();
        return $this->redirectToRoute('afficher_panier');
    }

    public function searchAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $mot=$request->get('mot');
            $em=$this->getDoctrine();
            $centreD=$em->getRepository("VenteBundle:Produit")->rechercheAjax($mot);
            //etape 1: initialiser le serializer
            $serializer=new Serializer(array(new ObjectNormalizer()));
            //etape 2 : transformation liste des objets
            $data=$serializer->normalize($centreD);
            //etape 3 : encodage format JSON
            return new JsonResponse($data);
        }
        return $this->render('VenteBundle:Produit:afficher_produits.html.twig', array(

        ));
    }

}
