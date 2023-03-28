<?php
require_once 'yasmf/datasource.php';

use Models\Table;
use PHPUnit\Framework\TestCase;
use yasmf\DataSource;
use services\Mood;
use Models\User;
require_once 'Test/DataBase.php';
require_once 'Modeles/Table.php';
require_once 'Modeles/QueryBuilder.php';
use function PHPUnit\Framework\assertEquals;
use Modeles\QueryBuilder;
class TestQueryBuilder extends TestCase
{
    private $pdo;
    private $services;

    protected function setUp(): void
    {
        $this->pdo =  DataBase::getPDOTest();
        $this->pdo->beginTransaction();
        QueryBuilder::setDBSource($this->pdo);
    }

    protected function tearDown(): void
    {
        $this->pdo->rollBack();
    }


    public function testUpdate() {
        // GIVEN Un objet QueryBuilder sur la table 'utilisateur' sur lequel
        // on demande de construire une requete de type UPDATE
         $query = QueryBuilder::Table('utilisateur')
                  ->update(['nom' => 'Medard']);
        // EXCEPTED On obtient la requete attendue
        $qStmt = $query->getQuery();
        assertEquals("UPDATE utilisateur SET nom = :nom",$qStmt);
    }

    public function testInsert() {
        // GIVEN Un objet QureyBuilder sur la table 'utilisateur' sur lequel
        // on demande de construire une requete de type INSERT
        $query = QueryBuilder::Table('utilisateur')->insert(['nom' => 'Medard']);
        // EXCEPTED On obtient la requete attendue
        $qStmt = $query->getQuery();
        assertEquals("INSERT INTO utilisateur (nom) VALUES (:nom)",$qStmt);
    }


    public function testSelectWithNoArgument() {
        // GIVEN Un objet QueryBuilder sur la table 'utilisateur' sans methode select appeler
        $queryWithoutSelectMethod = QueryBuilder::Table('utilisateur')->getQuery();
        // et un objet QueryBuilder sur la table 'utilisateur' avec la methode select sans argument.
        $queryWithSelectWithoutArguments = QueryBuilder::Table('utilisateur')->select()->getQuery();
        // EXCEPTED Les requetes des deux objet sont égales
        assertEquals($queryWithSelectWithoutArguments,$queryWithoutSelectMethod);
        // Et correspondent a la requete attendu
        assertEquals("SELECT * FROM utilisateur",$queryWithoutSelectMethod);
    }

    public function testSelectWithArguments() {
        // GIVEN Un objet QueryBuilder sur la table 'utilisateur' en ayant spécifier
        // plusieurs colonnes avec la méthode select
        $query = QueryBuilder::Table('utilisateur')->select('nom','prenom','pseudo');
        // WHEN  On appeles la méthode getQuery
        $qStmt = $query->getQuery();
        // THEN  On obtient la requête sql correspondante
        assertEquals("SELECT nom,prenom,pseudo FROM utilisateur",$qStmt);
    }

    public function testWhere() {
        // GIVEN Un objet QueryBuilder sur lequel on appele la méthode where
        $query = QueryBuilder::Table('utilisateur')->where("prenom","Guillaume");
        // WHEN  on appeles la méthode getParams
        $params = $query->getParams();
        // THEN  on récupère les paramètres précédement donner
        assertEquals(['prenom' => 'Guillaume'],$params);
        // Et on obtient la requete correspondante
        assertEquals("SELECT * FROM utilisateur WHERE prenom = :prenom",$query->getQuery());
    }
    // TODO Corriger le QueryBuilder
    public function test2WhereWithSameName() {
        // GIVEN Un objet QueryBuilder sur lequel on appele
        // la méthode where deux fois en spécifiant la même colonne
        $query = QueryBuilder::Table('utilisateur')
                 ->where("prenom","Guillaume")
                 ->where("prenom","Clement");
        // WHEN  on appeles la méthode getParams
        $params = $query->getParams();
        // THEN  on récupère les paramètres précédement donner
        assertEquals(['prenom' => 'Guillaume','prenom' => 'Clement'],$params);
        // Et on obtient la requete correspondante en différentient les clés
        assertEquals("SELECT * FROM utilisateur WHERE prenom = :prenom1 AND prenom = :prenom2",$query->getQuery());



    }
}