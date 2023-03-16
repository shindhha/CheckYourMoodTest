<?php
namespace controllers;

use services\InscriptionService;
use yasmf\HttpHelper;
use yasmf\View;


class VisualisationController {

    private $visualisationService;

    
    public function __construct()
    {
        $this->visualisationService = VisualisationService::getDefaultVisualisationService();
        $this->MoodService = MoodService:: getDefaultMoodService();
    }

    public function countMoodByDay($pdo){
        $code = (int) HttpHelper::getParam('humeur');
        $idUtil = $_SESSION['util'];

        $humeurs = $this->MoodService->viewMoods($pdo,$_SESSION['util']);
        $libelles = $this->MoodService->libelles($pdo);

        $nbr = $this->visualisationService->visualisation($pdo, $idUtil, $code);

        $view = new View("check-your-mood/views/visualisation");
        $view->setVar('humeurs',$humeurs);
        $view->setVar('libelles',$libelles);
        $view->setVar('nbrHumeurs',$nbr);
        $view->setVar('test',$code);
        return $view;
    }

    public function dayWeek($pdo){

        $week = $this->visualisationService->getCurrentWeek($pdo);
        $view = new View("check-your-mood/views/visualisation");
        $view->setVar('currentWeek',$week);
        return $view;
    }
}