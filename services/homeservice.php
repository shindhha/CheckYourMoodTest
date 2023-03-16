<?php


namespace services;

use PDOException;

class HomeService
{
    /**
     * Connexion d'un utilisateur
     * @param $idUtil id de l'utilisateur
     * @param $mdpUtil mot de passe de l'utilisateur
     * @param $pdo \PDO the pdo object
     * @return \PDOStatement the statement referencing the result set
     */
    public function connexion($pdo, $idUtil, $mdpUtil)
    {
        $mdpUtil = md5($mdpUtil);
        $sql = "SELECT codeUtil, prenom, nom, mail FROM utilisateur WHERE identifiant = :idUtil AND motDePasse = :mdpUtil" ;
        $searchStmt = $pdo->prepare($sql);
        $searchStmt->bindParam('idUtil', $idUtil);
        $searchStmt->bindParam('mdpUtil', $mdpUtil);
        $searchStmt->execute();

        $infos = ["util" => 0];

        while($row = $searchStmt->fetch()){
            $infos = [
                "util" => $row['codeUtil'],
                "nom" => $row['nom'],
                "prenom" => $row['prenom'],
                "mail" => $row['mail']
            ];
        }

        //Renvoie un tableau avec les infos de l'utilisateur si le id et mdp sont corrects
        return $infos;
    }


    private static $defaultHomeService ;
    public static function getDefaultHomeService()
    {
        if (HomeService::$defaultHomeService == null) {
            HomeService::$defaultHomeService = new HomeService();
        }
        return HomeService::$defaultHomeService;
    }
}