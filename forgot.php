<?php require_once("config/database.php"); ?>
<?php require_once("header.php"); ?>
<?php if (isset($_SESSION['login'])) {echo "<script> document.location.href =\"index.php\"</script>";	}?>

<?php 
		if ($_POST && $_POST['login'] != "")
		{
			$_POST['login'] = strtolower($_POST['login']);
			$db = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$exec = $db->prepare("SELECT login, mail from user where mail = ?");
			$exec->execute(array($_POST['login']));
			$result = $exec->fetch();
			if ($result['mail'] == $_POST['login']) {
					$token = md5(uniqid(rand(), true));
					$exec = $db->prepare("UPDATE user set reset_password = ? where mail = ?");
					$exec->execute(array($token, $_POST['login']));
					mail($result['mail'], "Renitialisation du mot de passe camagru", "Hello Voici votre lien pour renitialiser le mot de passe de votre conmpte camagru http://".$_SERVER['HTTP_HOST']."/pass.php?login=".urlencode($result['login'])."&token=".urlencode($token), "From: no-reply@camagru.com");
					echo ("<h2> Un mail de renitialisation du mot de passe vous a ete envoyez, Merci ! </h2>");
			}
			else
				echo ("<h2> Utilisateur inconnue ! </h2>");
		}
		else if ($_POST && $_POST['login'] == "")
			echo ("<h2> Utilisateur inconnue ! </h2>");


?>

<br>
<form method="post">
	<label for="login">Votre email :</label>
	<input type="text" name="login" id="login"> <br> <br>
	<input type="submit">
</form>

<?php require_once("footer.php"); ?>