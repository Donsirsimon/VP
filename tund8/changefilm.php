<?php

require("usesession.php");
require("../../../config.php");	
require("fnc_movie_ab.php");
require("fnc_common.php");
//$database ="if20_ken_pi_1";
//$filmhtml = readfilms ();  
$genrenotice = "";
$selectedfilm = "";
$selectedgenre = "";
$selectedperson = "";
$selectedposition = "";
$selectedquote = "";
$studionotice = "";
$selectedstudio = "";
$notice ="";
$personnotice = "";
$quotenotice = "";
$roleinput = "";
//kui klikit submit siis ...
//SEOSED
if(isset($_POST["filmgenrerelationsubmit"])){
	//$selectedfilm = $_POST["filminput"];
	if(!empty($_POST["filminput"])){
		$selectedfilm = intval($_POST["filminput"]);
	} else {
		$genrenotice = " Vali film!";
	}
	if(!empty($_POST["filmgenreinput"])){
		$selectedgenre = intval($_POST["filmgenreinput"]);
	} else {
		$genrenotice .= " Vali žanr!";
	}
	if(!empty($selectedfilm) and !empty($selectedgenre)){
		$genrenotice = storenewgenrerelation($selectedfilm, $selectedgenre);
	}
}


if(isset($_POST["filmstudiorelationsubmit"])){
	if(!empty($_POST["filminput"])){
		$selectedfilm = intval($_POST["filminput"]);
	} else {
		$studionotice = " Vali film!";
	}
	if(!empty($_POST["filmstudioinput"])){
		$selectedstudio = intval($_POST["filmstudioinput"]);
	} else {
		$studionotice .= " Vali stuudio!";
	}
	if(!empty($selectedfilm) and !empty($selectedstudio)){
	$studionotice = storenewstudiorelation($selectedfilm, $selectedstudio);
	}
}
if(isset($_POST["filmpersonrelationsubmit"])){
	if(!empty($_POST["filminput"])){
		$selectedfilm = intval($_POST["filminput"]);
	} else {
		$personnotice = " Vali film!";
	}
	if(!empty($_POST["personinput"])){
		$selectedperson = intval($_POST["personinput"]);
	} else {
		$personnotice .= " Vali isik!";
	}
	if(!empty($_POST["positioninput"])){
		$selectedposition = intval($_POST["positioninput"]);
		if ($selectedposition == 1) {
			if(!empty($_POST["roleinput"])) {
				$insertedrole = test_input($_POST["roleinput"]);
			}
			else {
				$personnotice .= " Sisesta roll!";
			}
		} else {
			$insertedrole = "";
			
		}
	} else {
		$personnotice .= " Vali positsioon!";
	}
	if(!empty($selectedfilm) and !empty($selectedperson) and !empty($selectedposition)){
		if ($selectedposition == 1 and !empty($insertedrole)) {
			$personnotice = storenewpersonrelation($selectedfilm, $selectedperson, $selectedposition, $insertedrole);
		}
		else if($selectedposition != 1 and empty($insertedrole)) {
			$personnotice = storenewpersonrelation($selectedfilm, $selectedperson, $selectedposition, $insertedrole);
		}
	}
}

//QUOTE 
if(isset($_POST["filmquoterelationsubmit"])){
	if(!empty($_POST["filminput"])){
		$selectedfilm = intval($_POST["filminput"]);
	} else {
		$quotenotice = " Vali film!";
	}
	if(!empty($_POST["personinput"])){
		$selectedperson = intval($_POST["personinput"]);
	} else {
		$quotenotice .= " Vali isik!";
	}
	if(!empty($_POST["quoteinput"])){
		$selectedquote = intval($_POST["quoteinput"]);
	} else {
		$quotenotice .= " Vali tsitaat!";
	}
	if(!empty($selectedfilm) and !empty($selectedperson) and !empty($selectedquote)){
		$quotenotice = storenewquoterelation($selectedfilm, $selectedperson, $selectedquote);
	}
}
//TABELITE TÄITMINE
$monthnameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
$birthday = null;
$birthmonth = null;
$birthyear = null;
$birthdate = null;
$storefilmnotice = "";
$storepersonnotice = "";
$storestudionotice = "";
$storegenrenotice = "";
$storepositionnotice = "";
$storequotenotice = "";
//FILM
if(isset($_POST["filmsubmit"])){

	if (!empty($_POST["titleinput"])){
		$titleinput = test_input($_POST["titleinput"]);
		//echo $firstname;
	} else {
		$storefilmnotice .= " Palun sisesta pealkiri!";
	}
	if (!empty($_POST["yearinput"])){	
		if ($_POST["yearinput"] > date ("Y") or $_POST["yearinput"] < 1895){
			$storefilmnotice .= " Ebareaalne valmimisaasta!";
		}else{
			$yearinput = test_input($_POST["yearinput"]);
		}
	}	
	if (!empty($_POST["durationinput"])){
		$durationinput = test_input($_POST["durationinput"]);
	} else {
		$storefilmnotice .= " Palun sisesta filmi pikkus!";
	}
	if (!empty($_POST["filmdescriptioninput"])){
		$filmdescriptioninput = test_input($_POST["filmdescriptioninput"]);
	} else {
		$storefilmnotice .= " Palun sisesta filmi kirjeldus!";
	}	
	if (!empty($titleinput) and !empty($yearinput) and !empty($durationinput) and !empty($filmdescriptioninput)){
		$storefilmnotice = storefilm($titleinput, $yearinput, $durationinput, $filmdescriptioninput);
	}
}	

//PERSON
if(isset($_POST["personsubmit"])){

	if (!empty($_POST["first_name_input"])){
		$firstnameinput = test_input($_POST["first_name_input"]);
	} else {
		$storepersonnotice .= " Palun sisesta eesnimi!";
	}
	if (!empty($_POST["last_name_input"])){
		$lastnameinput = test_input($_POST["last_name_input"]);
	} else {
		$storepersonnotice .= " Palun sisesta perekonnanimi!";
	}
	if(!empty($_POST["birthdayinput"])){
	  $birthday = intval($_POST["birthdayinput"]);
	} else {
	  $storepersonnotice .= " Palun vali sünnikuupäev!";
	}

	if(!empty($_POST["birthmonthinput"])){
	  $birthmonth = intval($_POST["birthmonthinput"]);
	} else {
	  $storepersonnotice .= " Palun vali sünnikuu!";
	}

	if(!empty($_POST["birthyearinput"])){
	  $birthyear = intval($_POST["birthyearinput"]);
	} else {
	  $storepersonnotice .= " Palun vali sünniaasta!";
	}

	//kontrollime kuupäeva kehtivust (valiidsust)
	if(!empty($birthday) and !empty($birthmonth) and !empty($birthyear)){
	  if(checkdate($birthmonth, $birthday, $birthyear)){
		  $tempdate = new DateTime($birthyear ."-" .$birthmonth ."-" .$birthday);
		  $birthdate = $tempdate->format("Y-m-d");
	  } else {
		  $storepersonnotice .= " Kuupäev ei ole reaalne!";
	  }
	}	
	
	if (!empty($firstnameinput) and !empty($lastnameinput) and !empty($birthdate)){
		$storepersonnotice = storeperson($firstnameinput, $lastnameinput, $birthdate);
	}
}
//STUUDIO/TOOTJA
if(isset($_POST["studiosubmit"])){

	if (!empty($_POST["studioinput"])){
		$studioinput = test_input($_POST["studioinput"]);
	} else {
		$storestudionotice .= " Palun sisesta stuudio/tootja nimi!";
	}
	if (!empty($_POST["addressinput"])){
		$addressinput = test_input($_POST["addressinput"]);
	} else {
		$storestudionotice .= " Palun sisesta  aadress!";
	}	
	if (!empty($studioinput) and !empty($addressinput)){
		$storestudionotice = storestudio($studioinput, $addressinput);
	}
}
//ŽANR
if(isset($_POST["genresubmit"])){

	if (!empty($_POST["genrenameinput"])){
		$genrenameinput = test_input($_POST["genrenameinput"]);
	} else {
		$storegenrenotice .= " Palun sisesta žanr!";
	}
	if (!empty($_POST["genredescriptioninput"])){
		$genredescriptioninput = test_input($_POST["genredescriptioninput"]);
	} else {
		$storegenrenotice .= " Palun sisesta žanri kirjeldus!";
	}	
	if (!empty($genrenameinput) and !empty($genredescriptioninput)){
		$storegenrenotice = storegenre($genrenameinput, $genredescriptioninput);
	}
}	
//POSITION
if(isset($_POST["positionsubmit"])){

	if (!empty($_POST["positionnameinput"])){
		$positioninput = test_input($_POST["positionnameinput"]);
	} else {
		$storepositionnotice .= " Palun sisesta positsioon!";
	}
	if (!empty($_POST["positiondescriptioninput"])){
		$positiondescriptioninput = test_input($_POST["positiondescriptioninput"]);
	} else {
		$storepositionnotice .= " Palun sisesta positsiooni kirjeldus!";
	}
		
	if (!empty($positioninput) and !empty($positiondescriptioninput)){
		$storepositionnotice = storeposition($positioninput, $positiondescriptioninput);
	}
}	
//TSITAAT
if(isset($_POST["quotesubmit"])){
	if (!empty($_POST["quoteinput"])){
		$quoteinput = test_input($_POST["quoteinput"]);
		$storequotenotice = storequote($quoteinput);
	} else {
		$storequotenotice = " Palun sisesta tsitaat!";
	}	
}			
$filmselecthtml = readmovietoselect($selectedfilm);
$filmgenreselecthtml = readgenretoselect($selectedgenre);
$filmstudioselecthtml = readstudiotoselect($selectedstudio);
$personselecthtml = readpersontoselect($selectedperson);
$positionselecthtml = readpositiontoselect($selectedposition);
$quoteselecthtml = readquotetoselect($selectedquote);
require("header.php");
 ?>
<html> 
<img src="../tund3/img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
<h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>

<hr>
<ul>
<li><a href="?logout=1">Logi välja</a>!</li>
<li><a href="home.php">Avaleht</a></li>
<li><a href="insertidea.php">Pane oma mõtted kirja!</a></li>
<li><a href="readidea.php">Kirja pandud mõtted</a></li>
<li><a href="listfilms.php">Loe filmide infot.</a></li>
</ul>
<hr>
<h1>ANDMED</h1>
<br>
<h2>Filmi andmete sisestamine</h2>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="titleinput"> Filmi pealkiri</label><br>
	<input type="test" name="titleinput" id="titleinput" placeholder="Pealkiri">
	<br>
	<label for="yearinput"> Filmi valmimisaasta</label><br>
	<input type="number" name="yearinput" id="yearinput" value="<?php echo date ("Y"); ?>">
	<br>
	<label for="durationinput"> Filmi kestus minutites</label><br>
	<input type="number" name="durationinput" id="durationinput" value="80"; ?>
	<br>
	<label for="filmdescriptioninput"> Filmi lühikirjeldus</label><br>
	<textarea rows="8" cols="80" name="filmdescriptioninput" id="filmdescriptioninput" placeholder="Kirjeldus" ></textarea>
	<br>
	<input type="submit" name="filmsubmit" value="Salvesta filmi info"><span><?php echo $storefilmnotice ?></span>
</form>
<hr>
<h2>Isikuandmete sisestamine</h2>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="first_name_input"> Eesnimi</label><br>
	<input type="test" name="first_name_input" id="first_name_input" placeholder="Eesnimi">
	<br>
	<label for="last_name_input"> Perekonnanimi</label><br>
	<input type="test" name="last_name_input" id="last_name_input" placeholder="Perekonnanimi">
	<br>
	<label for="birthdayinput">Sünnipäev: </label><br>
	<?php
		echo '<select name="birthdayinput" id="birthdayinput">' ."\n";
		echo "\t \t" .'<option value="" selected disabled>päev</option>' ."\n";
		for ($i = 1; $i < 32; $i ++){
			echo "\t \t" .'<option value="' .$i .'"';
			if ($i == $birthday){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "\t </select> \n";
	?>
	<br>
	<label for="birthmonthinput">Sünnikuu: </label><br>
	<?php
		echo "\t" .'<select name="birthmonthinput" id="birthmonthinput">' ."\n";
		echo "\t \t" .'<option value="" selected disabled>kuu</option>' ."\n";
		for ($i = 1; $i < 13; $i ++){
			echo "\t \t" .'<option value="' .$i .'"';
			if ($i == $birthmonth){
				echo " selected ";
			}
			echo ">" .$monthnameset[$i - 1] ."</option> \n";
		}
		echo "\t </select> \n";
	?>
	<br>
	<label for="birthyearinput">Sünniaasta: </label><br>
	<?php
		echo "\t" .'<select name="birthyearinput" id="birthyearinput">' ."\n";
		echo "\t \t" .'<option value="" selected disabled>aasta</option>' ."\n";
		for ($i = date("Y") - 8; $i >= date("Y") - 120; $i --){
			echo "\t \t" .'<option value="' .$i .'"';
			if ($i == $birthyear){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "\t </select> \n";
	?>
	<br>


	<input type="submit" name="personsubmit" value="Salvesta isiku andmed"><span><?php echo $storepersonnotice ?></span>

</form>
<hr>
<h2>Stuudio/tootja andmete sisestamine</h2>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="studioinput"> Filmi tootja / stuudio</label><br>
	<input type="test" name="studioinput" id="studioinput" placeholder="Stuudio">
	<br>
	<label for="addressinput"> Aadress </label><br>
	<textarea rows="2" cols="80" name="addressinput" id="addressinput" placeholder="Aadress"></textarea>
	<br>
	<input type="submit" name="studiosubmit" value="Salvesta tootja/stuudio andmed"><span><?php echo $storestudionotice ?></span>

</form>





<hr>
<h2>Žanri andmete sisestamine</h2>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="genrenameinput"> Filmi žanr</label><br>
	<input type="test" name="genrenameinput" id="genrenameinput" placeholder="Žanr">
	<br>
	
	<label for="genredescriptioninput"> Žanri kirjeldus</label><br>
	<textarea rows="6" cols="80" name="genredescriptioninput" id="genredescriptioninput" placeholder="Kirjeldus"></textarea>
	<br>
	
	
	<input type="submit" name="genresubmit" value="Salvesta žanri andmed"><span><?php echo $storegenrenotice ?></span>

</form>


<hr>
<h2>Positsiooni andmete sisestamine</h2>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="positionnameinput"> Sisesta positsioon filmis</label><br>
	<input type="test" name="positionnameinput" id="positionnameinput" placeholder="Positsioon">
	<br>
	
	<label for="positiondescriptioninput"> Positsiooni kirjeldus</label><br>
	<textarea rows="6" cols="80" name="positiondescriptioninput" id="positiondescriptioninput" placeholder="Kirjeldus"></textarea>
	<br>
	
	
	<input type="submit" name="positionsubmit" value="Salvesta posititsiooni andmed"><?php echo $storepositionnotice ?></span>

</form>


<hr>
<h2>Tsitaadi sisestamine</h2>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="quoteinput"> Tsitaat</label><br>
	<textarea rows="6" cols="80" name="quoteinput" id="quoteinput" placeholder="Tsitaat"></textarea>
	<br>
	
	
	<input type="submit" name="quotesubmit" value="Salvesta tsitaat"><?php echo $storequotenotice ?></span>

<h1>SEOSED</h1>

<br> 
<h2>Määrame filmi stuudio/tootja</h2>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <?php
		echo $filmselecthtml;
		echo $filmstudioselecthtml;
	?>
    <input type="submit" name="filmstudiorelationsubmit" value="Salvesta seos stuudioga"><span><?php echo $studionotice; ?></span>
</form>
    
<hr>
<h2>Määrame filmile žanri</h2>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <?php
		echo $filmselecthtml;
		echo $filmgenreselecthtml;
	?>
	
	<input type="submit" name="filmgenrerelationsubmit" value="Salvesta seos žanriga"><span><?php echo $genrenotice; ?></span>
  </form>
<hr>
<h2>Määrame filmile isikud</h2>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <?php
		echo $filmselecthtml;
		echo $personselecthtml;
		echo $positionselecthtml;
	?>	
	<label for="roleinput">Roll, kui näitleja: </label>
	<input type="text" name="roleinput" id="roleinput" value="<?php echo $roleinput; ?>">
	
	
	<input type="submit" name="filmpersonrelationsubmit" value="Salvesta seos isikuga"><span><?php echo $personnotice; ?></span>
  </form> 
<hr>
<h2>Määrame tsitaadile filmi ja isiku</h2>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <?php
		echo $filmselecthtml;
		echo $personselecthtml;
		echo $quoteselecthtml
	?>
	
	<input type="submit" name="filmquoterelationsubmit" value="Salvesta tsitaadi seos"><span><?php echo $quotenotice; ?></span>
  </form>    
<hr>
<br>
<p> <?php echo $notice ?> </p>


</body>
	</html>
	