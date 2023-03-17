<?php

namespace Services;

use yasmf\DataSource;

class Table
{
    private $tableName;
    private $pdo;
    private $object;
    public function __construct($tableName,$object)
    {
        $this->tableName = $tableName;
        $this->pdo = DataSource::getPDO();
        $this->object = $object;
    }
    public function getData() {
        $currentQueries = new Queries($this->tableName);
        $currentQueries->select();
        $currentQueries->where("id",$this->object->getId());
        $stmt = $this->pdo->prepare($currentQueries->getQueries());
        $stmt->execute($currentQueries->getParams());
        return $stmt->fetch();

    }





}