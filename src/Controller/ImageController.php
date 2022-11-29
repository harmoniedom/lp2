<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/client', name:'client')]
class ImageController extends AbstractController
{

    #[Route('/info/{prenom}',name:'_info')]
    function info( $prenom ): Response
    {
        $urlImg=$this->generateUrl('client_photo',['prenom'=>$prenom]);
        return $this->render('monTemplate.html.twig',['prenom'=>$prenom,'url'=>$urlImg]);
    }

    #[Route('/photo/{prenom}', name:'_photo')]
    function photo($prenom)
    {
        return new BinaryFileResponse(__DIR__."/../../public/data/tintin.jpg");
    }

    #[Route('/afficherPhoto/{prenom}', name:'_afficher_photo')]
    function afficherPhoto($prenom): Response
    {
        return $this->render('_afficher_photo.html.twig',['prenom'=>$prenom]);

    }
    #[Route('/TP2/{prenom}', name:'_TP2')]
    function TP2($prenom): Response
    {
        return$this->render('_afficher_photo.html.twig',['photo'=>"$prenom"]);

    }

    #[Route('/TP2/BR/{prenom}', name:'BR_photo')]
    function BRTP2($prenom): BinaryFileResponse
    {
        return new BinaryFileResponse(__DIR__."/../../public/data/$prenom",200,["content-type"=>"image/jpg"]);
    }

    //suite exo3 tp2
    #[Route('/test')]
    public function afficher(){
        $LeChemin = __DIR__."/../../public/data/";
        $LesFichiers = scandir($LeChemin);
        foreach($LesFichiers as $TestDir){
            if(is_dir($TestDir)){
                continue;
            }
            else{
                $TabPhoto[] = $TestDir;
            }
        }
        dump($TabPhoto);
        return $this->render('ImgMenu.html.twig',['Photo'=>$TabPhoto]);
    }
}
