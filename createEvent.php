<?php
session_start();
if (!isset($_SESSION['CLASE'])) header("location:index.php");
		$basepath = "system";
		require_once $basepath."/connect.php";
		require_once $basepath."/user.class.php";
		require_once $basepath."/eventsfunctions.php";
$User = $_POST['whom'];
$id = $_SESSION['k_UserId'];
$Name=$_POST['NombreEvento'];
$Description=$_POST['DescripcionEvento'];
$ExpireDate=$_POST['FechaFin'];
$hora = $_POST['Hora'];
$Place=$_POST['LugarEvento'];
$Date = $ExpireDate.' '.$hora;
$DMovil=0;
$DMail=0;
$DEvents=1;
$errors=0;

$StartDate = CreateNewEvent($id, $Name, $Description, $Date, $Place, $DMovil, $DMail, $DEvents);
$errors= SendNewEvent($id, $id, 2,$StartDate);
$errors= SendNewEvent($id, $User, 0,$StartDate);

if($errors == 0){
echo 
			"<SCRIPT LANGUAGE='javascript'>
			 location.href = 'main.php';
			 </script>";
}
else{
echo "errores";
}
?>