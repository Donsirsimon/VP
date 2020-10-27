<?php

require("usesession.php");
require("../../../config.php");	
require("fnc_movie_ab.php");
require("fnc_common.php");
//$database ="if20_ken_pi_1";
//$filmhtml = readfilms ();  
$notice = "";

readmovies();
readgenres();
//kui klikit submit siis ...
if(isset($_POST["filmgenresubmit"])){
	$movieinput = $_POST["filminput"];
	$genreinput = $_POST["genreinput"];
	if(!empty($movieinput) and !empty($genreinput)){
		$notice = storemoviegenre($movieinput, $genreinput);
	
	}
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
	<label for="filminput">Film: </label>
	<br>
	<select name="filminput" id="filminput">
		<option value="" selected disabled>Vali film</option>
		<?php echo readmovies();?>
	</select>
	<br>
	<label for="genreinput">Žanr: </label>
	<br>
	<select name="genreinput" id="genreinput">
		<option value="" selected disabled>Vali žanr</option>
		<?php echo readgenres();?>
	</select>
	<br>
	<br>
	<input type="submit" name="filmgenresubmit" value="Salvesta filmižanr">

</form>
<br>
<p> <?php echo $notice ?> </p>


</body>
	</html>
	