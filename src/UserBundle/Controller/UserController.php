<?php

namespace UserBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class UserController extends Controller
{

    public function authAction(Request $request)
    {
        $username=$request->get("username");
        $em=$this->getDoctrine();
        $users=$em->getRepository("UserBundle:User")->selectuser($username);
        $normaliser=new ObjectNormalizer();
        //etape 1: initialiser le serializer
        $serializer=new Serializer(array($normaliser));
        //etape 2 : transformation liste des objets
        $data=$serializer->normalize($users);

        //etape 3 : encodage format JSON
        return new JsonResponse($data);
    }

}
