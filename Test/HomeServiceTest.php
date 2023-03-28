<?php 
require_once 'services/HomeService.php';
require_once 'yasmf/datasource.php';
require_once 'Test/DataBase.php';
use services\HomeService;
use PHPUnit\Framework\TestCase;
class HomeServiceTest extends TestCase
{
    private $pdo;
    private $service;
    protected function setUp(): void
    {
        $this->pdo =  DataBase::getPDOTest();
        $this->pdo->beginTransaction();
        $this->service = HomeService::getDefaultHomeService();
    }

    protected function tearDown(): void
    {
        $this->pdo->rollBack();
    }

    public function testConnexionSucces(){
        

        $idUtil = 'idTest1';
        $mdpUtil = 'test';
        $expectedResult = [
            "nom" => "nomTest1",
            "prenom" => "prenomTest1",
            "mail" => "mail.test@test.test",
            "idUtil" => 6

        ];
        $result = $this->service->connexion($this->pdo, $idUtil, $mdpUtil);
        
        $this->assertEquals($expectedResult,$result);
    }
}

