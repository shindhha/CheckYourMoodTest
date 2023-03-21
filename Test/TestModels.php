<?php
require_once 'yasmf/datasource.php';

use Models\Table;
use PHPUnit\Framework\TestCase;
use yasmf\DataSource;
use services\MoodService;
use Models\User;
require_once 'Models/users.php';
require_once 'Test/DataBase.php';
require_once 'Models/Table.php';
require_once 'Models/Queries.php';
use function PHPUnit\Framework\assertEquals;

class TestModels extends TestCase
{
    private User $user;
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


    public function test() {
        $user = new User();

        //assertEquals("SELECT * FROM utilisateur WHERE id = ?",$user->getData());
        assertEquals("utilisateur",$user->getNom());
    }

}