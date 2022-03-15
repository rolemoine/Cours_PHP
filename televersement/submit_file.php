<html>


<?php


// On vérifie que le fichier telechargé ne présente pas d erreure sinon on renvoie l erreure à l utilisateur selon le type de l erreur
if ($_FILES['fichier']['error']) {
  switch ($_FILES['fichier']['error']){
	case 1: // UPLOAD_ERR_INI_SIZE
      echo "Le fichier dépasse la limite autorisée par le serveur";
      break;
    case 2: // Erreur dans la taille maximale dépassée selon le critère donné dans le formulaire
      echo "La fichier dépasse la taille maximale acceptée";
      break;
    case 3: // Telechargement partiel du fichier
      echo "Erreure dans le téléchargement du fichier";
      break;
    case 4: // Aucun fichier telecharge 
      echo "Aucun fichier n'a été envoyé. Merci de recommencer";
      break;
  }
}


// Si aucune erreur n a ete detecte 
else{
	?>
	<!-- Creation du tableau contenant les infos qu on veut afficher -->
		<table>
		<table border="1">
		<thead>
			<tr>
				<th>Nom du fichier</th>
				<th> Taille du fichier </th>
				<th> Extension </th>
				<th> Nouveau nom du fichier </th>
			</tr>
		</thead>
		<tbody>
	
	<?php
	
	if (isset($_FILES['fichier']) AND $_FILES['fichier']['error'] == 0)
	{			
		// Test pour voir si l extension du fichier est bien respectee 
		
		$fileInfo = pathinfo($_FILES['fichier']['name']);  
		$extension = $fileInfo['extension']; // Extension du fichier
		$allowedExtensions = ['jpg', 'jpeg', 'png','PNG']; // definition des extensions qu on autorise pour le telechargement du fichier

		if (in_array($extension, $allowedExtensions))
		{
			// Affichage des donnees dans le tableau 
			echo'<tr>'; 
			echo'<td>'.$_FILES['fichier']['name']."</td>"; // nom du fichier comme defini localement
			echo'<td>'. $_FILES['fichier']['size'].'</td>'; // taille du fichier
			echo'<td>'. $extension.'</td>'; // extension
			echo'<td>'. $_POST['Renommer']; // eventuel nouveau nom que le fichier aura dans le dossier dans lequel il aura ete deplace
			echo'<tr>';
			
			if ($_POST['Renommer']) {
				// si un nouveau nom de fichier a ete donne on renomme le fichier par ce nom quand on le telecharge dans son nouveau dossier
				move_uploaded_file($_FILES['fichier']['tmp_name'], __DIR__.'/images/'. $_POST['Renommer'].'.'.$extension);
			}
			else {
				// si aucun nouveau nom de fichier a ete donne on laisse le nom initial quand on le telecharge dans son nouveau dossier
				move_uploaded_file($_FILES['fichier']['tmp_name'], __DIR__.'/images/'. $_FILES["fichier"]['name']);
			}
		}
		
		// Si l extension n est pas autorisee 
		else {
			echo "Le fichier ne respecte pas les extensions autorisees ";

		}
	?>
	</tbody>
	</table>
	
	<?php
			
	}
}
?>

</html>

