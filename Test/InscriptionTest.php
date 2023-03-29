<?php 
use PHPUnit\Framework\TestCase;
use yasmf\DataSource;
use services\InscriptionService;
require_once 'services/inscriptionservice.php';
require_once 'yasmf/datasource.php';
require_once 'Test/DataBase.php';

class InscriptionTest extends TestCase
{
    private $pdo;
    private $services;
    protected function setUp(): void
    {
        $this->pdo =  DataBase::getPDOTest();
        $this->services = InscriptionService::getDefaultInscriptionService();
        $this->pdo->beginTransaction();
    }

    protected function tearDown(): void
    {
        $this->pdo->rollBack();
    }
    public function testInscriptionSucces(){

        $randomId = "TestControllers";
        $mdp = "password";
        $mail ="amine.d2amouch@gmail.com";
        $nom = "daamouch";
        $prenom = "amine";

        $result = $this->services->inscription($this->pdo, $randomId, $mdp, $mail, $nom, $prenom);
       
        $this->assertEquals("ok",$result);
        
    }
    
    public function testInscriptionFailled(){
        /// atribut null 
        // nous ne mettons pas des paramètres du type $id =" " 
        // car la saisie est vérfier en php donc on initialise directement à null    
        $id = null;
        $mdp = null;
        $mail = null;
        $nom = null;
        $prenom = null;

        $result = $this->services->inscription($this->pdo, $id, $mdp, $mail, $nom, $prenom);

        $this->assertEquals("nOk",$result);
    }


}