<?php
  $username = "Ken Pikanõmme";
  $fulltimenow = date("d.m.Y H:i:s");
  $hournow = date("H");
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
 


?>
<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <title><?php echo $username; ?> programmeerib veebi</title>

</head>
<body>
  <h1><?php echo $username; ?></h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p> See veebileht on tehtud 2020 sügissemestril <a href="http://www.tlu.ee">TLÜ </a> Digitehnoloogiate instituudi veebiprogrammeerimise kursusel.</p>
<p> Lehe avamise hetk <?php echo $fulltimenow; ?>. </p>

<p><?php echo "Praegu on " .$partofday ."."; ?></p>
<p><?php echo "Semester on kestnud " .$semesterdaysdone ." päeva."; ?></p>
<p><?php echo "Semestri pikkus on " .$semesterdurationdays ." päeva."; ?></p>
<p><?php echo $semesterstatus ; ?></p>

</body>
</html>