<?php
require("usesession.php");

require("../../../config.php");
require("fnc_scale.php");	
//$database ="if20_ken_pi_1";
//$filmhtml = readfilms ();  
$inputerror = "";
$result= "";
$notice= "";
$weightoutnotice = "";
$selectedrun = "";
$carselecthtml = readcarstoselect($selectedrun);
//kui klikit submit siis ...
if(isset($_POST["scaledatasubmit"])){
	if(empty($_POST["carinput"]) or empty($_POST["weightin_input"]) ){
		$inputerror .= "Osa infot on sisestamata! ";
	}
	
	if(empty($inputerror)){
		if($_POST["isgoingoutinput"]== 1){
			$result =saveScaleData($_POST["carinput"], $_POST["weightin_input"], $_POST["weightout_input"]);
			if($result == 1){
				$notice = "Andmed edukalt sisestatud andmebaasi!";
			}	
			if($result == 0){
				$notice = "Midagi läks valesti! Andmeid ei sisestatud andmebaasi!";
			}
		}	
		if($_POST["isgoingoutinput"] == 2){
			$result =saveScaleInData($_POST["carinput"], $_POST["weightin_input"]);
			if($result == 1){
				$notice = "Andmed edukalt sisestatud andmebaasi!";
			}	
			if($result == 0){
				$notice = "Midagi läks valesti! Andmeid ei sisestatud andmebaasi!";
			}
		}
	}	
		
}


if(isset($_POST["scaleoutsubmit"])){
	//$selectedfilm = $_POST["filminput"];
	if(!empty($_POST["carinput"])){
		$selectedrun = intval($_POST["carinput"]);
	} else {
		$weightoutnotice = " Vali auto!";
	}
	if(!empty($_POST["wout_input"])){
		$weightout = intval($_POST["wout_input"]);
	} else {
		$weightoutnotice .= "Sisesta kaal!";
	}
	if(!empty($selectedrun) and !empty($weightout)){
		$weightoutnotice .= storeWeightOut($selectedrun, $weightout);	
	}		
}


require("header.php");
 ?>
<html> 
<h1>Viljaveo sisestamine</h1>

<hr>
<ul>
<li><a href="home.php">Avaleht</a></li>
<li><a href="listscaledata.php">Loe Viljaveo infot.</a></li>
</ul>
<hr> 

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="carinput"> Sisesta auto number!</label>
	<br>
	<input type="test" name="carinput" id="carinput" placeholder="Autonumber">
	<br>
	<label for="weightin_input"> Auto kaal sisenemisel(kg):</label>
	<br>
	<input type="number" name="weightin_input" id="weightin_input" placeholder="Kaal sisse">
	
	
	<br>Sisestan kohe ka väljumise kaalu!</label>
	<input type="radio" name="isgoingoutinput" id="isgoingoutinput" value="1"><label for="isgoingoutinput">Jah</label>
	<input type="radio" name="isgoingoutinput" id="isgoingoutinput" value="2"><label for="isgoingoutinput">Ei</label>
	
	<br>
	<label for="weightout_input"> Auto kaal väljumisel(kg):</label>
	<br>
	<input type="number" name="weightout_input" id="weightout_input" placeholder="Kaal välja">
	
	<br>
	
	<input type="submit" name="scaledatasubmit" value="Salvesta info">

</form>
<br>
<p> <?php echo $inputerror ?> </p>
<p> <?php echo $notice ?> </p>
<hr>
<h2>Auto kaalu sisestamine väljumisel</h2>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <?php
		echo $carselecthtml;
	?>
	<br>
	<label for="wout_input"> Auto kaal väljumisel(kg):</label>
	<br>
	<input type="number" name="wout_input" id="wout_input" placeholder="Kaal välja">
	
	<br>
    <input type="submit" name="scaleoutsubmit" value="Salvesta auto kaal väljumisel">
</form>
<br>
<p><?php echo $weightoutnotice; ?> <//p>


</body>
	</html>
	