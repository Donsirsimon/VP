<?php
 $username = "Ken Pikanõmme";
 $fulltimenow = date("d.m.Y H:i.s");
 $hournow =date("H");
 $partofday = "lihtsalt aeg";
 if($hournow < 6){$partofday = "uneaeg";} //enne kuut lõpeb tavaline sulg ja jätkub loogeline sulg
 if($hournow >= 8 and $hournow <= 18){$partofday = "õppimise aeg";}
 $semesterstart = new DateTime ("2020-8-31");
 $semesterend = new DateTime ("2020-12-13");
 $semesterduration = $semesterstart->diff($semesterend);
 $semesterdurationdays = $semesterduration->format ("%r%a");
 $today = new DateTime ("now");
?>

<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <title><?php echo $username; ?> veebileht</title>

</head>
<body>
  <h1><?php echo $username; ?> </h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p> See veebileht on tehtud 2020 sügissemestri <a href="http://www.tlu.ee">TLÜ </a> Digitehnoloogiate instituudi veebiprogrammeerimise kursusel.</p>
<p> Lehe avamise hetk <?php echo $fulltimenow; ?>. </p>
<p><?php echo "Praegu on " .$partofday ."."; ?></p>
</body>
</html>