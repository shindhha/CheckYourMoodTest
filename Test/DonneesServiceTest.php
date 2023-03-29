<?php 
require_once 'yasmf/datasource.php';
require_once 'services/donneesservice.php';
require_once 'Test/DataBase.php';
require_once 'services/moodservice.php';

use services\DonneesService;
use PHPUnit\Framework\TestCase;
use yasmf\DataSource;
use PHPUnit\Framework;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;
use services\MoodService;

class DonneesServiceTest extends TestCase
{
    private $pdo;
    private $services;
    private $moodService;

    protected function setUp(): void
    {
        $this->services = DonneesService::getDefaultDonneesService();
        $this->moodService = MoodService::getDefaultMoodService();
        $this->pdo =  DataBase::getPDOTest();
        $this->pdo->beginTransaction();
    }
    protected function tearDown(): void
    {
        $this->pdo->rollBack();
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
        assertEquals('Jules',$infosParDefauts['prenom']);
        assertEquals('jules.blanchard@iut-rodez.fr',$infosParDefauts['mail']);
    }

    public function testsDonneesUserSuccess()
    {
        // GIVEN Un utilisateur enregistré dans la base de données
        $idUtil = 1;
        $user = [
            'prenom' => 'Jules',
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


        $date = date('Y-m-d ');
        $heure = date('H:i:s');
        $mood = $this->moodService->insertMood($this->pdo, 22,$date,$heure,'aaaaaaa', 1);
        $lastId = $this->pdo->lastInsertId();
        $tab = [
              'id' => 1,
              'codeHumeur' => $lastId,
              'contexte' => "Jadore cette journée"
        ];
        // WHEN On valide les changements
        $result = $this->services->updateHumeur($this->pdo, $tab);
        // THEN La nouvelle description est enregistrer dans la base de données
        $this->assertTrue($result);
        $humeurModifier = $this->pdo->query("SELECT contexte FROM humeur WHERE codeHumeur =".$lastId." AND idUtil = 1");
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
        // GIVEN Un utilisateur ayant saisie un nouveau mot de passe valide
        $idUtil = 1;
        $nouveauMDP = "root2022";
        $exceptedMDP = md5($nouveauMDP);
        // WHEN On valide l'enregistrement du nouveau mot de passe
        $result = $this->services->updateMDP($this->pdo, $idUtil, $nouveauMDP);
        // THEN Le mot de passe est crypté en md5 puis enregistrer dans la base de données
        $content = $this->pdo->query("SELECT motDePasse FROM utilisateur WHERE codeUtil = " . $idUtil);
        $mdpModifier = $content->fetchColumn();
        assertTrue($result);
        $this->assertEquals($exceptedMDP, $mdpModifier);
    }

    public function testUpdateMDPFail()
    {
        // GIVEN Un utilisateur n'etant pas enregistrer dans la base de données
        // n'ayant donc pas de mot de passe
        $idUtil = 999;
        $nouveauMDP = "password";
        // WHEN On modifie son mot de passe
        $result = $this->services->updateMDP($this->pdo, $idUtil, $nouveauMDP);
        // THEN Rien ne se passe et la fonction retourne un resultat négatif
        self::assertFalse($result);
    }
    public function testViewMoodsPagination()
    {
        /* TODO */
        // GIVEN Les humeurs enregistrés par un utilisateur ainsi que ces préférances d'affichage
        $idUtil = 1;
        $indicePremièreHumeur = 0;
        $nbHumeurParPage = 10;
        // Excepted On récupère ses humeurs selon ses préférances
        $result = $this->services->viewMoodsPagination($this->pdo, $idUtil, $indicePremièreHumeur, $nbHumeurParPage);
        $this->assertEquals($nbHumeurParPage, $result->rowCount());
    }

    public function testMdpSuccess()
    {
        // GIVEN Un utilisateur avec un mot de passe
        $idUtil=1 ;
        $mdpAttendu= "63a9f0ea7bb98050796b649e85481845";
        // Excepted On récupère son mot de passe
        $result = $this->services->mdp($this->pdo,$idUtil);
        $this->assertEquals($mdpAttendu,$result->fetchColumn());
    }
}
  

   

 