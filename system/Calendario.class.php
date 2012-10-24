<?php

class Calendario {
	public function  __construct() {
		require_once "system/connect.php";    
		require_once "system/eventsfunctions.php";
	}

	function draw_calendar($mes, $ano){ //si $mes es 0, se toma como el actual mes
		$hoy = time ();  // El dia de hoy
		$dia = date('d', $hoy);
		$primer_dia_del_mes = mktime(0,0,0,$mes, 1, $ano);	
		$dia_de_la_semana = date('D', $primer_dia_del_mes); //$dia es numerico, esto es lunes, martes,...
		$nombre_mes = date('F', $primer_dia_del_mes);
		
		
		//inicializa el calendario, pone X huecos dependiendo de cual es el primer
		//dia de la semana. $blank indica el numero de huecos en blanco
		switch($dia_de_la_semana){
			case "Mon": $blank = 0; break;
			case "Tue": $blank = 1; break;
			case "Wed": $blank = 2; break;
			case "Thu": $blank = 3; break;
			case "Fri": $blank = 4; break;
			case "Sat": $blank = 5; break;
			case "Sun": $blank = 6; break;
		} 
		
		$dias_en_mes = cal_days_in_month(0, $mes, $ano);
		
		//dibuja el "frame" del calendario
		$calendar = "<table class='calendario'>";
		$calendar.= "<tr><th colspan=60> $nombre_mes $ano </th></tr>";
		//He quitado las clases calendario de los dias para que no sean seleccionables
		$calendar.= "<tr><td>L</td><td>M</td><td>X</td><td>J</td><td>V</td><td>S</td><td>D</td></tr>";
		
		$contador_dias = 1;
		$contador_dias_misma_semana = 1;
		
		//rellena los primeros huecos del calendario
		$calendar.= "<tr>";
		while($blank > 0){
			$calendar.= "<td></td>";
			$blank--;
			$contador_dias_misma_semana++;
		}
		
		//rellena el grueso del calendario
		$miseventos = GetEvents();
		while($contador_dias <= $dias_en_mes){
			
			$message = "\"$contador_dias/$nombre_mes/$ano"; //init el mensaje de hoy
			$eventos_hoy = GetDaysEvents(mktime(0,0,0,$mes,$contador_dias,$ano));
			
			$i=0;
			while($i < sizeof($eventos_hoy)){ //anade la informacion del siguiente evento de hoy
				$message.= '\n';
				$message.="(time)&nbsp;&nbsp;".$eventos_hoy[$i]["Name"].'\n';
				$message.=$eventos_hoy[$i]["Description"];
				$i++;
			}
			$message.="\"";
			//html se comporta analmente con los espacios (" ") asique tienes que reemplazarlos por su equivalente en html (&nbsp;)
			$message = str_replace(" ","&nbsp;",$message);
			if($contador_dias == $dia)
				$calendar.= '<td class="calendarioF calendario" onclick=alert('.$message.')> '.$contador_dias.' </td>';
			else
				$calendar.= '<td class="calendario" onclick=alert('.$message.')>'.$contador_dias.'</td>';
			$contador_dias++;
			$contador_dias_misma_semana++;
			
			if($contador_dias_misma_semana > 7){ //si ya hemos rellenado esta fila, pasa a la siguiente
				$calendar.= "</tr><tr>"; 
				$contador_dias_misma_semana = 1; 
			}
			
		}
		
		//rellena los huecos que queden en blanco y cierra el calendario
		while($contador_dias_misma_semana <= 7){
			$calendar.= "<td></td>";
			$contador_dias_misma_semana++;
		}
		$calendar.= "</tr></table>";
		return $calendar;
	}
}
?>
