<?php 
session_start();

if (!isset($_SESSION['CLASE'])) {
	header("location:index.php");
}
		if(!isset($_GET['p'])){
			$redirect = 'inicio';
		}
		else{
			$redirect = $_GET['p'];
		}
		$basepath = "system";
		include_once $basepath."/connect.php";
		require_once $basepath."/User.class.php";
		$User = LoadUser();
		header('Content-Type: text/html; charset=UTF-8');			
?>

<!DOCTYPE html>
<html>
	<head>
<SCRIPT TYPE="text/javascript">
	<!--
		function submitenter(myfield,e)
		{
			var keycode;
			if (window.event) keycode = window.event.keyCode;
			else if (e) keycode = e.which;
			else return true;
			if (keycode == 13){
			   myfield.form.submit();
			   return false;
			}
			else
			   return true;
		}
	//-->
	</SCRIPT>
		<title>e-Vents</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" href="css/theme2.css" />
		<link rel="shortcut icon" href="css/eventsico.png">
		<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.17.custom.min.js"></script>
		<script type="text/javascript" src="js/eventslib.js"></script>	
		
		
	</head>
	<body>


		<nav id='dBarramenu' onclick='hideconfig()'>
			<div  id="dLogo" onclick='cargarpagina("inicio")'><div class="log"><img src="css/eventsico.png" /></div></div> 
			<ul id='ulListamenu'>
				<li><div id='dinicio' class='botonmenu bcolor1' onclick='cargarpagina("inicio")'>Inicio</div></li>
				<li><div id='dperfil' class='botonmenu bcolor1' onclick='cargarpagina("perfil")'>Perfil</div></li>
				<li><div id='dcontactos' class='botonmenu bcolor1' onclick='cargarpagina("contactos")'>Contactos</div></li>
				<li><div id='deventos' class='botonmenu bcolor1' onclick='cargarpagina("eventos")'>Eventos</div></li>
				<li><div id='dmensajes' class='botonmenu bcolor1' onclick='cargarpagina("mensajes")'>Mensajes</div></li>
			</ul>
			<div id='dSearchmain'>
				<a>
				<form class="searchform">
					<input id="inBuscar_users" name="search" type="text" placeholder="Buscar..." autocomplete="off" class="hint searchfield">
					<button id="bBuscar"></button>
				</form>
				</a>
			</div>
			<div id='dLoog'>
				<div id="dLg">Conectado como <?php echo '<b>'.$User->Data["Perfil"]["Name"].'</b>.';?></div>
				<div class="options" onclick='showconfig(event)'></div>
				
				
			</div>
		</nav>
		
		<section id='dPaginamuestra'  onclick="hideconfig()">
			<div id="dContenido"></div>
		</section>
		<footer id='dPie'><c style="font-size: 15px;">©</c>e-Vents2012</footer>
	<script type="text/javascript">
		cargarpagina('<?php echo $redirect; ?>');
	</script>
	</body>
<div id="config">
<ul>
<li><div id='configuracion' onclick="cargarpagina('configuration')">Configuracion</div></li>
<li><div id='dLogout'><a href="logout.php">Salir</a></div></li>
</ul>
</div>	
</html>