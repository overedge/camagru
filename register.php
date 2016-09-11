<?php require_once("config/database.php"); ?>
<?php require_once("header.php"); ?>
<?php if (isset($_SESSION['login'])) {echo "<script> document.location.href =\"index.php\"</script>";	}?>
<?php
	$good = 0;
	// ETAPE 1 VERIFIER LA PRECENE DU FORMULAIRE
	if ($_POST)
	{
		if ($_POST['login'] != "" && $_POST['password'] != "" && $_POST['mail'] != "")
		{
			if (strlen($_POST['password']) >= 6 && strlen($_POST['password']) <= 32 && preg_match("/[a-z]/", $_POST['password']) && preg_match("/[A-Z]/", $_POST['password']) && preg_match("/[1-9]/", $_POST['password']))
			{
				if (!filter_var($_POST['mail'],  FILTER_VALIDATE_EMAIL) === false)
				{
					if (preg_match("/^([a-zA-Z0-9-_]{3,32})$/", $_POST['login']))
						$good = 1;
					else
						echo "<h2> Nom d'utilisateur doit faire 3 caractere minimum (32 max)Ne posseder que des caractere alphanumerique et (-_ ) </h2>";
				}
				else
					echo "<h2> L'adresse email saisi est incorrect !! </h2>";
			}
			else
				echo"<h2>Le mot de passe doit faire 6 caractere minimum (32 max) contenir au moins un chiffre, une majuscule, et une miniscule</h2>";
		}
		else
			echo("<h2>Veuillez remplire touts les champs</h2>");
	}

	// Etape 2 connextion a la BDD et verification si l'user existe deja (mail, user)
	if ($good == 1) {
		$_POST['login'] = strtolower($_POST['login']);
		$_POST['mail'] = strtolower($_POST['mail']);
		$db = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$exec = $db->prepare("SELECT login, mail from user where login = ? or mail = ?");
		$exec->execute(array($_POST['login'], $_POST['mail']));
		$result = $exec->fetch();
		if ($result == false)
			$good = 2;
		else
		{
			if ($result['login'] == $_POST['login'] && $result['mail'] == $_POST['mail']) {
				echo("<h2>Cette email est ce nom d'utilisateur sont deja associer a un compte, veuillez vous connecter</h2>");
			}
			else if ($result['login'] == $_POST['login']) {
				echo("<h2>Ce pseudo est deja utiliser par un autre utilisateur</h2>");
			}
			else if ($result['mail'] == $_POST['mail']) {
				echo("<h2>Cette email est deja utiliser sur un autre compte</h2>");
			}
		}
	}

	// Etape 3 Insertion du nouveau user et envoi du mail de confirmation 
	if ($good == 2) {
		$exec = $db->prepare("INSERT INTO user (login, mail, password, token) VALUES (?, ?, ?, ?)");
		$token = md5(uniqid(rand(), true));
		$exec->execute(array($_POST['login'], $_POST['mail'], hash('whirlpool', $_POST['password']), $token));
		mail($_POST['mail'], "Validation de votre compte camagru", "Hello Voici votre lien pour valider votre conmpte camagru http://".$_SERVER['HTTP_HOST']."/activate.php?login=".urlencode($_POST['login'])."&token=".urlencode($token), "From: no-reply@camagru.com");
		echo("<h2>Un email de validation vous a ete envoyer cliquer sur le lien pour valider votre inscription</h2>");
	}
?>
<br>
<form method="post">
	<label for="login">Votre pseudo :</label>
	<input type="text" name="login" id="login"> <br> <br>

	<label for="password">Votre mot de passe :</label>
	<input type="password" name="password" id="password"> <br> <br>

	<label for="mail">Votre adresse mail :</label>
	<input type="mail" name="mail" id="mail"> <br> <br>
	<input type="submit">
</form>
<?php require_once("footer.php"); ?>