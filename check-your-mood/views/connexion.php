<?php header( 'Cache-Control: max-age=900' );?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheckYourMood - Connexion</title>
    <meta HTTP-EQUIV="Pragma" content="no-cache">
    <meta HTTP-EQUIV="Expires" content="-1">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/check-your-mood/css/connexion.css">
    <link rel="icon" href="/check-your-mood/images/YeuxLogo.png">


</head>
<?php
spl_autoload_extensions(".php");
spl_autoload_register();

use yasmf\HttpHelper;

?>
    <body>
    <script src="script\script.js"></script>
        <div class="bigContainer">
            <div class="container">
                <div class="row ligne">
                    <div class="col-md-6 bigLeft">
                        <div class="wrapper">
                            <form action="index.php" method="post">
                                <span class="titre"><h1>Connexion</h1></span>
                                <div class="wrapperForm">
                                    <div class="formulaireCo">
                                        <input type="hidden" name="premiereCo" value="1">
                                        <input type="hidden" name="action" value="login">
                                        <div class="logoInput">
                                            <span class="glyphicon glyphicon-user logoConnexion"></span>
                                            <input class="form-control <?php if(isset($_POST['premiereCo'])) echo "errDonnees"; ?>" type="text" name="identifiant" placeholder="Identifiant">
                                        </div>
                                        <div class="contain-mdp">
                                            <div class="logoInput">
                                                <span class="glyphicon glyphicon-lock logoConnexion"></span>
                                                <input class="form-control <?php if(isset($_POST['premiereCo'])) echo "errDonnees"; ?>" type="password" name="motdepasse" placeholder="Mot de passe" id="myInput">
                                            </div>
              
                                            <div class="checkbox-wrapper-13">
                                                <label>
                                                    <input id="c1-13" class="checkInput" type="checkbox" name="revele" onclick="myFunction()">
                                                    <label for="c1-13" style="font-weight:300;">Révéler mot de passe</label>
                                                </label>
                                            </div>
                                            
                                            
                                        </div><br>
                                    </div>
                                    <div class="btn-connect">
                                        <button class="btn" type="submit">Se connecter</button>
                                        <span><u>OU</u>&nbsp;&nbsp;<a href="/check-your-mood?action=goTo&namepage=inscription"> s'inscrire</a></span> 
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
        
                    <div class="col-md-6 bigRight">
                        <img clas="img-responsive" src="/check-your-mood/images/CheckYourMoodLogo.png" alt="logo">
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>