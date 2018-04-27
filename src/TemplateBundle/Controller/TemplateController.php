<?php

namespace TemplateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class TemplateController extends Controller
{
    public function layoutAction()
    {

        return $this->render('TemplateBundle:Layout:frontpage.html.twig');
    }

    public function adminAction()
    {

        return $this->render('TemplateBundle:Layout:BackAcceuilpage.html.twig');
    }

    public function adoptionPageAction()
    {
        return $this->render('TemplateBundle:Adoption:adoption.html.twig');
    }

    public function accessoirePageAction()
    {
        return $this->render('TemplateBundle:Ventes:accessoire.html.twig');
    }
    public function alimentPageAction()
    {
        return $this->render('TemplateBundle:Ventes:aliment.html.twig');
    }

}
