<?php
	session_start();
	if (!isset($_SESSION['CLASE'])) header("location:index.php");
			$basepath = "system";
		require_once $basepath."/connect.php";

		require_once $basepath."/user.class.php";
	$q=$_GET["q"];
	$datetime = new DateTime;
	$date = $datetime->format('Y/m/d H:i:s');
	$id = $_SESSION['k_UserId'];
	switch($q){
		case 0:
			$qGetEvents = mysql_query("SELECT * FROM `events` JOIN listevents ON events.IdEvent = listevents.IdEvent WHERE events.UserId = $id 
									AND (events.Status = '0' OR events.Status = '1') AND listevents.ExpireDate > '$date'");
			break;
		case 1:
			$qGetEvents = mysql_query("SELECT * FROM `events` JOIN listevents ON events.IdEvent = listevents.IdEvent WHERE events.UserId = $id 
									AND events.Status = '2' AND listevents.ExpireDate > '$date'");
			break;
		case 2:
			$qGetEvents = mysql_query("SELECT * FROM `events` JOIN listevents ON events.IdEvent = listevents.IdEvent WHERE events.UserId = $id 
									AND events.Status = '3' AND listevents.ExpireDate > '$date'");
			break;
		case 3:
			$qGetEvents = mysql_query("SELECT * FROM `listevents` WHERE IdCreator = $id AND ExpireDate > '$date'");
			break;
		
		case 4:
			$qGetEvents = mysql_query("SELECT * FROM `events` JOIN listevents ON events.IdEvent = listevents.IdEvent WHERE events.UserId = $id 
									AND events.Status = '3' AND listevents.ExpireDate < '$date'");
			break;
	}
		
	echo "<table border='1'>
	<tr>
	<th>NOMBRE</th>
	<th>DESCRIPCIÓN</th>
	<th>FECHA DE CREACIÓN</th>
	<th>FECHA DE EVENTO</th>
	<th>LUGAR</th>
	</tr>";
	$x=0;
	while($row = mysql_fetch_array($qGetEvents) and $x<10)
	{
	  echo "<tr>";
	  echo "<td>" . $row['Name'] . "</td>";
	  echo "<td>" . $row['Description'] . "</td>";
	  echo "<td>" . $row['StartDate'] . "</td>";
	  echo "<td>" . $row['ExpireDate'] . "</td>";
	  echo "<td>" . $row['Place'] . "</td>";
	  echo '<td>';
			switch ($q){
				case 0:
					echo '
						<form>
						<select name="events" onchange="editEvents(this.value)">
						<option value="">Confirmar:</option>
						<option value="1_'. $row['IdEvent'] .'">Asistir</option>
						<option value="2_'. $row['IdEvent'] .'">Rechazar</option>
						</select>
						</form>
						';
					break;
				case 1:
					echo '
						<form>
						<select name="events" onchange="editEvents(this.value)">
						<option value="">Rechazar:</option>
						<option value="2_'. $row['IdEvent'] .'">Confirmar</option>
						<option value="1_'. $row['IdEvent'] .'">Cancelar</option>
						</select>
						</form>
						';
					break;
				case 2:
					echo '
						<form>
						<select name="events" onchange="editEvents(this.value)">
						<option value="">Asistir:</option>
						<option value="1_'. $row['IdEvent'] .'">Confirmar</option>
						<option value="2_'. $row['IdEvent'] .'">Cancelar</option>
						</select>
						</form>
						';
					break;
			}
	  echo '</td> </tr>';
	  
	  $x++;
	  }
	echo "</table>";	
?>