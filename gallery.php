<?php require_once("config/database.php"); ?>
<?php require_once("header.php"); ?>
<?php
	$db = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$exec = $db->prepare("SELECT COUNT(id) from image");
	$exec->execute();
	$result = $exec->fetch();
	if ($result['COUNT(id)'] > 0) {

		$nbpic = $result['COUNT(id)'];
		$nbpicperpage = 3;
		$cpage = 1;
		$nbpage = ceil($nbpic / $nbpicperpage);
		if (isset($_GET['p']) && $_GET['p'] > 0 && $_GET['p'] <= $nbpage)
			$cpage = $_GET['p'];
		$exec = $db->prepare("SELECT * from image ORDER BY id DESC LIMIT ?,?");
		$exec->bindValue(1, ($cpage - 1) * $nbpicperpage, PDO::PARAM_INT);
		$exec->bindValue(2, $nbpicperpage, PDO::PARAM_INT);
		$exec->execute();
		$result = $exec->fetchAll();
		foreach ($result as $k => $val) {	
			echo "<div id=\"gal\">
			<img src=\"pictures/".$val['path']."\" />
			<p>Like : ".$val['likes']."</p>";
			if (isset($_SESSION['id']))
				echo "<div id=\"like\"><a class=\"link\"href=\"like.php?id=".$val['path']."&p=".$cpage."\"> Like </a> <a class=\"red\" href=\"dislike.php?id=".$val['path']."&p=".$cpage."\"> Dislike </a><a href=\"comment.php?id=".$val['path']."&p=".$cpage."\"> <p> Ajouter un commentaire </p></a></div>";
			$exec = $db->prepare("SELECT comment, id_user from comment where id_photo = ? ORDER BY id
				");

			$exec->execute(array($val['id']));
			$final = $exec->fetchAll();
			foreach ($final as $key => $value) {
				echo "<div id=\"comment\"> ".$value['comment'];
				$exec = $db->prepare("SELECT login from user where id = ?");
				$exec->execute(array($value['id_user']));
				$ndc = $exec->fetch();
				echo "<p class= \"underline\">By ".$ndc['login']."</p></div>";
			}
			echo "</div>";
		}
		echo "<br/>";
		for ($i = 1; $i <= $nbpage; $i++) {
			if ($i == $cpage) {
				echo " Page ".$i."/";
			}
			else if ($i == $nbpage)
				echo "<a class=\"link\"href=gallery.php?p=".$i.">Page ".$i."</a>";
			else
				echo "<a class=\"link\"href=gallery.php?p=".$i.">Page ".$i."/</a>";
		}

	}
	else
		echo "<h2> Aucune photo publier sur camagru soyer le premier </h2>";
?>
<?php require_once("footer.php");?>