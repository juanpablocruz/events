<?php
if(!isset($_SESSION))session_start();
if (!isset($_SESSION['CLASE']))header("location:index.php");
if(!isset($_GET['p']))$redirect = 'inicio';
else $redirect = $_GET['p'];

		$basepath = "system";
		require_once $basepath."/connect.php";
		require_once $basepath."/user.class.php";
		require_once $basepath."/eventsfunctions.php";
		$User = LoadUser();
		require_once $basepath."/view.php";
		$id = $User->id;
		$fImage = view("$User->id");
		header('Content-Type: text/html; charset=UTF-8');
		$ListEvents = GetEvents();
		$TomorrowEvents = GetNearEvents($ListEvents,1);
		$TodayEvents = GetNearEvents($ListEvents,0);
		$seguidores[0] = GetFollowers();
		$seguidores[1] = GetFollowed();
		$NewMessage = GetNewMessages();
		$Notifications = GetNotifications();
?>
<!DOCTYPE html>
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="css/style_inicio.css" />
	<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="js/eventslib.js"></script>
	<!--<script>
			$('#dCalendar').load(
					'calendar.php'
                 );	
	</script>-->
<script>

</script>	
	</head>
	<body>
		<div id="dEvento">
		<h3 class="pHeader">Próximos Eventos:</h3>
		<p>Hoy:</p><?php $i = 0;if($TodayEvents[0])while($i<sizeof($TodayEvents)){echo "<span class='nearEvent'>".$TodayEvents[$i]['Name']."</span>";$i++;}else{echo "<span>No hay eventos</span>";};?>
		<p>Mañana:</p><?php $i = 0;if($TomorrowEvents[0])  while($i<sizeof($TomorrowEvents)){echo "<span class='nearEvent'>".$TomorrowEvents[$i]['Name']."</span>";$i++;}else{echo "<span>No hay eventos</span>";};?>
		</div>
		<div id="dTablon" >
			<p class="pHeader">Mensajes</p>
			<ul id="ulMensajes" style="list-style:none;">
				<?php 
				$i = 0;
				if($NewMessage[0])
					while($i<sizeof($NewMessage))
					{
						$IdWriter = $NewMessage[$i]['IdWriter'];
						$qGetUser=mysql_query("SELECT Name,LastName,UserId FROM members WHERE UserId = '$IdWriter'");
						$user = mysql_fetch_array($qGetUser);

					echo //classes: post back first | post mainpost | post back second
					"<li>
						<div class='post mainpost'>
							<div class='content' onclick=(ViewMessage('".$NewMessage[$i]['IdMessage']."'))><img class='iPhoto' src=".view($user['UserId']).">".$user['Name']." ".$user['LastName']."</div>
						</div>
					</li>";							
					$i++;
					}
				else{
					echo
					"<li>
						<div class='post emptymsg' style='width:300px;'>
							<div class='content'>No hay Mensajes</div>
						</div>
					</li>";			
					}
				?>
			</ul>

			<p class="pHeader">Notificaciones</p>
			<div id="dNews">
			<?php
			echo "<ul id='ulNews'>";
			if($Notifications[0]){
				for($i = 0; $i <sizeof($Notifications[0]);$i++){
					switch($Notifications[0][$i]['Type']){
					case '1':{ //case of being a system message of relations activity
						$idf = $Notifications[0][$i]['IdWriter'];
						if($idf != $id){
						$qGetUser=mysql_query("SELECT Name,LastName FROM members WHERE UserId = '$idf'");
						$user = mysql_fetch_array($qGetUser);
						echo "<li onclick=cargarpagina('contactos')><div class='iNewFriendo'></div>  Ahora te sigue ".$user['Name']." ".$user["LastName"]." En ".$Notifications[0][$i]['Text']."</li>";
						}
						else{
						$idf = $Notifications[0][$i]['IdReader'];
						$qGetUser=mysql_query("SELECT Name,LastName FROM members WHERE UserId = '$idf'");
						$user = mysql_fetch_array($qGetUser);
						echo "<li onclick=cargarpagina('contactos')><div class='iNewFriendo'></div>  Ahora sigues a ".$user['Name']." ".$user["LastName"]." En ".$Notifications[0][$i]['Text']."</li>";						
						}
						break;
						}					
					case '2':{ //case of being a system message of friends activity
						echo "<li onclick=cargarpagina('contactos')><div class='iNewo'></div>  ".$Notifications[0][$i]['IdCreator']." ".$Notifications[0][$i]['Text']."</li>";
						break;
						}
					case '0':{//case of being a user message
						$IdWriter = $Notifications[0][$i]['IdWriter'];
						$qGetUser=mysql_query("SELECT Name,LastName FROM members WHERE UserId = '$IdWriter'");
						$user = mysql_fetch_array($qGetUser);
						echo "<li onclick=cargarpagina('mensajes')><div class='iNewMsgo'></div>  Tienes un mensaje sin leer de: ".$user['Name']." ".$user['LastName']."</li>";
						break;
						}
					case '5':{//case of friendship request
						$IdWriter = $Notifications[0][$i]['IdWriter'];
						$qGetUser=mysql_query("SELECT Name,LastName FROM members WHERE UserId = '$IdWriter'");
						$user = mysql_fetch_array($qGetUser);
						echo "<li onclick=cargarpagina('mensajes')><div class='iFriendReqo'></div>  Tienes una peticion de amistad de: ".$user['Name']." ".$user['LastName']."</li>";
						break;
						}
					}
				}
			}

			if($Notifications[1]){
				for($i = 0; $i <sizeof($Notifications[1]);$i++){
					echo "<li onclick=cargarpagina('eventos')><div class='iNewEvento'></div>  Nuevo evento: ".$Notifications[1][$i]['Description']."</li>";}
				}
			echo "</ul>";	
			?>
			</div>
		</div>

		<div id="dPerfil">
			<?php 
			echo "<div id='dImagePerfilInicio' class='image'><img src=".$fImage."></div>";
			echo "<div id='dInfoPerfil'><h3 id='h3Name' onclick=cargarpagina('perfil')>".$User->Data["Perfil"]["Name"]."</h3>";
			echo "<p>Seguidores:<span style='color=#333;font-size:15px;font-weight: bold;'>".$seguidores[0]."</span></p>";
			echo "<p>Sigues:<span style='color=#333;font-size:15px;font-weight: bold;'>".$seguidores[1]."</span></p></div>";
			?>
			<form action="sendmail.php">
			<input type="submit" value="submit">
			</form>
			
		</div>
		<div>
		<div id="dCalendar" >
		
		</div>
		</div>
	</body>
</html>
