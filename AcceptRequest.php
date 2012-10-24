<?php
session_start();
if (!isset($_SESSION['CLASE'])) header("location:index.php");

require_once "system/eventsfunctions.php";
$IdMessage = $_POST['msg'];
$errors = AceptRequest($IdMessage);
if($errors == 0){
echo
			"<SCRIPT LANGUAGE='javascript'>
			 location.href = 'main.php';
			 </script>";
}
else{
echo "errores: ".$errors;
}
?>
