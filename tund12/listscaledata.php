<?php
require("usesession.php");

require("../../../config.php");
require("fnc_scale.php");	

$sortby = 0;
$sortorder = 0;

require("header.php");
 ?> 

<h1>Viljavedu</h1>





<hr>
<ul>
<li><a href="home.php">Avaleht</a></li>

<li><a href="scale.php">Lisa viljaveo andmeid.</a></li>
</ul>
<hr> 
 <?php 
	if (isset( $_GET["sortby"]) and isset($_GET["sortorder"])){
			if ($_GET["sortby"] >= 1 and $_GET["sortby"] <= 4){
				$sortby = $_GET["sortby"];
			}
			if($_GET["sortorder"] == 1 or $_GET["sortorder"] == 2){
			$sortorder = $_GET["sortorder"];
			}	
	}
  
	echo readScaleData($sortby, $sortorder); 
	?>

</body>
</html>
	