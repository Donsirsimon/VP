<?php
$database ="if20_ken_pi_1";
require("config.php");
//var_dump($GLOBALS);
// funktsioon mis loeb kõikide filmide infot.
function readfilms (){
$conn =new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT pealkiri, aasta, kestus, zanr, tootja, lavastaja FROM film");
	// kogu nimekirja asemel et all saada võid ka * panna (tärn)
	echo $conn->error;
	//seome tulemuse muutujaga
	$stmt->bind_result ($titlefromdb,$yearfromdb,$durationfromdb,$gernefromdb,$studiofromdb,$directorfromdb);
	// samas järjestuses mis databaasist tuleb mõni rida üleval pool... tärniga tuleb nii nagu databaasis on, nimedega saad järjestust muuta a pole soovitav
	$stmt->execute();
	$filmhtml = "\t <ol> \n";
	while($stmt->fetch()){
		$filmhtml .="\t \t <li>" .$titlefromdb ."\n";
		$filmhtml .="\t \t \t <ul> \n";
		$filmhtml .= "\t \t \t \t <li>Valmimisaasta: " .$yearfromdb . " </li> \n" ;
		$filmhtml .= "\t \t \t \t <li> Kestus minutites: " .$durationfromdb . " minutit.</li> \n" ;
		$filmhtml .= "\t \t \t \t <li>Zanr: " .$gernefromdb . " </li> \n" ;
		$filmhtml .= "\t \t \t \t <li>Filmistuudio: " .$studiofromdb . " </li> \n" ;
		$filmhtml .= "\t \t \t \t <li>Lavastaja: " .$directorfromdb . " </li> \n" ;
		$filmhtml .="\t \t \t </ul> \n";
		$filmhtml .= "\t \t </li> \n";
	}
	$filmhtml .= "\t</ol> \n";
	
	$stmt->close();
	$conn->close(); 
	return $filmhtml; // tagastab ainult ühe muutuja kui vaja mitut tuleb teha massiiivina....
}//readfilms lõppeb

function savefilm($titleinput, $yearinput, $durationinput, $gerneinput, $studioinput, $directorinput){
	$conn =new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("INSERT INTO film (pealkiri,aasta,kestus,zanr,tootja,lavastaja) VALUES(?,?,?,?,?,?)");
	echo $conn-> error;
	$stmt->bind_param("siisss", $titleinput, $yearinput, $durationinput, $gerneinput, $studioinput, $directorinput );  
	// s on varchar ja i on int ehk mis on tüüp andmebaasis
	$stmt->execute();
	$stmt->close();
	$conn->close();
	
}	//savefilm lõppeb