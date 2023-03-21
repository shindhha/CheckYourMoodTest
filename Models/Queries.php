<?php

namespace Models;

class Queries
{
    private $select;
    private $from;
    private $where;
    private $params;
    private $tableName;
    public function __construct($tableName)
    {
        $this->tableName = $tableName;
        $this->from = "FROM " . $tableName;
        $this->select = "SELECT ";
        $this->where = "WHERE ";
    }

    public function select($params = null) {
        if ($params == null) {
            $this->select .= '*';
        }
        if (isset($params)) {
            foreach ($params as $param) {
                if ($this->select != "SELECT ") $this->select .= ",";
                $this->select .= $param;
            }
        }
        return $this;
    }
    public function where($columnName,$value) {
        $this->where .= $columnName . " = ?";
        $this->params[] = $value;
        return $this;
    }

    public function insert($column) {
        $insert = "INSERT INTO " . $this->tableName . "( ";
        foreach ($column as $keys) {
            if ($this->select != "SELECT ") $insert .= ",";
            $insert .= $keys . ",";
        }
        return $insert;

    }

    public function update() {
        $update = "UPDATE " . $this->tableName;
    }

    public function getQueries() {
        return $this->select . " " . $this->from . " " . $this->where;
    }
    public function getParams() {
        return $this->params;
    }

}