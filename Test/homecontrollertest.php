<?php
namespace Test;
require_once 'services/homeservice.php';
require_once 'services/donneesservice.php';
require_once 'controllers/homecontroller.php';
require_once 'services/moodservice.php';

use controllers\HomeController;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use services\donneesservice;
use services\homeservice;
use services\moodservice;
use yasmf\View;


class homecontrollertest extends TestCase
{
    private PDO $pdo;
    private homeservice $HomeService;
    private donneesservice $DonneesService;
    private moodservice $MoodService;
    private PDOStatement $PDOStatement;
    private HomeController $HomeController;

    protected function setUp(): void
    {
        $this->pdo =  $this->createStub(PDO::class);
        $this->HomeService = $this->createStub(HomeService::class);
        $this->DonneesService = $this->createStub(donneesservice::class);
        $this->PDOStatement = $this->createStub(PDOStatement::class);
        $this->MoodService = $this->createStub(moodservice::class);
        $this->HomeController = new HomeController($this->DonneesService,$this->HomeService,$this->MoodService);
    }
    public function testIndex(){
        $view = $this->HomeController->index();
        self::assertEquals($view->getRelativePath(), "check-your-mood/views/accueil");
    }
    public function testGoTo(){
        $_POST['namepage'] = 'humeurs';
        $expected = new View("check-your-mood/views/humeurs");
        $view = $this->HomeController->goTo();
        self::assertEquals("check-your-mood/views/humeurs",$view->getRelativePath());
        self::assertEquals($expected,$view);

    }
    public function testGoTo2(){
        $_POST['namepage'] = 'connexion';
        $expected = new View("check-your-mood/views/connexion");
        $view = $this->HomeController->goTo();
        self::assertEquals("check-your-mood/views/connexion",$view->getRelativePath());
        self::assertEquals($expected,$view);
    }
    public function testLoginfail(){
        $_POST['identifiant'] = 'TestControllers';
        $_POST['motdepasse'] = 'TestControllers';
        $expected = new View("check-your-mood/views/connexion");
        $this->HomeService->method('connexion')->willReturn(array('util' => 0));
        $view =  $this->HomeController->login($this->pdo);
        self::assertEquals("check-your-mood/views/connexion",$view->getRelativePath());
        self::assertEquals($expected,$view);
    }

    public function testLoginsuccess(){
        $_POST['identifiant'] = 'jules22b';
        $_POST['motdepasse'] = 'root';
        $this->HomeService->method('connexion')->willReturn(array('util' => 1));
        $this->DonneesService->method('nombreHumeur')->willReturn($this->PDOStatement);
        $this->PDOStatement->method('fetchColumn')->willReturn(1);
        $this->MoodService->method('libelles')->willReturn($this->PDOStatement);
        $view =  $this->HomeController->login($this->pdo);
        self::assertEquals("check-your-mood/views/humeurs",$view->getRelativePath());
    }
}