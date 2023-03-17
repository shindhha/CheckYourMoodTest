<?php 
require_once 'yasmf/datasource.php';
require_once 'services/users.php';
require_once 'Test/DataBase.php';

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
        $this->pdo->beginTransaction();
    }

    protected function tearDown(): void
    {
        $this->pdo->rollBack();
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
        $newMotDePasse = [
            'motDePasse' => md5('TestMotDePasse')
        ];
        // WHEN Il valide la modification de son mot de passe
        $result = $this->services->updateData($this->pdo, $newMotDePasse, $idUtil);
        // THEN Son mot de passe et mis a jour dans la base de données après avoir été cryptées en md5
        $motDePasseModifier = $this->pdo->query("SELECT motDePasse FROM utilisateur WHERE codeUtil = 1");
        $motDePasseModifier = $motDePasseModifier->fetch();
        assertTrue($result);
        assertEquals(md5('TestMotDePasse'),$motDePasseModifier['motDePasse']);


        // GIVEN Un utilisateur ayant saisie un nouvel identifiant valide
        $idUtil = 1;
        $newIdentifiant = [
            'identifiant' => 'guillaume'
        ];
        // WHEN Il valide la modification de son identifiant
        $result = $this->services->updateData($this->pdo, $newIdentifiant, $idUtil);
        // THEN Son identifiant et mis à jour dans la base de données
        $identifiantModifier = $this->pdo->query("SELECT identifiant FROM utilisateur WHERE codeUtil = 1");
        $identifiantModifier = $identifiantModifier->fetch();
        assertTrue($result);
        assertEquals('guillaume',$identifiantModifier['identifiant']);

        // GIVEN Un utilisateur ayant saisie plusieur nouvelles informations valides a propos de son compte
        $idUtil = 1;
        $newInfos = [
            'identifiant' => 'guillaume',
            'mail' => 'guillaume.medard@iut-rodez.fr',
            'motDePasse' => md5('TestMotDePasse')
        ];
        // WHEN Il valide la modification de ses informations
        $result = $this->services->updateData($this->pdo, $newInfos, $idUtil);

        // THEN L'enssemble de ces informations sont mis à jour dans la base de données.
        $infosModifier = $this->pdo->query("SELECT * FROM utilisateur WHERE codeUtil = 1");
        $infosModifier = $infosModifier->fetch();
        assertTrue($result);
        assertEquals('guillaume',$infosModifier['identifiant']);
        assertEquals('guillaume.medard@iut-rodez.fr',$infosModifier['mail']);
        assertEquals(md5('TestMotDePasse'),$infosModifier['motDePasse']);

    }
    public function testsUpdateFaillure()
    {
        // GIVEN Un utilisateur ayant entrer des nouvelles valeurs vides
        $idUtil = 1;
        $emptyNewValues = [
            'nom' => "",
            'prenom' => "",
            'mail' => "",
        ];
        // WHEN Il valide la modification de ces informations
        $result = $this->services->updateData($this->pdo, $emptyNewValues, $idUtil);
        // THEN Les valeurs de la base de données ne sont pas modifier
        $infosParDefauts = $this->pdo->query("SELECT * FROM utilisateur WHERE codeUtil = 1");
        $infosParDefauts = $infosParDefauts->fetch();
        self::assertFalse($result);
        assertEquals('Blanchard',$infosParDefauts['nom']);
        assertEquals('Jules22b',$infosParDefauts['prenom']);
        assertEquals('jules.blanchard@iut-rodez.fr',$infosParDefauts['mail']);
    }

    public function testsDonneesUserSuccess()
    {
        // GIVEN Un utilisateur enregistré dans la base de données
        $idUtil = 1;
        $user = [
            'prenom' => 'Jules22b',
            'nom' => 'Blanchard',
            'identifiant' => 'jules22b',
            'mail' => 'jules.blanchard@iut-rodez.fr',
        ];
        // EXCEPTED On récupère un pdo statement qui contient ses données
        $result = $this->services->donneesUser($this->pdo,$idUtil);
        $this->assertEquals($result->fetch(),$user);

    }

    // Ce test n'est pas passé ( Cause appel du Trigger pour modifier le contexte sous 24h)
    public function testUpdateHumeurSuccess() {


        // GIVEN Une description valide pour une humeur déjà enregistrer
        $tab = [
              'id' => 1,
              'codeHumeur' => 3002,
              'contexte' => "Jadore cette journée"
        ];
        // WHEN On valide les changements
        $result = $this->services->updateHumeur($this->pdo, $tab);
        // THEN La nouvelle description est enregistrer dans la base de données
        $this->assertTrue($result);
        $humeurModifier = $this->pdo->query("SELECT contexte FROM humeur WHERE codeHumeur = 3002 AND idUtil = 1");
        $humeurModifier = $humeurModifier->fetch();
        assertEquals($tab['contexte'],$humeurModifier['contexte']);
    }

    public function testUpdateHumeurFailure()
    {
        /* TODO  - Guillaume -> J'ai pas compris pourquoi sa devait échouer */

        $tab = [
            'id' => 1,
            'codeHumeur' => 56,
            'contexte' => "Happy"
        ];
        $expectedResult = false;
        // Appelez la méthode à tester avec les données de test
        $result = $this->services->updateHumeur($this->pdo, $tab);

        // Assurez-vous que les résultats de la méthode sont ceux que vous attendez
        $this->assertEquals($expectedResult, $result);
    }

    public function testNombreHumeur()
    {
        /* TODO testNombreHumeur */
        $idUtil = 1 ;
        // Appeler la fonction à tester
        $result = $this->services->nombreHumeur($this->pdo, $idUtil);
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
  

   

 