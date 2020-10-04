<?php
$username = "Ken Pikanõmme";
  require("/home/kenpik/public_html/VP/tund3/config.php");
  $database ="if20_ken_pi_1";
  //loen lehele mõtted andmebaasist
   $conn =new mysqli($serverhost, $serverusername, $serverpassword, $database);
   $stmt = $conn->prepare("SELECT idea FROM myideas");
   echo $conn->error;
   //seome tulemuse muutujaga
   $stmt->bind_result($ideafromdb);
   $stmt->execute();
   $ideahtml = "";
   while($stmt->fetch()){
	   $ideahtml .="<p>" .$ideafromdb ."<p>";
   }
   $stmt->close();
   $conn->close(); 
   
 require("header.php");
 ?>
<html> 
<img src="../tund3/img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
<h1><?php echo $username. " ideed"; ?></h1>
    <?php echo $ideahtml; ?>
<hr>
<a href="home.php">Tagasi</a>

</html>
	