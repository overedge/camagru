<?php require_once("config/database.php"); ?>
<?php require_once("header.php"); ?>
<?php

	if (isset($_POST['comment']) && isset($_GET['id']) && isset($_SESSION['id']) && isset($_GET['p']))
	{
		$db = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$exec = $db->prepare("SELECT id, id_auteur from image where path = ?");
		$exec->execute(array($_GET['id']));
		$result = $exec->fetch();
		if (strlen($_POST['comment']) <= 35 && strlen($_POST['comment']) > 0) {
			if ($result != null) {
				$exec = $db->prepare("INSERT INTO comment (id_photo, id_user, comment) VALUES (?, ?, ?)");
				$exec->execute(array($result['id'], $_SESSION['id'], htmlentities($_POST['comment'])));
				echo "<h2> Commentaire ajouter </h2>";
				$exec = $db->prepare("SELECT mail from user where id = ?");
				$exec->execute(array($result['id_auteur']));
				$result = $exec->fetch();
				if ($result['mail'] != $_SESSION['mail']){
					mail($result['mail'], "Vous avez un nouveau commentaire camagru", "Hello Vous avez un commentaire sur l'une de vos photos le voici : ".htmlentities($_POST['comment']), "From: no-reply@camagru.com");
				}
				echo "<script>document.location.href =\"gallery.php?p=".$_GET['p']."\" </script>";
			}
			else
				echo "<h2>Image non trouver</h2>";
		}
		else
			echo "<h2> Le texte est trop grand ou vide </h2>";
		
	}
	else if (isset($_GET['id']) && isset($_SESSION['id']) && isset($_GET['p'])) {
		echo "<h4> Votre commentaire (35 caracteres max ) </h4>
		<form method=\"post\" action=\"\">
		<textarea name=\"comment\" rows=\"5\" cols=\"60\"></textarea> <br />
		<input type=\"submit\" value=\"Envoyer\">
		</form>";
	}
	else
		echo "<h2> Erreur d'acces a la page </h2>";
?>
<?php require_once("footer.php");?>