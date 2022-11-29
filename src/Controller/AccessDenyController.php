<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;




#[Route('/TP3', name: "TP3")]

class AccessDenyController extends AbstractController
{

    #[Route('/Access', name: "Access", defaults: ['ouverture' => '8-10'])]

    function home ()
    {
        return $this->render('ouverture.html.twig');

    }
}