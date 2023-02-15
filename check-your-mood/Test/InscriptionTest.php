<?php 
use PHPUnit\Framework\TestCase;
use yasmf\DataSource;
require_once 'yasmf\DataSource.php';

use services\InscriptionService;
require_once 'services/InscriptionService.php';

require_once 'Test/DataBase.php';

class InscriptionTest extends TestCase
{
    
    public function testInscriptionSucces(){
    
        $pdo = DataBase::getPDOTest();
        $services = InscriptionService::getDefaultInscriptionService();
        $randomId = "test";
        $mdp = "password";
        $mail ="amine.d2amouch@gmail.com";
        $nom = "daamouch";
        $prenom = "amine";

        $result = $services->inscription($pdo, $randomId, $mdp, $mail, $nom, $prenom);
       
        $this->assertEquals("ok",$result);
        
    }
    
    public function testInscriptionFailled(){
        $pdo = DataBase::getPDOTest();
        $services = InscriptionService::getDefaultInscriptionService();
        /// atribut null 
        // nous ne mettons pas des paramètres du type $id =" " 
        // car la saisie est vérfier en php donc on initialise directement à null    
        $id = null;
        $mdp = null;
        $mail = null;
        $nom = null;
        $prenom = null;

        $result = $services->inscription($pdo, $id, $mdp, $mail, $nom, $prenom);

        $this->assertEquals("nOk",$result);
    }


}