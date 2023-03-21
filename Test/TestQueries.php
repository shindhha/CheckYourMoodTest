<?php
require_once 'yasmf/datasource.php';

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
use Models\Queries;
class TestQueries extends TestCase
{
    private User $user;
    private $pdo;
    private $services;

    protected function setUp(): void
    {
        $this->pdo =  DataBase::getPDOTest();
        $this->pdo->beginTransaction();
        Queries::setDBSource($this->pdo);
    }

    protected function tearDown(): void
    {
        $this->pdo->rollBack();
    }


    public function testUpdate() {
        $qStmt = Queries::Table('utilisateur')
                 ->where('codeUtil',1)
                 ->update(['nom' => 'Medard']);
        $stmt = $this->pdo->prepare("UPDATE utilisateur SET nom = :nom WHERE codeUtil = :codeUtil");
        $stmt->execute(['nom' => 'Medard','codeUtil' => 1]);
        assertEquals($stmt,$qStmt);
    }

    public function testInsert() {
        $qStmt = Queries::Table('utilisateur')->insert(['nom' => 'Medard']);
        $stmt = $this->pdo->prepare("INSERT INTO utilisateur (nom) VALUES (:nom)");
        $stmt->execute(['nom' => 'Medard']);
        assertEquals($stmt,$qStmt);
    }

    public function testDelete() {
        
    }

    public function testSelectWithNoArgument() {
        $qStmt = Queries::Table('utilisateur')
                 ->select()
                 ->where('codeUtil',1)
                   ->get();
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE codeUtil = :codeUtil");
        $stmt->execute(['codeUtil' => 1]);
        assertEquals($stmt,$qStmt);

    }

    public function testSelectWithArgument() {
        $qStmt = Queries::Table('utilisateur')
                 ->select('nom','prenom','pseudo')
                   ->where('codeUtil',1)
                   ->get();
        $stmt = $this->pdo->prepare("SELECT nom,prenom,pseudo FROM utilisateur WHERE codeUtil = :codeUtil");
        $stmt->execute(['codeUtil' => 1]);
        assertEquals($stmt,$qStmt);

    }
}