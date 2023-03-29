<?php
require_once 'Modeles/Table.php';
use Modeles\Table;
class TableTest extends Table
{
    protected $tableName = "tests";
    protected $fillable = ['nomTest','description','id'];

    public function __construct($id = 0)
    {
        parent::__construct($id);
    }
}