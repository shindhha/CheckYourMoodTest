<?php
namespace controllers;

use services\DonneesService;
use services\MoodService;
use yasmf\HttpHelper;
use yasmf\View;
use services\VisualisationService;


class DonneesController {

    private $DonneesService;
    private $MoodService;
    private $visualisationService;

    public function __construct()
    {
        $this->DonneesService = DonneesService::getDefaultDonneesService();
        $this->MoodService = MoodService::getDefaultMoodService(); 
        $this->visualisationService = VisualisationService::getDefaultVisualisationService();
    }

    public function goToMood($pdo){

        $anneeAComparer = (int) HttpHelper::getParam('anneeChoisi') ?: 2023; 
        $typeDeRpresentation = (int) HttpHelper::getParam('typeDeRpresentation') ?: 1;
        $namepage = htmlspecialchars(HttpHelper::getParam('namepage'));
        $requeteCurrentWeek = $this->visualisationService->getCurrentWeek($pdo);
        while($row = $requeteCurrentWeek->fetch()){
            $currentWeek = $row['week'];
        }
        $weekGeneral = (int) htmlspecialchars(HttpHelper::getParam('weekGeneral')) ?: $currentWeek;

		
		
		$requeteCurrentDay = $this->visualisationService->getCurrentDay($pdo);
		while($row = $requeteCurrentDay->fetch()){
            $currentDay = $row['day'];
        }
        $dateDonught = htmlspecialchars(HttpHelper::getParam('dateChoisiDonught')) ?: $currentDay;
		
		
        while($row = $requeteCurrentWeek->fetch()){
            $currentWeek = $row['weekTableau'];
        }
        
        $idUtil = $_SESSION['util'];
        $code = (int) HttpHelper::getParam('humeur') ?: 1; 
		$codeDigrammeBatton = (int) HttpHelper::getParam('humeurDigrammeBatton') ?: 1;

        $requeteAnneeActuelle = $this->visualisationService->getCurrentYear($pdo);
        while($row = $requeteAnneeActuelle->fetch()){
            $anneeActuelle = $row['year'];
        }
        $anneeComparaison = (int) htmlspecialchars(HttpHelper::getParam('anneeAComparer')) ?: $anneeActuelle;

        $tabAnneeActuelle = $this->visualisationService->visualisationHumeurAnnee($pdo, $idUtil, $anneeAComparer,$codeDigrammeBatton);
        $tabAnneeComparaison = $this->visualisationService->visualisationHumeurAnnee($pdo, $idUtil, $anneeComparaison,$codeDigrammeBatton);

		$anneHumeurMax = (int) htmlspecialchars(HttpHelper::getParam('anneeAComparerHumeur')) ?: $anneeActuelle;
        
        $humeursRadar = $this->MoodService->viewMoods($pdo,$_SESSION['util']);
        $libellesRadar = $this->MoodService->libelles($pdo);
        $libellesTableau = $this->MoodService->libelles($pdo);
        $visualisationRadar = $this->visualisationService->visualisationRadar($pdo, $idUtil, $code, $weekGeneral, $anneeAComparer);
        $visualisationTableau = $this->visualisationService->visualisationTableau($pdo, $idUtil, $weekGeneral, $anneeAComparer);
        $visualisationDonught = $this->visualisationService->visualisationDoughnut($pdo, $idUtil, $dateDonught);

        foreach($visualisationDonught as $key => $value){
            $tableauLibelleDonught[] = $key; 
            $tableauCountDonught[] = $value; 
        }
        $humeursLaPlusFrequente = $this->visualisationService->visualisationHumeurSemaine($pdo, $idUtil, $weekGeneral);
		$humeursLaPlusFrequenteAnnee = $this->visualisationService->visualisationHumeurAnneeLaPlus($pdo, $idUtil, $anneeAComparer);
		$humeursLaPlusFrequenteJour = $this->visualisationService->visualisationHumeurJour($pdo, $idUtil, $dateDonught);
        

        $view = new View("check-your-mood/views/".$namepage);
        $view->setVar('humeursRadar',$humeursRadar);
        $view->setVar('libellesRadar',$libellesRadar);
        $view->setVar('libellesTableau',$libellesTableau);
        $view->setVar('currentWeek',$currentWeek);
        $view->setVar('visualisationRadar',$visualisationRadar);
        $view->setVar('visualisationTableau',$visualisationTableau);
        $view->setVar('visualisationDonught',$visualisationDonught);
        $view->setVar('humeursLaPlusFrequente',$humeursLaPlusFrequente);
		$view->setVar('humeursLaPlusFrequenteAnnee',$humeursLaPlusFrequenteAnnee);
        $view->setVar('dataPoints2',$tabAnneeComparaison);
        $view->setVar('dataPoints1',$tabAnneeActuelle);
        $view->setVar('anneeActuelle',$anneeActuelle);
		$view->setVar('currentDay',$currentDay);
        $view->setVar('anneeComparaison',$anneeComparaison);
        $view->setVar('anneeChoisi',$anneeAComparer);
        $view->setVar('weekGeneral',$weekGeneral);
        $view->setVar('typeDeRpresentation',$typeDeRpresentation);
		$view->setVar('codeDigrammeBatton',$codeDigrammeBatton);
		$view->setVar('dateDonught',$dateDonught);
        $view->setVar('tableauLibelleDonught',$tableauLibelleDonught);
        $view->setVar('tableauCountDonught',$tableauCountDonught);
		$view->setVar('humeursLaPlusFrequenteJour',$humeursLaPlusFrequenteJour);

        return $view;
    }
	
	/**
	 * Permet la modification du contexte d'une humeur
	 */
	public function updateHumeur($pdo) {
		
        $tab['contexte'] = htmlspecialchars(HttpHelper::getParam('contexte'));
        $tab['codeHumeur'] = htmlspecialchars(HttpHelper::getParam('codeHumeur'));
        $tab['id'] = $_SESSION['util'];
		
        $util = $_SESSION['util'];

		$updateOk = $this->DonneesService->updateHumeur($pdo,$tab);
        $humeurs = $this->MoodService->viewMoods($pdo,$_SESSION['util']);
        $libelles = $this->MoodService->libelles($pdo);
		
		return $this->changementPage($pdo);
    }

    //Insertion d'une humeur
    public function insertHumeur($pdo){
        $code = (int) HttpHelper::getParam('humeur');
        $date  = HttpHelper::getParam('dateHumeur');
        $heure  = HttpHelper::getParam('heure');
        $contexte = htmlspecialchars(HttpHelper::getParam('contexte'));
        $util = $_SESSION['util'];

        $insertion = $this->MoodService->insertMood($pdo, $code, $date, $heure, $contexte, $util);

        $humeurs = $this->MoodService->viewMoods($pdo,$util);
        $libelles = $this->MoodService->libelles($pdo);

        /* pour ne pas que quand on actualise la page, la requête se re éxécute et remette une nouvelle humeur */
        header('Location: index.php?controller=donnees&action=changementPage');
    }

    /**
     * Permet la pagination de la page de visualisation des humeurs
     * @param $pdo 
     * @return View 
     */
    public function changementPage($pdo)
    {
        //Libelle disponible
        $libelles = $this->MoodService->libelles($pdo);

        // Nombre d'humeurs global
        $nbHumeur = $this->DonneesService->nombreHumeur($pdo, $_SESSION['util'])->fetchColumn(0);

        // On détermine le nombre d'humeurs par page
        $parPage = 9;

        // On calcule le nombre de pages total
        $pages = ceil($nbHumeur / $parPage);

        // Page actuelle
        $currentPage = HttpHelper::getParam('noPage') ?: 1;

        if ($currentPage == "<<") {
            $currentPage = 1;
        } else if ($currentPage == ">>") {
            $currentPage = $pages;
        }
        
        // Calcul du 1er article de la page
        $premier = ($currentPage * $parPage) - $parPage;

        // On récupère les humeurs à afficher sur la page no 1
        $humeurs = $this->DonneesService->viewMoodsPagination($pdo, $_SESSION['util'], $premier, $parPage);

        //Création de la vue et set vraiable
        $view = new View("check-your-mood/views/humeurs");
        $view->setVar('humeurs',$humeurs);
        $view->setVar('libelles',$libelles);
        $view->setVar('pages',$pages);
        $view->setVar('noPage',$currentPage);
        return $view;
    }

    // Modification des données personnelles
    public function updateData($pdo){
        $tab['identifiant'] = htmlspecialchars(HttpHelper::getParam('identifiant'));
        $tab['nom'] = htmlspecialchars(HttpHelper::getParam('nom'));
        $tab['prenom'] = htmlspecialchars(HttpHelper::getParam('prenom'));
        $tab['mail'] = htmlspecialchars(HttpHelper::getParam('mail'));
        $util = $_SESSION['util'];

        $modificationInformationOk = $this->DonneesService->updateData($pdo,$tab,$util);

        $view = $this->viewModification($pdo);
        // Modification des Informations (hors mot de passe) 
        $view->setVar('tentativeModificationInformation',true);
        $view->setVar('modificationInformationOk',$modificationInformationOk);

		return $view;
    }
    /**
     * 
     * @param $pdo 
     * @return View 
     */
    public function viewModification($pdo) {

        $donnees = $this->DonneesService->donneesUser($pdo, $_SESSION['util'])->fetchAll();
        
        //Création de la vue et set vraiable
        $view = new View("check-your-mood/views/modification");
        // Informations de l'utilisateur
        $view->setVar('prenom',$donnees[0]['prenom']);
        $view->setVar('nom',$donnees[0]['nom']);
        $view->setVar('identifiant',$donnees[0]['identifiant']);
        $view->setVar('courriel',$donnees[0]['mail']);
        // Modification des Informations (hors mot de passe) 
        $view->setVar('tentativeModificationInformation',false);
        $view->setVar('modificationInformationOk',false);
        // Modification du mot de passe
        $view->setVar('tentativeModificationMDP',false);
        $view->setVar('mdpOk','');
        $view->setVar('mdpNouveauOk','');
        $view->setVar('modificationMDPOk',false);
        return $view;
    }
        
    /**
     * Permet la modification du mdp
     * @param $pdo 
     * @return View 
     */
    public function updateMDP($pdo) {

        $MDP = htmlspecialchars(HttpHelper::getParam('ancienMDP'));

        /* Controle que le mot de passe est bon */
        if ($this->DonneesService->mdp($pdo, $_SESSION['util'])->fetchAll()[0]['motDePasse'] == md5($MDP)) {
            
            $mdpOk = true;
            $nouveauMDP = htmlspecialchars(HttpHelper::getParam('nouveauMDP'));
            $confirmationNouveauMDP = htmlspecialchars(HttpHelper::getParam('confirmationNouveauMDP'));

            /* Controle que le nouveau mot est valide */
            if (strlen($nouveauMDP) != 0 && $nouveauMDP == $confirmationNouveauMDP) {
                $this->DonneesService->updateMDP($pdo, $_SESSION['util'], $nouveauMDP);
                $mdpNouveauOk = true;
                $modificationMDPOk = true;
            } else {
                $mdpNouveauOk = false;
                $modificationMDPOk = false;
            }
        
        } else {
            // Mauvais mot de passe
            $mdpOk = false;
            $modificationMDPOk = false;
            $mdpNouveauOk = false;
        }
        //Création de la vue et set vraiable
        $view = $this->viewModification($pdo);
        $view->setVar('tentativeModificationMDP',true);
        $view->setVar('mdpOk',$mdpOk);
        $view->setVar('modificationMDPOk',$modificationMDPOk);
        $view->setVar('mdpNouveauOk',$mdpNouveauOk);
        return $view;
    }
	
	
	public function supprimerCompte($pdo) {
		
		$this->DonneesService->supprimerCompte($pdo, $_SESSION['id']);
		$view = new View("check-your-mood/views/connexion");
		$view->setVar("errData",true);
		return $view ; 

	}

}
