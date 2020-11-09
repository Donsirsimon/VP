<?php
require("usesession.php");
require("../../../config.php");
//var_dump($_POST);	
//var_dump($_FILES);
$notice = null;
$thumbnotice = null;
$inputerror = "";
$filetype ="";
$filesizelimit = 1048576;
$photouploaddir_orig = "../photouploadorig/";
$photouploaddir_normal = "../photouploadnormal/";
$photouploaddir_thumb = "../photouploadthumbnail/";
$filename =null;
$filenameprefix = "vp_";
$photomaxwidth = 600;
$photomaxheight = 400;
$thumbmaxres = 100;


//kui klikit submit siis ...
if(isset($_POST["photosubmit"])){
	$check = getimagesize($_FILES["photoinput"]["tmp_name"]);
	if($check !== false){
		//kas on pilt
		if($check ["mime"] == "image/jpeg"){
			$filetype ="jpg";
		}
		if($check ["mime"] == "image/png"){
			$filetype ="png";
		}
		if($check ["mime"] == "image/gif"){
			$filetype ="gif";
		}
	}
	else{
		$inputerror = "Valitud fail pole pilt!";
	}
		
	//kas on sobiva suurusega fail
	if(empty($inputerror) and $_FILES["photoinput"]["size"] > $filesizelimit){
		$inputerror = "Liiga suur pilt!";
	}	
	//loome uue failinime
	$timestamp = microtime(1) * 10000;
	$filename = $filenameprefix .$timestamp ."." .$filetype;	
	//kas juba fail olemas
	if(file_exists($photouploaddir_orig .$filename)) {
		$inputerror = "Fail juba olemas!";
	}	
	//kui vigu pole
	if(empty($inputerror)){
		$target = $photouploaddir_normal .$filename;
		$targetthumb = $photouploaddir_thumb .$filename; 
		//Muudame suurust
		//loome pikslikogumi, pildi objekti
		if($filetype == "jpg"){
			$mytempimage = imagecreatefromjpeg($_FILES["photoinput"]["tmp_name"]);
		}
		if($filetype == "png"){
			$mytempimage = imagecreatefrompng($_FILES["photoinput"]["tmp_name"]);
		}
		if($filetype == "gif"){
			$mytempimage = imagecreatefromgif($_FILES["photoinput"]["tmp_name"]);
		}
		//Teemekindlaks originaalsuuruse
		$imagew = imagesx($mytempimage);
		$imageh = imagesy($mytempimage);
		
		if($imagew > $photomaxwidth or $imageh > $photomaxheight){
			if($imagew / $photomaxwidth > $imageh / $photomaxheight) {
				$photosizeratio = $imagew / $photomaxwidth;
			}
			else{
				$photosizeratio = $imageh / $photomaxheight;		
			}	
			$neww = round($imagew / $photosizeratio);
			$newh = round($imageh / $photosizeratio);
			//teeme uue pikslikogumi
			$mynewtempimage = imagecreatetruecolor($neww, $newh);
			//Kirjutae järelejäävad pikslid uuele pildile
			imagecopyresampled($mynewtempimage, $mytempimage, 0, 0, 0, 0, $neww, $newh, $imagew, $imageh);
			//salvestame faili
			$notice = saveimage($mynewtempimage, $filetype, $target);
			imagedestroy($mynewtempimage);
		} else {
			//kui pole suurust vaja muuta
			$notice = saveimage($mytempimage, $filetype, $target);
		}
		//THUMBNAIL
		
		if($imagew > $thumbmaxres or $imageh > $thumbmaxres){
			if ($imageh >$imagew){
				$thumbratio = $thumbmaxres / $imagew;
				$newthumbw = $thumbmaxres;
				$newthumbh = $imageh * $thumbratio;
				$diff = $newthumbh - $newthumbw;
				$xaxis = 0;
				$yaxis = $diff /2;
			}
			else{
				$thumbratio = $thumbmaxres / $imageh;
				$newthumbh = $thumbmaxres;
				$newthumbw = $imagew * $thumbratio;
				$diff = $newthumbw - $newthumbh; 
				$xaxis = $diff / 2;
				$yaxis = 0;
			}
			$mynewtempthumbnail = imagecreatetruecolor($newthumbw, $newthumbh);
			//Kirjutame järelejäävad pikslid uuele pildile
			imagecopyresampled($mynewtempthumbnail, $mytempimage, 0, 0, 0, 0, $newthumbw, $newthumbh, $imagew, $imageh);
			//Lõikame pildi mõõtu
			$mynewtempthumbnailcropped = imagecreatetruecolor($thumbmaxres, $thumbmaxres);
			//Kirjutae järelejäävad pikslid uuele pildile
			imagecopyresampled($mynewtempthumbnailcropped, $mynewtempthumbnail, 0, 0, $xaxis, $yaxis, $thumbmaxres, $thumbmaxres, $thumbmaxres, $thumbmaxres);
			//salvestame faili
			$thumbnotice = savethumbnail($mynewtempthumbnailcropped, $filetype, $targetthumb);
			imagedestroy($mynewtempthumbnail);
			imagedestroy($mynewtempthumbnailcropped);
		} else {
			//kui pole suurust vaja muuta
			$thumbnotice = savethumbnail($mytempimage, $filetype, $targetthumb);
		}
		
		
		imagedestroy($mytempimage);
		
		if(move_uploaded_file($_FILES["photoinput"]["tmp_name"], $photouploaddir_orig .$filename)){
			$notice .= " Originaalpildi salvestamine õnnestus!";
		}
		else{
			$notice .= " Originaalpildi salvestamisel tekkis tõrge!";
		}
	}
}
function saveimage($mynewtempimage, $filetype, $target){
	$notice = null;
	//salvestame faili
	if($filetype == "jpg"){
		if(imagejpeg($mynewtempimage, $target, 90)){
			$notice = "Vähendatud pildi salvestamine õnnestus!";
		}
		else{
			$notice = "Vähendatud pildi salvestamisel tekkis tõrge!";
		}
	}	
	if($filetype == "png"){
		if(imagepng($mynewtempimage, $target, 6)){
			$notice = "Vähendatud pildi salvestamine õnnestus!";
		}
		else{
			$notice = "Vähendatud pildi salvestamisel tekkis tõrge!";
		}
	}	
	if($filetype == "gif"){
		if(imagegif($mynewtempimage, $target)){
			$notice = "Vähendatud pildi salvestamine õnnestus!";
		}
		else{
			$notice = "Vähendatud pildi salvestamisel tekkis tõrge!";
		}
	}
	//imagedestroy($mynewtempimage);	
	return $notice;
}	
function savethumbnail($mynewtempthumbnailcropped, $filetype, $targetthumb){
	$thumbnotice = null;
	//salvestame faili
	if($filetype == "jpg"){
		if(imagejpeg($mynewtempthumbnailcropped, $targetthumb, 90)){
			$thumbnotice = "Pildi thumbnaili salvestamine õnnestus!";
		}
		else{
			$thumbnotice = "Pildi thumbnaili salvestamisel tekkis tõrge!";
		}
	}	
	if($filetype == "png"){
		if(imagepng($mynewtempthumbnailcropped, $targetthumb, 6)){
			$thumbnotice = "Pildi thumbnaili salvestamine õnnestus!";
		}
		else{
			$thumbnotice = "Vähendatud pildi salvestamisel tekkis tõrge!";
		}
	}	
	if($filetype == "gif"){
		if(imagegif($mynewtempthumbnailcropped, $targetthumb)){
			$thumbnotice = "Pildi thumbnaili salvestamine õnnestus!";
		}
		else{
			$thumbnotice = "Pildi thumbnaili salvestamisel tekkis tõrge!";
		}
	}
	return $thumbnotice;
}	
require("header.php");
 ?>
<html> 
<img src="../tund3/img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
<h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
<hr>
<ul>
<li><a href="home.php">Avaleht</a></li>
<li><a href="insertidea.php">Pane oma mõtted kirja!</a></li>
<li><a href="readidea.php">Kirja pandud mõtted</a></li>
<li><a href="listfilms.php">Loe filmide infot.</a></li>
</ul>
<hr> 

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
	<label for ="photoinput">Vali pildifail!</label><br>
		<input id="photoinput" name="photoinput" type="file" required>
		<br>
		<label for ="altinput">Lisa pildi lühikirjeldus (alternatiivtekst).</label><br>
		<input id="altinput" name="altinput" type="text" ><br>
		<label>Privaatsustase</label>
		<br>
		<input id="privinput1" name="privinput" type="radio" value="1">
		<label for ="privinput1">Privaatne(ainult mina näen)</label>
		<br>
		<input id="privinput2" name="privinput" type="radio" value="2">
		<label for ="privinput2">Klubi liikmetele (sisseloginud kasutajad näevad)</label>
		<br>
		<input id="privinput3" name="privinput" type="radio" value="3">
		<label for ="privinput3">Avalik(kõik näevad)</label>
		<br>
		<input type="submit" name="photosubmit" value="Lae foto üles">
		
</form>
<br>
<p> <?php echo $inputerror ?> </p>
<p> <?php echo $notice?> </p>
<p> <?php echo $thumbnotice?> </p>

</body>
	</html>
	