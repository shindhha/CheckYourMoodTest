<?php

namespace Models;
require_once 'Models/Table.php';

use PDOException;
use services\code;
use services\DonneesService;
use services\ID;
use services\Mood;
use services\nouveau;
use services\pdo;
use services\tab;
use services\util;
use Models\Table;
class User extends Table
{

    protected $tableName = "utilisateur";
    protected $fillable = ['nom','prenom','identifiant','mail','motDePasse'];
    protected $primaryKey = "codeUtil";

    public function __construct($id = 0)
    {
        parent::__construct($id);
    }

    public function humeurs () {
        return $this->hasMany(Mood::class,"idUtil");
    }

    /**
     * @return mixed
     */



    /**
     * Cette fonction permet de modifier les données de l'utilisateur qui possède le codeUtilisateur :$util
     * les données modifiables sont : (identifiant, mot de passe, nom, prenom, mail)
     * @param pdo 
     * @param tab tableau avec les données modifiés.
     * @param util id de l'utilisateur
     */
    public function updateData($pdo,$tab,$util){
        $user = new User($util);
        $user->fill($tab);
        try {
            $user->save();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }

    }
	
	/**
	 * Permet la modification du contexte de l'humeur
     * @param pdo 
	 */
	public function updateHumeur($pdo,$tab){	
        try {

            $requete = "UPDATE humeur SET contexte = :contexte WHERE idUtil = :id AND codeHumeur = :codeHumeur " ;
            $sql = $pdo->prepare($requete);
            $sql->execute($tab);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
	
	/**
     * Permet de récuperer (le code, la date, l'heure, le contexte le libelle de l'humeur,l'emoji, idUtil) ordonnée par date et heure de l'utilisateur $idUtil
     * @param $pdo
     * @param $idUtil ID de l'utilisateur
     * @return $searchStmt Objet PDO qui stocke toutes les humeurs de l'utilisateur $idUtil
     */
    public function viewMoods($pdo, $idUtil)
    {
        $sql = "SELECT h.codeHumeur, h.dateHumeur, h.heure, h.contexte, l.libelleHumeur, l.emoji, h.idUtil FROM humeur h, libelle l WHERE h.libelle = l.codeLibelle AND idUtil = :id ORDER BY h.dateHumeur DESC, h.heure DESC";
        $searchStmt = $pdo->prepare($sql);
        $searchStmt->bindParam('id', $idUtil);
        $searchStmt->execute();
        return $searchStmt;
    }

    /**
     * Cette fonction permet de récuperer le nombre d'humeur de l'utilisateur
     * @param $pdo 
     * @param $idUtil ID utilisateur
     * @return $searchStmt Objet PDO qui stocke le nombre d'humeur
     * 
     */
    public function nombreHumeur($pdo, $idUtil)
    {
        $sql = "SELECT COUNT(codeHumeur) FROM humeur h WHERE idUtil = :id";
        $searchStmt = $pdo->prepare($sql);
        $searchStmt->bindParam('id', $idUtil);
        $searchStmt->execute();
        return $searchStmt;
    } 
    
    /**
     * Cette fonction permet de récuperer toutes les données de l'utilisateur de code utilisateur saisie en paramaètre
     * (prenom, nom, identifiant, mail)
    * @param $pdo
    * @param $idUtil ID utilisateur
    * @return \PDOStatement the statement referencing the result set
    */
   public function donneesUser($pdo, $idUtil)
   {
        $user = new User($idUtil);
        return $user->toArray();
       $sql = "SELECT prenom, nom, identifiant, mail FROM `utilisateur` WHERE codeUtil = :id";
       $searchStmt = $pdo->prepare($sql);
       $searchStmt->bindParam('id', $idUtil);
       $searchStmt->execute();
       return $searchStmt;
   }

    /**
     * Cette fonction permet de visualiser les humeurs jusqu'a le nombre $parPage d'humeur pour une page  de l'utilisateur 
     * @param $pdo l'objet pdo
     * @param $idUtil code de l'utilisateur (PK)
     * @param $premier
     * @param $parPage
     * @return $searchStmt
     */
    public function viewMoodsPagination($pdo, $idUtil, $premier, $parPage)
    {
        $sql = "SELECT h.codeHumeur, h.dateHumeur, h.heure, h.contexte, l.libelleHumeur, l.emoji, h.idUtil FROM humeur h, libelle l WHERE h.libelle = l.codeLibelle AND idUtil = :id ORDER BY h.dateHumeur DESC, h.heure DESC LIMIT :premier, :parpage;";
        $searchStmt = $pdo->prepare($sql);
        $searchStmt->bindParam('id', $idUtil);
        $searchStmt->bindParam('premier', $premier);
        $searchStmt->bindParam('parpage', $parPage);
        $searchStmt->execute();
        return $searchStmt;
    }    
    
    /**
     * Cette fonction permet de récuperer le mdp de l'utilisateur
     * Le mdp sera encrypté en md5
     * @param $idUtil id de l'utilisateur
     * @param $pdo  
     * @return $searchStmt Objet de type PDO
     */
   public function mdp($pdo, $idUtil)
   {
        $user = new User($idUtil);
        return $user->motDePasse;
       $sql = "SELECT motDePasse FROM `utilisateur` WHERE codeUtil = :id";
       $searchStmt = $pdo->prepare($sql);
       $searchStmt->bindParam('id', $idUtil);
       $searchStmt->execute();
       return $searchStmt;
   }
   
   /**
    * Cette fonction permet de modifier le mot de passe
    * @param $pdo 
    * @param $idUtil id de l'utilisateur
    * @param $nouveauMDP nouveau mot de passe
    * @return \PDOStatement the statement referencing the result set
    */
    public function updateMDP($pdo, $idUtil, $nouveauMDP)
    {
        $user = new User($idUtil);
        $user->motDePasse = md5($nouveauMDP);
        $user->save();
        $sql = "UPDATE `utilisateur` SET motDePasse=:nouveauMDP WHERE codeUtil = :id";
        $searchStmt = $pdo->prepare($sql);
        $searchStmt->bindParam('id', $idUtil);
        $nvMDP = md5($nouveauMDP);
        $searchStmt->bindParam('nouveauMDP', $nvMDP);
        $searchStmt->execute();
        return $searchStmt;
    }

	public function supprimerCompte($pdo,$idUtil)
   {
	   try{
		   
			$pdo->beginTransaction();
		   
			$sql = "DELETE FROM humeur WHERE  idUtil = :id";
			$searchStmt = $pdo->prepare($sql);
			$searchStmt->bindParam('id',$idUtil);
			$searchStmt->execute();
			$sql = "DELETE FROM utilisateur WHERE codeUtil = :id ";
			$searchStmt = $pdo->prepare($sql);
			$searchStmt->bindParam('id',$idUtil);
			$searchStmt->execute();
			
			
			$pdo->commit();
			return "ok";
			
	   }catch(PDOException $e){
		   $pdo->rollBack();
		   return "nOk";
	   }
	   
	   
	   

   }


    private static $defaultDonneesService ;
    public static function getDefaultDonneesService()
    {
        if (DonneesService::$defaultDonneesService == null) {
            DonneesService::$defaultDonneesService = new DonneesService();
        }
        return DonneesService::$defaultDonneesService;
    }
	
	
	
}