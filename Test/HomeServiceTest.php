<?php 
require_once 'services/HomeService.php';
require_once 'yasmf/datasource.php';
require_once 'Test/DataBase.php';
require_once 'Modeles/QueryBuilder.php';
use services\HomeService;
use PHPUnit\Framework\TestCase;
use Modeles\QueryBuilder;
class HomeServiceTest extends TestCase
{
    private $pdo;
    private $service;
    protected function setUp(): void
    {
        $this->pdo =  DataBase::getPDOTest();
        $this->pdo->beginTransaction();
        $this->service = HomeService::getDefaultHomeService();
        QueryBuilder::setDBSource($this->pdo);
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

    public function test() {
        $query = QueryBuilder::Table('utilisateur')
            ->select('codeUtil','prenom','nom','mail')
            ->where('identifiant',"identifiant")
            ->where('motDePasse',md5("mdpUtil"));

        $this->assertEquals(['identifiant' => 'identifiant','motDePasse'=>md5("mdpUtil")],$query->getParams());
        $this->assertEquals("SELECT codeUtil,prenom,nom,mail FROM utilisateur WHERE identifiant = :identifiant AND motDePasse = :motDePasse",$query->getQuery());
        $this->assertInstanceOf(PDOStatement::class,$query->execute());
    }
}

