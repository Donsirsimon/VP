<?php
  $database ="if20_ken_pi_1";
  //konto loomise funktsioon
  function signup($firstname, $lastname, $birthdate, $gender, $email, $password) {
	  $notice = null;
	  $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	  $stmt = $conn->prepare("INSERT INTO vpusers (firstname, lastname, birthdate, gender, email, password) VALUES(?, ?, ?, ?, ?, ?)");
	  echo $conn->error;
	  
	  // Krüpteerime salasõna//In cryptography, SHA-1 (Secure Hash Algorithm 1) is a cryptographic hash function which takes an input and produces a 160-bit (20-byte) hash value known as a message digest – typically rendered as a hexadecimal number, 40 digits long.
	  $options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)];
	  $pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
	  // sssiss = string või int...
	  $stmt->bind_param("sssiss", $firstname, $lastname, $birthdate, $gender, $email, $pwdhash);
	  
	  if($stmt->execute()) {
		  $notice = "OK";
	  }
	  else {
		  $notice = $stmt->error;
	  }
	  
	  $stmt->close();
	  $conn->close();
	  return $notice;
  }
  //Sisse logimise funktsioon
  function signin($email, $password) {
	  $notice = null;
	  $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	  $stmt = $conn->prepare("SELECT password FROM vpusers WHERE email = ?");
	  echo $conn->error;
	  
	  $stmt->bind_param("s", $email);
	  $stmt->bind_result($passwordfromdb);
	  
	  if($stmt->execute()) {
		  //andmebaasipäring õnnestus
		  if($stmt->fetch()) {
			  // Kasutaja leiti
			  if(password_verify($password, $passwordfromdb)) {
				  // Parool õige
				  $stmt->close();
				  
				  //loen sisseloginud kasutaja infot
				  $stmt = $conn->prepare("SELECT vpusers_id, firstname, lastname FROM vpusers WHERE email = ?");
				  echo $conn->error;
	  
				  $stmt->bind_param("s", $email);
				  $stmt->bind_result($idfromdb, $firstnamefromdb, $lastnamefromdb);
				  $stmt->execute();
				  $stmt->fetch();
				  //salvestame sessioonimuutujad
				  $_SESSION["userid"]=$idfromdb;
				  $_SESSION["userfirstname"] = $firstnamefromdb;
				  $_SESSION["userlastname"] = $lastnamefromdb;
				  
				  //värvid tuleb lugeda profiilist, kui see on olemas
				  $_SESSION["userbgcolor"] = "#FFFFFF";					$stmt->close();
				  $_SESSION["usertxtcolor"] = "#000000";					$stmt = $conn->prepare("SELECT bgcolor, txtcolor FROM vpuserprofiles WHERE userid = ?");
				  $stmt->bind_param("i", $_SESSION["userid"]);
				  $stmt->bind_result($bgcolorfromdb, $txtcolorfromdb);
				  $stmt->execute();
				  if($stmt->fetch()){
					  $_SESSION["usertxtcolor"] = $txtcolorfromdb;
					  $_SESSION["userbgcolor"] = $bgcolorfromdb;
				  } else {
					  $_SESSION["usertxtcolor"] = "#000000";
					  $_SESSION["userbgcolor"] = "#FFFFFF";
				  }
				  $stmt->close();
				  $conn->close();
				  header("Location: home.php");
				  exit();
			  }
			  else {
				  $notice = "Vale salasõna!";
			  }
		  }
		  else {
			  $notice = "Sellist kasutajat (" .$email .") ei leitud!";
		  }
	  }
	  else {
		  // Tehniline viga
		  $notice = $stmt->error;
	  }
	  $stmt->close();
	  $conn->close();
	  return $notice;
  }
  
  
  //Kasutajaprofiili salvestamine
  function storeuserprofile($description, $bgcolor, $txtcolor){
	  $notice = null;
	  $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	  $stmt = $conn->prepare("SELECT vpuserprofiles_id FROM vpuserprofiles WHERE userid = ?");
	  echo $conn->error;
	  $stmt->bind_param("i", $_SESSION["userid"]);
	  $stmt->execute();
	  if($stmt->fetch()) {
		  $stmt->close();
		  //uuendame profiili
		  $stmt = $conn->prepare("UPDATE vpuserprofiles SET description = ?, bgcolor = ?, txtcolor = ? WHERE userid = ?");
		  echo $conn->error;
		  $stmt->bind_param("sssi", $description, $bgcolor, $txtcolor, $_SESSION["userid"]);	   
	  }
	  else {
		  $stmt->close();
		  //tekitame uue profiili
		  $stmt = $conn->prepare("INSERT INTO vpuserprofiles (userid, description, bgcolor, txtcolor) VALUES(?,?,?,?)");
		  echo $conn->error;
		  $stmt->bind_param("isss", $_SESSION["userid"], $description, $bgcolor, $txtcolor);
			   
	  }
	  if ($stmt->execute()) {
		  $notice = "Profiil edukalt salvestatud";
	  }
	  else {
		  $notice = "Profiili salvestamisel tekkis viga: " .$stmt->error;
      }
	  $stmt->close();
	  $conn->close();
	  return $notice;    	  
  }				  
	  //kontrollime kas äkki on profiil olemas
	  //SELECT vpuserprofiles_id fromvpuserprofiles where userid = ?
	  // küsimärk asendada väärtusega 
	  // $_SESSION["userid"];
	  // kui profiili pole olemas siis loome
	  // INSERT INTO vpuserprofiles (userid, description, bgcolor, txtcolor) VALUES(?,?,?,?)
	  
	  //kui porofiil on olems siis uuendame
	  //UPDATE vpusersprofiles SET description = ?, bgcolor = ?, txtcolor = ? WHERE userid = ?
	  //execute jms võib loomisel/uuendamisel ühine siia...
	  
  	  
  function readuserdescription(){
	//kui profiil on olemas, loeb kasutaja lühitutvustuse
	  $notice = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		//vaatame, kas on profiil olemas
		$stmt = $conn->prepare("SELECT description FROM vpuserprofiles WHERE userid = ?");
		echo $conn->error;
		$stmt->bind_param("i", $_SESSION["userid"]);
		$stmt->bind_result($descriptionfromdb);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = $descriptionfromdb;
		}
		$stmt->close();
		$conn->close();
		return $notice;
  }