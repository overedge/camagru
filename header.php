<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Camagru</title>
	<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="header"/>
         <a id="logo" href="index.php">Camagru</a>
           <ul>
             <?php if (!isset($_SESSION['login'])) echo ("<li><a href=\"register.php\"> Inscription </a></li>");?>
             <?php if (!isset($_SESSION['login'])) echo ("<li><a href=\"login.php\">Se Connecter</a></li>");?>
             <?php if (isset($_SESSION['login']))echo ("<li><a href=\"index.php\">Shoot !</a></li>");?>
             <li> <a href="gallery.php">Gallerie</a></li>
             <?php if (isset($_SESSION['login']))echo ("<li><a href=\"logout.php\">Deconnection</a></li>");?>

             

           </ul>
	</div>
<div id="container">