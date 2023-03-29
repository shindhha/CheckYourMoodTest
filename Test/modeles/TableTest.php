<?php

namespace modeles;
require_once 'Modeles/Table.php';

class TableTest extends Table
{
    protected $tableName = "tests";
    protected $fillable = ['nomTest', 'description', 'id'];

    public function __construct($id = 0)
    {
        parent::__construct($id);
    }
}