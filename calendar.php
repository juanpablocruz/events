<?php
session_start();
if (!isset($_SESSION['CLASE'])) header("location:index.php");
		require_once "system/connect.php";
		require_once "system/eventsfunctions.php";
		include_once "variables.php";

	require_once "system/Calendario.class.php";
	
	$mes = (int) (isset($_GET['mes']) ? $_GET['mes'] : date('m'));
	$ano = (int)  (isset($_GET['ano']) ? $_GET['ano'] : date('Y'));
	
	$mes_siguiente = '<a href="?mes='.($mes != 12 ? $mes + 1 : 1).'&ano='.($mes != 12 ? $ano : $ano + 1).'" title="Mes siguiente" class="control nexM"></a>';//&gt;
    $mes_anterior = '<a href="?mes='.($mes != 1 ? $mes - 1 : 12).'&ano='.($mes != 1 ? $ano : $ano - 1).'" title="Mes anterior"  class="control prevM"></a>';//&lt;
    $ano_siguiente = '<a href="?mes='.$mes.'&ano='.($ano + 1).'" title="Año siguiente" class="control nexY"></a>';//&raquo;
    $ano_anterior = '<a href="?mes='.$mes.'&ano='.($ano - 1).'" title="Año anterior" class="control prevY"></a>';//&laquo


	$f = '<form method="post"><span class="laquo flecha">'.$ano_anterior.'</span>&nbsp;&nbsp;<span class="lt flecha">'.$mes_anterior.'</span>&nbsp;&nbsp;<span class="gt flecha">'.$mes_siguiente.'</span>&nbsp;&nbsp;<span class="raquo flecha">'.$ano_siguiente.'</span></form>';
	echo $f;

	$mi_calendario = new Calendario();
	echo $mi_calendario->draw_calendar($mes,$ano);
?>
