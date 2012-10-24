<?php
include_once 'connect.php';
$name = $_POST['name'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$pass = has($_POST['password']);
$movil = $_POST['movil'];
$hoy = date("Y-m-d");
$birthmon = $_POST['BirthMon'];
$birthyear = $_POST['BirthYear'];
$birthd = $_POST['BirthDay'];
if($birthmon<10)$birthmon = '0'.$birthmon;
if($birthd<10)$birthd = '0'.$birthd;

$birthday = $birthyear.'-'.$birthmon.'-'.$birthd;
$authk = '2';

if( empty($_POST['name'])||empty($_POST['email'])||empty($_POST['password'])||empty($_POST['lastname']))
	{
	echo "Incomplete Form";
	}
else{
	$SelectNameq = mysql_query("SELECT `Name` FROM `members` WHERE `Email`='$email'")
		or die (mysql_error());
		if(mysql_num_rows($SelectNameq)==0)
		{
		$result = mysql_query("INSERT INTO members (Name,LastName,Password,BirthDay,Email,Phone,Auth_Key,Active,
								RegisterDay,Online,Img)
			VALUES ('$name','$lastname','$pass','$birthday','$email','$movil','$authk','FALSE','$hoy','0','img/default.jpg')")
			or die ("Couldn't execute query.");
		$SelectUserq = mysql_query("SELECT * FROM members WHERE Name = '$name'")
			or die(mysql_error());
		$UserArray= mysql_fetch_array($SelectUserq);
		$id = $UserArray["UserId"];

		$FillGroups = mysql_query("
								INSERT INTO `groups`(IdCreator,Open,Group_Name,Type)
								VALUES($id,1,'Abierto',1);
								")
								or die(mysql_error());

			echo
			"<SCRIPT LANGUAGE='javascript'>
			 location.href = 'index.php';
			 </script>";

				 }
	else{
		echo "User allready exists";
	}}
	/* ----------------ENCRYPTION FUNCTION--------------------*/
	function has($parametro)
	{
	return (hash('whirlpool',$parametro));
	}
?>