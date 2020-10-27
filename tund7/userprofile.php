<?php
require("usesession.php");
require("../../../config.php");
require("fnc_user.php");	
require("fnc_common.php");
//$database ="if20_ken_pi_1";
//$filmhtml = readfilms ();  
$notice = "";
$userdescription = readuserdescription();

//kui klikit submit siis ...
if(isset($_POST["profilesubmit"])){
	$userdescription = test_input($_POST["descriptioninput"]);
	
	$notice = storeuserprofile($userdescription, $_POST["bgcolorinput"], $_POST["txtcolorinput"]);
	$_SESSION["userbgcolor"] = $_POST["bgcolorinput"];
	$_SESSION["usertxtcolor"] = $_POST["txtcolorinput"];
	
}

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

 <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="descriptioninput">Lühikirjeldus</label>
	<br>
	<textarea rows="10" cols="80" name="descriptioninput" id="descriptioninput" placeholder="Minu lühikirjeldus ..."><?php echo $userdescription; ?></textarea>

	<br>
	<label for="bgcolorinput">Minu valitud taustavärv</label>
	<br>
	<input type="color" name="bgcolorinput" id="bgcolorinput" value="<?php echo $_SESSION["userbgcolor"]; ?>">
	<br>
	<label for="txtcolorinput"> Minu valitud tekstivärv</label>
	<br>
	<input type="color" name="txtcolorinput" id="txtcolorinput" value= "<?php echo $_SESSION["usertxtcolor"]; ?>"> 
	<br>
	
	<input type="submit" name="profilesubmit" value="Salvesta profiil">

</form>
<br>
<p> <?php echo $notice ?> </p>


</body>
	</html>
	