<?php<?php
 
  
  //session_start(); 
  require("classes/SessionManager.class.php");
  SessionManager::sessionStart("vp", 0, "/~kenpik/", "greeny.cs.tlu.ee");
  
  //kontrollin kas on sisselogitud
  if(!isset($_SESSION["userid"])){
	  //jõuga suunatakse sisselogimise lehele
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