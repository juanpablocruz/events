<?php
session_start();
if (!isset($_SESSION['CLASE']))header("location:index.php");
		require_once $basepath."/connect.php";
		require_once $basepath."/user.class.php";
		require_once $basepath."/eventsfunctions.php";


$grupo = $_POST['grupo'];
$Text = $_POST['Text'];
$id = $_SESSION['k_UserId'];
$Post['grupo'] = $grupo;
$Post['Text'] = $Text;
$error = '';
$error  = CreatePost($Post,$id);
if($error == ''){ echo "se";}
else{ echo $error;}
?>