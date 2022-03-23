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
                    echo "<h3 style='text-align:center;color: red;' > Le format de l'image n'est pas supporté </h3>";
                }	
 				if ($_GET['erreur'] == 6)  // Probleme de connexion a la BDD
				{
                    echo "<h3 style='text-align:center;color: red;' > Probleme de connexion à la base de donnée </h3>";
                }					
            }
		
			// Informations pour la connexion a la base de donnee mySQL
			$serveur = "localhost";
			$utilisateur = "root";
			$mot_de_passe = "root";
			$database = "images";
			
			
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
			// Les elements seront classés du plus récent au plus ancien pour afficher dans la premiere page par exemple 
			// les dernieres images ajoutées par l utilisateur
			$requete_mysql = "SELECT * FROM images.im  ORDER BY id DESC LIMIT " . $premiere_ligne . ',' . $nb_elements ;  
			$table = $connexion->query($requete_mysql); // table contenant les 6 images qui devront être affichées pour la page considérée
		?>
			
		<! cette partie sert a afficher les images sous forme d un tableau -->
			
			<table>
				<table border="0">
				<table align="center">

			<?php
					// Affichage de l ensemble des images contenues dans la BDD
					
					$i = 0; // Initilisation de i qui servira de compteur pour savoir quand passer à la ligne
					while ($ligne = mysqli_fetch_array($table)) // Boucle pour afficher les 6 images qui composent la page actuelle
						{
						
						echo '<td>'.'<img src="images/'.$ligne['name'].'" alt="top"  height="260" width="300">'; // Affichage de l image a partir de son nom en allant la chercher dans le dossier images						
						echo '<br>'. $ligne['name'].'<br>'.$ligne['size'].'ko'.'<td>'; // Affichage de son nom et de sa taille
						
						$i = $i+1; // incrementation du compteur
						
						if ($i % 3 ==0) // Si on a affiche les 3 images sur la meme ligne on passe a la ligne suivante
						{
							echo'<tr>'; 
						}

					} 
			?>

			</table>
			
			
			
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