<?php require_once("config/database.php"); ?>
<?php require_once("header.php"); ?>
<?php if (isset($_SESSION['login'])) {echo "<script> document.location.href =\"index.php\"</script>";	}?>

<br>

	<?php 
	// ETAPE 1 LIEN RECU DANS LE MAIL

	if ($_GET && $_GET['login'] && $_GET['token'] && $_GET['token'] != NULL)
	{
		$db = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$exec = $db->prepare("SELECT login from user where login = ? and reset_password = ?");
		$exec->execute(array($_GET['login'], $_GET['token']));
		$result = $exec->fetch();
		if ($result['login'] == $_GET['login']): 
	?>
		<form method="post">
		<label for="password">Votre nouveau mot de passe :</label>
		<input type="password" name="password" id="password"> <br> <br>
		<input type="submit">
		</form>
	<?php
		else:
			echo "<h2> Bad Hash or login </h2>";
		endif;
	}
	?>

	<?php
	// ETAPE 2 APRES SAISI DU NOUVEAU MOT DE PASSE
	if ($_POST && strlen($_POST['password']) >= 6 && strlen($_POST['password']) <= 32 && preg_match("/[a-z]/", $_POST['password']) && preg_match("/[A-Z]/", $_POST['password']) && preg_match("/[1-9]/", $_POST['password']))
	{
		if ($_GET && $_POST && $_GET['login'] && $_GET['token'] && $_GET['token'] != NULL && $_POST['password']) {
			$exec = $db->prepare("UPDATE user set password = ? where login = ? and reset_password = ?");
			$exec->execute(array(hash('whirlpool', $_POST['password']), $_GET['login'], $_GET['token']));
			$exec = $db->prepare("UPDATE user set reset_password = NULL where login = ? and reset_password = ?");
			$exec->execute(array($_GET['login'], $_GET['token']));
			echo "<script> alert(\"Mot de passe changer avec success redirection ...\"); document.location.href=\"login.php\"</script>";	
		}
	}
	else if ($_POST)
		echo "<h2>Le mot de passe doit faire 6 caractere minimum (32 max) contenir au moins un chiffre, une majuscule, et une miniscule</h2>";
	?>



<?php require_once("footer.php"); ?>