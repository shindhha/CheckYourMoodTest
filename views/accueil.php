<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheckYourMood - Accueil</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/check-your-mood/css/accueil.css">
    <link rel="stylesheet" href="/check-your-mood/css/header.css">
    <link href="fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet">
    <link rel="icon" href="/check-your-mood/images/YeuxLogo.png">


</head>
<?php
spl_autoload_extensions(".php");
spl_autoload_register();

use yasmf\HttpHelper;

?>
    <body class="css-selector">
        <?php include("header.php"); ?>
        <div class="container bigContain">
            <div class="conteneur css-selector">
                <div>
                    <img class="logo" src="/check-your-mood/images/CheckYourMoodLogo.png" alt="Logo Check Your Mood">
                </div>
                <div class="big-center-div text-center">
                    <span>Cette application vous permet de noter votre humeur au fil de la journée. En enregistrant vos
                        humeurs, vous pourrez ensuite voir comment elles évoluent au fil du temps en fonction de différents
                        facteurs tels que la période (jour, semaine ou année) ou le type d'humeur.</span>
                </div>
            </div>
        </div>
    </body>
</html>