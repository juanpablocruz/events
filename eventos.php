<?php 
if(!isset($_SESSION))session_start();
if (!isset($_SESSION['CLASE']))header("location:index.php");

		$basepath = "system";
		require_once $basepath."/connect.php";
		require_once $basepath."/user.class.php";
		require_once $basepath."/eventsfunctions.php";
		require_once $basepath."/event.class.php";		
		$User = LoadUser();
		$id = $User->id;
		
		$ArrayEventos[]=GetEvents();
		header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="css/style_eventos.css" />
	<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.17.custom.min.js"></script>
	<script type="text/javascript" src="js/eventslib.js"></script>	
	</head>
	<body>
		<form>
			<select name="events" onchange="showEvents(this.value)">
			<option value="">Eventos:</option>
			<option value="0">Pendientes</option>
			<option value="1">Confirmados</option>
			<option value="2">Rechazados</option>
			<option value="3">Creados por mi</option>
			<option value="4">Pasados</option>
			</select>
		</form>
		<br />
		<div id="txtHint"></div>
		<button id="bNewEvent" onclick="SaltarPaso('dContenedorFormEventos',1,1)"><i id="iNewEvent"></i>Nuevo evento</button>
		<?php

			$evento = new Evento();
			echo $evento->NewEvent();		
		?>	
		<div id="dCalendar" >		
		</div>		
	</body>
</html>