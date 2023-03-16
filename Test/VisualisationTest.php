<?php 
use PHPUnit\Framework\TestCase;
use yasmf\DataSource;
require_once 'yasmf\DataSource.php';

use services\VisualisationService;
require_once 'services/VisualisationService.php';

require_once 'Test/DataBase.php';

class VisualisationTest extends TestCase
{


    public function testVisualisationRadarSuccess(){
       
        $pdo = DataBase::getPDOTest();
        $services = VisualisationService::getDefaultVisualisationService(); 
        $tabSemaine = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 1,
            5 => 0,
            6 => 0,
            7 => 0
        ];
        $idUtil = 1;
        $code = 22;
        $week = 1;
        $anneeAComparer ='2022';

        $result = $services->visualisationRadar($pdo, $idUtil, $code, $week, $anneeAComparer);

        $this->assertEquals($tabSemaine,$result);
    }
    public function testVisualisationRadarFailled(){
       
        $pdo = DataBase::getPDOTest();
        $services = VisualisationService::getDefaultVisualisationService(); 
        $tabSemaine = [
            1 => 0,
            2 => 0,
            3 => 1,
            4 => 0,
            5 => 0,
            6 => 0,
            7 => 0
        ];
        $idUtil = 1;
        $code = 22;
        $week = 1;
        $anneeAComparer ='2022';

        $result = $services->visualisationRadar($pdo, $idUtil, $code, $week, $anneeAComparer);

        $this->assertFalse($tabSemaine == $result);
    }
    public function testVisualisationDoughnutSuccess(){
        $pdo = DataBase::getPDOTest();
        $services = VisualisationService::getDefaultVisualisationService(); 
        $date = '2022-01-01';
        $idUtil = 1 ;
        $tab = [
            'Peur'=> 1,
            'Soulagement'=> 1
        ];
        $result = $services->visualisationDoughnut($pdo, $idUtil, $date);
        $this->assertEquals($result,$tab);
    }

    public function testVisualisationDoughnutFailled(){
        $pdo = DataBase::getPDOTest();
        $services = VisualisationService::getDefaultVisualisationService(); 
        $date = '2022-01-01';
        $idUtil = 1 ;
        $tab = [
            'Peur'=> 2,
            'Soulagement'=> 1
        ];
        $result = $services->visualisationDoughnut($pdo, $idUtil, $date);
        $this->assertFalse($result == $tab);
    }
    public function testVisualisationTableauSuccess(){
        $pdo = DataBase::getPDOTest();
        $services = VisualisationService::getDefaultVisualisationService();
        $week = 4;
        $idUtil = 1 ;
        $anneeAComparer = '2022';
        
        $result = $services->visualisationTableau($pdo,$idUtil,$week,$anneeAComparer);
        // vérification du nombre ligne identique
        $this->assertTrue($result->rowCount()==25);
    }
    public function testVisualisationTableauFailled(){
        $pdo = DataBase::getPDOTest();
        $services = VisualisationService::getDefaultVisualisationService();
        $week = 4;
        $idUtil = 1 ;
        $anneeAComparer = '2022';
        
        $result = $services->visualisationTableau($pdo,$idUtil,$week,$anneeAComparer);
        // vérification du nombre ligne identique
        $this->assertFalse($result->rowCount()==0);
    }

    public function testVisualisationHumeurAnneeLaPlusSuccess(){
        $pdo = DataBase::getPDOTest();
        $services = VisualisationService::getDefaultVisualisationService();
        $idUtil = 1;
        $year ='2022';
        
        $result = $services->visualisationHumeurAnneeLaPlus($pdo,$idUtil,$year);

        $this->assertTrue($result->fetchAll()[0]['libelle'] == 'Adoration');
    }
    public function testVisualisationHumeurAnneeLaPlus(){
        $pdo = DataBase::getPDOTest();
        $services = VisualisationService::getDefaultVisualisationService();
        $idUtil = 1;
        $year ='2022';
        
        $result = $services->visualisationHumeurAnneeLaPlus($pdo,$idUtil,$year);

        $this->assertFalse($result->fetchAll()[0]['libelle'] == 'Soulagement');
    }

    
    public function testVisualisationHumeurJourSuccess(){
        $pdo = DataBase::getPDOTest();
        $services = VisualisationService::getDefaultVisualisationService();
        $idUtil = 1;
        $dateJour = '2022-01-01' ;
        $result = $services->visualisationHumeurJour($pdo, $idUtil, $dateJour);
        $resultatAttendu = 'Soulagement';
        $this->assertEquals($result->fetchAll()[0]['libelle'],$resultatAttendu );
    }


    public function testVisualisationHumeurJourFailled(){
        $pdo = DataBase::getPDOTest();
        $services = VisualisationService::getDefaultVisualisationService();
        $idUtil = 1;
        // date avec aucun ajout d'humeur
        $dateJour = '2010-01-01';
        $result = $services->visualisationHumeurJour($pdo, $idUtil, $dateJour);
        $this->assertEquals($result->rowCount(),0);
    }


    public function testVisualisationHumeurSemaineSuccess(){
        $pdo = DataBase::getPDOTest();
        $services = VisualisationService::getDefaultVisualisationService();
        $idUtil = 1;
        $week = 1;
        $result = $services->VisualisationHumeurSemaine($pdo,$idUtil,$week);
        $resultatAttendu = 'Peur';

        $this->assertTrue($result->fetchAll()[0]['libelle'] == $resultatAttendu);
    }
    public function testVisualisationHumeurSemaineFailled(){
        $pdo = DataBase::getPDOTest();
        $services = VisualisationService::getDefaultVisualisationService();
        $idUtil = 1;
        $week = 1;
        $result = $services->VisualisationHumeurSemaine($pdo,$idUtil,$week);
        $resultatAttendu ='Joie';// mauvais résultat

        $this->assertFalse($result->fetchAll()[0]['libelle']==$resultatAttendu);
    }

    public function testVisualisationHumeurAnneeSuccess(){
        $pdo = DataBase::getPDOTest();
        $services = VisualisationService::getDefaultVisualisationService();
        $idUtil = 1;
        $annee ='2022' ;
        $libelle = 22;
        $tabAttendu=[  'janvier'=> 7, 
        ];

        $tab = $services->visualisationHumeurAnnee($pdo,$idUtil,$annee,$libelle);

        $this->assertEquals($tab[0], $tab[0] );
        $this->assertEquals($tab[0]['y'],$tabAttendu['janvier']);
    }
    public function testVisualisationHumeurAnneeFailled(){
        $pdo = DataBase::getPDOTest();
        $services = VisualisationService::getDefaultVisualisationService();
        $idUtil = 1;
        $annee ='2022' ;
        $libelle = 22;
        $tabAttendu=[  'janvier'=> 5,//nombre incorrecte d'humeur pour le mois de janvier 
        ];

        $tab = $services->visualisationHumeurAnnee($pdo,$idUtil,$annee,$libelle);

        
        $this->assertFalse($tab[0]['y']==$tabAttendu['janvier']);
    }

    
    

    public function testGetCurrentDay(){
        $pdo = DataBase::getPDOTest();
        $services = VisualisationService::getDefaultVisualisationService();
        
        // prendre le jour actuel pour tester
        $jour = date("Y-m-d");

        $result = $services->getCurrentDay($pdo);

        $this->assertEquals($result->fetchAll()[0]['day'],$jour);
    }

    public function testGetCurrentWeek(){
        $pdo = DataBase::getPDOTest();
        $services = VisualisationService::getDefaultVisualisationService();
        
        
        $week = 3;

        $result = $services->getCurrentWeek($pdo);

        $this->assertEquals($result->fetchAll()[0]['week'],$week);
    }

    public function testGetCurrentYear(){
        $pdo = DataBase::getPDOTest();
        $services = VisualisationService::getDefaultVisualisationService();
        
        // prendre l'année actuelle pour tester
        $year = '2023';

        $result = $services->getCurrentYear($pdo);

        $this->assertEquals($result->fetchAll()[0]['year'],$year);
    }
}
