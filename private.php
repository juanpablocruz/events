<?php
session_start();
if (!isset($_SESSION['CLASE'])) header("location:index.php");

		require_once $basepath."/connect.php";
		require_once $basepath."/mensaje.class.php";
		require_once $basepath."/user.class.php";
		require_once $basepath."/eventsfunctions.php";			

$id = $_GET['id'];
$idgrupo = $_GET['idg'];
$grupo = $_GET['grupo'];
		echo $grupo;
		$mensaje = new MessageBox();
		echo $mensaje->NewMessage($row['UserId'],$idgrupo,0);
?>