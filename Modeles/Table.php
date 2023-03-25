<?php

namespace Modeles;


/**
 * Etendre un objet par cette classe permet d'interagir avec la base de données
 * en utilisant la programmation orienté objet.
 * Les attributs de cette classe on pour but d'être override de façon a représenter
 * une table dans la base de données.
 * Par exemple une table 'utilisateurs' avec les colonnes 'pseudo' , 'motDePasse'
 * pourra être représenter donnant la valeur 'utilisateurs' a l'attribut 'tableName'
 * et en donnant un tableau ['pseudo','motDePasse'] à l'attribut 'fillable'.
 */
class Table
{
    protected $tableName;
    protected $fillable;
    protected $primaryKey;
    private $id;

    protected function __construct($id = 0)
    {
        $this->id = $id;
        /*
        Si l'id donner est différentes de 0 on assume le fait qu'il existe
        une ligne dans la base de données correspondante.
         */
        if ($id != 0) {
            // TODO Initialiser les attributs au valeur stocker dans la base de données
        }
    }

    /**
     * Associe les attributs de l'objet lister dans 'fillable'
     * avec les valeurs passé en argument.
     * Si un attribut n'est pas lister dans values alors on assignera
     * la valeur null a l'attribut.
     * @param values Les valeurs a associer aux attributs de l'objet
     *               sous forme d'un tableau ['clef' => 'valeur' , ...]
     */
    public function fill($values) {
        foreach ($this->fillable as $keys) {
            $this->$keys = $values[$keys];
        }
    }
    /**
     * Associe les attributs null de l'objet lister dans 'fillable'
     * avec les valeurs passé en argument.
     * @param values Les valeurs a associer aux attributs de l'objet
     *               sous forme d'un tableau ['clef' => 'valeur' , ...]
     */
    public function fillWithoutOverride($values) {
        foreach ($this->fillable as $keys) {
            if ($this->$keys == null) {
                $this->$keys = $values[$keys];
            }
        }
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
     * Assigne null a tout les attributs lister dans fillable
     */
    public function reset() {
        $this->fill([]);
    }
}