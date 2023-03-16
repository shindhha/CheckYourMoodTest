<?php 
use PHPUnit\Framework\TestCase;
use yasmf\DataSource;
require_once 'yasmf\DataSource.php';
use services\HomeService;
require_once 'services/HomeService.php';

use DataBase;
require_once 'Test/DataBase.php';

class ConnexionTest extends TestCase
{
    
    public function testConnexionSucces(){
        
        $pdo = DataBase::getPDOTest();
        $compte = HomeService::getDefaultHomeService();        
        
        // Préparez les données de test
        $idUtil = 'jules22b';
        $mdpUtil = 'root2022';
        $expectedResult = [
            "util" => "1",
            "nom" => "Blanchard",
            "prenom" => "Jules22b",
            "mail" => "jules.blanchard@iut-rodez.fr"
        ];
        // Appelez la méthode à tester avec les données de test
        $result = $compte->connexion($pdo, $idUtil, $mdpUtil);
        
        // Assurez-vous que les résultats de la méthode sont ceux que vous attendez
        $this->assertEquals($result,$expectedResult );
    }
}

