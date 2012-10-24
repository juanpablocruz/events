<?php
if(!isset($_SESSION))session_start();
if (!isset($_SESSION['CLASE']))header("location:index.php");

		$basepath = "system";
		require_once $basepath."/connect.php";
		require_once $basepath."/view.php";
		require_once $basepath."/user.class.php";
		require_once $basepath."/eventsfunctions.php";
		$User = LoadUser();
		
		$id = $User->id;
		$fImage = view("$id");
		
		if(isset($_GET['search']))
			{
				$name = $_GET['search'];
  			echo
			"<SCRIPT LANGUAGE='javascript'>
				alert(".$name.");	
				 $('#dPerfilview').load(
                        'viewperfil.php?name=' + ".$name."
                       );
						
			 </script>";				

			}		
		header('Content-Type: text/html; charset=UTF-8');
echo 
"<!DOCTYPE html>
<html>
	<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	<link rel='stylesheet' href='css/style_contactos.css' />
	<script type='text/javascript' src='js/jquery-1.7.1.min.js'></script>
	<script type='text/javascript' src='js/jquery-ui-1.8.17.custom.min.js'></script>
	<script type='text/javascript' src='js/eventslib.js'></script>	
	</head>
	<body>";
		
		echo "<aside id='asideGroups'><div id='dfollowed'><span>Sigues:</span>";

		echo "<ul id='ulFollowed'>";
		foreach($User->Data["Friends"]["Sigo"] as $individuo){
		echo "<li><div class='imagefollowed' style='height:30px'><img src=".view($individuo[0])."></div><div class='online'></div>
				<p class='followeduser' onclick =viewperfil(".$individuo[0].")>
				".$individuo['Name']." ".$individuo['LastName']."</p> en <div class='followeduser' onclick = viewgroup('".$individuo['Grupo'][0]."')>".$individuo['Grupo'][1]."</div></li>";		
		}
		echo "</ul></div><section id=friend;>";
		$i = 0;
		while($i<count($User->Data["Grupos"]["Mios"]["Group_Name"])){
				$arraygrupos[$i]=$User->Data["Grupos"]["Mios"]["Group_Name"][$i];
				$i++;
		}
		sort($arraygrupos);
		$arraygrupos=json_encode($arraygrupos);
		
		$i = 0;
		while($i<count($User->Data["Grupos"]["Amigos"])){
				$arraysusgrupos[$i]=$User->Data["Grupos"]["Amigos"][$i][1];
				$IdsDeAmigos[$i]=$User->Data["Grupos"]["Amigos"][$i]["IdOwner"]; //Lista de arrays con los ids de los dueños de cada grupo.
				$i++;
		}
		sort($arraysusgrupos);
		$arraysusgrupos=json_encode($arraysusgrupos);			
			
			echo "	<div id='misgruposfather'>
					<div id='misgrupos' class='drag' onclick='crearbolas(".$id.",".$arraygrupos.")'>
						<div id='misgruposchild'><div id='dPerfilview'><img id='contactimg' src='".view("$id")."'></div></div>
					</div>
					</div>";
		echo "	<div id='home' onclick='viewperfil(".$id.")'></div>
				<div id='ejeHome'> </div>
				<script>crearbolasSeg(".$id.",".$arraysusgrupos.",0,5)</script>";
		echo "<div id='seguidores' class='drag'></aside>";


			echo "</ul></div></div>";	

			echo"</div><aside id='asideForo'>
				<section id='foro'></section></aside>
				<aside id=formulario>
				<form method=post action='newgroup.php'><input type=text name='grupo' placeholder='New Group'><button id='newgroupbutton' type=submi>Create</button></form>
				</aside>
				<div id='FollowRequest'></div>
		
	</body>
</html>";
?>