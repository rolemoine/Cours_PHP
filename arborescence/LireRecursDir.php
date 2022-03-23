
<P>
<B>DEBUTTTTTT DU PROCESSUS :</B>
<BR>
<?php echo " ", date ("h:i:s"); ?>
</P>
<?php

// Temps maximum d exécution du script
set_time_limit (500);
	
$path= "docs";
echo 'DOSSIER : '.$path.'<br>' ;

// Appelle de la fonction definie par la suite pour le dossier docs/
explorerDir($path);

function explorerDir($path)
{
	// ouverture du dossier et pointeur sur ce dossier
	$folder = opendir($path);
	
	// Informations pour la connexion a la base de donnee mySQL
	$serveur = "localhost";
	$utilisateur = "root";
	$mot_de_passe = "root";
	
	// Connexion a la base de donnée mySQL
	$connexion = new mysqli($serveur, $utilisateur, $mot_de_passe);
	

	// Boucle sur tous les éléments contenus dans le dossier ouvert précédement
	while($entree = readdir($folder))
	{		
		// On exclue les éléments . et .. car on ne souhaite pas revenir dans le dossier précédent
		if($entree != "." && $entree != "..")
		{
			// Si l élément pointé est un dossier
			if(is_dir($path."/".$entree))
			{
				// On stocke le chemin initial du dossier dans lequel on se trouvait
				$sav_path = $path;
				// on met a jour le chemin du nouveau dossier qu on souhaite ouvrir
				$path .= "/".$entree;
				echo 'DOSSIER : '.$path.'<br>' ;
				//	on rapelle la fonction pou boucler jusqu a ce que tous les dossiers soient ouvert 
				// en recuperation a chaque ouverture de dossier les fichiers aux extensions voulues
				explorerDir($path);
				// on revient dans le dossier precedent 
				$path = $sav_path;
			}
			else
			{
				// Chemin d acces au fichier depuis le dossier de depart soit le dossier docs ici
				$path_source = $path."/".$entree;	
				echo 'FICHIER : '. $entree.'<br>';
				//Si c'est un .png ou un .jpeg		
				//Alors je ferais quoi ? Devinez !
				
				// extraction de l extension du fichier
				$file_ext = pathinfo($path_source, PATHINFO_EXTENSION);
				// Definition des extensions autorisées 
				$allowedExtensions = ['jpeg', 'png'];
				$taille = filesize($path_source)/1000;
				// Boucle sur les fichiers ayant la bonne extension
				if (in_array($file_ext, $allowedExtensions)){					
					$requete_mysql = "INSERT INTO IMAGES.arbo (name,size,path,extension) VALUES ('$entree','$taille','$path_source','$file_ext')";
					$result = $connexion->query($requete_mysql);
				}
			}
		}
	}
	closedir($folder);
	
	// Fermeture de la base de donnée 
	mysqli_close($connexion);
}
?>
<P>
<B>FINNNNNN DU PROCESSUS :</B>
<BR>
<?php echo " ", date ("h:i:s"); ?>
</P>
