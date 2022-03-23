<html>


<?php


// On vérifie que le fichier telechargé ne présente pas d erreure sinon on renvoie l erreure à l utilisateur selon le type de l erreur
if ($_FILES['fichier']['error']) {
  switch ($_FILES['fichier']['error']){
	case 1: // Erreur dans la taille du fichier autorisée par el serveur
	  $erreur = 1;
    case 2: // Erreur dans la taille maximale dépassée selon le critère donné dans le formulaire
	  $erreur = 2;
    case 3: // Telechargement partiel du fichier
	  $erreur = 3;
    case 4: // Aucun fichier telechargé
	  $erreur = 4;
  }
}


// Si aucune erreur n a ete detecte 
else{
	
	if (isset($_FILES['fichier']) AND $_FILES['fichier']['error'] == 0)
	{			
		// Test pour voir si l extension du fichier est bien respectee 
		
		$fileInfo = pathinfo($_FILES['fichier']['name']);  
		// Recuperation de l extension du fichier
		$extension = $fileInfo['extension']; 
		 // Definition des extensions qu on autorise pour le telechargement du fichier
		$allowedExtensions = ['jpg', 'jpeg', 'png','PNG'];

		if (in_array($extension, $allowedExtensions))
		{
			// Information pour la connexion a la base de donnees 
			$serveur = "localhost";
			$utilisateur = "root";
			$mot_de_passe = "root";
			
			// Connexion a la base de données 
			$connexion = new mysqli($serveur, $utilisateur, $mot_de_passe);
			
		if (mysqli_connect_error()) // La connexion a la base de donnee n a pas été réussite
			{
			$erreur = 6 ;
			exit();
		}
			// Taille de l image convertie en Ko
			$size = (int)(($_FILES['fichier']['size'])/1000);
			// Requete pour connaitre le nombre d element de la table
			$requete_mysql = "select * from images.im"; 
			$table = $connexion->query($requete_mysql); 
			// Incrementation de l'ID à partir du nombre d elements de la table +1
			// Cela permettra par la suite l affichage des images du plus récent au plus ancien
			$id = mysqli_num_rows($table) + 1;
			// Récupération du nom de l image
			$name = $_FILES['fichier']['name'];
			// Récupération du chemin d acces de l image
			$chemin = realpath($_FILES['fichier']['tmp_name']);
			
			// Requete pour inserer l id, la taille, le chemin, le nom ainsi que l extension de l image dans une base de donnée
			$requete_mysql = "INSERT INTO IMAGES.im (id,size,path,name,extension) VALUES ('$id','$size','$chemin', '$name', '$extension')";
			// Insertion des informations dans la base de donnée
			$result = $connexion->query($requete_mysql);
			if ($result) echo "Fichier ajouté : ok";
			else $erreur = 6; // Si le fichier n a pas ete ajouté dans la BDD l utilisateur sera informe qu il y a eu un probleme de connexion avec la BDD 
			$connexion -> close();
			

			// fonction permettant de creer une copie de l image dans le dossier images/ 
			move_uploaded_file($_FILES['fichier']['tmp_name'], __DIR__.'/images/'. $_FILES["fichier"]['name']);
			
		}
		
		// Si l extension n est pas autorisee l utilisateur en sera informe 
		else {
			$erreur = 5 ;
		}
		
	// Si tout l'ensemble des actions a été bien effectué alors il n'y a pas d'erreur 
	// l image est bien telecharge et envoye dans le dossier avec ses infos dans la BDD
	$erreur = 0 ;
			
	}
}
	// fonction permettant de retourner dans la page index apres avoir executé ce script
	header('Location: ./?erreur=' . $erreur);
?>

</html>