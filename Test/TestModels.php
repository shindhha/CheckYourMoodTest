<?php
require_once 'yasmf/datasource.php';

use Models\Queries;
use Models\Table;
use PHPUnit\Framework\TestCase;
use yasmf\DataSource;
use services\Mood;
use Models\User;
require_once 'Models/users.php';
require_once 'Test/DataBase.php';
require_once 'Models/Table.php';
require_once 'Models/Queries.php';
use function PHPUnit\Framework\assertEquals;
require_once 'Models/DB.php';

class TestModels extends TestCase
{
    private $pdo;
    private $services;

    protected function setUp(): void
    {
        $this->pdo =  DataBase::getPDOTest();
        $this->pdo->beginTransaction();
    }

    protected function tearDown(): void
    {
        $this->pdo->rollBack();
    }


    public function testFillOnValidData() {
        // GIVEN Un utilisateur sans id (n'existant pas dans la base de données)
        $user = new User();
        // WHEN On lui demande d'enregistrer des valeurs prévus par la base de données
        $user->fill(['nom' => 'Medard']);
        // THEN Il enregistre ces valeurs
        assertEquals('Medard',$user->nom);
    }

    public function testFillOnUnidentifiedData() {
        // GIVEN Un utilisateur sans id (n'existant pas dans la base de données)
        $user = new User();
        // WHEN On lui demande d'enregistrer des valeurs non prévus par la base de données
        $user->fill(['age' => '12']);
        // THEN Il n'enregistre pas ces valeurs
        self::assertNull($user->age);
    }

    public function testSaveOnNonExistLine() {
        // GIVEN Un utilisateur n'ayant pas encore été enregistrer dans la base de données
        $user = new User();
        $user->fill([
            'nom' => 'Medard',
            'prenom' => 'Guillaume',
            'identifiant' => 'guigui',
            'mail' => 'guillaume.medard@iut-rodez.fr',
            'motDePasse' => md5('Test')
        ]);
        // WHEN La méhtode save est appelé
        $user->save();
        // THEN L'utilisateur est enregistré dans la base de données
        $nom = $this->pdo->query("SELECT nom FROM utilisateur where codeUtil = 1")->fetchColumn();
        assertEquals('Medard2',$nom);
    }

    public function testToArray() {
        // GIVEN Un utilisateur n'ayant pas encore été enregistrer dans la base de données avec des données pré enregistrer
        $user = new User();
        $user->fill(['nom' => 'Medard']);
        $user->motDePasse = md5('test');
        // EXCEPTED La fonction toArray() renvoie les attributs de l'utilisateur sous la forme d'un tableau 'cle' => 'valeur'
        assertEquals(['nom' => 'Medard','motDePasse' => md5('test')],$user->toArray());
    }

    public function testsaveOnNoExist() {
        // GIVEN Un utilisateur n'ayant pas encore été enregistrer dans la base de données avec des données pré enregistrer
        $user = new User();
        $user->fill([
            'nom' => 'Medard',
            'prenom' => 'Guillaume',
            'identifiant' => 'guiguidark12',
            'mail' => 'guillaume.medard@iut-rodez.fr',
            'motDePasse' => md5('Test')
        ]);
        // WHEN On appelle la méthode save 
        $user->save();
        // THEN L'utilisateur est enregistrer dans la base de données
        $insertedUser = $this->pdo->query("SELECT * FROM utilisateur WHERE codeUtil = " . $user->getId() )->fetch();
        assertEquals($user->toArray(),$insertedUser);
    }

    public function testSaveOnExist()
    {
        // GIVEN Un utilisateur ayant déjà été enregistrer dans la base de données dans un état.
        // Et avec des attributs d'objet différent.
        $user = new User(1);
        $user->motDePasse = md5('nouveauMDP');
        // WHEN On appelle la méthode save
        $user-save();
        // THEN Les données de l'utilisateur sont mises a jour.
        $insertedUser = $this->pdo->query("SELECT * FROM utilisateur WHERE codeUtil = " . $user->getId() )->fetch();
        assertEquals($user->toArray(),$insertedUser);
    }
}