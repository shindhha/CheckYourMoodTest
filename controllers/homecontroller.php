<?php
namespace controllers;

use services\HomeService;
use services\Mood;
use services\DonneesService;
use yasmf\HttpHelper;
use yasmf\View;


class HomeController {

    private $HomeService;
    private $DonneesService;
    private $MoodService;

    public function __construct()
    {
        $this->HomeService = HomeService::getDefaultHomeService();
        $this->DonneesService = DonneesService::getDefaultDonneesService();
        $this->MoodService = Mood::getDefaultMoodService();
    }

    //Fonction de connection
    public function login($pdo){
        $id = htmlspecialchars(HttpHelper::getParam('identifiant'));
        $mdp = htmlspecialchars(HttpHelper::getParam('motdepasse'));
        $infos = $this->HomeService->connexion($pdo,$id,$mdp);

        if($infos['util'] == 0){
            
            $view = new View("check-your-mood/views/connexion");
            return $view;
        }
			
		// Stockage dans la session //
        foreach($infos as $key => $value){
            $_SESSION["$key"] = $value;
        }

		$_SESSION['id'] = $infos["util"];		
		$_SESSION['numeroSession']=session_id();// Stockage numéro de session pour éviter les piratages.

        //Libelle disponible
        $libelles = $this->MoodService->libelles($pdo);

        // Nombre d'humeurs global
        $nbHumeur = $this->DonneesService->nombreHumeur($pdo, $infos['util'])->fetchColumn(0);

        // On détermine le nombre d'humeurs par page
        $parPage = 9;

        // On calcule le nombre de pages total
        $pages = ceil($nbHumeur / $parPage);

        // Page actuelle
        $currentPage = 1;
        
        // Calcul du 1er article de la page
        $premier = ($currentPage * $parPage) - $parPage;

        // On récupère les humeurs à afficher sur la page no 1
        $humeurs = $this->DonneesService->viewMoodsPagination($pdo, $infos['util'], $premier, $parPage);

        //Création de la vue et set vraiable
        $view = new View("check-your-mood/views/humeurs");
        $view->setVar('humeurs',$humeurs);
        $view->setVar('libelles',$libelles);
        $view->setVar('updateOk',true);
        $view->setVar('pages',$pages);
        $view->setVar('noPage',$currentPage);
        return $view;
    }

    //goTo pour ce déplacer entre la page de connexion et celle d'inscription
    public function goTo(){
        $namepage = HttpHelper::getParam('namepage');
        $view = new View("check-your-mood/views/".$namepage);
        return $view;
    }

    public function index() {

        $view = new View("check-your-mood/views/accueil");
        return $view;
    }

}
