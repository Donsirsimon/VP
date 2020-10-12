<?php
	$database ="if20_ken_pi_1";
	$movielist = null;
	$genrelist = null;
	function readmovies(){
		$notice = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		//vaatame, kas on profiil olemas
		$stmt = $conn->prepare("SELECT movie_id, title FROM movie");
		echo $conn->error;
		$stmt->bind_result($movieidfromdb, $movietitlefromdb);
		$stmt->execute();
		$movielist = null;
		while($stmt->fetch()){
			$movielist .= "\n \t \t" .'<option value="' .$movieidfromdb .'">' .$movietitlefromdb .'</option>';
		}
		
		$stmt->close();
		$conn->close();
		return $movielist;
	}
	
	
	
	
	
	
  
	function readgenres(){	
		$notice = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		//vaatame, kas on profiil olemas
		$stmt = $conn->prepare("SELECT genre_id, genre_name FROM genre");
		echo $conn->error;
		$stmt->bind_result($genreidfromdb, $genrefromdb);
		$stmt->execute();
		$genrelist = null;
		while($stmt->fetch()){
			$genrelist .= "\n \t \t" .'<option value="' .$genreidfromdb .'">' .$genrefromdb .'</option>';
		}
		
		$stmt->close();
		$conn->close();
		return $genrelist;

	}
	
	
	
	
	function storemoviegenre($movieinput, $genreinput){
		$notice = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SET FOREIGN_KEY_CHECKS=0");
		if ($stmt->execute());
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO movie_genre (movie_genre_id, movie_id, genre_id) VALUES(NULL,?,?)");
			echo $conn->error;
			$stmt->bind_param("ii", $movieinput, $genreinput);
			if ($stmt->execute()) {
				$notice = "Žanr edukalt salvestatud";
			}	
			else {
				$notice = "Žanri salvestamisel tekkis viga: " .$stmt->error;
			}
			$stmt->close();
			$stmt = $conn->prepare("SET FOREIGN_KEY_CHECKS=1");
			$stmt->execute();
			$stmt->close();
			$conn->close();
			return $notice;    	  
	}				  
		
  
  
  
  
  
  
  
  
  ?>
  