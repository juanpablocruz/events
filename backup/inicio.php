<?php session_start();
if (isset($_SESSION['k_username'])) {
  }
  else{
  			echo
			"<SCRIPT LANGUAGE='javascript'>
			 location.href = 'index.php';
			 </script>";
		}
		include_once "connect.php";
		include_once "eventsfunctions.php";
		include_once "variables.php";
		include_once "view.php";
		$fImage = view("$id");
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
	<script>
			$('#dCalendar').load(
					'calendar.php'
                 );	
	</script
<script>

</script>	
	</head>
	<body>
		<div id="dEvento">
		<h3>Próximos Eventos:</h3>
		<p>Hoy<br><?php $i = 0;if($TodayEvents[0])while($i<sizeof($TodayEvents)){echo $TodayEvents[$i]."<br>";$i++;}else{echo "No hay eventos<br>";};?><br>
		Mañana<br><?php $i = 0;if($TomorrowEvents[0])  while($i<sizeof($TomorrowEvents)){echo $TomorrowEvents[$i]."<br>";$i++;}else{echo "No hay eventos<br>";};?><br></p>
		</div>
		<div id="dTablon" >
			<p>Mensajes</p>
			<ul id="ulMensajes" style="list-style:none;">
				<?php 
				$i = 0;
				if($NewMessage[0])
					while($i<sizeof($NewMessage))
					{
						$IdWriter = $NewMessage[$i]['IdWriter'];
						$qGetUser=mysql_query("SELECT Name,LastName,UserId FROM members WHERE UserId = '$IdWriter'");
						$User = mysql_fetch_array($qGetUser);

					echo //classes: post back first | post mainpost | post back second
					"<li>
						<div class='post mainpost'>
							<div class='content' onclick=(ViewMessage('".$NewMessage[$i]['IdMessage']."'))><img class='iPhoto' src=".view($User['UserId']).">".$User['Name']." ".$User['LastName']."</div>
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

			<p>Notificaciones</p>
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
						$User = mysql_fetch_array($qGetUser);
						echo "<li><div class='iNewFriendo'></div>Ahora te sigue ".$User['Name']." ".$User["LastName"]." En ".$Notifications[0][$i]['Text']."</li>";
						}
						else{
						$idf = $Notifications[0][$i]['IdReader'];
						$qGetUser=mysql_query("SELECT Name,LastName FROM members WHERE UserId = '$idf'");
						$User = mysql_fetch_array($qGetUser);
						echo "<li><div class='iNewFriendo'></div>Ahora sigues a ".$User['Name']." ".$User["LastName"]." En ".$Notifications[0][$i]['Text']."</li>";						
						}
						break;
						}					
					case '2':{ //case of being a system message of friends activity
						echo "<li><div class='iNewo'></div>".$Notifications[0][$i]['IdCreator']." ".$Notifications[0][$i]['Text']."</li>";
						break;
						}
					case '0':{//case of being a user message
						$IdWriter = $Notifications[0][$i]['IdWriter'];
						$qGetUser=mysql_query("SELECT Name,LastName FROM members WHERE UserId = '$IdWriter'");
						$User = mysql_fetch_array($qGetUser);
						echo "<li><div class='iNewMsgo'></div>Tienes un mensaje sin leer de: ".$User['Name']." ".$User['LastName']."</li>";
						break;
						}
					case '5':{//case of friendship request
						$IdWriter = $Notifications[0][$i]['IdWriter'];
						$qGetUser=mysql_query("SELECT Name,LastName FROM members WHERE UserId = '$IdWriter'");
						$User = mysql_fetch_array($qGetUser);
						echo "<li><div class='iFriendReqo'></div>Tienes una peticion de amistad de: ".$User['Name']." ".$User['LastName']."</li>";
						break;
						}
					}
				}
			}

			if($Notifications[1]){
				for($i = 0; $i <sizeof($Notifications[1]);$i++){
					echo "<li><div class='iNewEvento'></div>Nuevo evento: ".$Notifications[1][$i]['Description']."</li>";}
				}
			echo "</ul>";	
			?>
			</div>
		</div>

		<div id="dPerfil">
			<?php 
			echo "<div id='dImagePerfilInicio' class='image'><img src=".$fImage."></div>";
			echo "<div id='dInfoPerfil'><h3 id='h3Name' onclick=cargarpagina('perfil')>".$_SESSION['k_username']."</h3>";
			echo "<p>Seguidores:".$seguidores[0]."</p>";
			echo "<p>Sigues:".$seguidores[1]."</p></div>";
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
