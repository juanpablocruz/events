<?php
class Usuarios
{
    public function  __construct() {
		$path = "system";
		require_once $path."/connect.php";
		require_once $path."/Buscador.class.php";
		require_once $path."/user.class.php";
		if(!isset($_SESSION))session_start();

	}
	
	function juntarnombresyapellidos($a, $b){
		return $a." ".$b;
	}
	
	public function pruebaaaa($nombreUsuario){
		$datos = array();

        $sql = "SELECT * FROM members
                WHERE Name LIKE '%$nombreUsuario%'";

        $resultado = mysql_query($sql);

        while ($row = mysql_fetch_array($resultado, MYSQL_ASSOC)){
            $datos[] = array("value" => $row['Name']);
        }
		var_dump($datos);
	}

	//en el futuro que use el busca de buscador ad+ del LIKE pa secuencias cortas
    public function buscarUsuario($nombreUsuario){
		/*
		$datos = array();
		$u = LoadUser();

        $sql = "SELECT * FROM members
                WHERE Name LIKE '%$nombreUsuario%'";

        $resultado = mysql_query($sql);

        while ($row = mysql_fetch_array($resultado, MYSQL_ASSOC)){
            $datos[] = array("value" => $row['Name']);
        }
		
        return $datos;
		*/
		/*
		$amigos = $user->Data["Friends"];
		$nombresyapellidos = array();
		$nombresyapellidos2 = array();
		if($amigos["Seguido"] != null){
			$nombres = array();
			$i=0;
			var_dump($amigos["Sigo"]);
			while($i < count($amigos["Sigo"])){
				$nombres[$i] = $amigos["Sigo"][$i]["Name"];
				$i++;
			}
			$apellidos = array();
			
			$i=0;
			while($i < count($amigos["Seguido"])){
				$apellidos[$i] = $amigos["Seguido"][$i]["LastName"];
				$i++;
			}
				
			$nombresyapellidos = array_map("juntarnombresyapellidos",$nombres,$apellidos);
		}
		
		if($amigos["Seguido"] != null){
			$nombres = array();
			$i=0;
			var_dump($amigos["Seguido"]);
			while($i < count($amigos["Seguido"])){
				$nombres[$i] = $amigos["Seguido"][$i]["Name"];
				$i++;
			}
			$apellidos = array();
			
			$i=0;
			while($i < count($amigos["Seguido"])){
				$apellidos[$i] = $amigos["Seguido"][$i]["LastName"];
				$i++;
			}
				
			$nombresyapellidos2 = array_map("juntarnombresyapellidos",$nombres,$apellidos);
		}
		$amigos = array_merge($nombresyapellidos,$nombresyapellidos2);
		
		
		$qry = mysql_query("SELECT name,lastname FROM members");
		
		$resto = array();
		while($row = mysql_fetch_array($qry)) {
			array_push($resto, $row['name']." ".$row['lastname']);
		}
		return array_merge($buscador->busca($busqueda,$amigos),$buscador->busca($busqueda,$resto)));
		*/
		$buscador = new Buscador();
		$user = LoadUser();
		$amigos = $user->Data["Friends"];
		$nombres = array();
		$nombres2 = array();
		if($amigos["Sigo"] != null){
			$i=0;
			while($i < count($amigos["Sigo"])){
				$nombres[$i] = $amigos["Sigo"][$i]["Name"];
				$i++;
			}
		}
		
		if($amigos["Seguido"] != null){
			$i=0;
			while($i < count($amigos["Seguido"])){
				$nombres2[$i] = $amigos["Seguido"][$i]["Name"];
				$i++;
			}
		}
		$amigos = array_merge($nombres,$nombres2);
		
		
		$qry = mysql_query("SELECT name,lastname FROM members");
		
		$resto = array();
		while($row = mysql_fetch_array($qry)) {
			array_push($resto, $row['name']);
		}
		$qwe = array_merge($buscador->busca($nombreUsuario,$amigos),$buscador->busca($nombreUsuario,$resto));
		$ewq = array();
		$i = 0;
		while($i < count($qwe)){
			$ewq[$i] = array("value" => $qwe[0][0]);
			$i++;
		}
		return $ewq;
	}
}
?>