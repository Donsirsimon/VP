<?php
	$database ="if20_ken_pi_1";
	$movielist = null;
	$genrelist = null;
	
//----------------------------------------------------------------------------------------------------------------------------------
//ANDMETE SALVESTAMINE ANDMEBAASI
	//FILMI SALVESTAMINE
	function storefilm($titleinput, $yearinput, $durationinput, $filmdescriptioninput){
		$notice = "";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT movie_id FROM movie WHERE title = ? AND production_year = ?");
		echo $conn->error;
		$stmt->bind_param("si", $titleinput, $yearinput);
		$stmt->bind_result($idfromdb);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = "Selline film on juba olemas!";
		} else {
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO movie (title, production_year, duration, description) VALUES(?,?,?,?)");
			echo $conn->error;
			$stmt->bind_param("siis", $titleinput, $yearinput, $durationinput, $filmdescriptioninput);
			if($stmt->execute()){
				$notice = "Uus film on edukalt salvestatud!";
			} else {
				$notice = "Filmi salvestamisel tekkis tehniline tõrge: " .$stmt->error;
			}
		}
		
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	//ISIKU SALVESTAMINE
	function storeperson($firstnameinput, $lastnameinput, $birthdate){
		$notice = "";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT person_id FROM person WHERE first_name = ? AND last_name = ? AND birth_date = ?");
		echo $conn->error;
		$stmt->bind_param("sss", $firstnameinput, $lastnameinput, $birthdate);
		$stmt->bind_result($idfromdb);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = "Selline isik on juba olemas!";
		} else {
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO person (first_name, last_name, birth_date) VALUES(?,?,?)");
			echo $conn->error;
			$stmt->bind_param("sss", $firstnameinput, $lastnameinput, $birthdate);
			if($stmt->execute()){
				$notice = "Uus isik on edukalt salvestatud!";
			} else {
				$notice = "Isiku salvestamisel tekkis tehniline tõrge: " .$stmt->error;
			}
		}
		
		$stmt->close();
		$conn->close();
		return $notice;
	}	
	//STUUDIO SALVESTAMINE
	function storestudio($studioinput, $addressinput){
		$notice = "";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT production_company_id FROM production_company WHERE company_name = ?");
		echo $conn->error;
		$stmt->bind_param("s", $studioinput);
		$stmt->bind_result($idfromdb);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = "Selline tootja on juba olemas!";
		}else{
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO production_company (company_name, company_address) VALUES(?,?)");
			echo $conn->error;
			$stmt->bind_param("ss", studioinput, $addressinput);
			if($stmt->execute()){
				$notice = "Uus tootja on edukalt salvestatud!";
			} 
			else{
				$notice = "Tootja salvestamisel tekkis tehniline tõrge: " .$stmt->error;
			}
		}
		
		$stmt->close();
		$conn->close();
		return $notice;
	}		
	//ŽANRI SALVESTAMINE
	function storegenre($genrenameinput, $genredescriptioninput){
		$notice = "";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT genre_id FROM genre WHERE genre_name = ?");
		echo $conn->error;
		$stmt->bind_param("s", $genrenameinput);
		$stmt->bind_result($idfromdb);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = "Selline žanr on juba olemas!";
		}else{
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO genre (genre_name, description) VALUES(?,?)");
			echo $conn->error;
			$stmt->bind_param("ss", $genrenameinput, $genredescriptioninput);
			if($stmt->execute()){
				$notice = "Uus žanr on edukalt salvestatud!";
			} 
			else{
				$notice = "Žanri salvestamisel tekkis tehniline tõrge: " .$stmt->error;
			}
		}
		
		$stmt->close();
		$conn->close();
		return $notice;
	}			
	//POSITSIOONI SALVESTAMINE
	function storeposition($positioninput, $positiondescriptioninput){
		$notice = "";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT position_id FROM position WHERE position_name = ?");
		echo $conn->error;
		$stmt->bind_param("s", $positioninput);
		$stmt->bind_result($idfromdb);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = "Selline positsioon on juba olemas!";
		} else {
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO position (position_name, description) VALUES(?,?)");
			echo $conn->error;
			$stmt->bind_param("ss", $positioninput, $positiondescriptioninput);
			if($stmt->execute()){
				$notice = "Uus positsioon on edukalt salvestatud!";
			} 
			else {
				$notice = "Positsiooni salvestamisel tekkis tehniline tõrge: " .$stmt->error;
			}
		}
		
		$stmt->close();
		$conn->close();
		return $notice;
	}				
	//TSITAADI SALVESTAMINE
	function storequote($quoteinput){
		$notice = "";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT quote_id FROM quote WHERE quote_text = ?");
		echo $conn->error;
		$stmt->bind_param("s", $quoteinput);
		$stmt->bind_result($idfromdb);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = "Selline tsitaat on juba olemas!";
		} else {
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO quote (quote_text) VALUES(?)");
			echo $conn->error;
			$stmt->bind_param("s", $quoteinput);
			if($stmt->execute()){
				$notice = "Uus tsitaat on edukalt salvestatud!";
			} 
			else {
				$notice = "tsitaadi salvestamisel tekkis tehniline tõrge: " .$stmt->error;
			}
		}
		
		$stmt->close();
		$conn->close();
		return $notice;
	}				

	

//------------------------------------------------------------------------------------------------------------------------------------	
//UUED FUNKTSIOONID Loeme seoste jaoks andmebaasi
	//STUUDIO lugemine
	function readstudiotoselect($selectedstudio){
		$notice = "<p>Kahjuks stuudioid ei leitud!</p> \n";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT production_company_id, company_name FROM production_company");
		echo $conn->error;
		$stmt->bind_result($idfromdb, $companyfromdb);
		$stmt->execute();
		$studios = "";
		while($stmt->fetch()){
			$studios .= '<option value="' .$idfromdb .'"';
			if($idfromdb == $selectedstudio){
				$studios .= " selected";
			}
			$studios .= ">" .$companyfromdb ."</option> \n";
		}
		if(!empty($studios)){
			$notice = '<select name="studioinput">' ."\n";
			$notice .= '<option value="" selected disabled>Vali stuudio</option>' ."\n";
			$notice .= $studios;
			$notice .= "</select> \n";
		}
		
		$stmt->close();
		$conn->close();
		return $notice;
	}
	//FILMI LUGEMINE
	function readmovietoselect($selectedfilm){
		$notice = "<p>Kahjuks filme ei leitud!</p> \n";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT movie_id, title FROM movie");
		echo $conn->error;
		$stmt->bind_result($idfromdb, $titlefromdb);
		$stmt->execute();
		$films = "";
		while($stmt->fetch()){
			$films .= '<option value="' .$idfromdb .'"';
			if(intval($idfromdb) == $selectedfilm){
				$films .=" selected";
			}
			$films .= ">" .$titlefromdb ."</option> \n";
		}
		if(!empty($films)){
			$notice = '<select name="filminput">' ."\n";
			$notice .= '<option value="" selected disabled>Vali film</option>' ."\n";
			$notice .= $films;
			$notice .= "</select> \n";
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	// ŽANRI LUGEMINE
	function readgenretoselect($selectedgenre){
		$notice = "<p>Kahjuks žanre ei leitud!</p> \n";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT genre_id, genre_name FROM genre");
		echo $conn->error;
		$stmt->bind_result($idfromdb, $genrefromdb);
		$stmt->execute();
		$genres = "";
		while($stmt->fetch()){
			$genres .= '<option value="' .$idfromdb .'"';
			if(intval($idfromdb) == $selectedgenre){
				$genres .=" selected";
			}
			$genres .= ">" .$genrefromdb ."</option> \n";
		}
		if(!empty($genres)){
			$notice = '<select name="filmgenreinput">' ."\n";
			$notice .= '<option value="" selected disabled>Vali žanr</option>' ."\n";
			$notice .= $genres;
			$notice .= "</select> \n";
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
//-----------------------------------------------------------------------------------------------------------------------------------	
	//KODUNE TÖÖ
	//isiku lugemine
	function readpersontoselect($selectedperson){
		$notice = "<p>Kahjuks inimesi ei leitud!</p> \n";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT person_id, first_name, last_name FROM person");
		echo $conn->error;
		$stmt->bind_result($personidfromdb, $firstnamefromdb, $lastnamefromdb);
		$stmt->execute();
		$people = "";
		while($stmt->fetch()){
			$people .= '<option value="' .$personidfromdb .'"';
			if(intval($personidfromdb) == $selectedperson){
				$people .=" selected";
			}
			$people .= ">" .$firstnamefromdb ." " .$lastnamefromdb ."</option> \n";
		}
		if(!empty($people)){
			$notice = '<select name="personinput">' ."\n";
			$notice .= '<option value="" selected disabled>Vali isik</option>' ."\n";
			$notice .= $people;
			$notice .= "</select> \n";
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}	
	//Positioni lugemine
	function readpositiontoselect($selectedposition){
		$notice = "<p>Kahjuks rolle ei leitud!</p> \n";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT position_id, position_name FROM position");
		echo $conn->error;
		$stmt->bind_result($positionidfromdb, $positionnamefromdb);
		$stmt->execute();
		$position = "";
		while($stmt->fetch()){
			$position .= '<option value="' .$positionidfromdb .'"';
			if(intval($positionidfromdb) == $selectedposition){
				$position .=" selected";
			}
			$position .= ">" .$positionnamefromdb ."</option> \n";
		}
		if(!empty($position)){
			$notice = '<select name="positioninput">' ."\n";
			$notice .= '<option value="" selected disabled>Vali roll</option>' ."\n";
			$notice .= $position;
			$notice .= "</select> \n";
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}	
	//Quote lugemine	
	function readquotetoselect($selectedquote){
		$notice = "<p>Kahjuks tsitaate ei leitud!</p> \n";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT quote_id, quote_text FROM quote");
		echo $conn->error;
		$stmt->bind_result($quoteidfromdb, $quotetextfromdb);
		$stmt->execute();
		$quote = "";
		while($stmt->fetch()){
			$quote .= '<option value="' .$quoteidfromdb .'"';
			if(intval($quoteidfromdb) == $selectedquote){
				$quote .=" selected";
			}
			$quote .= ">" .$quotetextfromdb ."</option> \n";
		}
		if(!empty($quote)){
			$notice = '<select name="quoteinput">' ."\n";
			$notice .= '<option value="" selected disabled>Vali tsitaat</option>' ."\n";
			$notice .= $quote;
			$notice .= "</select> \n";
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}


			
	
	
//---------------------------------------------------------------------------------------------------------------------------------	
// SEOSTE SALVESTaMINE
	// STORE GENRE RELATION
	function storenewgenrerelation($selectedfilm, $selectedgenre){
		$notice = "";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT movie_genre_id FROM movie_genre WHERE movie_id = ? AND genre_id = ?");
		echo $conn->error;
		$stmt->bind_param("ii", $selectedfilm, $selectedgenre);
		$stmt->bind_result($idfromdb);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = "Selline seos on juba olemas!";
		} else {
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO movie_genre (movie_id, genre_id) VALUES(?,?)");
			echo $conn->error;
			$stmt->bind_param("ii", $selectedfilm, $selectedgenre);
			if($stmt->execute()){
				$notice = "Uus seos edukalt salvestatud!";
			} else {
				$notice = "Seose salvestamisel tekkis tehniline tõrge: " .$stmt->error;
			}
		}
		
		$stmt->close();
		$conn->close();
		return $notice;
	}
	//STORE STUDIO RELATION
	function storenewstudiorelation($selectedfilm, $selectedstudio){
		$notice = "";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT movie_by_production_company_id FROM movie_by_production_company WHERE movie_id = ? AND production_company_id = ?");
		echo $conn->error;
		$stmt->bind_param("ii", $selectedfilm, $selectedstudio);
		$stmt->bind_result($idfromdb);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = "Selline seos on juba olemas!";
		} else {
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO movie_by_production_company (movie_id, production_company_id) VALUES(?,?)");
			echo $conn->error;
			$stmt->bind_param("ii", $selectedfilm, $selectedstudio);
			if($stmt->execute()){
				$notice = "Uus seos edukalt salvestatud!";
			} else {
				$notice = "Seose salvestamisel tekkis tehniline tõrge: " .$stmt->error;
			}
		}
		
		$stmt->close();
		$conn->close();
		return $notice;		
	}
	//STORE PERSON IN MOVIE RELATION
	function storenewpersonrelation($selectedfilm, $selectedperson, $selectedposition, $insertedrole){
		$notice = "";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT person_in_movie_id FROM person_in_movie WHERE person_id = ? AND movie_id = ? AND position_id = ?");
		echo $conn->error;
		$stmt->bind_param("iii", $selectedperson, $selectedfilm, $selectedposition);
		$stmt->bind_result($idfromdb);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = "Selline seos on juba olemas!";
		} else {
			$stmt->close();
			$stmt = $conn->prepare("SET FOREIGN_KEY_CHECKS = 0");
			$stmt->execute();
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO person_in_movie (person_id, movie_id, position_id, role) VALUES(?,?,?,?)");
			echo $conn->error;
			$stmt->bind_param("iiis", $selectedperson, $selectedfilm, $selectedposition, $insertedrole);
			if($stmt->execute()){
				$notice = "Uus seos edukalt salvestatud!";
			} else {
				$notice = "Seose salvestamisel tekkis tehniline tõrge: " .$stmt->error;
			}
			$stmt->close();
			$stmt = $conn->prepare("SET FOREIGN_KEY_CHECKS = 1");
			$stmt->execute();
		}
		
		$stmt->close();
		$conn->close();
		return $notice;		
	}
	
	// STORE QUOTE/PERSON IN MOVIE RELATION
	function storenewquoterelation($selectedfilm, $selectedperson, $selectedquote){
		$notice = "";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT person_in_movie_id FROM person_in_movie WHERE person_id = ? AND movie_id = ?");
		echo $conn->error;
		$stmt->bind_param("ii", $selectedperson, $selectedfilm);
		$stmt->bind_result($person_in_movie_idfromdb);
		$stmt->execute();
		if($stmt->fetch()){
			$stmt->close();
			$stmt = $conn->prepare("UPDATE quote SET person_in_movie_id = ? WHERE quote_id = ? ");
			$stmt->bind_param("ii", $person_in_movie_idfromdb, $selectedquote );
			if($stmt->execute()){
				$notice = "Uus seos edukalt salvestatud!";
			} else {
				$notice = "Seose salvestamisel tekkis tehniline tõrge: " .$stmt->error;
			}
		}
		
		$stmt->close();
		$conn->close();
		return $notice;		
	}
			
		
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
		if($sortby == 1){
			if($sortorder ==  2){
				$stmt = $conn->prepare($SQLsentence ." ORDER BY last_name DESC");
				
			}else{
				$stmt = $conn->prepare($SQLsentence ." ORDER BY last_name");
			}
		}
		if($sortby == 2){
			if($sortorder ==  2){
				$stmt = $conn->prepare($SQLsentence ." ORDER BY role DESC");
				
			}else{
				$stmt = $conn->prepare($SQLsentence ." ORDER BY role");
			}
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
			$notice .= '<th>Isik &nbsp; <a href="?sortby=1&sortorder=1">&uarr;</a> &nbsp; <a href="?sortby=1&sortorder=2">&darr;</a></th>';
			$notice .= '<th>Roll &nbsp; <a href="?sortby=2&sortorder=1">&uarr;</a> &nbsp; <a href="?sortby=2&sortorder=2">&darr;</a></th>';
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
