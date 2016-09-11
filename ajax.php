<?php session_start(); ?>
<?php require_once("config/database.php"); ?>
<?php
	if ($x = file_get_contents("php://input"))
	{
		$finalname = md5(uniqid(rand(), true)).".png";
		while (file_exists("pictures/".$finalname) == true)
			$finalname = md5(uniqid(rand(), true)).".png";
	    $imageData=$x;
	    $filteredData=substr($imageData, strpos($imageData, ",")+1);
	    $unencodedData=base64_decode($filteredData);
	    $fp = fopen( 'pictures/'.$finalname, 'wb' );
	    fwrite( $fp, $unencodedData);
	    fclose( $fp );

	    $i = 0;
		$files = glob('png/*.png');
		foreach($files as $file) {
			if ($i == $_GET['id']) {
				$image1 = imagecreatefrompng("pictures/".$finalname);
				$image2 = imagecreatefrompng($file);
				$image3 = imagecreatetruecolor(320, 320);
				$trans_colour = imagecolorallocatealpha($image3, 0, 0, 0, 127);
   				imagefill($image3, 0, 0, $trans_colour);
				imagecopyresized($image3, $image2, 0, 0, 0, 0, 320, 320, imagesx($image2), imagesy($image2));
				imagecopy($image1, $image3, 0, 0, 0, 0, 320, 320);
				imagepng($image1, "pictures/".$finalname);
				echo $finalname;
			}
			$i++;
		}	
		// ENRENGISTRER LA BDD 
		$db = new PDO($DB_DSN.";dbname=".$DB_NAME, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$exec = $db->prepare("INSERT INTO image (path, id_auteur) VALUES (?, ?)");
		$exec->execute(array($finalname, $_SESSION['id']));
	}
?>