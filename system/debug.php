<?php
function DebugQueries($query, $querytext,$function)
{
	$report = "<div style='border:1px solid #c0ccda;margin:5em;padding:1em;'><img src='css/ackbar_icon.png' style='display:inline-block'><span>¡ES UNA TRAMPA!</span>
	<p>No se han podido ejecutar el query ";
	$report .= $query."	de la funcion ".$function."</p><p>Este es el query: <br>".$querytext."</p>";
	//$report .= CheckDatabase($report,'members','UserId');
	$report .= Q($querytext);
	$report .= "</div>";
	return $report;
}
function Debug($problem,$page,$value)
{
	$report = "<div style='border:1px solid #c0ccda;margin:5em;padding:1em;'><img src='css/ackbar_icon.png' style='display:inline-block'><span>¡ES UNA TRAMPA!</span>
	<p>Error en: ";
	$report .= $page.".	".$problem." valor: ".$value."</p>";
	$report .= "</div>";
	return $report;
}
function CheckDatabase($report,$field,$value){
	/*
	//Case of database problems
	//prints the database architecture
	*/
	$tables = mysql_query("SHOW TABLES FROM events");
	$report .="<p>La estructura de la base de datos es: </p>";
	$report .= "Tables<ul>";
	while($table = mysql_fetch_row($tables)){
		for($i = 0;$i<sizeof($table);$i++) {$report .= "<li>".$table[$i]."</li>";
			$tablename = $table[$i];
			$col = mysql_query("SELECT * FROM $tablename");
			$report .= "<ul>";
			$j = 0;
			$row = mysql_fetch_row($col);
			while($j<sizeof($row)){
				$colname = mysql_field_name($col,$j);
				$report .= "<li>".$colname."</li><ul>";
				if ($tablename == $field){				//Shows only the members tables
					while($row = mysql_fetch_row($col)){
						if($colname == $value){			//Shows only the UserId cows
						$report .= "<li>".$row[0]."</li>";
						}
					}
				}
				$report .= "</ul>";
				$j++;
			}
			$report .= "</ul>";
			}
	}
	$report .= "</ul>";
	return $report;
}
function Q($query)
{
	/*
	 * First check the query char by char to find solitary quotes
	 */
	$sugest = '';
	$count = 0;
	$len = strlen($query);
	for($i = $len-1;$i==0;$i--)
	{
		if($query[$i] == "'")$count++;
	}
	if($count%2 != 0){
			$sugest .= "Sugerencia: Hay un error,no todas las comillas estan bien cerradas";
			return $sugest;
		}	
	/*
	 * Second, search for references to dates or id, in case of dates check if the value is properly encased in "'"
	 * In case of being a Id value, check if its without "'"
	 * Search for encased values by default
	 */
	 
}

?>