<?php
require("usesession.php");
//$username = "Ken Pikanõmme";
require("../../../config.php");
require("fnc_films.php");	
//$database ="if20_ken_pi_1";
//$filmhtml = readfilms ();  


require("header.php");
 ?> 

<h1>Filmide loend</h1>





<hr>
<ul>
<li><a href="home.php">Avaleht</a></li>
<li><a href="insertidea.php">Pane oma mõtted kirja!</a></li>
<li><a href="readidea.php">Kirja pandud mõtted</a></li>
<li><a href="addfilms.php">Lisa filme.</a></li>
</ul>
<hr> 
<?php echo readfilms (); ?>
</body>
</html>
	