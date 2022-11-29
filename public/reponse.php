<?php

use Symfony\Component\HttpFoundation\Response;

require_once "../vendor/autoload.php";

$maReponse = new Response();

$maReponse->setStatusCode(Response::HTTP_OK);
$maReponse->headers->set('Content-type','text/html');
$maReponse->setContent('<html><title>Reponse</title><body>Resultat d\'un objet reponse</body></html>');

$maReponse->send();
