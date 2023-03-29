<?php

namespace services;

use DataBase;
use PHPUnit\Framework\TestCase;

require_once 'yasmf/datasource.php';

require_once 'services/moodservice.php';

require_once 'Test/DataBase.php';
use PDOStatement;

class HumeurServiceTest extends TestCase
{
    private $pdo;
    private $services;

    protected function setUp(): void
    {
        $this->services = MoodService::getDefaultMoodService();
        $this->pdo = DataBase::getPDOTest();
        $this->pdo->beginTransaction();
    }

    protected function tearDown(): void
    {
        $this->pdo->rollBack();
    }

    public function testLibelles()
    {


        // Appeler la fonction à tester
        $result = $this->services->libelles($this->pdo);
        // Assertion
        $this->assertEquals($result->rowCount(), 27);
    }


    public function testInsertMoodFailed()
    {
        // Préparer les données de TestControllers

        $code = 0;//humeur invalide
        $date = '2023-14-01';
        $heure = '12:00:00';
        $contexte = 'Test fail';
        $util = 1;

        // Appeler la fonction à tester
        $result = $this->services->insertMood($this->pdo, $code, $date, $heure, $contexte, $util);

        // Assertions
        $this->assertEquals("nOk", $result);
        $code = 22; // humeur valide
        $date = '2022-14-01';// invalide
        $result = $this->services->insertMood($this->pdo, $code, $date, $heure, $contexte, $util);
        $this->assertEquals("nOk", $result);

    }

    // A revoir test non fonctionnelle
    public function testInsertMoodSuccess()
    {
        // Préparer les données de TestControllers
        $code = 22;
        $date = date("Y-m-d");
        $heureActuelle = date("H");
        $heure = date("H", strtotime("-1 hour", $heureActuelle));
        $contexte = 'TestControllers success';
        $util = 1;

        // Appeler la fonction à tester
        $result = $this->services->insertMood($this->pdo, $code, $date, $heure, $contexte, $util);

        // Assertions
        $this->assertEquals("ok", $result);
    }


    public function testViewMoods()
    {
        // Préparer les données de TestControllers
        $idUtil = 1;

        // Appeler la fonction à tester
        $result = $this->services->viewMoods($this->pdo, $idUtil);

        // Assertions
        $this->assertInstanceOf(PDOStatement::class, $result);
        $this->assertTrue($result->fetchAll() >= 1);
    }

    public function testViewMoodsWithNoMoods()
    {
        // Préparer les données de TestControllers

        $idUtil = 2;
        // Appeler la fonction à tester
        $result = $this->services->viewMoods($this->pdo, $idUtil);

        // Assertions
        $this->assertInstanceOf(PDOStatement::class, $result);
        $this->assertEmpty($result->fetchAll());
    }
}    