<?php
require_once 'yasmf/datasource.php';
require_once 'services/donneesservice.php';
require_once 'Test/DataBase.php';
require_once 'services/moodservice.php';
require_once 'services/inscriptionservice.php';
require_once 'controllers/inscriptioncontroller.php';
use services\DonneesService;
use PHPUnit\Framework\TestCase;
use yasmf\DataSource;
use PHPUnit\Framework;
use yasmf\View;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;
use services\MoodService;
class InscriptionControllerTest extends \PHPUnit\Framework\TestCase
{
    private $pdo;
    private $inscriptioncontroller;
    private $inscriptionService;
    protected function setUp(): void
    {
        $this->pdo = DataBase::getPDOTest();
        $this->inscriptionService = $this->createStub(\services\InscriptionService::class);
        $this->inscriptioncontroller = new \controllers\InscriptionController($this->inscriptionService);
    }

    protected function tearDown(): void
    {
        $_POST = [];
        $_GET = [];
    }

    public function testInscriptionSuccess() {

        // GIVEN des valeurs entrer par l'utilisateur valides
        $_POST['identifiant'] = "test";
        $_POST['motdepasse'] = "test";
        $_POST['mail'] = "test";
        $_POST['nom'] = "test";
        $_POST['prenom'] = "test";
        // et un services d'inscriptions qui renvoie un resultat positif.
        $this->inscriptionService->method("inscription")->willReturn("ok");
        // WHEN On appelle la fonction siging du controller d'inscription
        $returnedView = $this->inscriptioncontroller->signin($this->pdo);
        // THEN L'inscription est valider et on arrive sur la page de connexion
        $expectedView = new View("check-your-mood/views/connexion");
        self::assertEquals($expectedView,$returnedView);
    }

    public function testEmptyUsersValues() {
        // GIVEN des valeurs entrer par l'utilisateur valides sauf une vide
        $_POST['identifiant'] = "test";
        $_POST['motdepasse'] = "";
        $_POST['mail'] = "test";
        $_POST['nom'] = "test";
        $_POST['prenom'] = "test";
        // et un services d'inscriptions qui renvoie un resultat hypothétique positif.
        $this->inscriptionService->method("inscription")->willReturn("ok");
        // WHEN On appelle la fonction siging du controller d'inscription
        $returnedView = $this->inscriptioncontroller->signin($this->pdo);
        // THEN L'inscription est n'est pas lancer et on revien sur la page d'inscription
        $expectedView = new View("check-your-mood/views/inscription");
        self::assertEquals($expectedView,$returnedView);
    }

    public function testErrorFromService() {
        // GIVEN des valeurs entrer par l'utilisateur valides
        $_POST['identifiant'] = "test";
        $_POST['motdepasse'] = "test";
        $_POST['mail'] = "test";
        $_POST['nom'] = "test";
        $_POST['prenom'] = "test";
        // et un services d'inscriptions qui renvoie un resultat négatif.
        $this->inscriptionService->method("inscription")->willReturn("nOk");
        // WHEN On appelle la fonction siging du controller d'inscription
        $returnedView = $this->inscriptioncontroller->signin($this->pdo);
        // THEN L'inscription est n'est pas lancer et on revien sur la page d'inscription
        $expectedView = new View("check-your-mood/views/inscription");
        self::assertEquals($expectedView,$returnedView);
    }


}