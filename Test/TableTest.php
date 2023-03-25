<?php
require_once 'Modeles/Table.php';
use Modeles\Table;
class TableTest extends Table
{
    protected $tableName = "Tests";
    protected $primaryKey;
    protected $fillable = ['nomTest','description'];

    public function __construct($id = 0)
    {
        parent::__construct($id);
    }
}