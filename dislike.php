<?php require_once("config/database.php"); ?>
<?php 
session_start();
if (isset($_GET['id']) && isset($_GET['p']) && isset($_SESSION['id']))
{
	$db = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$exec = $db->prepare("UPDATE image set likes = likes - 1 where path = ?");
	$exec->execute(array($_GET['id']));
	header("Location: gallery.php?p=".$_GET[p]);
}
else
	echo "Il faut etre connecter pour deliker une photo";