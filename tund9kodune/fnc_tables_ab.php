<?php
	$database ="if20_ken_pi_1";
	$movielist = null;
	$genrelist = null;
	

		
//---------------------------------------------------------------------------------------------------------------------------------------
//TABELI LOOMINE  
  
	//VANA inimene filmis ilma tabelita
	function oldreadpersonsinfilm(){
		$notice = "<p>Kahjuks filmitegelasi seoses filmidega ei leitud!</p> \n";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT first_name, last_name, role, title FROM person JOIN person_in_movie ON person.person_id = person_in_movie.person_id JOIN movie ON movie.movie_id = person_in_movie.movie_id");
		echo $conn->error;
		$stmt->bind_result($firstnamefromdb, $lastnamefromdb, $rolefromdb, $titlefromdb);
		$stmt->execute();
		$lines = "";
		while($stmt->fetch()){
			$lines.= "<p>" .$firstnamefromdb ." " .$lastnamefromdb;
			if(!empty($rolefromdb)){
				$lines .= " on tegelane " .$rolefromdb;
			}
			$lines .= ' filmis "' .$titlefromdb .'".' ."</p> \n";
		}
		if(!empty($lines)){
			$notice = $lines;
		}
		
		$stmt->close();
		$conn->close();
		return $notice;
	}
  
	// UUS  inimene filmis tabelitena
	function readpersonsinfilm($sortby, $sortorder){
		$notice = "<p>Kahjuks filmitegelasi seoses filmidega ei leitud!</p> \n";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$SQLsentence = "SELECT first_name, last_name, role, title FROM person JOIN person_in_movie ON person.person_id = person_in_movie.person_id JOIN movie ON movie.movie_id = person_in_movie.movie_id";
		if($sortby == 0 and $sortorder ==  0){
			$stmt = $conn->prepare($SQLsentence);
		}
		if($sortby == 4){
			if($sortorder ==  2){
				$stmt = $conn->prepare($SQLsentence ." ORDER BY title DESC");
				
			}else{
				$stmt = $conn->prepare($SQLsentence ." ORDER BY title");
			}
		}
			
		echo $conn->error;
		$stmt->bind_result($firstnamefromdb, $lastnamefromdb, $rolefromdb, $titlefromdb);
		$stmt->execute();
		$lines = "";
		while($stmt->fetch()){
			$lines.=  "<tr> \n";
			$lines.= "\r <td>" .$firstnamefromdb ." " .$lastnamefromdb ."</td>";
			$lines.= "<td>" .$rolefromdb ."</td>";
			$lines.= "<td>" .$titlefromdb ."</td> \n";
			$lines.= "</tr> \n";
		}
		//pealkirjad TH puudu veel
		if(!empty($lines)){
			$notice = "<table> \n";
			$notice .= "<tr> \n";
			$notice .= "<th>Isik</th><th>Roll</th>";
			// uarr u ->upward  duarr d-> downward  sortorder 1 up sortorder 2 alla...
			$notice .= '<th>Film &nbsp; <a href="?sortby=4&sortorder=1">&uarr;</a> &nbsp; <a href="?sortby=4&sortorder=2">&darr;</a></th>' ."\n";
			$notice .= "</tr> \n";
			$notice .= $lines;
			$notice .= "</table> \n";
		}
		
		$stmt->close();
		$conn->close();
		return $notice;
	}
//--------------------------------------------------------------------------------------------------
//UUS KODUTÖÖ TABEL
	function readdatabase($sortby, $sortorder){
		$notice = "<p>Kahjuks filmitegelasi, tsitaate seoses filmidega ei leitud!</p> \n";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$SQLsentence = "SELECT first_name, last_name, position_name, role, title, quote_text FROM person JOIN person_in_movie ON person.person_id = person_in_movie.person_id JOIN position ON position.position_id = person_in_movie.position_id JOIN movie ON movie.movie_id = person_in_movie.movie_id JOIN quote ON quote.person_in_movie_id = person_in_movie.person_in_movie_id";
		if($sortby == 0 and $sortorder ==  0){
			$stmt = $conn->prepare($SQLsentence);
		}
		if($sortby == 1) {
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY last_name DESC"); 
			}
			else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY last_name"); 
			}
		}
		if($sortby == 2) {
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY position_name DESC"); 
			}
			else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY position_name"); 
			}	
		}	
		//if($sortby == 3) {
			//if($sortorder == 2) {
				//$stmt = $conn->prepare($SQLsentence ." ORDER BY role DESC"); 
			//}
			//else {
				//$stmt = $conn->prepare($SQLsentence ." ORDER BY role"); 
			//}
		//}
		if($sortby == 3) {
			if($sortorder == 2) {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY title DESC"); 
			}
			else {
				$stmt = $conn->prepare($SQLsentence ." ORDER BY title"); 
			}
		}
		if($sortby == 4) {
			if($sortorder == 2){
				$stmt = $conn->prepare($SQLsentence ." ORDER BY quote_text DESC"); 
			}
			else{
				$stmt = $conn->prepare($SQLsentence ." ORDER BY quote_text"); 
			}
		}
		echo $conn->error;
		$stmt->bind_result($firstnamefromdb, $lastnamefromdb, $positionnamefromdb, $rolefromdb, $titlefromdb, $quotefromdb );
		$stmt->execute();
		$lines = "";
		while($stmt->fetch()){
		$lines.=  "<tr>\n";
		$lines.= "\r <td>" .$firstnamefromdb ." " .$lastnamefromdb ."</td>";
			if(!empty($rolefromdb)) {
			$lines .= "<td>" .$positionnamefromdb ." [" .$rolefromdb ."]" ."</td>";
			}
			else {
				$lines .= "<td>" .$positionnamefromdb ."</td>";
			}			
			$lines.= "<td>" .$titlefromdb ."</td>/t";
			if(!empty($quotefromdb)){
			$lines.= "<td>" .$quotefromdb ."</td> \n";
			}
			else{
				$lines.= "<td>Tsitaat puudub</td> \n";
			}	
			$lines.= "</tr> \n";
		}
		//pealkirjad TH puudu veel
		if(!empty($lines)){
			$notice = "<table> \n";
			$notice .= "<tr> \n";
			$notice .= '<th>Isik &nbsp; <a href="?sortby=1&sortorder=1">&uarr;</a> &nbsp; <a href="?sortby=1&sortorder=2">&darr;</a></th>';
			$notice .= '<th>Roll &nbsp; <a href="?sortby=2&sortorder=1">&uarr;</a> &nbsp; <a href="?sortby=2&sortorder=2">&darr;</a></th>';
			// uarr u ->upward  duarr d-> downward  sortorder 1 up sortorder 2 alla...
			$notice .= '<th>Film &nbsp; <a href="?sortby=3&sortorder=1">&uarr;</a> &nbsp; <a href="?sortby=3&sortorder=2">&darr;</a></th>';
			$notice .= '<th>Tsitaat &nbsp; <a href="?sortby=4&sortorder=1">&uarr;</a> &nbsp; <a href="?sortby=4&sortorder=2">&darr;</a></th>' ."\n";
			$notice .= "</tr> \n";
			$notice .= $lines;
			$notice .= "</table> \n";
		}
		
		$stmt->close();
		$conn->close();
		return $notice;
	}  
    
  
  ?>
  