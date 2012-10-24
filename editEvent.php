<?php
	session_start();
	if (!isset($_SESSION['CLASE'])) header("location:index.php");
		$basepath = "system";
		require_once $basepath."/connect.php";
		require_once $basepath."/user.class.php";
	$Id = $_SESSION['k_UserId'];
	$editEvent=$_GET["q"];
	list($value,$IdEvent) = explode("_", $editEvent);

	switch($value){
		case 1:
			$qGetEvents = mysql_query("UPDATE `events` SET Status='2' WHERE IdEvent=$IdEvent AND UserId=$Id");
			break;
		case 2:
			$qGetEvents = mysql_query("UPDATE `events` SET Status='3' WHERE IdEvent=$IdEvent AND UserId=$Id");
			break;
	}

?>