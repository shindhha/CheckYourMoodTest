<?php 
require_once 'yasmf\DataSource.php';
require_once 'services/DonneesService.php';
require_once 'Test\DataBase.php';

use services\DonneesService;
use PHPUnit\Framework\TestCase;
use yasmf\DataSource;
use PHPUnit\Framework;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class DonneesServiceTest extends TestCase
{
    private $pdo;
    private $services;

    protected function setUp(): void
    {
        $this->services = DonneesService::getDefaultDonneesService();
        $this->pdo =  DataBase::getPDOTest();
    }

    public function getDataSet() {
        return new MyApp_DbUnit_ArrayDataSet(array(
            [
                'utilisateur' => [
                    'codeUtil' => 1,
                    'prenom' => 'Jules22b',
                    'nom' => 'Blanchard',
                    'identifiant' => 'jules22b',
                    'mail' => 'jules.blanchard@iut-rodez.fr',
                    'motDePasse' => '0cbc6611f5540bd0809a388dc95a615b'
                ]
            ]
        ));
    }
    public function testsUpdateDataSucces()
    {

        // GIVEN Un utilisateur ayant saisie un nouveau mot de passe valide
        $idUtil = 1;
        $tabMotDePasse = [
            'motDePasse' => 'TestMotDePasse'
        ];
        // WHEN Il valide la modification de son mot de passe
        $result = $this->services->updateData($this->pdo, $tabMotDePasse, $idUtil);
        // THEN Son mot de passe et mis a jour dans la base de données après avoir été cryptées en md5
        $motDePasseModifier = $this->pdo->query("SELECT motDePasse FROM utilisateur WHERE codeUtil = 1");
        $motDePasseModifier = $motDePasseModifier->fetchAll();
        assertTrue($result);
        assertEquals(md5('TestMotDePasse'),$motDePasseModifier[0]['motDePasse']);
        // GIVEN Un utilisateur ayant saisie un nouvel identifiant valide
        // WHEN Il valide la modification de son identifiant
        // THEN Son identifiant et mis à jour dans la base de données

        // GIVEN Un utilisateur ayant saisie plusieur nouvelles informations valides a propos de son compte
        // WHEN Il valide la modification de ses informations
        // THEN L'enssemble de ces informations sont mis à jour dans la base de données.

        /* -------------------------- ANCIEN TEST -------------------------- */
        /*
        $tab = [  
            'nom' => 'Blanchard',
            'prenom' => 'Jules22b',
            'mail' => 'jules.blanchard@iut-rodez.fr',

            
        ];
        $util = 1;
        $expectedResult = true;
            
        
        // Appelez la méthode à tester avec les données de test
        $result = $this->services->updateData($pdo, $tab, $util);

        // Assurez-vous que les résultats de la méthode sont ceux que vous attendez
        $this->assertEquals($expectedResult, $result);
        */

    }
    public function testsUpdateFaillure()
    {
        // Préparez les données de test
        $pdo = DataBase::getPDOTest();
        $services = DonneesService::getDefaultDonneesService();
        $tab = [  
            'nom' => "",
            'prenom' => "",
            'mail' => "",
        ];
        $util = 1;
        $expectedResult = false;

        // Appelez la méthode à tester avec les données de test
        $result = $services->updateData($pdo, $tab, $util);

        // Assurez-vous que les résultats de la méthode sont ceux que vous attendez
        $this->assertEquals($expectedResult, $result);

    }

    public function testsDonneesUserSuccess()
    {
        $pdo = DataBase::getPDOTest();
        $services = DonneesService::getDefaultDonneesService();
        $nomAttendu = "Blanchard";
        $prenomAttendu = "Jules22b";
        $idUtil =1;

        $result = $services->donneesUser($pdo,$idUtil);

        $this->assertEquals($result->fetch()['prenom'],$prenomAttendu);

    }
    public function testDonnesUserSuccess()
    {
        $pdo = DataBase::getPDOTest();
        $services = DonneesService::getDefaultDonneesService();
        $idUtil = 2 ; // utilisateur inexistant
        
        $result = $services->donneesUser($pdo,$idUtil);

        $this->assertEquals($result->rowCount(),0);
    }

    // Ce test n'est pas passé ( Cause appel du Trigger pour modifier le contexte sous 24h)
    public function testUpdateHumeurSuccess(){
        // Préparez les données de test
        $pdo = DataBase::getPDOTest();
        $services = DonneesService::getDefaultDonneesService();
        $tab = [
              'id' => 1,
              'codeHumeur' => 3002,
              'contexte' => "Jadore cette journée"
        ];
        $expectedResult = true;
        // Appelez la méthode à tester avec les données de test
        $result = $services->updateHumeur($pdo, $tab);
  
        // Assurez-vous que les résultats de la méthode sont ceux que vous attendez
        $this->assertEquals($expectedResult, $result);
    }

    public function testUpdateHumeurFailure()
    {
        // Préparez les données de test
        $pdo = DataBase::getPDOTest();
        $services = DonneesService::getDefaultDonneesService();
        $tab = [
            'id' => 1,
            'codeHumeur' => 56,
            'contexte' => 'Happy'
        ];
        $expectedResult = false;

       

        // Appelez la méthode à tester avec les données de test
        $result = $services->updateHumeur($pdo, $tab);

        // Assurez-vous que les résultats de la méthode sont ceux que vous attendez
        $this->assertEquals($expectedResult, $result);
    }

    public function testNombreHumeur()
    {
        
        $pdo = DataBase::getPDOTest();
        $idUtil = 1 ;
        $services = DonneesService::getDefaultDonneesService();
        // Appeler la fonction à tester
        $result = $services->nombreHumeur($pdo, $idUtil);
       

        // Assertion pour vérifier le résultat attendu
        $this->assertTrue($result->fetchColumn()>= 200);
    }
    
    public function testUpdateMdp()
    {
        // Prépare les données de test (exemple : insérer un utilisateur avec un mot de passe dans la base de données)
        $idUtil = 1;
        $nouveauMDP = "root2022";
        $nvMDP = md5($nouveauMDP);
        $pdo = DataBase::getPDOTest();
    
        
        $services = DonneesService::getDefaultDonneesService();
        // Appele la fonction à tester
        $result = $services->updateMDP($pdo, $idUtil, $nouveauMDP);

        // Vérifie que le mot de passe a été mis à jour dans la base de données
        $sql = "SELECT motDePasse FROM utilisateur WHERE codeUtil = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $idUtil);
        $stmt->execute();
        $password = $stmt->fetchColumn();
        $this->assertEquals($nvMDP, $password);
    }

    public function testUpdateMDPFail()
    {
        // Prépare les données de test (exemple : utiliser un id utilisateur qui n'existe pas dans la base de données)
        $idUtil = 999;
        $nouveauMDP = "password";
       
        $pdo = DataBase::getPDOTest();
        // Instancie la classe contenant la fonction à tester
        $services = DonneesService::getDefaultDonneesService();

        // Appeler la fonction à tester
        $result = $services->updateMDP($pdo, $idUtil, $nouveauMDP);

        // Vérifier que la mise à jour n'a pas réussi (aucune ligne n'a été affectée)
        $this->assertEquals(0, $result->rowCount());
    }
    public function testViewMoodsPagination()
    {
        // Préparer les données de test (exemple : insérer des humeurs dans la base de données)
        $idUtil = 1;
        $premier = 0;
        $parPage = 10;
        $pdo = DataBase::getPDOTest();
        // Instancie la classe contenant la fonction à tester
        $services = DonneesService::getDefaultDonneesService();
        

        // Appeler la fonction à tester
        $result = $services->viewMoodsPagination($pdo, $idUtil, $premier, $parPage);


        // Vérifier le nombre d'humeurs retournées
        $this->assertEquals($parPage, $result->rowCount());
    }

    public function testMdpSuccess()
    {

        $idUtil=1 ;
        $mdpAttendu= "802914f9f0eb162333be54e12dddeb6b";
        $pdo = DataBase::getPDOTest();
        // Instancie la classe contenant la fonction à tester
        $services = DonneesService::getDefaultDonneesService();
        $result = $services->mdp($pdo,$idUtil);

        $this->assertEquals($mdpAttendu,$result->fetch()['motDePasse']);


    }
}
  

   

 