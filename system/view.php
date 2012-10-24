<?php
if (!isset($_SESSION))session_start(); 
if (isset($_SESSION['CLASE'])) {
  }
  else{
  			echo
			"<SCRIPT LANGUAGE='javascript'>
			 location.href = 'index.php';
			 </script>";
		}
require_once "connect.php";
/*
@param string "$id"
@return string $name[0] with the image name of the user with UserId = $id
*/
function view($id){
	$qGetImg = mysql_query("SELECT Img FROM members WHERE UserId = '$id'");
	$name = mysql_fetch_row($qGetImg);
	return $name[0];
}
?>