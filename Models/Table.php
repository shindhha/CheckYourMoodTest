<?php

namespace Models;

use yasmf\DataSource;

class Table
{
    protected $tableName;
    protected $fillable;
    private $pdo;
    private $id;

    protected function __construct($id = 0)
    {
        if ($id != 0) {
            $this->id = $id;
        }
        $this->pdo = DataSource::getPDO();

        foreach ($this->fillable as $keys) {
            $this->$keys = null;
        }
    }

    public static function all() {

    }
    public function fill($values) {
        foreach ($this->fillable as $keys) {
            $this->$keys = $values[$keys];
        }
    }
    public function save() {
        if ($this->id == null) {
            // TODO insert
        } else {
            // TODO UPDATE

            $q = new Queries($this->tableName);

        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNom() {
        return $this->tableName;
    }








}