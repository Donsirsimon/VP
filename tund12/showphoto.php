<?php
	
	$database ="if20_ken_pi_1";
	$id = $_REQUEST["photo"];
	require("../../../config.php");
	$allow = "0";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT filename, alttext, privacy, userid FROM vpphotos WHERE vpphotos_id = ? AND deleted IS NULL");
	
	$stmt->bind_param("i", $id);
	$stmt->bind_result($filenamefromdb, $alttextfromdb, $privacyfromdb, $useridfromdb);
	$stmt->execute();
	$stmt->fetch();
	$stmt->close();
	$conn->close();
	
	if($privacyfromdb == 1){
		require("usesession.php");
		if($useridfromdb != $_SESSION["userid"]){
			$allow = "1";
		}else{
			$allow = "0";
		}	
	}		
	if($privacyfromdb == 2){
		require("usesession.php");
		if(empty($_SESSION["userid"])){
			$allow = "1";
		}else{
			$allow = "0";
		}
	}	
	if($allow == "0"){
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
		readfile("../photouploadnormal/" .$filenamefromdb);
	}	
	//if($allow == "1"){
		//echo ("Sul pole õigusi seda pilti näha!");	
	//}	