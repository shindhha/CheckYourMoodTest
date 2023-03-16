<?php
namespace controllers;

use services\InscriptionService;
use yasmf\HttpHelper;
use yasmf\View;

class InscriptionController {

    private $inscriptionService;

    public function __construct()
    {
        $this->inscriptionService = InscriptionService::getDefaultInscriptionService();
    }

    //
    public function signin($pdo) {

        $id = htmlspecialchars(HttpHelper::getParam('identifiant'));
        $mdp = htmlspecialchars(HttpHelper::getParam('motdepasse'));
        $mail = htmlspecialchars(HttpHelper::getParam('mail'));
        $nom = htmlspecialchars(HttpHelper::getParam('nom'));
        $prenom = htmlspecialchars(HttpHelper::getParam('prenom'));
        
        if( $id == "" || $mdp == "" || $mail == "" || $nom == "" || $prenom == ""){
            $insertOk = "nOk";
        }else{
            $insertOk = $this->inscriptionService->inscription($pdo,$id,$mdp,$mail,$nom,$prenom);
        }

        if($insertOk == "nOk"){
            $view = new View("check-your-mood/views/inscription");
            return $view;
        }

        $view = new View("check-your-mood/views/connexion");
        return $view;
    }

}
