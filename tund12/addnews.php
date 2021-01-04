<?php
  require("usesession.php");

  require("../../../config.php");
  require("fnc_photo.php");
  require("fnc_common.php");
  require("fnc_news.php");
  require("classes/photouploadclass.php");
  
  $tolink = "\t" .'<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>' ."\n";
  $tolink .= "\t" .'<script>tinymce.init({selector:"textarea#newsinput", plugins: "link", menubar: "edit",});</script>' ."\n";

  $monthnameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
  $expdate = null;
  $expire = new DateTime("now");
  $expire->add(new DateInterval("P7D"));
  $expday = null;
  $expmonth = null;
  $expyear = null;
  $expdate = null;
  $photoinputerror = "";
  $inputerror = "";
  $notice = null;
  $news = "";
  $newstitle = "";
  
  
  $filetype = null;
  $filesizelimit = 2097152; //1048576;
  $photouploaddir_news = "../photouploadnews/";
  $watermark = "../img/vp_logo_w100_overlay.png";
  $filenameprefix = "vpnews_";
  $filename = null;
  $photomaxwidth = 600;
  $photomaxheight = 400;

 
  $alttext = null;
    
  //kui klikiti submit, siis ...
  if(isset($_POST["newssubmit"])){
	if(strlen($_POST["newstitleinput"]) == 0){
		$inputerror = "Uudise pealkiri on puudu!";
	}else{
		$newstitle = test_input($_POST["newstitleinput"]);
	}	
	if(strlen($_POST["newsinput"]) == 0){
		$inputerror .= " Uudise sisu on puudu!";
	}else{
		$news = test_input($_POST["newsinput"]);
		// htmlspecialchars teisendab html noolsulud.
		// nende tagasisaamiseks htmlspecialshars_decode(uudis)
	}	
	if(!empty($_POST["expdayinput"])){
		$expday = intval($_POST["expdayinput"]);
	} 

	if(!empty($_POST["expmonthinput"])){
		$expmonth = intval($_POST["expmonthinput"]);
	}
	  

	if(!empty($_POST["expyearinput"])){
		$expyear = intval($_POST["expyearinput"]);
	} 
	

	//kontrollime kuupäeva kehtivust (valiidsust)
	if(!empty($expday) and !empty($expmonth) and !empty($expyear)){
	  if(checkdate($expmonth, $expday, $expyear)){
		  $tempdate = new DateTime($expyear ."-" .$expmonth ."-" .$expday);
		  $expdate = $tempdate->format("Y-m-d");
	  } else {
		  $inputerror .= " Kuupäev ei ole reaalne!";
	  }
	}	
	
	
	
	
	//PILDI SISESTUS
	if(!empty($_FILES["photoinput"]["name"])){
		//echo $_FILES["photoinput"]["name"];
		$alttext = test_input($_POST["altinput"]);
		$check = getimagesize($_FILES["photoinput"]["tmp_name"]);
		if($check !== false){
			//var_dump($check);
			if($check["mime"] == "image/jpeg"){
				$filetype = "jpg";
			}
			if($check["mime"] == "image/png"){
				$filetype = "png";
			}
			if($check["mime"] == "image/gif"){
				$filetype = "gif";
			}
		} else {
			$photoinputerror = "Valitud fail ei ole pilt! ";
		}
	
		//kas on sobiva failisuurusega
		if(empty($inputerror) and $_FILES["photoinput"]["size"] > $filesizelimit){
			$photoinputerror .= "Liiga suur fail!";
		}
	
		//loome uue failinime
		$timestamp = microtime(1) * 10000;
		$filename = $filenameprefix .$timestamp ."." .$filetype;
		
		//salvestame foto
		$myphoto = new Photoupload($_FILES["photoinput"], $filetype);
		//teeme pildi väiksemaks
		$myphoto->resizePhoto($photomaxwidth, $photomaxheight, true);
		//lisame vesimärgi
		$myphoto->addWatermark($watermark);
		//salvestame vähendatud pildi
		$result = $myphoto->saveimage($photouploaddir_news .$filename);
		if($result == 1){
			$notice .= " Vähendatud pildi salvestamine õnnestus!";
		} else {
			$photoinputerror .= " Vähendatud pildi salvestamisel tekkis tõrge!";
		}
		unset($myphoto);
	
		
	}	
	if(empty($inputerror) and empty($photoinputerror)){
		//uudis salvestada
		//echo $news;
		$result = saveNews($newstitle, $news, $expdate, $filename, $alttext);
		if($result == 1){
			$notice = "Uudis salvestatud!";
			$error = "";
			$newstitle = "";
			$news = "";
			//$expiredate = date("Y-m-d");
			$expdate = null;
		}
	}
  }	

  require("header.php");
?>
  <h1>Uudise lisamine</h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See konkreetne leht on loodud veebiprogrammeerimise kursusel aasta 2020 sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  
  <ul>
    <li><a href="?logout=1">Logi välja</a>!</li>
    <li><a href="home.php">Avaleht</a></li>
  </ul>
  
  <hr>
  
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
    <label for="newstitleinput">Sisesta uudise pealkiri!</label>
	<br>
	<input id="newstitleinput" name="newstitleinput" type="text" value="<?php echo $newstitle; ?>" required>
	<br>
	<label for="newsinput">Kirjuta uudis</label>
	<textarea id="newsinput" name="newsinput"><?php echo $news; ?></textarea>
	<br>
	<label for="photoinput">Vali pildifail!</label>
	<input id="photoinput" name="photoinput" type="file" required>
	<br>
	<br>
    <label for="altinput">Lisa pildi lühikirjeldus (alternatiivtekst)</label>
	<br>
    <input id="altinput" name="altinput" type="text" value="<?php echo $alttext; ?>">
    <br>
	<h3>Aegumise kuupäev</h3>
	<br>
	<label for="expdayinput">Päev: </label><br>
	<?php
		echo '<select name="expdayinput" id="expdayinput">' ."\n";
		echo "\t \t" .'<option value="" selected disabled>päev</option>' ."\n";
		for ($i = 1; $i < 32; $i ++){
			echo "\t \t" .'<option value="' .$i .'"';
			if ($i == $expday){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "\t </select> \n";
	?>
	<br>
	<label for="expmonthinput">Kuu: </label><br>
	<?php
		echo "\t" .'<select name="expmonthinput" id="expmonthinput">' ."\n";
		echo "\t \t" .'<option value="" selected disabled>kuu</option>' ."\n";
		for ($i = 1; $i < 13; $i ++){
			echo "\t \t" .'<option value="' .$i .'"';
			if ($i == $expmonth){
				echo " selected ";
			}
			echo ">" .$monthnameset[$i - 1] ."</option> \n";
		}
		echo "\t </select> \n";
	?>
	<br>
	<label for="expyearinput">Aasta: </label><br>
	<?php
		echo "\t" .'<select name="expyearinput" id="expyearinput">' ."\n";
		echo "\t \t" .'<option value="" selected disabled>aasta</option>' ."\n";
		for ($i = date("Y"); $i <= date("Y") + 10; $i ++){
			echo "\t \t" .'<option value="' .$i .'"';
			if ($i == $expyear){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "\t </select> \n";
	?>	
	<br>	
	<input type="submit" id="newssubmit" name="newssubmit" value="Salvesta uudis">
  </form>
  <p id="notice">
  <?php
	echo $inputerror;
	echo $notice;
  ?>
  </p>
  
</body>
</html>




