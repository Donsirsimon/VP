<?php
 
  require("usesession.php");
  
  
  
  //require("classes/first_class.php");
  //$myclassobject = new first();
  //echo "Salajane arv on " .$myclassobject.mybusiness;
  //echo "Salajane arv on " .$myclassobject.everybodysbusiness;
  //$myclassobject = tellME();
  //unset($myclassobject);
  // SIIN MINGI JAMA!!
  
  
  // Tegelen küpsistega - cookies
  //setcookie see funktsioon peab olema enne <html> elementi
  // küpsise nimi/ väärtus/ aegumisaeg/ failitee(domeenipiires)/ domeen/ HTTPS kasutamine(peaks olema True)/
  setcookie("vpvisitorname", $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"],time() + (86400 * 8), "/~kenpik/", "greeny.cs.tlu.ee",  isset($_SERVER["HTTPS"]),  true);
  $lastvisitor = null;
  if(isset($_COOKIE["vpvisitorname"])){
		$lastvisitor = "<p>Viimati külastas lehte: " .$_COOKIE["vpvisitorname"] .".</p> \n";
  }
  else{
		$lastvisitor = "<p>Viimast külastajat ei leitud! .</p> \n";
  }		
  //kustutamiseks tuleb sama küpsis kirjutada minevikus aegumistähtajaga näiteks time() - 3600
  
  $username = "Ken Pikanõmme";
  $fulltimenow = date("d.m.Y H:i:s");
  $timenow = date ("H:i:s");
  $hournow = date("H");
  $weekdaynameset =  ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
  $monthnameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
  $weekdaynow = date ("N");
  $monthnow = date ("m");
  $daynow = date ("d");
  $yearnow = date ("Y");
  //echo $weekdaynow;
  $partofday = "lihtsalt aeg";
  if($hournow <= 6){
		$partofday = "uneaeg";
		} //enne kuut lõpeb tavaline sulg ja jätkub loogeline sulg
  if($hournow == 7){
		$partofday = "ärkamise aeg";
		}
  if($hournow >= 8 and $hournow <= 11){
		$partofday = "õppimise aeg";
		}
  if($hournow == 12){
		$partofday = "lõuna aeg";
		}
  if($hournow >= 13 and $hournow <= 18){
		$partofday = "õppimise aeg";
		}
  if($hournow > 18 and $hournow < 22){
		$partofday = " vaba aeg";
		}
  if($hournow >= 22){
		$partofday = "uneaeg";
		}
// vaatame semestri kulgemist
 $semesterstart = new DateTime ("2020-8-31");
 $semesterend = new DateTime ("2020-12-13");
 $semesterduration = $semesterstart->diff($semesterend);
 $semesterdurationdays = $semesterduration->format ("%r%a");
 $today = new DateTime ("now");
 $semesterdone = $semesterstart->diff($today);
 $semesterdaysdone = $semesterdone->format ("%r%a");
 $semesterpercentage = ($semesterdaysdone / $semesterdurationdays) * 100;
 $semesterpercentageround = round($semesterpercentage,2);
 $semesterstatus = "Semestri kohta info puudulik";
 if($semesterdaysdone < 0){
 		$semesterstatus = "Semester pole veel alanud";
 			}
 if($semesterdaysdone > $semesterdurationdays){
		$semesterstatus = "Semester on lõppenud";
		}
 if($semesterdaysdone >= 0 and $semesterdaysdone < $semesterdurationdays){
		$semesterstatus = "Semester on kestnud " .$semesterdaysdone ." päeva. Semester täies hoos. Semestrist on läbitud " .$semesterpercentageround ."%";
		}
 //KOGU PILDI LAADIMISE TEEMA
 //annan ette lubatud pildivormingute loendi
 $picfiletypes = ["image/jpeg", "image/png", "image/jpg"];
//loeme piltide kataloogi sisu ja näitame pilte
 $allfiles = array_slice (scandir("../vp_pics/"), 2);
 $picfiles = [];
 
 foreach ($allfiles as $thing){
	 $fileinfo = getImagesize("../vp_pics/" .$thing);
	 if(in_array($fileinfo["mime"], $picfiletypes) == true){
		 array_push($picfiles, $thing);
	 }
 }

//paneme kõik pildid ekraanile
 $piccount = count($picfiles);
//$i = $i + 1
//$i ++
//$i += 2;
 //$imghtml = "";
//<img src="vp_pics/failinimi.jpg" alt="tekst"
 //for($i = 0; $i < $piccount; $i ++){
		//$imghtml .= '<img src= ../vp_pics/' .$picfiles[$i] .'" ';
		//$imghtml .= 'alt="Tallinna Ülikool">';
// }
 $randompic = rand(0, $piccount - 1);
  $imghtml = '<img src="../vp_pics/' . $picfiles[$randompic] . '" alt="Tallinna Ülikool">';
  
 
 
 require("header.php");
 
?>

<h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
<p><a href="?logout=1">Logi välja!</a></p>
<p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
<p> See veebileht on tehtud 2020 sügissemestril <a href="http://www.tlu.ee">TLÜ </a> Digitehnoloogiate instituudi veebiprogrammeerimise kursusel.</p>
<p> Lehe avamise hetk <?php echo  $weekdaynameset[$weekdaynow -1].", ".$daynow.", ".$monthnameset[$monthnow -1].", ".$yearnow.", kell ".$timenow; ?>. </p>

<p><?php echo "Praegu on " .$partofday ."."; ?></p>
<p><?php echo "Semester on kestnud " .$semesterdaysdone ." päeva."; ?></p>
<p><?php echo "Semestri pikkus on " .$semesterdurationdays ." päeva."; ?></p>
<p><?php echo $semesterstatus ; ?></p>
<hr>
<?php echo $imghtml; ?>
<hr>
<ul>
<li><a href="insertidea.php">Pane oma mõtted kirja!</a></li>
<li><a href="readidea.php">Kirja pandud mõtted</a></li>
<li><a href="addfilms.php">Lisa filme.</a></li>
<li><a href="listfilms.php">Loe filmide infot.</a></li>
<li><a href="listfilmpersons.php">Inimesed filmides.</a></li>
<li><a href="listfilmpersonquote.php">Inimesed, filmid ja tsitaadid.</a></li>
<li><a href="changefilm.php">Muuda filmide seoseid andmebaasis.</a></li>
<li><a href="createuser.php">Loo kasutajakonto.</a></li>
<li><a href="userprofile.php">Minu kasutajaprofiil</a></li>
<li><a href="photoupload.php">Galeriipiltide üleslaadimine</a></li>
<li><a href="photogallery_public.php">Avalike piltide galerii</a></li>
<li><a href="photogallery_private.php">Privaatsete piltide galerii</a></li>
<li><a href="photogallery_members.php">Liikmete piltide galerii</a></li>
<li><a href="addnews.php">Uudiste lisamine</a></li>
<li><a href="readnews.php">Kõik uudised</a></li>
<li><a href="scale.php">Viljaveo kaalumine</a></li>
</ul>
<hr>
	<h3> Viimane külastaja sellest arvutist</h3>
	<?php
		if(count($_COOKIE) > 0){
			echo ("<p>Küpsised on lubatud! Leiti: " .count($_COOKIE) ."küpsist.</p> \n");
		}	
		echo $lastvisitor;
	?>	
</body>
 </html>