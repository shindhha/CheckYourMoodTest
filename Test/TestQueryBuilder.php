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
        $qStmt = QueryBuilder::Table('utilisateur')
                 ->where('codeUtil',1)
                 ->update(['nom' => 'Medard']);
        $stmt = $this->pdo->prepare("UPDATE utilisateur SET nom = :nom WHERE codeUtil = :codeUtil");
        $stmt->execute(['nom' => 'Medard','codeUtil' => 1]);
        assertEquals($stmt,$qStmt);
    }

    public function testInsert() {
        $qStmt = QueryBuilder::Table('utilisateur')->insert(['nom' => 'Medard']);
        $stmt = $this->pdo->prepare("INSERT INTO utilisateur (nom) VALUES (:nom)");
        $stmt->execute(['nom' => 'Medard']);
        assertEquals($stmt,$qStmt);
    }


    public function testSelectWithNoArgument() {
        $qStmt = QueryBuilder::Table('utilisateur')
                 ->select()
                 ->where('codeUtil',1)
                   ->get();
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE codeUtil = :codeUtil");
        $stmt->execute(['codeUtil' => 1]);
        assertEquals($stmt,$qStmt);

    }

    public function testSelectWithArgument() {
        $qStmt = QueryBuilder::Table('utilisateur')
                 ->select('nom','prenom','pseudo')
                 ->where('codeUtil',1)->getQuery();
        assertEquals("SELECT nom,prenom FROM utilisateur WHERE codeUtil = :codeUtil",$qStmt);

    }
}