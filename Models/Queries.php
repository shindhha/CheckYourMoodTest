<?php

namespace Models;

use yasmf\DataSource;
use function PHPUnit\Framework\isNull;
class Queries
{
    private $fields = [];
    private $params = [];
    private $whereClose = [];
    private $tableName;
    private static $pdo;

    private function __construct()
    {
    }
    public function setDBSource($pdo) {
        static::$pdo = $pdo;
    }
    public static function Table($tableName) {
        $q = new Queries();
        $q->tableName = $tableName;
        return $q;
    }


    public function insert($column) {
        foreach ($column as $keys => $value) {
            if ($fields != "") {
                $fields .= ",";
                $params .= ",";
            }
            $fields .= $keys;
            $params .= " :" . $keys;
            $this->params[$keys] = $value;
        }
        static::$pdo->prepare("INSERT INTO " . $this->tableName . "( ". $fields . ") VALUES " . "( " . $params . ")")->execute($this->params);
        return static::$pdo->lastInsertId();
    }
    public function select($tabParams = ['*'],...$params) {
        foreach ($tabParams as $param) {
            $this->fields[] = $param;
        }
        foreach ($params as $param) {
            $this->fields[] = $param;
        }
        return $this;
    }
    public function where($columnName,$value,$operateur = '=') {
        $this->whereClose[] = $columnName . " " . $operateur . " :" . $columnName;
        $this->params[$columnName] = $value;
        return $this;
    }
    public function update($column) {
        foreach ($column as $keys => $value) {
            if ($value != null) {
                $this->params[$keys] = $value;
                if ($params != "") $params .= ",";
                if ($keys != '') $params .= $keys . " = :" . $keys;
            }
        }
        return static::$pdo->prepare("UPDATE " . $this->tableName . " SET " . $params . $this->getWhereClose())->execute($this->params);
    }

    public function delete() {
        return $this->pdo->prepare("DELETE FROM " . $this->tableName . " " . $this->getWhereClose())->execute($this->params);
    }

    public function get() {
        foreach ($this->fields as $column) {
            if ($select != "") {
                $select .= " , ";
            }
            $select .= $column;
        }
        return static::$pdo->prepare("SELECT " . $select . " FROM " . $this->tableName . $this->getWhereClose())->execute($this->params);
    }

    private function getWhereClose()
    {
        foreach ($this->whereClose as $close) {
            if ($closes == "") {
                $closes = " WHERE ";
            } else {
                $closes .= " AND ";
            }
            $closes .= $close;
        }
        return $closes;
    }

}