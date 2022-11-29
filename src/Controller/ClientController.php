<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Stamp\RedeliveryStamp;
use Symfony\Component\Routing\Annotation\Route;



#[Route('/client', name:'client')]

class ClientController extends AbstractController
{

    #[Route('/info/{prenom}',name:'_info', requirements: ["prenom"=>"^(?!-).+[a-zA-Zéèà][^ .](?<!-)$"])]
    function info( $prenom ): Response
    {
        $urlImg=$this->generateUrl('client_photo',['prenom'=>$prenom]);
        return new Response("Le prénom de $prenom est Tintin <img src=\"$urlImg\"/>");
        //return new Response("Le nom est : $prenom");

    }

    #[Route('/photo/{prenom}', name:'_photo')]
    function photo($prenom)
    {
        return new BinaryFileResponse(__DIR__."/../../data/tintin.jpg");
    }
}