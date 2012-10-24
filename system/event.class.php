<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="css/style_eventos.css" />
	<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="js/eventslib.js"></script>	
	</head>


<?php

class Evento{
	public function  __construct() {
		$basepath = "system";
		require_once $basepath."/connect.php";
		require_once $basepath."/user.class.php";
	}	
	function Destination()
		{
			$id = $_SESSION["k_UserId"];
			$aListppl[]='';
			$i=0;
				$qGetFollowers = mysql_query("SELECT * FROM relations WHERE IdFollowed = $id");	
				$ToList= "<ul>";
				while ($aFollower = mysql_fetch_array($qGetFollowers)){			
						$tNombreGrupo = $aFollower["IdGroup"];
						$iFollowerId = $aFollower["IdFollower"];
						if(!in_array($iFollowerId,$aListppl)){
							$aListppl[$i]=$iFollowerId;
							$i++;
							$qSelectName = mysql_query("SELECT Name,UserId FROM members WHERE UserId = $iFollowerId");
							$aNames = mysql_fetch_row($qSelectName);
							$ToList.= "<li><div><INPUT TYPE=radio NAME='whom' value=".$aNames[1].">".$aNames[0]."</div></li>";					
					}
				$ToList.= "</ul>";
			}
			return $ToList;
		}
	function NewEvent()
	{
		$evento= "
		
			<form action='createEvent.php' method='POST' id='formevent' onclick= apagar(event,0)>
			 
				<div id='dContenedorFormEventos1' onclick= mantener(event)>
					<header style='padding-left:40%;'>Nuevo Evento</header>	
					<div id='dNombreEvento'><input type='text' placeholder='Nombre del evento' name='NombreEvento'/></div>
					<div id='dDescripcionEvento'>
						<TEXTAREA NAME='DescripcionEvento' COLS=40 ROWS=6 placeholder='Descripcion del Evento' ></TEXTAREA>
					</div>
					<div class='nexBotton' onclick=SaltarPaso('dContenedorFormEventos',1,2)></div>
				</div>
			
				<div id='dContenedorFormEventos2' onclick= mantener(event)>
					<div id='dFechaFinEvento'>Fecha:<input id='inFecha' type='text' placeholder='YYYY-MM-DD' name='FechaFin'/></div>
					<div id='dHoraEvento'>Hora:<input type='text' placeholder='hh:mm:ss' name='Hora'/></div>
					<div id='dLugarEvento'><input type='text' placeholder='Lugar del evento' name='LugarEvento'/></div>
					<div class='preBotton' onclick=SaltarPaso('dContenedorFormEventos',2,1)></div>
					<div class='nexBotton' onclick=SaltarPaso('dContenedorFormEventos',2,3)></div>
				</div>
				<div id='dContenedorFormEventos3' onclick= mantener(event)>
					";
			$evento.=$this->Destination();		
			$evento.="<div class='preBotton' onclick=SaltarPaso('dContenedorFormEventos',3,2)></div>
					<input type='SUBMIT' value='send'>
				</div>
			</form>
			";
		return $evento;
		}
}	

?>
</html>
