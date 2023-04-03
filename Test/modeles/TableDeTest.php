<?php

namespace Modeles;
require_once 'modeles/Table.php';

class TableTest extends Table
{
    protected $tableName = "tests";
    protected array $fillable = ['nomTest', 'description', 'id'];

    public function __construct($id = 0)
    {
        parent::__construct($id);
    }
}