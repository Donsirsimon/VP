<?php<?php
 
  
  session_start(); 
  
  //kontrollin kas on sisselogitud
  if(!isset($_SESSION["userid"])){
	  //jõugasuunatakse sisselogimise lehele
	  header("Location: page.php");
	  exit();
  }

  //logime välja
  if(isset($_GET["logout"])){
	  //lõpetame sessiooni
	  session_destroy();
	  //suuname minema
	  header("Location: page.php");
	  exit();	
  }	  