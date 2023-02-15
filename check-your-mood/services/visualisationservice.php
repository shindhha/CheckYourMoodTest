<?php


namespace services;

use PDOException;

class VisualisationService
{
    /**
     * La fonction parcourt les résultats de la requête sql 
     * et crée un tableau $tableau avec le jour de la semaine comme clé et le nombre d'occurrences comme valeur. 
     * Il crée ensuite un nouveau tableau $tabJour et le remplit avec les valeurs de $tableau si elles existent ou 0 sinon
     *Enfin, la fonction renvoie $tabJour
     */
    public function visualisationRadar($pdo, $idUtil, $code, $week, $anneeAComparer){
        $sql="SELECT COUNT(dateHumeur), DAYOFWEEK(dateHumeur) as jour 
        FROM humeur where idUtil = :id and libelle = :codeHumeur 
        and WEEK(dateHumeur) = :date and YEAR( dateHumeur ) = :year 
        GROUP by dateHumeur ";
        $searchStmt = $pdo->prepare($sql);
        $searchStmt->bindParam('id', $idUtil);
        $searchStmt->bindParam('codeHumeur', $code);
        $searchStmt->bindParam('date', $week);
        $searchStmt->bindParam('year', $anneeAComparer);
        $searchStmt->execute();
        while($dateWeek = $searchStmt->fetch()){
            $tableau[$dateWeek['jour']] = $dateWeek['COUNT(dateHumeur)'];
        }
        for ($i = 1 ; $i < 8 ; $i++) {
            $tabJour[$i] = isset($tableau[$i]) ? $tableau[$i] : 0;
        }
        return $tabJour;
    }
	/**
     * Filtre les résultats en fonction du $idUtil code utilisateur et de la date spécifiés en entrée de la méthode. 
     * Il groupe également les résultats en fonction de la colonne "libelleHumeur" et compte le nombre d'occurrences de chaque libellé d'humeur. 
     * Les résultats sont ensuite stockés dans un tableau associatif avec le libellé comme clé et le nombre d'occurrences comme valeur. 
     * Si aucun résultat n'est trouvé, le tableau est défini comme étant vide.Sinon la fonction retourne le tableau rempli.
     */
	public function visualisationDoughnut($pdo, $idUtil, $date){
        $sql="SELECT COUNT(*) as nombreHumeur, libelleHumeur as libelle 
        FROM humeur JOIN libelle ON humeur.libelle = libelle.codeLibelle 
        WHERE idUtil = :id and dateHumeur = :date 
        GROUP BY libelleHumeur";
        $searchStmt = $pdo->prepare($sql);
        $searchStmt->bindParam('id', $idUtil);
        $searchStmt->bindParam('date', $date);
        $searchStmt->execute();

        if ($searchStmt ->rowCount() == 0) {
            $tableauDoughnut[] = null; 
        }  
        while($countLibelle = $searchStmt->fetch()){
            $tableauDoughnut[$countLibelle['libelle']] = $countLibelle['nombreHumeur'];
        }
        return $tableauDoughnut;
    
    }
    /**
     * Cette fonction permet de récuperer un objet PDO qui stocke pour le semaine et l'année saisie en paramètre  le jour de la semaine, le libelle, l'emoji, la date, l'heure et le contexte.
     *  @return $searchStmt un objet de type PDO 
     */
    public function visualisationTableau($pdo, $idUtil, $week, $anneeAComparer){
        //La requete sélectionne les informations suivantes: le jour de la semaine, le libellé, l'emoji, la date, l'heure et le contexte .
        $sql="SELECT DAYOFWEEK(humeur.dateHumeur) as jourDeLaSemaine, libelle.libelleHumeur as libelle, libelle.emoji as emoji, humeur.dateHumeur as date, humeur.heure as heure, humeur.contexte as contexte 
        from humeur join libelle on humeur.libelle = libelle.codeLibelle 
        where humeur.idUtil = :id and WEEK(humeur.dateHumeur) = :date and YEAR( dateHumeur ) = :year 
        order by humeur.dateHumeur";
        $searchStmt = $pdo->prepare($sql);
        $searchStmt->bindParam('id', $idUtil);
        $searchStmt->bindParam('date', $week);
        $searchStmt->bindParam('year', $anneeAComparer);
        $searchStmt->execute();
        return $searchStmt;
    
    }
	
	/** 
     * Cette fonction permet de visualiser l'humeur qui a le plus était ajouté dans l'année 
     * @return $searchStmt un objet de type PDO 
     */
	public function visualisationHumeurAnneeLaPlus($pdo, $idUtil, $year){
        $sql="SELECT libelle.libelleHumeur as libelle, libelle.emoji  as emoji 
        FROM humeur join libelle on libelle.codeLibelle = humeur.libelle 
        where humeur.idUtil = :id  and YEAR(dateHumeur) = :date 
        GROUP BY libelle ORDER BY COUNT(libelle) DESC LIMIT 1";
        $searchStmt = $pdo->prepare($sql);
        $searchStmt->bindParam('id', $idUtil);
        $searchStmt->bindParam('date', $year);
        $searchStmt->execute();
        return $searchStmt;
    
    }
	/** 
     * Cette fonction permet de visualiser l'humeur qui a le plus était ajouté
     * durant cette journée 
     * @return $searchStmt un objet de type PDO 
     */
	public function visualisationHumeurJour($pdo, $idUtil, $jour){
        $sql="SELECT libelle.libelleHumeur as libelle, libelle.emoji  as emoji 
        FROM humeur join libelle on libelle.codeLibelle = humeur.libelle 
		where humeur.idUtil = :id  and dateHumeur = :date 
		GROUP BY libelle 
		ORDER BY COUNT(libelle) DESC LIMIT 1";
        $searchStmt = $pdo->prepare($sql);
        $searchStmt->bindParam('id', $idUtil);
        $searchStmt->bindParam('date', $jour);
        $searchStmt->execute();
        return $searchStmt;
    }
	/** 
     * Cette fonction permet de visualiser l'humeur qui a le plus était ajouté
     * durant cette semaine
     *  @return $searchStmt un objet de type PDO
     */
    public function visualisationHumeurSemaine($pdo, $idUtil, $week){
        $sql="SELECT libelle.libelleHumeur as libelle, libelle.emoji  as emoji
        FROM humeur join libelle on libelle.codeLibelle = humeur.libelle 
        where humeur.idUtil = :id  and week(dateHumeur) = :date 
        GROUP BY libelle 
        ORDER BY COUNT(libelle) DESC LIMIT 1";
        $searchStmt = $pdo->prepare($sql);
        $searchStmt->bindParam('id', $idUtil);
        $searchStmt->bindParam('date', $week);
        $searchStmt->execute();
        return $searchStmt;
    
    }

    /**
     * Cette fonction permet retourner un tableau avec le nombre de fois que l'humeur ($libelle)
     * pour chaque mois de l'annee rentrer en paramètre 
     */
    public function visualisationHumeurAnnee($pdo, $idUtil, $annee, $libelle){
        $tableauAnnee=array();
        $tabMois = array( 1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre');
        for($mois = 1 ; $mois <= 12; $mois ++){
            $sql="SELECT count(libelle) as nbrHumeurs from humeur 
            where libelle = :libelle and humeur.idUtil = :id 
            and YEAR(dateHumeur) = :annee and month(dateHumeur) = :mois";
            $searchStmt = $pdo->prepare($sql);
            $searchStmt->bindParam('id', $idUtil);
            $searchStmt->bindParam('libelle', $libelle);
            $searchStmt->bindParam('annee', $annee);
            $searchStmt->bindParam('mois', $mois);
            $searchStmt->execute();
            while($row = $searchStmt->fetch()){
                $resultat = $row['nbrHumeurs'];
            }
            array_push($tableauAnnee,array("label"=> $tabMois[$mois], "y"=> $resultat));
        }
        return $tableauAnnee;
    }
	/**
     * Récupere le jour courant
     */
	public function getCurrentDay($pdo){
		$sql="Select curdate() as day ";
        $searchStmt = $pdo->prepare($sql);
        $searchStmt->execute();
        return $searchStmt;
	}

    /**
     * Récupere la semaine courante 
     */
    public function getCurrentWeek($pdo){
        $sql="Select week(curdate()) as week ";
        $searchStmt = $pdo->prepare($sql);
        $searchStmt->execute();
        return $searchStmt;
    }

    /** Recupere l'année courante  */
    public function getCurrentYear($pdo){
        $sql="Select Year(curdate()) as year";
        $searchStmt = $pdo->prepare($sql);
        $searchStmt->execute();
        return $searchStmt;
    }
    

    private static $defaultVisualisationService ;
    public static function getDefaultVisualisationService()
    {
        if (VisualisationService::$defaultVisualisationService == null) {
            VisualisationService::$defaultVisualisationService = new VisualisationService();
        }
        return VisualisationService::$defaultVisualisationService;
    }
}