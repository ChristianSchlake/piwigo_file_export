<?php
	include("sub_init_database.php");
?>

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf8"/>
	<meta name="viewport" content="width=device-width">

	<?php
		echo "<title>Create Piwigo Filestructure</title>";
	?>

	<!--link rel="stylesheet" href="css/foundation.css">
	<link rel="stylesheet" href="icons/foundation-icons.css"/-->

<style>
	.size-12 { font-size: 12px; }
	.size-14 { font-size: 14px; }
	.size-16 { font-size: 16px; }
	.size-18 { font-size: 18px; }
	.size-21 { font-size: 21px; }
	.size-24 { font-size: 24px; }
	.size-36 { font-size: 36px; }
	.size-48 { font-size: 48px; }
	.size-60 { font-size: 60px; }
	.size-72 { font-size: 72px; }
	.size-X { font-size: 26px; }
</style>

</head>

<body>

<?php
	ini_set('max_execution_time', 1200);
	/*
	Variablen deklarieren
	*/
	$sBildverzeichnisTags="pics/Tags/";
	$sBildverzeichnisKategorie="pics/Kategorie/";
	$sDocumentRoot=dirname($_SERVER["SCRIPT_FILENAME"])."/";
//	$sDocumentTargetVerz = "/media/server/bilder/bilder_piwigo/";
//	$sDocumentTargetVerz = "/storage/daten2/bilder/bilder_piwigo/";
	$sDocumentTargetVerz = "/mnt/myBookLiveDuo/bilder/bilder_piwigo/";

	echo "<h1>Tags</h1>"."<br>";
	/*
	Tags erstellen
	*/
	$result = mysqli_query($verbindung, "SELECT id, name FROM piwigo_tags");
	while($row = mysqli_fetch_row($result)) {
		/*
		Unterverzeichnis erstellen
		*/
		$sTmpVerz = $sDocumentRoot.$sBildverzeichnisTags.$row[1]."_".$row[0]."/";

		mkdir($sTmpVerz, 0777, true);

		/*
		Bilder zum Tag ermitteln
		*/
		$result2 = mysqli_query($verbindung, "SELECT file, path FROM piwigo_image_tag AS T INNER JOIN piwigo_images AS I ON T.image_id = I.id WHERE T.tag_id=".$row[0].";");
		while($row2 = mysqli_fetch_row($result2)) {
			$sLinkName = $sTmpVerz.$row2[0];
			$sTarget = str_replace("./upload/",$sDocumentTargetVerz,$row2[1]);
			echo $sTarget." --> ".$sLinkName."<br>";
			shell_exec ("ln -s '".$sTarget."' '".$sLinkName."'");
		}
	}

	echo "<h1>Kategrorien</h1>"."<br>";
	/*
	Kategorien erstellen
	*/
	$result = mysqli_query($verbindung, "SELECT id, uppercats FROM piwigo_categories");
	while($row = mysqli_fetch_row($result)) {
		$sCategory=explode(",", $row[1]);
		$sTmpVerz="";
		foreach($sCategory AS $id) {
			$result2 = mysqli_query($verbindung, "SELECT name FROM piwigo_categories WHERE id=".$id);
			while($row2 = mysqli_fetch_row($result2)) {
				//echo "Name der Kategorie: ".$row2[0]."<br>";
				$sTmpVerz=$sTmpVerz.$row2[0]."/";
			}
		}
		$sTmpVerz = $sDocumentRoot.$sBildverzeichnisKategorie.$sTmpVerz;
		mkdir($sTmpVerz, 0777, true);
		/*
		Bilder zur Kategorie ermitteln
		*/
		$result2 = mysqli_query($verbindung, "SELECT file, path FROM piwigo_image_category AS C INNER JOIN piwigo_images AS I ON C.image_id = I.id WHERE C.category_id=".$row[0].";");
		while($row2 = mysqli_fetch_row($result2)) {

                        $sLinkName = $sTmpVerz.$row2[0];
                        $sTarget = str_replace("./upload/",$sDocumentTargetVerz,$row2[1]);
                        echo $sTarget." --> ".$sLinkName."<br>";
			shell_exec ("ln -s ".$sTarget." ".$sLinkName);


//			echo $sDocumentRoot.$row2[1].' -> '.$sTmpVerz.$row2[0].'<br />';
//			symlink($sDocumentRoot.$row2[1], $sTmpVerz.$row2[0]);
                        shell_exec ("ln -s '".$sTarget."' '".$sLinkName."'");

		}
	}
	/*
	Verbindung zum Datenbankserver beenden
	*/
	mysqli_close($verbindung);
	echo "<h1>Fertig</h1>"."<br>";
?>
</body>
