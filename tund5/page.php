<?php
	
	require("../../../config.php");
	require("fnc_common.php");
	require("fnc_user.php");
	$username = "Keegi";
	$emailerror = "";
	$passworderror = "";
	$email = "";
	$notice = "";
	//kui klikit submit siis ...
	if(isset($_POST["loginsubmit"])){
		if (!empty($_POST["emailinput"])){
			$email = filter_var(test_input($_POST["emailinput"]), FILTER_VALIDATE_EMAIL);
				//if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
					//$emailerror = "Pole meiliaadress!";
				//}
		} else {
			$emailerror = "Palun sisesta e-postiaadress!";
				
		}  
		if (empty($_POST["passwordinput"])){
			$passworderror = "Palun sisesta salasõna!";
		} else {
			if(strlen($_POST["passwordinput"]) < 8){
				$passworderror = "Liiga lühike salasõna (sisestasite ainult " .strlen($_POST["passwordinput"]) ." märki).";
			}
		}
	if(empty($emailerror) and empty($passworderror)){
		$result = signin($email, $_POST["passwordinput"]);
		//$notice = "Kõik korras!";
		if($result == "Ok"){
			$email = "";
			}	
		} else {
			$notice = "Tekkis tehniline tõrge: " .$result;
		}
	}
	//kell, kuu, semestriaeg
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

	<img src="../tund3/img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
	<h1>Tere tulemast!</h1>
	<p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
	<p> See veebileht on tehtud 2020 sügissemestril <a href="http://www.tlu.ee">TLÜ </a> Digitehnoloogiate instituudi veebiprogrammeerimise kursusel.</p>
	<p> Lehe avamise hetk <?php echo  $weekdaynameset[$weekdaynow -1].", ".$daynow.", ".$monthnameset[$monthnow -1].", ".$yearnow.", kell ".$timenow; ?>. </p>

	<p><?php echo "Praegu on " .$partofday ."."; ?></p>
	<p><?php echo "Semester on kestnud " .$semesterdaysdone ." päeva."; ?></p>
	<p><?php echo "Semestri pikkus on " .$semesterdurationdays ." päeva."; ?></p>
	<p><?php echo $semesterstatus ; ?></p>
	<hr>
	<br>
	<h2>Sisselogimine</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="usernameinput"> Meiliaadress</label>
		<br>
		<input type="email" name="emailinput" id="emailinput" value="<?php echo $email; ?>"><span><?php echo $emailerror; ?></span>
		<br>
		<br>
		<label for="passwordinput">Sisesta salasõna</label>
		<br>
		<input type="password" name="passwordinput" id="passwordinput" placeholder="Password"><span><?php echo $passworderror; ?></span>
		<br>
		<br>
		<input type="submit" name="loginsubmit" value="Logi sisse"><span><?php echo "&nbsp; &nbsp; &nbsp;" .$notice; ?></span>

	<hr>
	<?php echo $imghtml; ?>
	<hr>
	<ul>

	<li><a href="createuser.php">Loo kasutajakonto.</a></li>
	</ul>
	</body>
	 </html>