<?php
namespace controllers;

use services\HomeService;
use services\MoodService;
use yasmf\HttpHelper;
use yasmf\View;


class MoodController {

    private $MoodService;

    public function __construct()
    {
        $this->MoodService = MoodService::getDefaultMoodService();
        $this->HomeService = HomeService::getDefaultHomeService();
    }

    /**
     * Déplacement entre la page de parametre et des humeurs
     */
    public function goTo($pdo){

        $namepage = htmlspecialchars(HttpHelper::getParam('namepage'));

        $view = new View("check-your-mood/views/".$namepage);

        if($namepage == "modification"){
            $view->setVar('updateOk',0);
        }else if($namepage == "humeurs"){

            //Humeurs de l'utilisateur
            $humeurs = $this->MoodService->viewMoods($pdo,$_SESSION['util']);

            //Libelle disponible
            $libelles = $this->MoodService->libelles($pdo);
            
            $view->setVar('humeurs',$humeurs);
            $view->setVar('libelles',$libelles);
        }
        
        return $view;
    }

    //Insertion d'une humeur
    //Verifier que la date Entree est valide
    public function index($pdo){
        $code = (int) HttpHelper::getParam('humeur');
        $date  = HttpHelper::getParam('dateHumeur');
        $heure  = HttpHelper::getParam('heure');
        $contexte = htmlspecialchars(HttpHelper::getParam('contexte'));
        $util = $_SESSION['util'];

        $insertion = $this->MoodService->insertMood($pdo, $code, $date, $heure, $contexte, $util);

        $humeurs = $this->MoodService->viewMoods($pdo,$util);
        $libelles = $this->MoodService->libelles($pdo);

        $view = new View("check-your-mood/views/humeurs");
        $view->setVar('humeurs',$humeurs);
        $view->setVar('libelles',$libelles);
        $view->setVar('updateOk',true);

        return $view;
    }

}
