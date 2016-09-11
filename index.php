<?php require_once("header.php");?>
<?php require_once("config/database.php"); ?>

<?php
	if (isset($_SESSION['login']))
	{
		echo "<div id=\"shoot\"><h2>Prendre votre photo</h2><video id=\"video\"></video><button id=\"startbutton\">Shoot !</button></div>";
		echo "<div id =\"shoot\"><h2>Upload votre photo (.jpg ou .png) et selectioner un filtre</h2><input type=\"file\" id=\"upload\" accept=\"image/png, image/jpg\"/><button id=\"uploadsend\"> Upload </button></div>";
		$x = 0;
		$files = glob('png/*.png');
		echo "<div id=\"sticker\">";
		foreach($files as $file) {
			echo "<input type=\"radio\" name=\"filter\" value=\"".$x."\"/><img src=\"".$file."\"/ width=\"120px\" height=\"120px\" name=\"imgs\" id=\"".$x."\" >";
			$x++;
		}
		echo "</div><h2>Historique (Cliquer = suprimer)</h2><div id=\"history\">";

		$db = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$exec = $db->prepare("SELECT path from image where id_auteur = ? ORDER BY id DESC");
		$exec->execute(array($_SESSION['id']));
		$result = $exec->fetchAll();
		if (isset($result))
		{
		foreach ($result as $key) {
			echo "<a href=\"delete.php?id=".$key['path']."\"><img title=\"suprimer la photo\"src=\"pictures/".$key['path']."\" width=\"120px\" height=\"120px\"/></a>";}
		echo "<canvas id=\"canvas\"></canvas><img id=\"photo\" alt=\"photo\"></div>";
		}
	}
	else
		echo ("<h2> Pour acceder au fabuleux Camagru il faut au prealable etre connecté !! <h2>");
?>
<?php require_once("footer.php");?>
