<?php
	require_once("database.php");
	try {
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->exec(file_get_contents('camagru.sql'));
		echo "done <br />";
	} catch (PDOException $e) {
		echo 'Echec lors de la connextion : ' . $e->getMessage();
	}
?>