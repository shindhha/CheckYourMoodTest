<?php
namespace controllers;
require_once 'Modeles/User.php';
use Modeles\QueryBuilder;
use services\InscriptionService;
use yasmf\HttpHelper;
use yasmf\HttpHelper\getParam;
use yasmf\View;
use Modeles\User;
class InscriptionController {


    public function signin($pdo,$userTest = null) {

        $user = $userTest === null ? new User() : $userTest;
        $view = new View("check-your-mood/views/inscription");
        $user->identifiant = htmlspecialchars(HttpHelper::getParam('identifiant'));
        $user->motDePasse = htmlspecialchars(HttpHelper::getParam('motdepasse'));
        $user->mail = htmlspecialchars(HttpHelper::getParam('mail'));
        $user->nom = htmlspecialchars(HttpHelper::getParam('nom'));
        $user->prenom = htmlspecialchars(HttpHelper::getParam('prenom'));

        if ($user->identifiant == "" || $user->motDePasse == ""
            || $user->mail == ""     || $user->nom == ""  || $user->prenom == "") {
            return $view;
        }
        $user->motDePasse = md5($user->motDePasse);

        QueryBuilder::setDBSource($pdo);
        try {
            $user->save();
        } catch (\PDOException $e) {
            return $view;
        }

        $view = new View("check-your-mood/views/connexion");
        return $view;
    }



}
