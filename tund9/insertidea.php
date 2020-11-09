<?php
  require("usesession.php");
//$username = "Ken Pikanõmme";
  require("../../../config.php");
  $database ="if20_ken_pi_1";
 //kui on idee sisestatud ja nuppu vajutatud, salvestame selle andmebaasi
  if(isset($_POST["ideasubmit"]) and !empty($_POST["ideainput"])){
	  $conn =new mysqli($serverhost, $serverusername, $serverpassword,$database);
	  //valmistan ette SQL käsu
	  $stmt = $conn->prepare("INSERT INTO myideas (idea) VALUES(?)");
	  echo $conn->error;
	  //seome käsuga päris andmed
	  //1 -integer, d - decimal, s- string
	  $stmt->bind_param("s", $_POST["ideainput"]);
	  $stmt->execute();
	  $stmt->close();
	  $conn->close();
  }
  
  require("header.php");
  ?>
<html>
<img src="../tund3/img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
<h1>Ideede sisestamine</h1> 
<hr>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label>Sisesta oma pähetulnud mõte!</label>
	<input type="text" name="ideainput" placeholder="Kirjuta siia mõte!">
	<input type="submit" name="ideasubmit" value="Saada mõte ära!">


 </form>
<hr>
<a href="readidea.php">Kirja pandud mõtted</a>
<a href="home.php">Tagasi</a>
</body>
 </html>