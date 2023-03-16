<?php 
use PHPUnit\Framework\TestCase;
use yasmf\DataSource;
require_once 'yasmf\DataSource.php';
use services\DonneesService;
require_once 'services/DonneesService.php';

use DataBase;
require_once 'Test/DataBase.php';

class DonneesServiceTest extends TestCase
{
    public function testsUpdateDataSucces()
    {

        $services = DonneesService::getDefaultDonneesService();
        $pdo =  DataBase::getPDOTest();
        $tab = [  
            'nom' => 'Blanchard',
            'prenom' => 'Jules22b',
            'mail' => 'jules.blanchard@iut-rodez.fr',
            
        ];
        $util = 1;
        $expectedResult = true;
            
        
        // Appelez la méthode à tester avec les données de test
        $result = $services->updateData($pdo, $tab, $util);

        // Assurez-vous que les résultats de la méthode sont ceux que vous attendez
        $this->assertEquals($expectedResult, $result);


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
  

   

 