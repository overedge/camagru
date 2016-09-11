<?php require_once("header.php");?>
<?php require_once("config/database.php"); ?>
<?php
	if (isset($_GET['id']))
	{
		$db = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$exec = $db->prepare("SELECT id, path from image where path = ? and id_auteur = ?");
		$exec->execute(array($_GET['id'], $_SESSION['id']));
		$result = $exec->fetch();
		if ($result != NULL)
		{
			$exec = $db->prepare("DELETE FROM comment where id_photo = ? ");
			$exec->execute(array($result['id']));
			unlink("pictures/".$result['path']);
			$exec = $db->prepare("DELETE FROM image where id = ? ");
			$exec->execute(array($result['id']));
			echo "<h2>Photo supprimer</h2>";
		}
		else
			echo "<h2>Erreur cette photo n'existe pas dans votre camagru</h2>";
	}
	else
		echo "<h2>Erreur cette photo n'existe pas dans votre camagru</h2>";
?>
<?php require_once("footer.php");?>
