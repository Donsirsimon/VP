<?php
$database ="if20_ken_pi_1";
require("config.php");


function saveScaleData($carinput, $weightin_input, $weightout_input){
	$weightdiff = $weightin_input - $weightout_input;
	$response = 0;
	$conn =new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("INSERT INTO vpscaledata (car_number,weight_in,weight_out,weightdiff) VALUES(?,?,?,?)");
	
	$stmt->bind_param("siii", $carinput, $weightin_input, $weightout_input, $weightdiff); 	
	// s on varchar ja i on int ehk mis on tüüp andmebaasis
		if($stmt->execute()){
			$response = 1;
		} else {
			$response = 0;
		}
		$stmt->close();
		$conn->close();
		return $response;
	
}	//saveScaleData lõppeb

function saveScaleInData($carinput, $weightin_input){
	
	$response = 0;
	$conn =new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("INSERT INTO vpscaledata (car_number,weight_in) VALUES(?,?)");
	
	$stmt->bind_param("si", $carinput, $weightin_input); 	
	// s on varchar ja i on int ehk mis on tüüp andmebaasis
		if($stmt->execute()){
			$response = 1;
		} else {
			$response = 0;
		}
		$stmt->close();
		$conn->close();
		return $response;
}

	function readcarstoselect($selectedrun){
		$notice = "<p>Kahjuks autosid ei leitud!</p> \n";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT scaledata_id, car_number FROM vpscaledata WHERE weight_out IS NULL");
		echo $conn->error;
		$stmt->bind_result($idfromdb, $carfromdb);
		$stmt->execute();
		$cars = "";
		while($stmt->fetch()){
			$cars .= '<option value="' .$idfromdb .'"';
			if($idfromdb == $selectedrun){
				$cars .= " selected";
			}
			$cars .= ">" .$carfromdb ."</option> \n";
		}
		if(!empty($cars)){
			$notice = '<select name="carinput">' ."\n";
			$notice .= '<option value="" selected disabled>Vali auto</option>' ."\n";
			$notice .= $cars;
			$notice .= "</select> \n";
		}
		
		$stmt->close();
		$conn->close();
		return $notice;
	}


//Read car to select lõppeb



	function  storeWeightOut($selectedrun, $weightout){
		$notice = "";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT weight_in FROM vpscaledata WHERE scaledata_id = ?");
		echo $conn->error;
		$stmt->bind_param("i", $selectedrun);
		$stmt->bind_result($weightinfromdb);
		$stmt->execute();
		$stmt->fetch();
		$stmt->close();
		
		if(!empty($weightinfromdb)){ 
			$wdiff = $weightinfromdb - $weightout;
			$stmt = $conn->prepare("UPDATE vpscaledata SET weight_out =? ,weightdiff = ? WHERE scaledata_id = ?");
			echo $conn->error;
			$stmt->bind_param("iii", $weightout, $wdiff,  $selectedrun);
			if($stmt->execute()){
				$notice = "Väljuv kaal edukalt salvestatud!";
			} else {
				$notice = "Kaalu salvestamisel tekkis tehniline tõrge!";
			}
		
		
			$stmt->close();
			$conn->close();
		}	
		return $notice;		
	}

// store  weight out lõppeb

function readScaleData($sortby, $sortorder){
		$notice = "";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$SQLsentence = "SELECT car_number, weight_in, weight_out, weightdiff FROM vpscaledata";
		if($sortby == 0 and $sortorder ==  0){
			$stmt = $conn->prepare($SQLsentence);
		}
		if($sortby == 1) {
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY car_number DESC"); 
			}
			else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY car_number"); 
			}
		}
		if($sortby == 2) {
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY weight_in DESC"); 
			}
			else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY weight_in"); 
			}	
		}	
		
		
		if($sortby == 3) {
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY weight_out DESC"); 
			}
			else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY weight_out"); 
			}
		}
		if($sortby == 4) {
			if($sortorder == 2){
				$stmt = $conn->prepare($SQLsentence ." ORDER BY weightdiff DESC"); 
			}
			else{
				$stmt = $conn->prepare($SQLsentence ." ORDER BY weightdiff"); 
			}
		}
		echo $conn->error;
		$stmt->bind_result ($carfromdb,$weightinfromdb,$weightoutfromdb,$weightdifffromdb);
		$stmt->execute();
		$lines = "";
		while($stmt->fetch()){
		$lines.=  "<tr>";
		$lines.= "<td>" .$carfromdb ."</td>";
			
		$lines .= "<td>" .$weightinfromdb ."</td>";
						
		$lines.= "<td>" .$weightoutfromdb ."</td>";
			
		$lines.= "<td>" .$weightdifffromdb ."</td>";
				
		$lines.= "</tr>";
		}
		//pealkirjad TH puudu veel
		if(!empty($lines)){
			
			$notice .= '<table><tr><th>Auto &nbsp; <a href="?sortby=1&sortorder=1">&uarr;</a> &nbsp; <a href="?sortby=1&sortorder=2">&darr;</a></th>';
			$notice .= '<th>Sisendkaal &nbsp; <a href="?sortby=2&sortorder=1">&uarr;</a> &nbsp; <a href="?sortby=2&sortorder=2">&darr;</a></th>';
			// uarr u ->upward  duarr d-> downward  sortorder 1 up sortorder 2 alla...
			$notice .= '<th>Väljundkaal &nbsp; <a href="?sortby=3&sortorder=1">&uarr;</a> &nbsp; <a href="?sortby=3&sortorder=2">&darr;</a></th>';
			$notice .= '<th>Maha laetud &nbsp; <a href="?sortby=4&sortorder=1">&uarr;</a> &nbsp; <a href="?sortby=4&sortorder=2">&darr;</a></th></tr>';
			$notice .= $lines;
			$notice .= "</table>";
		}
		 else {
			  $notice = "<p>Kahjuks viljaveo andmeid ei leitud!</p>";
		  }
		
		
		
		$stmt->close();
		$conn->close();
		return $notice;
	}  