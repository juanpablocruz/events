<?php
session_start();
if (isset($_SESSION['CLASE'])) {
  }
  else header("location:index.php");
		require_once $basepath."/connect.php";
		require_once $basepath."/view.php";
		require_once $basepath."/user.class.php";
		require_once $basepath."/eventsfunctions.php";

		require_once "variables.php";

$IdGroup = 0;
$User = 0;
$Text = '';
$Type = '';
if (isset($_POST['whom']))$User = $_POST['whom'];
else{echo "error: Di a quien";};
if (isset($_POST['Text']))$Text = $_POST['Text'];
if (isset($_POST['Type']))$Type = $_POST['Type'];
if (isset($_POST['IdGroup']))$IdGroup = $_POST['IdGroup'];

$id = $_SESSION['k_UserId'];

$errors = SendUserMessage($id,$User,$IdGroup,$Text,$Type);
if($errors == 0){
echo
			"<SCRIPT LANGUAGE='javascript'>
			 location.href = 'main.php';
			 </script>";
}
else{
echo $errors;
}
?>