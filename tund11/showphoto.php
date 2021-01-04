<?php
	//require("usesession.php");
	
	//require("../../../config.php");
	
	header("Content-type: image/jpeg");
	readfile("../photouploadnormal/" .$_REQUEST["photo"]);