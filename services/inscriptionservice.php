<?php


namespace services;

use PDOException;

class InscriptionService
{
    /**
     * Inscription d'un utilisateur 
     * @param pdo 
     * @param id identifiant de l'utilisateur
     * @param mdp mot de passe de l'utilisateur
     * @param mail mail de l'utilisateur
     * @param prenom 
     * @param nom
     * @return \PDOStatement the statement referencing the result set
     */
    public function inscription($pdo, $id, $mdp, $mail, $nom, $prenom)
    {
        try{

            $mdp = md5($mdp);
            $searchStmt = $pdo->prepare("INSERT INTO utilisateur(prenom,nom,identifiant,mail,motdePasse) VALUES(:prenom,:nom,:id,:mail,:mdp);" );
            $searchStmt->bindParam('id', $id);
            $searchStmt->bindParam('mdp', $mdp);
            $searchStmt->bindParam('mail', $mail);
            $searchStmt->bindParam('nom', $nom);
            $searchStmt->bindParam('prenom', $prenom);
            $searchStmt->execute();
            return "ok";
        }catch(PDOException $e){
            return "nOk";
        }
    }


    private static $defaultInscriptionService ;
    public static function getDefaultInscriptionService()
    {
        if (InscriptionService::$defaultInscriptionService == null) {
            InscriptionService::$defaultInscriptionService = new InscriptionService();
        }
        return InscriptionService::$defaultInscriptionService;
    }
}