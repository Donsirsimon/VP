<?php
	
	$database ="if20_ken_pi_1";
	$id = $_REQUEST["photo"];
	require("../../../config.php");
	$allow = "0";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT filename FROM vppnewshotos WHERE vpnewsphotos_id = ? AND deleted IS NULL");
	
	$stmt->bind_param("i", $id);
	$stmt->bind_result($filenamefromdb);
	$stmt->execute();
	$stmt->fetch();
	$stmt->close();
	$conn->close();
	

	$info = new SplFileInfo($filenamefromdb);
	$filetype = $info->getExtension();
	
	if($filetype == "jpeg" or $filetype == "jpg"){
		header("Content-type: image/jpeg");
	}
	if($filetype == "png"){
		header("Content-type: image/png");
	}
	if($filetype == "gif"){
		header("Content-type: image/gif");
	}	
	readfile("../photoupload_news/" .$filenamefromdb);
	
