<?php 
if(!isset($_SESSION))session_start();
if (!isset($_SESSION['CLASE']))header("location:index.php");

		$basepath = "system";
		require_once $basepath."/connect.php";
		require_once $basepath."/view.php";
		require_once $basepath."/mensaje.class.php";
		require_once $basepath."/user.class.php";
		require_once $basepath."/eventsfunctions.php";
		$User = LoadUser();
		$id = $User->id;

		$fImage = view("$id");
		header('Content-Type: text/html; charset=UTF-8');
?>
<html>
<head>
<link rel="stylesheet" href="css/style_mensajes.css" />
</head>
</html>
<div id="MessagesBar">
<div id="CrearMsg">
	<button id="newmssg" onclick="SendMessage()"><i id="iNewMsg"></i>New Message</button>
</div>
</div>
<div id="msg">
	<div id="Messages">
	<p class="pHeader">Mensajes</p>
	<?php
	$aMessageList = GetMessageList();
	if($aMessageList[0]){
	echo "<ul id=ulMessages>";
	for($i = 0; $i<sizeof($aMessageList);$i++){
		$IdMssg = $aMessageList[$i]['IdWriter'];
		$qGetUser=mysql_query("SELECT Name,LastName FROM members WHERE UserId = '$IdMssg'");
		$user = mysql_fetch_array($qGetUser);
		echo "<li><details clas='Message'><summary id='dTitleMsg' onclick =ViewMessage('".$aMessageList[$i]['IdMessage']."')>".
				$user['Name']." ".$user['LastName']."  <span id='Date'>".$aMessageList[$i]['DateWrited']."</span></summary>";
		echo "<div id='Message".$aMessageList[$i]['IdMessage']."' class='dContenidoMsg'>".$aMessageList[$i]['Text']."</div></details></li>";
		}
	echo "</ul>";	
	}
	$aFriendshipR = GetFriendshipR();
	if($aFriendshipR[0]){
	for($i = 0; $i<sizeof($aFriendshipR);$i++){
		$IdMssg = $aFriendshipR[$i]['IdWriter'];
		$qGetUser=mysql_query("SELECT Name,LastName FROM members WHERE UserId = '$IdMssg'");
		$user = mysql_fetch_array($qGetUser);
		$Text = explode("_",$aFriendshipR[$i]['Text']);
		$Text = $Text[0];
		echo "<div clas='Message'><div id='dTitleMsg' onclick =ViewMessage('".$aFriendshipR[$i]['IdMessage']."')>".$user['Name']." ".$user['LastName']."</div>";
		echo "<div id='Message".$aFriendshipR[$i]['IdMessage']."' class='dContenidoMsg'>".$Text."</div>
		<form method=post action=AcceptRequest.php> <input type='hidden' name=msg value=".$aFriendshipR[$i]['IdMessage']."><input type='submit' value='Aceptar'></form>
		</div>";
		}
	}	
	echo "</div></div>";
	$mensaje = new MessageBox();
	echo $mensaje->NewMessage('0','0',0);
	?>
</div>