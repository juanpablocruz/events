<?php
session_start();
$path = "system";
require_once $path."/connect.php";
require_once $path."/Image.class.php";

    $image = new ImageFile();
    $image->load($_FILES["file"]['tmp_name']);
    $image->resizeToHeight(300);
	$id = $_SESSION["k_UserId"];
	$archivo = "img/".$id.".jpg";
	if(file_exists($archivo))unlink($archivo);
	$image->save($archivo);
	
	$qInsertImg = mysql_query("UPDATE members SET Img = '$archivo' WHERE UserId = '$id'");
 	header("location:main.php");

?>