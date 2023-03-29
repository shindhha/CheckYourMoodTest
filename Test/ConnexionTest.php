<?php 
use PHPUnit\Framework\TestCase;
use yasmf\DataSource;

use services\HomeService;
require_once 'services/homeservice.php';

class ConnexionTest extends TestCase
{
    private $pdo;
    private $services;
    protected function setUp(): void
    {
        $this->pdo =  DataBase::getPDOTest();
        $this->services = HomeService::getDefaultHomeService();
        $this->pdo->beginTransaction();
    }
    protected function tearDown(): void
    {
        $this->pdo->rollBack();
    }
    public function testConnexionSucces(){

        
        // Préparez les données de TestControllers
        $idUtil = 'jules22b';
        $mdpUtil = 'root';
        $expectedResult = [
            "util" => "1",
            "nom" => "Blanchard",
            "prenom" => "Jules",
            "mail" => "jules.blanchard@iut-rodez.fr"
        ];
        // Appelez la méthode à tester avec les données de TestControllers
        $result = $this->services->connexion($this->pdo, $idUtil, $mdpUtil);
        
        // Assurez-vous que les résultats de la méthode sont ceux que vous attendez
        $this->assertEquals($result,$expectedResult );
    }
}

