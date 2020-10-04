<?php
  $database ="if20_ken_pi_1";
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