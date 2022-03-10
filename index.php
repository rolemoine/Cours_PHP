<!DOCTYPE html>

<html>
    <head>
        <title> Pagination en PHP </title>
    </head>
    <body>
	
	
        <?php
		
			// Informations pour la connexion a la base de donnee mySQL
			$serveur = "localhost";
			$utilisateur = "root";
			$mot_de_passe = "root";
			$database = "paris8";
			
			// Connexion a la base de donnée mySQL
			$connexion = new mysqli($serveur, $utilisateur, $mot_de_passe);
	    		// Afficher message erreur si probleme de connexion a la base de donnee 
			if (mysqli_connect_error()) {
                    		printf("Probleme de connexion a la base de donnée " ,mysqli_connect_error());
				exit();
                	}
			
			// Definition du nombre d elements par page 
			$nb_elements = 10;  

			// Requete mysql pour recuperer la table salariepro dans la base de donnee paris8

			$requete_mysql = "select * from paris8.salariepro";    

			// Recuperation de la table dans mysql
			$table = $connexion->query($requete_mysql); 

			// Calcul du nombre de lignes contenues dans la table
			$nombre_total_ligne = mysqli_num_rows($table);    
			
			// Calcul du nombre total de pages que contiendra la pagination
			$nombre_total_pages = ceil ($nombre_total_ligne / $nb_elements);    
			
			
			// Requête pour connaître le numéro actuel de la page sur laquelle on se trouve
			if (!isset ($_GET['page']) ) {  
				$numero_page = 1;  // par défaut pour partir du localhost on se place sur la page 1 sinon on aura rien sans cette condition
			} else {  
				$numero_page = $_GET['page'];  // recuperation du numero de page  
			} 
			
			// Calcul du numero de la premiere ligne à aller chercher dans la base de donnee pour la page numero_page
			$premiere_ligne = ($numero_page-1) * $nb_elements;   
			
			// Recuperation des nb_elements de la page numero_page correspondant aux lignes comprises entre (premiere_ligne et premiere_ligne+nb_elements)    
			$requete_mysql = "SELECT * FROM paris8.salariepro LIMIT " . $premiere_ligne . ',' . $nb_elements;  
			$table = $connexion->query($requete_mysql);  

			
			// Definition des colonnes qui seront affichées dans ma page web pour chaque ligne 
			while ($ligne = mysqli_fetch_array($table)) {  

				echo $ligne['CODE_DU_SALARIE'] . ' ' . $ligne['SALAIRE_MENSUEL'] . '</br>';  // ici seul 2 colonnes seront affichées, celles contenant le code du salarie et celle contenant le salaire du salarie correspondant

			}   
	    
	    		// Fermeture de la base de donnée 
			mysqli_close($connexion);

			// Affichage de la pagination

			// Affichage du bouton suivant et lien vers la page qu'il renvoie 
			if($numero_page!=1)// Il n apparaitra pas pour la premiere page car la page 0 n existe pas
			{
               		 	$precedent=$numero_page-1;
                		echo'<a href = "index.php?page=' . $precedent . '">précédent</a>';   
 
           		 }


			// Affichage des boutons et des liens correspondant à chaque numero de page. il y aura nombre_total_pages de boutons avec liens associés
			for($numero_page = 1; $numero_page<= $nombre_total_pages; $numero_page++) {  
				echo '<a href = "index.php?page=' . $numero_page . '">' . $numero_page . ' </a>';  
			} 

			// Affichage du bouton precedent et lien vers la page qu'il renvoie
			$numero_page = $_GET['page'];			
			if($numero_page<$nombre_total_pages)//Il n apparaitra que si la page actuelle ne depasse pas le nombre de page total
			{
               			 $suivant=$numero_page+1;
                		 echo'<a href = "index.php?page=' . $suivant . '">suivant</a>';   
 
           		 }			

		?>
    </body>
</html>
