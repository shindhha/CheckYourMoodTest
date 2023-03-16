<?php header( 'Cache-Control: max-age=900' );?>
<?php include("session.php"); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta HTTP-EQUIV="Pragma" content="no-cache">
<meta HTTP-EQUIV="Expires" content="-1">
    <title>Check Your Mood - Humeurs</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/check-your-mood/css/humeurs.css">
	<link rel="stylesheet" href="/check-your-mood/css/header.css">
	<link rel="icon" href="/check-your-mood/images/YeuxLogo.png">

</head>
<body>
	<?php include("header.php"); ?>

	<?php
		// Permet de récupérer le nombre d'humeur en JS
		echo '<script>var nbHumeur = '.$humeurs->rowCount().'</script>'
	?>
	<script>
	function openForm() {
		document.getElementById("popupForm").style.display = "block";
		// Ajout du flou sur toutes les humeurs
		for (let noId = 0 ; noId <= nbHumeur ; noId++) {
			document.getElementById("containHumeur" + noId).classList.add("flou");
		}
	}
	function closeForm() {
		document.getElementById("popupForm").style.display = "none";
		// Suppression du flou sur toutes les humeurs
		for (let noId = 0 ; noId <= nbHumeur ; noId++) {
			document.getElementById("containHumeur" + noId).classList.remove("flou");
		}
	}
	
	function openPopupHumeur(id) {
		document.getElementById("popupHumeur" + id).style.display = "block";
		// Ajout du flou sur toutes les humeurs
		for (let noId = 0 ; noId <= nbHumeur ; noId++) {
			document.getElementById("containHumeur" + noId).classList.add("flou");
		}
	}
	function closePopupHumeur(id) {
		document.getElementById("popupHumeur" + id).style.display = "none";
		// Suppression du flou sur toutes les humeurs
		for (let noId = 0 ; noId <= nbHumeur ; noId++) {
			document.getElementById("containHumeur" + noId).classList.remove("flou");
		}
	}
	
	function openPopupHumeur(id) {
		document.getElementById("popupHumeur" + id).style.display = "block";
		// Ajout du flou sur toutes les humeurs
		for (let noId = 0 ; noId <= nbHumeur ; noId++) {
			document.getElementById("containHumeur" + noId).classList.add("flou");
		}
	}
	function closePopupHumeur(id) {
		document.getElementById("popupHumeur" + id).style.display = "none";
		// Suppression du flou sur toutes les humeurs
		for (let noId = 0 ; noId <= nbHumeur ; noId++) {
			document.getElementById("containHumeur" + noId).classList.remove("flou");
		}
	}
	</script>

<?php
spl_autoload_extensions(".php");
spl_autoload_register();

use yasmf\HttpHelper;
?>

    <div class="contain page">
        <div id="contain-contenu" class="contain-mood">

            <div class="head-mood">

                <!-- Ligne d'ajout d'une humeur -->
                <span class="humeurTitre">Humeurs</span>
                <button onclick="openForm()" class="btn-ajout">Ajouter une humeur +</button>

                <!-------------------------------->
                <!-- Popup d'ajout d'une humeur -->
                <!-------------------------------->
                <!-- N'est affiché que lorsque  -->
                <!-- l'utilisateur clique sur   -->
                <!-- le +                       -->
                <div id="popupForm">
					<form action = "index.php" method="post">
						<input type="hidden" name="controller" value="donnees">
						<input type="hidden" name="action" value="insertHumeur">
						<?php
							echo '<input type="hidden" name="noPage" value="'.$noPage.'">';
						?>
						<p class="sansBordure">Création humeur</p>
						<hr>
						<div class="ajoutHumeurForm">
							<!-- Libellé de l'humeur -->
							<select name="humeur" class="form-control">
								<?php
								while($row = $libelles->fetch()){
									echo "<option value = '".$row['codeLibelle']."'>".$row['emoji']." ".$row['libelleHumeur']."</option>";
								}
								?>
							</select>
							<!-- Date de l'humeur -->
							<input type="date" name="dateHumeur" class="form-control" value="<?php echo date('Y-m-d'); ?>" required />
							<!-- Heure de l'humeur -->
							<input type="time" class="form-control" name="heure" value="<?php echo date('H:i:s'); ?>" required /><br/>
						</div>
						<!-- Contexte de l'humeur -->
						<input type="textarea" name="contexte" class="form-control" placeholder="Contexte...">
						<!-- Boutons d'ajout et d'annulation de l'humeur -->
						<div class="btnNav">
							<button type="button" class="annuler" onclick="closeForm()">Annuler</button>
							<button type="submit" class="confirmerAjout">Ajouter</button>
						</div>
					</form>
                </div>
            </div>
            <div class="container"> 
				<div class="row">
				<?php
					$i = 0;
					while($row = $humeurs->fetch()){

						echo '<div class="col-md-4 col-xs-12 cadreHumeur" id="containHumeur'.$i.'">';
							echo '<button class="containCadre" onclick="openPopupHumeur('.$i.')">';
								echo '<span>'.$row['emoji'].'  '.$row['libelleHumeur'].'</span><br/>';
								echo '<span>'.$row['dateHumeur'].'  '.$row['heure'].'</span>';
							echo '</button>';
						echo '</div>';
						
						/**
						 * Popup d'ajout d'une humeur
					     * --------------------------
                		 * N'est affiché que lorsque 
                		 * l'utilisateur clique sur  
                		 * le +                      
						 */

						echo '<div id="popupHumeur'.$i.'" class="popupHumeur">';
							echo '<form class="containPopup">';
								echo '<input type="hidden" name="controller" value="donnees">';
								echo '<input type="hidden" name="action" value="updateHumeur">';
								echo '<input type="hidden" name="noPage" value="'.$noPage.'">';
								echo '<input type="hidden" name="codeHumeur" value="'.$row['codeHumeur'].'">';
								echo '<span class="title">'.$row['emoji'].'  '.$row['libelleHumeur'].'</span>';
								echo '<span>'.$row['dateHumeur'].'  '.$row['heure'].'</span><br/>';
								echo '<span>Contexte</span><br/>';
								echo '<input type="textarea" class="form-control" name="contexte" value="'.$row['contexte'].'">';
								echo '<div class="btnNav">';
									echo '<button type="button" class="annuler" onclick="closePopupHumeur('.$i.')">Fermer</button>';
									echo '<button type="submit" class="confirmerAjout">Modifier</button>';
								echo '</div>';
							echo '</form>';
						echo '</div>';
						$i++;
					}
				?>
				</div>

				<!-- Numéro de pagination -->
				<div class="row">
					<form action = "index.php" method="post" class="col-xs-12">
						<input type="hidden" name="controller" value="donnees">
						<input type="hidden" name="action" value="changementPage">
						<ul class="pager">
							<li><input class="bt-pagination" type="submit" name="noPage" value="<<"></li>
						<?php

							for ($i = ($noPage - 5 < 1 ? 1 : $noPage - 5) ; $i <= ($noPage + 5 > $pages ? $pages : $noPage + 5) ; $i++) {
							echo '<li><input class="bt-pagination '. ($i == $noPage ? ' active' : '').'" type="submit" name="noPage" value="' . $i . '"></li>';
							}
						?>
							<li><input class="bt-pagination" type="submit" name="noPage" value=">>"></li>
						</ul>
					</form>
				</div>
				
			</div>

        </div>
    </div>
</body>


</html>
