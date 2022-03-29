<html>
    <head>
        <title> Affichage images </title>
    </head>
	
	
	
    <body>
		<header style='text-align:center;'>
			<div>
				<a  href="./formulaire.php">Ajouter des images</a>
			</div>
		</header>
		
	
        <?php
		
		// La boucle suivante permet d'affichier les si l image a ete correctement telechargee 
		// à partir du formulaire ou quelle erreur à empêché le bon déroulement du téléchargement
		// Un bandeau s'affichera pour donner l information sur le téléchargmeent de l image
		
		 if (isset($_GET['erreur'])) {
                if ($_GET['erreur'] == 0) // L image a été correctement telechargée
				{
                    echo "<h3 style='text-align:center;color: green;'> Téléchargement de l'image effectué </h3>";
                }                 
				if ($_GET['erreur'] == 1) // Taille limite du serveur depassé 
				{
                    echo "<h3 style='text-align:center;color: red;'> Le fichier dépasse la limite autorisée par le serveur </h3>";
                }
				if ($_GET['erreur'] == 2) // Taille limite autorisée par le fomulaire dépassée
				{
                    echo "<h3 style='text-align:center;color: red;'> Le fichier dépasse la taille maximale acceptée </h3>";
                }
				if ($_GET['erreur'] == 3) // Erreur dans le chargement
					{

                    echo "<h3 style='text-align:center;color: red;'> Erreur dans le téléchargement du fichier </h3>";
                }
				if ($_GET['erreur'] == 4) // L utilisateur n a rentré aucun ficher
					{
                    echo "<h3 style='text-align:center;color: red;' > Aucun fichier n'a été envoyé. Merci de recommencer </h3>";
                }				
 				if ($_GET['erreur'] == 5)  // L extension de l image n est pas supportée
				{
                    echo "<h3 style='text-align:center;color: red;' > Aucun fichier n'a été envoyé. Merci de recommencer </h3>";
                }	
				if ($_GET['erreur'] == 6)  // Probleme de connexion mysql
				{
                    echo "<h3 style='text-align:center;color: red;' > Probleme de connexion a la base de données </h3>";
                }
				if ($_GET['erreur'] == 'true') // L image a bien ete supprimee
					{
                    echo "<h3 style='text-align:center;color: blue;' > L'image a bien été supprimée </h3>";
                }				
 				if ($_GET['erreur'] == 'false')  // Probleme dans la suppression d image
				{
                    echo "<h3 style='text-align:center;color: red;' > L'image n'a pas pu être supprimée </h3>";
                }				
            }
		
			// Informations pour la connexion a la base de donnee mySQL
			$serveur = "localhost";
			$utilisateur = "root";
			$mot_de_passe = "root";
			
			
			// Connexion a la base de donnée mySQL
			$connexion = new mysqli($serveur, $utilisateur, $mot_de_passe);
			// Afficher message erreur si probleme de connexion
			if (mysqli_connect_error()) {
                    printf("Probleme de connexion a la base de donnée " ,mysqli_connect_error());
					exit();
                }
			
			// Definition du nombre d elements par page 
			$nb_elements = 6;  

			// Requete mysql pour recuperer la table salariepro dans la base de donnee paris8
			$requete_mysql = "select * from IMAGES.im";    

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
			$requete_mysql = "SELECT * FROM images.im  ORDER BY id DESC LIMIT " . $premiere_ligne . ',' . $nb_elements ;  
			$table = $connexion->query($requete_mysql); 
		?>
			
		<! cette partie sert a afficher les donnees sous forme d un tableau -->
			
			<table>
				<table border="0">
				<table align="center">

			<?php
					// Affichage de l ensemble des images contenues dans la BDD
					
					$i = 0; // Initilisation de i qui servira de compteur pour savoir quand passer à la ligne
					while ($ligne = mysqli_fetch_array($table)) // Boucle pour afficher les 6 images qui composentla page actuelle
						{
						// Affichage de l image a partir de son nom en allant la chercher dans le dossier images
						echo '<td>'.'<img src="images/'.$ligne['name'].'" alt="top"  height="260" width="300">'; 
						// Affichage de son nom et de sa taille
						echo '<br>'. $ligne['name'].'<br>'.$ligne['size'].'ko'; 
						// affichage du boutton supprimer qui aura comme valeur de référence l'id qui est une clé unique de la table
						echo '<br><a href="?deleteid='.$ligne["id"].'" class="btn btn-primary">Supprimer</a><td>'; 
						
						$i = $i+1; // incrementation du compteur
						
						if ($i % 3 ==0) 
						// Si on a affiche les 3 images sur la meme ligne on passe a la ligne suivante
						{
							echo'<tr>'; // On passe a la ligne suivante pour afficher images sous forme de grille
						}

					} 
			?>

			</table>
			
			<?php 
			// Création de la condition permettant de supprimer une image si l utilisateur 
			// clique sur le bouton 
			if(isset($_GET['deleteid'])) 
				{
					// Creation de la requete permettant de recuperer les info liées à l image qu'on veur supprimer en utilisant l id 
					// retournée lorsque l utilisateur clique sur le bouton supprimer
					$requete_mysql = "select * from IMAGES.im where id = ".$_GET['deleteid'];
					// Exécution de la requête
					$table = $connexion->query($requete_mysql);
					// Renvoi la requete sous forme de tableau associatif
					$table = mysqli_fetch_assoc($table);
					// Création du chemin d acces pour supprimer l image dans le dossier images
					$DeletePath = "images/".$table['name'];
					
					if(unlink($DeletePath)) // si on trouve bien dans le dossier l image a supprimer on la supprime
					{
						// Suppression de la ligne dans la base de donnees correspondant a l image à supprimer
						// l image ne sera alors plus affichée sur la page web
						$requete_mysql = "delete from IMAGES.im where id = ".$table['id'];
						// Execution de la requete
						$tableDelete = $connexion->query($requete_mysql);	
						
						if($tableDelete)
						{
							// si l image a bien été supprimée dans la BDD l utilisateur en sera informé
							header('Location:./?erreur=true');
						}
					}
					else
					{
						// si l image n a pas pu être supprimé un message d erreur sera renvoyé 
						header('Location:./?erreur=false');
					}
					
				}
			?>
			
			
			
			<?php
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