<?php

namespace Models;

use http\QueryString;
use yasmf\DataSource;
/**
 * Abstraction d'une ligne de données dans la table 'tableName'
 * avec les colonnes fillable et la primary key 'primaryKey'
 */
class Table
{
    protected $tableName;
    protected $fillable = [];
    protected $primaryKey = "id";
    private $id;

    protected function __construct($id = 0)
    {
        $this->id = $id;
        if ($id != 0) {
            $dataValues = Queries::Table($this->tableName)
                          ->select($this->fillable)
                          ->where($this->primaryKey,$id)
                          ->get()->fetch();
            foreach ($dataValues as $keys => $value) {
                $this->$keys = $value;
            }
        }
    }


    public static function all() {
    }

    
    /**
     * Associe les attributs de l'objet lister dans 'fillable' 
     * avec les valeurs passé en argument.
     * @param values Les valeurs a associer aux attributs de l'objet 
     *               sous forme d'un tableau ['clef' => 'valeur' , ...]
     */
    public function fill($values) {
        foreach ($this->fillable as $keys) {
            $this->$keys = $values[$keys];
        }
    }

    /**
     * Si l'id de l'objet est égale a 0 : 
     * Insère les attributs de l'objet dans la table 
     * 'tableName' de la base de données puis récupère l'id
     * Sinon update les valeurs des attributs de l'objet pour la ligne avec l'id 'id'
     */
    public function save() {
        $q = Queries::Table($this->tableName);
        if ($this->id == 0) {
            $this->id = $q->insert($this->toArray());
        } else {
            $queries = $q->where($this->primaryKey,$this->id)
                         ->update($this->toArray());
        }
    }

    /**
     * Supprime la ligne de la base de données avec l'id 'id'
     */
    public function delete() {
        Queries::Table($this->tableName)
        ->where($this->primaryKey,$this->id)
          ->delete();
    }

    /**
     * Empaquete les attributs de l'objet lister dans 'fillable'
     * à l'intérieur d'un tableau sous la forme ['nomAttribut' => 'valeur']
     */
    public function toArray() {
        $fields = [];
        foreach ($this->fillable as $key) {
            if ($this->$key != null) {
                $fields[$key] = $this->$key;
            }
        }
        return $fields;
    }

    /**
     * Permet de récupérer plusieurs objet 'related' dans la table correspondante 
     * dans la base de données avec la foreign key renseigne
     * @param related class de l'objet a récupérer.
     * @param foreignKey nom de la colonne dans la base de données 
     *                   contenant les foreign key cibles
     * @param ownerKey nom de la colonne contenant les primary key cibler par les foreign Key
     */
    protected function hasMany($related,$foreignKey,$ownerKey = "") {
        $instance = new $related;
        $q = new Queries($instance->getName());
        return $q->select()->where($instance->getPrimaryKey(),$this->$foreignKey)->get();
    }

    protected function belongsTo($related) {

    }

    public function getPrimaryKey() {
        return $this->primaryKey;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTableName() {
        return $this->tableName;
    }



}