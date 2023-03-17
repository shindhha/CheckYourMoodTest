<?php

namespace Services;

class Queries
{
    private $select;
    private $from;
    private $where;
    private $params;
    public function __construct($tableName)
    {
        $this->from = "FROM " . $tableName;
        $this->select = "SELECT ";
        $this->select = "WHERE ";
    }

    public function select($params = null) {

        if (isset($params)) {
            foreach ($params as $param) {
                if ($this->select != "SELECT ") $this->select .= ",";
                $this->select .= $param;
            }
        }
        return $this;
    }
    public function where($columnName,$value) {
        if ($this->where != "WHERE ") { $this->where .= " AND ";}
        $this->where .= $columnName . " = ?";
        $this->params[] = $value;
        return $this;
    }

    public function getQueries() {
        return $this->select . "\n" . $this->from . "\n" . $this->where;
    }
    public function getParams() {
        return $this->params;
    }

}