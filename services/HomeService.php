<?php


namespace services;

require_once 'Modeles/User.php';
require_once 'Modeles/QueryBuilder.php';
use PDOException;
use Modeles\User;
use Modeles\QueryBuilder;
class HomeService
{
    /**
     * Connexion d'un utilisateur
     * @param $identifiant id de l'utilisateur
     * @param $mdpUtil mot de passe de l'utilisateur
     * @param $pdo \PDO the pdo object
     * @return \PDOStatement the statement referencing the result set
     */
    public function connexion($pdo, $identifiant, $mdpUtil)
    {
        $searchStmt = QueryBuilder::Table('utilisateur')
            ->select('codeUtil','prenom','nom','mail')
            ->where('identifiant',$identifiant)
            ->where('motDePasse',md5($mdpUtil))->execute();
        $infos = ["util" => 0];

        if ($searchStmt->rowCount() > 0) {
            $row = $searchStmt->fetch();
            $infos = [
                "idUtil" => $row['codeUtil'],
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