<?php require_once("config/database.php"); ?>
<?php require_once("header.php"); ?>
<?php if (isset($_SESSION['login'])) {echo "<script> document.location.href =\"index.php\"</script>";	}?>

<?php 
	if (isset($_POST['login']) && isset($_POST['password']) && $_POST['login'] != ""  && $_POST['password'] != "" ){ 
		$_POST['login'] =  strtolower($_POST['login']);
		$db = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$exec = $db->prepare("SELECT login, password, mail, confirmation, id from user where login = ? and password = ?");
		$_POST['password'] = hash('whirlpool', $_POST['password']);
		$exec->execute(array($_POST['login'], $_POST['password']));
		$result = $exec->fetch();
		if ($_POST['login'] == $result['login'] && $_POST['password'] == $result['password'] && $result['confirmation'] == 1)
		{
			$_SESSION['login'] = $result['login'];
			$_SESSION['mail'] = $result['mail'];
			$_SESSION['id'] = $result['id'];
			echo "<script>document.location.href=\"index.php\"</script>";
		}
		else if ($_POST['login'] == $result['login'] && $_POST['password'] == $result['password'] && $result['confirmation'] == 0){
			echo "<h2>Utilisateur non confirmer, Veuillez checker vos mails !</h2>";
		}
		else {
			echo "<h2> Echec : Mauvais utilisateur ou mot de passe </h2>";
		}
	}
	else if  (isset($_POST['login']) && isset($_POST['password']) && ($_POST['login'] == ""  || $_POST['password'] == "" )){ 
		echo "<h2> Echec : Mauvais utilisateur ou mot de passe </h2>";
	}
?>

<br>
<form method="post">
	<label for="login">Votre pseudo :</label>
	<input type="text" name="login" id="login"> <br> <br>

	<label for="password">Votre mot de passe :</label>
	<input type="password" name="password" id="password"> <br> <br>
	<input type="submit">
</form>

<br>

<a href="forgot.php">Mot de passe oublier ?</a>


<?php require_once("footer.php"); ?>