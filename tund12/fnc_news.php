<?php
	$database ="if20_ken_pi_1";
	$photouploaddir_news = "../photouploadnews/";
	
	function saveNews($newsTitle, $news, $expiredate, $filename, $alttext){
		$response = 0;
		$photoid = null;
		//kõigepealt foto!
		//echo "SALVESTATAKSE UUDIST!";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vpnewsphotos (userid, filename, alttext) VALUES(?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("iss", $_SESSION["userid"], $filename, $alttext);
		if($stmt->execute()){
			$photoid = $conn->insert_id;
		}
		$stmt->close();
		
		//nüüd uudis ise
		$stmt = $conn->prepare("INSERT INTO vpnews (userid, title, content, photoid, expire) VALUES (?, ?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("issis", $_SESSION["userid"], $newsTitle, $news, $photoid, $expiredate);
		if($stmt->execute()){
			$response = 1;
		} else {
			$response = 0;
		}
		$stmt->close();
		$conn->close();
		return $response;
	
	}
	function readNewsData($id) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT firstname, lastname, title, content, vpnewsphotos_id, alttext, expire, vpnews.added, modified FROM vpnews JOIN vpusers ON vpnews.userid = vpusers.vpusers_id LEFT JOIN vpnewsphotos ON vpnews.photoid = vpnewsphotos.vpnewsphotos_id WHERE vpnews.vpnews_id = ? AND vpnews.deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("i", $id);
		$stmt->bind_result($firstnamefromdb, $lastnamefromdb, $titlefromdb, $contentfromdb, $photoidfromdb, $alttextfromdb, $expirefromdb, $addedfromdb, $modifiedfromdb);
		if($stmt->execute()) {
			$datafromdb = null;
			if ($stmt->fetch()) {
				$datafromdb = array("firstname"=>$firstnamefromdb, "lastname"=>$lastnamefromdb, "title"=>$titlefromdb, "content"=>$contentfromdb, "photoid"=>$photoidfromdb, "alttext"=>$alttextfromdb, "expire"=>$expirefromdb, "added"=>$addedfromdb, "modified"=>$modifiedfromdb);
			}
			if(!empty($datafromdb)) {
				$return = $datafromdb;
			}
			else {
				$return = "<p>Kahjuks uudiseid ei leitud!</p>";
			}
		}
		else {
			$return = "<p>Kahjuks tekkis tehniline tõrge</p>";
		}

		$stmt->close();
		$conn->close();
		return $return;
	}	


	
	function readSingleNews($newsid){
		
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		//$stmt = $conn->prepare("SELECT title, content, added FROM vpnews WHERE expire >=? AND deleted IS NULL ORDER BY id DESC LIMIT ?");
		$stmt = $conn->prepare("SELECT title, content, expire FROM vpnews WHERE vpnews_id = ?");
		echo $conn->error;
		$stmt->bind_param("i", $newsid);
		//$stmt->bind_param("i", $limit);
		$stmt->bind_result($titlefromdb, $contentfromdb, $expirefromdb);
		if($stmt->execute()){
			if($stmt->fetch()) {
				$datafromdb = array("title"=>$titlefromdb, "content"=>$contentfromdb, "expire"=>$expirefromdb);
			}
			if(!empty($datafromdb)) {
				$response = $datafromdb;
			
			}else {
				$response = "<p>Uudiseid ei leitud!</p>";
			}
		}
		else {
			$response = "<p>Tehniline tõrge</p>";
		}
		$stmt->close();
		$conn->close();
		return $response;	
		
		
	}	
	function modifyNews($newstitle, $news, $expiredate, $newsid){
		$response = 0;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("UPDATE vpnews SET title = ?, content = ?, expire = ? WHERE vpnews_id = ?");
		$stmt->bind_param("sssi", $newstitle, $news, $expiredate, $newsid);
		if($stmt->execute()){
			$response = 1;
		} else {
			$response = 0;
		}
		$stmt->close();
		$conn->close();
		return $response;
	
	}
	
	
	function deleteNews($newsid){
		$response = 0;
		
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		//nüüd uudis ise  "UPDATE vpnews SET title = ?, content = ?, expire = ? WHERE userid = ?"
		//$stmt = $conn->prepare("INSERT INTO vpnews (userid, title, content, photoid, expire) VALUES (?, ?, ?, ?, ?)");
		$stmt = $conn->prepare("UPDATE vpnews SET deleted = NOW() WHERE vpnews_id = ?");
		$stmt->bind_param("i", $newsid);
		if($stmt->execute()){
			$response = 1;
		} else {
			$response = 0;
		}
		$stmt->close();
		$conn->close();
		return $response;
	
	}



	function storeNewsData($newstitle, $news, $filename, $expdate, $userid){
		$notice = null;
		$photoid = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		if ($filename != null) {
			$stmt = $conn->prepare("SELECT vpnewsphotos_id FROM vpnewsphotos WHERE filename = ? AND deleted IS NULL");
			$stmt->bind_param("s", $filename);
			$stmt->bind_result($idfromdb);
			$stmt->execute();
			if($stmt->fetch()) {
				$photoid = $idfromdb;
			}
			$stmt->close();
		}
		$stmt = $conn->prepare("INSERT INTO vpnews (userid, title, content, expire, photoid) VALUES (?, ?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("isssi", $userid, $newstitle, $news, $expdate, $photoid);
		if($stmt->execute()){
			$notice = 1;
		} else {
			$notice = 0;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	
	
	function readNews($limit = 5){
		$newshtml = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT title, content, vpnewsphoto_id, alttext, firstname, lastname, vpnews.added FROM vpnews JOIN vpusers ON vpnews.userid = vpusers.vpusers_id LEFT JOIN vpnewsphotos ON vpnews.photoid = vpnewsphotos.vpnewsphotos_id WHERE expire >= CURDATE() AND vpnews.deleted IS NULL ORDER BY vpnews_id DESC LIMIT ?");
				
		$stmt->bind_param("i", $limit);
		$stmt->bind_result($titlefromdb, $contentfromdb, $photoidfromdb, $alttextfromdb, $firstnamefromdb, $lastnamefromdb, $datefromdb);
		$stmt->execute();
		$temphtml = null;
		while($stmt->fetch()){
			// <h2> .$titlefromdb </h2>
			// '<img src="' .$GLOBALS["photouploaddir_normal"] .$filenamefromdb .'" alt="' .$alttextfromdb .'">' ."\n";
			// <p> .$contentfromdb </p>
			// <p> Autor .............
			// <p> kuupäev.....
			
			$temphtml .= "<h2>" .$titlefromdb ."</h2>\n";
			if($photoidfromdb != null){
				$temphtml .= '<img src="' ."shownewsphoto.php?photo=" .$photoidfromdb .'" alt="' .$alttextfromdb .'">' ."\n";
			}	
			$temphtml .= "<p>" .htmlspecialchars_decode($contentfromdb) ."<p> \n";
			$temphtml .= "<p> Autor: " .$firstnamefromdn ." " .$lastnamefromdb ."<p> \n";
			$temphtml .= "<p> Kuupäev: " .$datefromdb ."<p> \n";
			$temphtml .= "<hr>\n";
			
		}	
		$newshtml = "<div> \n" .$temphtml ."\n</div>\n";
		$stmt->close();
		$conn->close(); 
		return $newshtml;
	}	
	
	
	function latestNews($limit){
		$newshtml = null;
		$today = date("Y-m-d");
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		//$stmt = $conn->prepare("SELECT title, content, added FROM vpnews WHERE expire >=? AND deleted IS NULL ORDER BY id DESC LIMIT ?");
		//SELECT title, content, vpnews.added, filename, alttext FROM vpnews LEFT JOIN vpnewsphotos on vpnewsphotos.vpnewsphotos_id = vpnews.photoid GROUP BY vpnews.vpnews_id
		//$stmt = $conn->prepare("SELECT title, content, added FROM vpnews WHERE expire >=? AND deleted IS NULL ORDER BY vpnews_id DESC LIMIT ?");
		$stmt = $conn->prepare("SELECT title, content, vpnews.added, filename, alttext FROM vpnews LEFT JOIN vpnewsphotos on vpnewsphotos.vpnewsphotos_id = vpnews.photoid WHERE vpnews.expire >= ? AND vpnews.deleted IS NULL GROUP BY vpnews.vpnews_id ORDER By vpnews_id DESC LIMIT ?");
		echo $conn->error;
		$stmt->bind_param("si", $today, $limit);
		//$stmt->bind_param("i", $limit);
		$stmt->bind_result($titlefromdb, $contentfromdb, $addedFromDb, $filenamefromdb, $alttextfromdb);
		$stmt->execute();
		while ($stmt->fetch()){
			$newshtml .= '<div class="newsblock';
			if(!empty($filenamefromdb)){
				$newshtml .=" fullheightnews";
			}
			$newshtml .= '">' ."\n";
			if(!empty($filenamefromdb)){
				$newshtml .= "\t" .'<img src="' .$GLOBALS["photouploaddir_news"].$filenamefromdb .'" ';
				if(!empty($alttextfromdb)){
					$newshtml .= 'alt="' .$alttextfromdb .'"';
				} else {
					$newshtml .= 'alt="' .$titlefromdb .'"';
				}
				$newshtml .= "> \n";
			}
			
			$newshtml .= "\t <h3>" .$titlefromdb ."</h3> \n";
			$addedtime = new DateTime($addedFromDb);
			$newshtml .= "\t <p>(Lisatud: " .$addedtime->format("d.m.Y H:i:s") .")</p> \n";
			
			$newshtml .= "\t <div>" .htmlspecialchars_decode($contentfromdb) ."</div> \n";
			$newshtml .= "</div> \n";
		}
		if($newshtml == null){
			$newshtml = "<p>Kahjuks uudiseid pole!</p>";
		}
		$stmt->close();
		$conn->close();
		return $newshtml;
	}
	
	function originaallatestNews($limit){
		$newshtml = null;
		$today = date("Y-m-d");
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		//$stmt = $conn->prepare("SELECT title, content, added FROM vpnews WHERE expire >=? AND deleted IS NULL ORDER BY id DESC LIMIT ?");
		$stmt = $conn->prepare("SELECT title, content, added FROM vpnews WHERE expire >=? AND deleted IS NULL ORDER BY vpnews_id DESC LIMIT ?");
		echo $conn->error;
		$stmt->bind_param("si", $today, $limit);
		//$stmt->bind_param("i", $limit);
		$stmt->bind_result($titlefromdb, $contentfromdb, $addedFromDb);
		$stmt->execute();
		while ($stmt->fetch()){
			$newshtml .= "<div> \n";
			$newshtml .= "\t <h3>" .$titlefromdb ."</h3> \n";
			$addedtime = new DateTime($addedFromDb);
			$newshtml .= "\t <p>(Lisatud: " .$addedtime->format("d.m.Y H:i:s") .")</p> \n";
			$newshtml .= "\t <div>" .htmlspecialchars_decode($contentfromdb) ."</div> \n";
			$newshtml .= "</div> \n";
		}
		if($newshtml == null){
			$newshtml = "<p>Kahjuks uudiseid pole!</p>";
		}
		$stmt->close();
		$conn->close();
		return $newshtml;
	}
	function readAllNews(){
		$newshtml = null;
		$today = date("Y-m-d");
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		//$stmt = $conn->prepare("SELECT title, content, added FROM vpnews WHERE expire >=? AND deleted IS NULL ORDER BY id DESC LIMIT ?");
		//SELECT title, content, vpnews.added, filename, alttext FROM vpnews LEFT JOIN vpnewsphotos on vpnewsphotos.vpnewsphotos_id = vpnews.photoid GROUP BY vpnews.vpnews_id
		//$stmt = $conn->prepare("SELECT title, content, added FROM vpnews WHERE expire >=? AND deleted IS NULL ORDER BY vpnews_id DESC LIMIT ?");
		$stmt = $conn->prepare("SELECT title, content, vpnews.added, filename, alttext, vpnews_id FROM vpnews LEFT JOIN vpnewsphotos on vpnewsphotos.vpnewsphotos_id = vpnews.photoid WHERE  vpnews.deleted IS NULL GROUP BY vpnews.vpnews_id ORDER By vpnews_id DESC");
		echo $conn->error;
		//$stmt->bind_param("i", $limit);
		$stmt->bind_result($titlefromdb, $contentfromdb, $addedFromDb, $filenamefromdb, $alttextfromdb, $newsidfromdb);
		$stmt->execute();
		while ($stmt->fetch()){
			$newshtml .= '<div class="newsblock';
			if(!empty($filenamefromdb)){
				$newshtml .=" fullheightnews";
			}
			$newshtml .= '">' ."\n";
			if(!empty($filenamefromdb)){
				$newshtml .= "\t" .'<img src="' .$GLOBALS["photouploaddir_news"].$filenamefromdb .'" ';
				if(!empty($alttextfromdb)){
					$newshtml .= 'alt="' .$alttextfromdb .'"';
				} else {
					$newshtml .= 'alt="' .$titlefromdb .'"';
				}
				$newshtml .= "> \n";
			}
			
			$newshtml .= "\t <h3>" .$titlefromdb ."</h3> \n";
			$addedtime = new DateTime($addedFromDb);
			$newshtml .= "\t <p>(Lisatud: " .$addedtime->format("d.m.Y H:i:s") .")</p> \n";
			
			$newshtml .= "\t <div>" .htmlspecialchars_decode($contentfromdb) ."</div> \n";
			//\t" .'<button type="button"><a class="nupp" href="alterNews.php?id=' .$newsidfromdb .'">Muuda/kustuta uudis "' .$newstitlefromdb .'"</a></button>' ."\n";
			$newshtml .= "\t " .'<button type="button"><a class="button" href="modifynews.php?id=' .$newsidfromdb .'">Muuda uudist "' .$titlefromdb .'"</a></button>' ."\n";
			$newshtml .= "<hr>";
			$newshtml .= "</div> \n";
		}
		if($newshtml == null){
			$newshtml = "<p>Kahjuks uudiseid pole!</p>";
		}
		$stmt->close();
		$conn->close();
		return $newshtml;
	}