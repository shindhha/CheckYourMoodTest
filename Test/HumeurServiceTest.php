<?php 
use PHPUnit\Framework\TestCase;
use yasmf\DataSource;
require_once 'yasmf\DataSource.php';

use services\MoodService;
require_once 'services/MoodService.php';

require_once 'Test/DataBase.php';


class HumeurServiceTest extends TestCase
{
   
    public function testLibelles(){
    
        $pdo = DataBase::getPDOTest();
        $services = MoodService::getDefaultMoodService();
        
        // Appeler la fonction à tester
        $result = $services->libelles($pdo);
        // Assertion
        $this->assertEquals($result->rowCount(),27 );
    }


    public function testInsertMoodFailed()
    {
        // Préparer les données de test
        $pdo = DataBase::getPDOTest();
        $services = MoodService::getDefaultMoodService();
        $code = 0;//humeur invalide
        $date = '2023-14-01';
        $heure = '12:00:00';
        $contexte = 'Test fail';
        $util = 1;

        // Appeler la fonction à tester
        $result = $services->insertMood($pdo, $code, $date, $heure, $contexte, $util);

        // Assertions
        $this->assertEquals("nOk", $result);
        $code = 22 ; // humeur valide
        $date = '2022-14-01';// invalide
        $result = $services->insertMood($pdo, $code, $date, $heure, $contexte, $util);
        $this->assertEquals("nOk", $result);

    }
    // A revoir test non fonctionnelle
    public function testInsertMoodSuccess()
    {
        // Préparer les données de test
        $pdo = DataBase::getPDOTest();
        $services = MoodService::getDefaultMoodService();
        $code = 22;
        $date = date("Y-m-d");
        $heureActuelle = date("H");
        $heure = date("H", strtotime("-1 hour", $heureActuelle));
        $contexte = 'test success';
        $util = 1;
       
        // Appeler la fonction à tester
        $result = $services->insertMood($pdo, $code, $date, $heure, $contexte, $util);

        // Assertions
        $this->assertEquals("ok", $result);
    }


    public function testViewMoods()
    {
        // Préparer les données de test
        $pdo = DataBase::getPDOTest();
        $services = MoodService::getDefaultMoodService();
        $idUtil = 1;

        // Appeler la fonction à tester
        $result = $services->viewMoods($pdo, $idUtil);

        // Assertions
        $this->assertInstanceOf(PDOStatement::class, $result);
        $this->assertTrue($result->fetchAll() >= 1 );
    }

    public function testViewMoodsWithNoMoods()
    {
        // Préparer les données de test
        $pdo = DataBase::getPDOTest();
        $services = MoodService::getDefaultMoodService();
        $idUtil = 2 ;
        // Appeler la fonction à tester
        $result = $services->viewMoods($pdo, $idUtil);

        // Assertions
        $this->assertInstanceOf(PDOStatement::class, $result);
        $this->assertEmpty($result->fetchAll());
    }
}    