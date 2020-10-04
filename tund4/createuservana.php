<?php
	$username = "Ken Pikanõmme";
	require("../../../config.php");
	require("fnc_common.php");	
	require("fnc_user.php");	

 
	$weekdaynameset =  ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
	$monthnameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
	$inputerror = "";
	$firstnameerror = "";
	$lastnameerror = "";
	$gendererror = "";
	$usernameerror = "";
	$passworderror = "";
	$passwordsecondaryerror = "";
	$birthdateerror = null;
	$birthdayerror = null;
	$birthmontherror = null;
	$birthyearerror = null;


	$firstname = "";
	$lastname = "";
	$gender = "";
	$email = "";
	$birthmonth = null;
	$birthday = null;
	$birthyear = null;
	$birthdate = null;
	$notice = "";
	//kui klikit submit siis ...
	if(isset($_POST["submituserdata"])){
		if(!empty($_POST["firstnameinput"])){
			$firstname = test_input($_POST["firstnameinput"]);
		} else {
			$firstnameerror .= "Eesnimi on sisestamata! ";
		}
		if(!empty($_POST["lastnameinput"])){
			$lastname =test_input($_POST["lastnameinput"]);
		} else {
			$lastnameerror .= "Perekonnanimi on sisestamata! ";
		}
		if(isset($_POST["genderinput"])){
			$gender = intval($_POST["genderinput"]);
			//$gender = $_POST["genderinput"];
		} else {
			  $gendererror = "Palun märgi sugu!";
		}
		if(!empty($POST["birthdayinput"])) {
			$birthday = intval($_POST["birthdayinput"]);
		} else {
			$birthdayerror = " Palun vali kuupäev.";
		}	
		if(!empty($POST["birthmonthinput"])) {
			$birthmonth = intval($_POST["birthmonthinput"]);
		} else {
			$birthmontherror = " Palun vali sünnikuu.";
		}	
		if(!empty($POST["birthyearinput"])) {
			$birthyear = intval($_POST["birthyearinput"]);
		} else {
			$birthyearerror = " Palun vali sünniaasta.";
		}	
		//kontrollime kuupäeva kehtivust (valiidsust)
		if(!empty($birthday) and !empty($birthmonth) and !empty($birthyear)) {
			if (checkdate ($birthmonth, $birthday, $birthyear)){
				$tempdate = new DateTime($birthyear ."-" .$birthmonth ."-" .$birthday);
				$birthdate = $tempdate->format("Y-m-d");
			} else {
				$birthdateerror = " Kuupäev ei ole reaalne!";
			}
		}		
		if(!empty($_POST["usernameinput"])){
			$email = test_input($_POST["usernameinput"]);
		} else {
			$usernameerror .= "Kasutajanimi on sisestamata! ";
		}
		
		if(empty($_POST["passwordinput"])){
			$passworderror .= "Salasõna on sisestamata! ";
		} else {
				if(strlen($_POST["passwordinput"]) < 8){
					$passworderror = "Liiga lühike salasõna (sisestasite ainult " .strlen($_POST["passwordinput"]) ." märki).";
				} 
		}		
		if(empty($_POST["passwordsecondaryinput"])){
			$passwordsecondaryerror .= "Salasõna on teist korda sisestamata! ";	
		} else {
				if ($_POST["passwordinput"] !== $_POST["passwordsecondaryinput"]){
					$passwordsecondaryerror .= "Sisestatud salasõnad ei klapi.";
				}
		}
		
		if(empty($firstnameerror) and empty($lastnameerror) and empty($gendererror) and empty($birthdayerror) and empty($birthmontherror)  and empty($birthyearerror) and empty($birthdateerror) and empty($usernameerror) and empty($passworderror) and empty($passwordsecondaryerror)){
			//saveuser($_POST["firstnameinput"], $_POST["lastnameinput"], $_POST["genderinput"], $_POST["usernameinput"],$_POST["passwordinput"], $_POST["passwordsecondaryinput"]);
			$notice = signup($firstname, $lastname, $email, $gender, $birthdate, $_POST["passwordinput"]);
			$notice = "Kõik korras!";
			echo $firstname ,$lastname ,$gender, $birthdate, $email, $_POST["passwordinput"];
			if ($result == "ok"){
				$firstname = "";
				$lastname = "";
				$gender = "";
				$email = "";
				$birthmonth = null;
				$birthday = null;
				$birthyear = null;
				$birthdate = null;
				$notice = "Kasutaja loomine õnnestus!";
			}
			else {
				$notice = "kahjuks tekkis tehniline viga ";
			}	
		}	
	}

require("header.php");
 ?>
<html> 
<img src="../tund3/img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
<h1><?php echo $username. " Kasutajakonto loomine"; ?></h1>

<hr>
<ul>
<li><a href="home.php">Avaleht</a></li>
<li><a href="insertidea.php">Pane oma mõtted kirja!</a></li>
<li><a href="readidea.php">Kirja pandud mõtted</a></li>
<li><a href="addfilms.php">Lisa filme.</a></li>
<li><a href="listfilms.php">Loe filmide infot.</a></li>
</ul>
<hr> 
<form method= "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="firstnameinput"> Eesnimi</label>
	<br>
	<input type="test" name="firstnameinput" id="firstnameinput" placeholder="Eesnimi" value="<?php echo $firstname; ?>"><span><?php echo $firstnameerror; ?></span>
	<br>
	<br>
	<label for="lastnameinput"> Perekonnanimi</label>
	<br>
	<input type="test" name="lastnameinput" id="lastnameinput" placeholder="Perekonnanimi" value="<?php echo $lastname; ?>" ><span><?php echo $lastnameerror; ?></span>
	<br>
	<br>
	<p>Sisesta sugu:</p>
	<input type="radio" name="genderinput" id="gendermaleinput" value="1"><label for="gendermale">Mees</label><?php if($gender == "1"){echo " checked";}?>
	<input type="radio" name="genderinput" id="genderfemaleinput" value="2"><label for="genderfemale">Naine</label><?php if($gender == "2"){echo " checked";}?><span><?php echo $gendererror; ?></span>
	<br>
	<br>
	
	
	  <label for="birthdayinput">Sünnipäev: </label>
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
	  <label for="birthmonthinput">Sünnikuu: </label>
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
	  <label for="birthyearinput">Sünniaasta: </label>
	  <?php
	    echo "\t" .'<select name="birthyearinput" id="birthyearinput">' ."\n";
		echo "\t \t" .'<option value="" selected disabled>aasta</option>' ."\n";
		for ($i = date("Y") - 15; $i >= date("Y") - 105; $i --){
			echo "\t \t" .'<option value="' .$i .'"';
			if ($i == $birthyear){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "\t </select> \n";
	  ?>
	  <br>
	  <br>
	  <span><?php echo $birthdateerror ." " .$birthdayerror ." " .$birthmontherror ." " .$birthyearerror; ?></span>
	<br>
	<br>
	<label for="usernameinput"> Kasutajanimi meiliaadressina</label>
	<br>
	<input type="email" name="usernameinput" id="usernameinput" value="<?php echo $email; ?>"><span><?php echo $usernameerror; ?></span>
	<br>
	<br>
	<label for="passwordinput">Sisesta salasõna</label>
	<br>
	<input type="password" name="passwordinput" id="passwordinput" placeholder="Password"><span><?php echo $passworderror; ?></span>
	<br>
	<br>
	<label for="passwordsecondaryinput"> Korda salasõna</label>
	<br>
	<input type="password" name="passwordsecondaryinput" id="passwordsecondaryinput" placeholder="Password"><span><?php echo $passwordsecondaryerror; ?></span>
	<br>
	<br>
	
	<input type="submit" name="submituserdata" value="Salvesta kasutajakonto"><?php echo "&nbsp; &nbsp; &nbsp;" .$notice; ?></span>

</form>
<br>



</body>
</html>
	