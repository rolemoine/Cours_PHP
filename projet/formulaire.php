
<html>

<!-- Création d un formulaire pour telecharger un fichier -->

	<form action="submit_file.php" method="post" enctype="multipart/form-data">
	<!-- L utilisateur se retrouvera sur une nouvelle page une fois la requete envoyee -->
	
	  <input type="hidden" name="MAX_FILE_SIZE" value="200000">
	  <!-- Definition de la taille limite autorisee du fichier  -->
	  
	  <label>Télécharger un ficher</label> :
	  
	  <input type="file" name="fichier"><br>
	  <!-- Telechargement du fichier localement  --> 
	  
	  
	  <p> Max 200 Ko </p>
	  <p> Extensions acceptées : 'jpg', 'jpeg', 'png','PNG' </p>
	  <!-- Affichage pour l utilisateur des conditions qu il doit respecter pour que son fichier puisse être téléchargé --> 
	  
	  <input type="submit" value="Envoyer">
	  <!-- Bouton envoyer qui amenera sur une autre page definie ligne 6 --> 
	</form>



</html>