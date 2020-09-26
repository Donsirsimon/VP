<?php
$username = "Ken Pikanõmme";
require("/home/kenpik/public_html/VP/tund3/config.php");
require("fnc_films.php");	
//$database ="if20_ken_pi_1";
//$filmhtml = readfilms ();  
$inputerror = "";
$firstnameerror = "";
$lastnameerror = "";
$gendererror = "";
$usernameerror = "";
$passworderror = "";
$passwordsecondaryerror = "";
$passwordlengtherror = "";
$passwordcompareerror = "";
$firstname = "";
$lastname = "";
$gender = "";
$user = "";
//kui klikit submit siis ...
if(isset($_POST["usersubmit"])){
	if(empty($_POST["firstnameinput"])){
		$firstnameerror .= "Eesnimi on sisestamata! ";
	}	
	else {
		$firstname = $_POST["firstnameinput"];
	}
	if(empty($_POST["lastnameinput"])){
		$lastnameerror .= "Perekonnanimi on sisestamata! ";
	}
	else {
		$lastname = $_POST["lastnameinput"];
	}
	if(empty($_POST["genderinput"])){
		$gendererror .= "  Kasutaja sugu on sisestamata! ";
	}
	else {
		$gender = intval($_POST["genderinput"]);
	}
	if(empty($_POST["usernameinput"])){
		$usernameerror .= "Kasutajanimi on sisestamata! ";
	}
	else {
		$user = $_POST["usernameinput"];
	}
	if(empty($_POST["passwordinput"])){
		$passworderror .= "Salasõna on sisestamata! ";
	}	
	if(empty($_POST["passwordsecondaryinput"])){
		$passwordsecondaryerror .= "Salasõna on teist korda sisestamata! ";	
	}
	if(strlen($_POST["passwordinput"]) < 8 and isset($_POST["passwordinput"])){
		$passwordlengtherror .= "Salasõna on liiga lühike (min 8 kohta)! ";
	}
	
	if ($_POST["passwordinput"] !== $_POST["passwordsecondaryinput"]){
		$passwordcompareerror .= "Sisestatud salasõnad ei klapi.";
	}
	if(empty($firstnameerror) and empty($lastnameerror) and empty($gendererror) and empty($usernameerror) and empty($passworderror) and empty($passwordsecondaryerror) and empty($passwordcompareerror)){
		saveuser($_POST["firstnameinput"], $_POST["lastnameinput"], $_POST["genderinput"], $_POST["usernameinput"],$_POST["passwordinput"], $_POST["passwordsecondaryinput"]);
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

<form method= "POST">
	<label for="firstnameinput"> Eesnimi</label>
	<input type="test" name="firstnameinput" id="firstnameinput" placeholder="Eesnimi" value="<?php echo $firstname; ?>"><span><?php echo $firstnameerror; ?></span>
	<br>
	<label for="lastnameinput"> Perekonnanimi</label>
	<input type="test" name="lastnameinput" id="lastnameinput" placeholder="Perekonnanimi" value="<?php echo $lastname; ?>" ><span><?php echo $lastnameerror; ?></span>
	<br>
	<p>Sisesta sugu:</p>
	<input type="radio" name="genderinput" id="gendermale" value="1"><label for="gendermale">Mees</label><?php if($gender == "1"){echo " checked";}?>
	<input type="radio" name="genderinput" id="genderfemale" value="2"><label for="genderfemale">Naine</label><?php if($gender == "2"){echo " checked";}?><span><?php echo $gendererror; ?></span>
	<br>
	<label for="usernameinput"> Kasutajanimi meiliaadressina</label>
	<input type="test" name="usernameinput" id="usernameinput" placeholder="user@domain.com" value="<?php echo $user; ?>"><span><?php echo $usernameerror; ?></span>
	<br>
	<label for="passwordinput">Sisesta salasõna</label>
	<input type="password" name="passwordinput" id="passwordinput" placeholder="Password"><span><?php echo $passworderror, $passwordcompareerror, $passwordlengtherror ; ?></span>
	<br>
	<label for="passwordsecondaryinput"> Korda salasõna</label>
	<input type="password" name="passwordsecondaryinput" id="passwordsecondaryinput" placeholder="Password"><span><?php echo $passwordsecondaryerror ; ?></span>
	<br>
	
	
	<input type="submit" name="usersubmit" value="Salvesta kasutajakonto">

</form>
<br>



</body>
	</html>
	