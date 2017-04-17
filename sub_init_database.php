<?php
	$serverhost="192.168.2.113";
        $db_user="somebody";
        $db_pass="pn4jKFd0uV4Qxt2d";
        $db_database="piwigo";
	$verbindung = mysqli_connect ($serverhost,$db_user,$db_pass) or die ("keine Verbindung möglich. Benutzername oder Passwort sind falsch");

	mysqli_select_db($verbindung, $db_database) or die ("Die Datenbank existiert nicht.");
	mysqli_query($verbindung, "SET CHARACTER SET utf8");
	mysqli_query($verbindung, "SET NAMES utf8");
	header("Content-type:text/html; charset=utf8");
?>
