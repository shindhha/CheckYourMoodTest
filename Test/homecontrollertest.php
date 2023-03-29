<?php
namespace controllers;
require_once 'services/homeservice.php';
require_once 'services/donneesservice.php';
require_once 'controllers/homecontroller.php';
require_once 'services/moodservice.php';
use services\moodservice;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use services\homeservice;
use services\donneesservice;
use yasmf\HttpHelper;


class homecontrollertest extends TestCase
{
    private PDO $pdo;
    private homeservice $HomeService;
    private donneesservice $DonneesService;
    private moodservice $MoodService;
    private PDOStatement $PDOStatement;
    private HomeController $HomeController;
    private HttpHelper $HttpHelper;

    protected function setUp(): void
    {
        $this->pdo =  $this->createStub(PDO::class);
        $this->homeservice = $this->createStub(HomeService::class);
        $this->DonneesService = $this->createStub(donneesservice::class);
        $this->PDOStatement = $this->createStub(PDOStatement::class);
        $this->MoodService = $this->createStub(moodservice::class);
        $this->HomeController = new HomeController($this->donneesservice,$this->homeservice,$this->moodservice);
    }
    public function testIndex(){
        $view = $this->HomeController->index();
        self::assertEquals($view->getRelativePath(), "check-your-mood/views/accueil");
    }
    public function testGoTo(){
        $_POST['namepage'] = 'humeurs';
        $view = $this->HomeController->index();

        $view = $this->HomeController->goTo();
        self::assertEquals("check-your-mood/views/humeurs",$view->getRelativePath());
    }
    public function testLogin(){


    }
}