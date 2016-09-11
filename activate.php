<?php require_once("config/database.php"); ?>
<?php require_once("header.php"); ?>

<?php 
	if ($_GET && isset($_GET['login']) && isset($_GET['token']))
	{
		$db = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$exec = $db->prepare("SELECT login, token, confirmation from user where login = ? and token = ?");
		$exec->execute(array($_GET['login'], $_GET['token']));
		$result = $exec->fetch();
		if ($result == true && $_GET['login'] == $result['login'] && $_GET['token'] == $result['token'] && $result['confirmation'] == 0)
		{
			$exec = $db->prepare("UPDATE user set confirmation = '1' where login = ?");
			$exec->execute(array($_GET['login']));
			echo "<h2>Votre compte et desormais activer amuser vous bien chez camagru </h2>";
		}
		else if ($result == true && $_GET['login'] == $result['login'] && $_GET['token'] == $result['token'] && $result['confirmation'] == 1)
			echo "<h2>Ce compte est deja activer  </h2>";
		else
			echo "<h2>Impossible d'activer ce compte si le probleme persiste, merci de contacter le suport technique</h2>";
	}
?>

<?php require_once("footer.php"); ?>