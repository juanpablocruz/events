<?php session_start();
		require_once $basepath."/connect.php";
		require_once $basepath."/user.class.php";

$id = $_SESSION["k_UserId"];
$grupo = $_POST['grupo'];
//$type = $_POST['grouptype'];
echo "INSERT INTO groups('IdCreator','Open','Name','Type') VALUES ($id,1,'$grupo',1)";
$qAddGroup = mysql_query("INSERT INTO groups(IdCreator,Open,Group_Name,Type) VALUES ($id,1,'$grupo',1)");

	echo
			"<SCRIPT LANGUAGE='javascript'>
			 location.href = 'main.php?p=contactos';
			 </script>";
?>