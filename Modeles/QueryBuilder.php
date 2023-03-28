<?php

namespace Modeles;

class QueryBuilder
{
    // La requete construite
    private $query;
    // Objet PDO établissant la connexion a la base de données
    private static $pdo;
    // Champ souhaiter lors d'une requete select
    private $fields = [];
    // Parametre généraux de la requete
    private $params = [];
    // Les condition sont stocker sous forme de string
    private $whereClose = [];
    // Nom de la table cible
    private $tableName;
    /**
     * Substitue du constructeur par défaut , plus explicite car on comprend que
     * l'on doit renseigne un nom de table en argument.
     * Permet également d'écrire les requetes d'une seule traite.
     * @param $tableName nom de la table par defaut dans laquelle faire la requete
     * @return Queries la nouelle requete sql
     */
    public static function Table($tableName) {
        $q = new QueryBuilder();
        $q->tableName = $tableName;
        return $q;
    }

    /**
     * Permet de changer le lien vers la base de données
     * @param $pdo l'objet pdo du lien vers la base de données
     */
    public static function setDBSource($pdo) {
        static::$pdo = $pdo;
    }

    /**
     * Permet de spécifier les colonnes que l'on souhaite récupérer dans notre requete
     * que sa soit de la forme d'un tableau ou une série d'arguement
     *               (['nom','prenom',...]) ou ('nom','prenom',...)
     * @param ...$params Liste des colonnes sous forme d'argument
     * @return $this la requete pour continuer la construction en chaine
     */
    public function select(...$params) {
        foreach ($params as $param) {
            $this->fields[] = $param;
        }
        return $this;
    }

    /**
     * Permet d'ajouter une condition a la requete sql.
     * @param $columnName Colonne concerné par la condition.
     * @param $value Valeur que doit matcher la colonne en fonction de l'operateur.
     * @param $operateur Indique comment la colonne doit matcher la valeur.
     * @return $this la requete pour continuer la construction en chaine
     */
    public function where($columnName,$operateur = '=',$value = null) {
        if ($value === null) {
            $value = $operateur;
            $operateur = "=";
        }
        $this->whereClose[] = $columnName . " " . $operateur . " :" . $columnName;
        $this->params[$columnName] = $value;
        return $this;
    }

    /**
     * Permet de construire une requete update en spécifiant les colonnes concerné dans $column
     * @param $column Liste des colonnes dont la valeur doit changer.
     *                Les valeurs sont également spécifier sous cette forme [column => valeur]
     * @return mixed le statement pdo de la requete après avoir été éxécuter avec les parametres
     */
    public function update($column) {
        foreach ($column as $keys => $value) {
            if ($value != null) {
                $this->params[$keys] = $value;
                if ($params != "") $params .= ",";
                if ($keys != '') $params .= $keys . " = :" . $keys;
            }
        }
        $this->query =  "UPDATE " . $this->tableName . " SET " . $params . $this->getWhereClose();
        return $this;
    }

    /**
     * Execute la requete de type select précédement construite puis renvoie le pdo statement
     * @return mixed le pdo statement correspondant a la requete construite
     */
    private function construct_select_request() {
        if (empty($this->fields)) {
            $select = "*";
        } else {
            foreach ($this->fields as $column) {
                if ($select != "") {
                    $select .= ",";
                }
                $select .= $column;
            }
        }
        return "SELECT " . $select . " FROM " . $this->tableName . $this->getWhereClose();
    }

    /**
     * Récupére les conditions stocker sous forme de chaine de caractère
     * ensuite utilise dans la construction des requete.
     * @return string conditions sous chaine de caractère
     */
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

    /**
     * Construit la requete sql permetant d'insert une ligne dans la base de données
     * @param $column
     * @return mixed
     */
    public function insert($column) {
        foreach ($column as $keys => $value) {
            if ($fields != "") {
                $fields .= ",";
                $params .= ",";
            }
            $fields .= $keys;
            $params .= ":" . $keys;
            $this->params[$keys] = $value;
        }
        $this->query = "INSERT INTO " . $this->tableName . " (". $fields . ") VALUES " . "(" . $params . ")";
        return $this;
    }
    /**
     * Prepare et Execute la requete avec les arguments avec l'objet pdo
     * @return void
     */
    public function execute() {
        if ($this->query == null) {
            $this->query = $this->construct_select_request();
        }
        return static::$pdo->prepare($this->query)->execute($this->params);
    }
    public function getQuery() {
        if ($this->query == null) {
            $this->query = $this->construct_select_request();
        }
        return $this->query;
    }

    public function getParams() {
        return $this->params;
    }

}