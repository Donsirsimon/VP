<?php
$username = "Ken Pikanõmme";
require("/home/kenpik/public_html/VP/tund3/config.php");
require("fnc_films.php");	
//$database ="if20_ken_pi_1";
//$filmhtml = readfilms ();  
$inputerror = "";
//kui klikit submit siis ...
if(isset($_POST["filmsubmit"])){
	if(empty($_POST["titleinput"]) or empty($_POST["gerneinput"]) or empty($_POST["studioinput"]) or empty($_POST["directorinput"])){
		$inputerror .= "Osa infot on sisestamata! ";
	}
	if ($_POST["yearinput"] > date ("Y") or $_POST["yearinput"] < 1895){
		$inputerror .= "Ebareaalne valmimisaasta! ";
	}
	if(empty($inputerror)){
		savefilm($_POST["titleinput"],$_POST["yearinput"], $_POST["durationinput"], $_POST["gerneinput"], $_POST["studioinput"], $_POST["directorinput"]);
	}	
		
}

require("header.php");
 ?>
<html> 
<img src="../tund3/img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
<h1><?php echo $username. " filmi loend"; ?></h1>

<hr>
<ul>
<li><a href="home.php">Avaleht</a></li>
<li><a href="insertidea.php">Pane oma mõtted kirja!</a></li>
<li><a href="readidea.php">Kirja pandud mõtted</a></li>
<li><a href="listfilms.php">Loe filmide infot.</a></li>
</ul>
<hr> 

<form method= "POST">
	<label for="titleinput"> Filmi pealkiri</label>
	<input type="test" name="titleinput" id="titleinput" placeholder="Pealkiri">
	<br>
	<label for="yearinput"> Filmi valmimisaasta</label>
	<input type="number" name="yearinput" id="yearinput" value="<?php echo date ("Y"); ?>">
	<br>
	<label for="durationinput"> Filmi kestus minutites</label>
	<input type="number" name="durationinput" id="durationinput" value="80"; ?>">
	<br>
	<label for="gerneinput"> Filmi pealkiri</label>
	<input type="test" name="gerneinput" id="gerneinput" placeholder="Zanr">
	<br>
	<label for="studioinput"> Filmi tootja / stuudio</label>
	<input type="test" name="studioinput" id="studioinput" placeholder="Stuudio">
	<br>
	<label for="directorinput"> Filmi lavastaja </label>
	<input type="test" name="directorinput" id="directorinput" placeholder="Lavastaja">
	
	<input type="submit" name="filmsubmit" value="Salvesta filmi info">

</form>
<br>
<p> <?php echo $inputerror ?> </p>


</body>
	</html>
	