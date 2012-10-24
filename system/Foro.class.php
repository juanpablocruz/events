<?php
class Tema{
	public $Id;
	public $Padre = 0;
	public $FechaCreacion;
	public $Creador = array();
	public $Titulo;
	public $Estado;
	protected $IdForo;
	
	protected function Insertar(){
		$sql = "INSERT INTO foro(IdHilo,IdForo,Nivel,IdCreator,Estado,Titulo,FechaCreacion) VALUES
			('".$this->Id."','".$this->IdForo."','".$this->Padre."','".$this->Estado['idModerador']."','".$this->Estado['Estado']."','".$this->Titulo."','".$this->FechaCreacion."')";
			echo $sql;
	}
	public function __construct($IdForo){
		$this->IdForo = $IdForo;
	}	
	public function Crear($id,$user,$titulo){
		$datetime = new DateTime;
		$time = $datetime->format('Y/m/d H:i:s');
		$this->FechaCreacion = $time;
		$this->Id = $id;
		$this->Estado["Estado"] = "Abierto";
		$this->Estado["idModerador"] = $user["Id"];
		$this->Titulo = $titulo;
		$this->Creador["IdCreator"] = $user["Id"];
		$this->Creador["Name"] = $user["Name"];
		$this->Creador["LastName"] = $user["LastName"];
		$this->Insertar();
	}
	public function Borrar(){
		//
	}
	public function Cerrar($id){
		$this->Estado["Estado"] = "Cerrado";
		$this->Estado["idModerador"] = $id;
	}
}
class Categoria extends Tema{
	public function __construct($IdForo,$padre){
		$this->IdForo = $IdForo;
		$this->Padre = $padre;
	}
}

class Post extends Tema{
	public $Contenido;
	public $Respondido = 0;
	
	protected function Insertar(){
		$sql = "INSERT INTO foro(IdHilo,IdForo,Nivel,IdCreator,Estado,Titulo,Contenido,Respondido,FechaCreacion) VALUES
			('".$this->Id."','".$this->IdForo."','".$this->Padre."','".$this->Estado['idModerador']."','".$this->Estado['Estado']."','".$this->Titulo."','".$this->Contenido."','".$this->Respondido."','".$this->FechaCreacion."')";
			echo $sql;
	}
	
	public function __construct($IdForo,$padre){
		$this->IdForo = $IdForo;
		$this->Padre = $padre;
	}
	public function Crear($id,$user,$titulo,$contenido){
		$datetime = new DateTime;
		$time = $datetime->format('Y/m/d H:i:s');
		$this->FechaCreacion = $time;
		$this->Titulo = $titulo;
		$this->Id = $id;
		$this->Estado["Estado"] = "Abierto";
		$this->Estado["idModerador"] = $user["Id"];		
		$this->Creador["IdCreator"] = $user["Id"];
		$this->Creador["Name"] = $user["Name"];
		$this->Creador["LastName"] = $user["LastName"];
		$this->Contenido = $contenido;
	}		
}


$user["Id"] = 1;
$user["Name"]="Juan Pablo";
$user["LastName"] ="Cruz";
$i = 1;

$Tema = new Tema(5);
$Tema->Crear($i,$user,"Tema principal");
var_dump($Tema);
$i++;

$Categoria = new Categoria(5,$Tema->Id);
$Categoria->Crear($i,$user,"Categoria principal");
var_dump($Categoria);
$i++;

$Post1 = new Categoria(5,$Categoria->Id);
$Post1->Crear($i,$user,"Post de categoria","Probando un post dentro de una categoria");
var_dump($Categoria);
$i++;

$Post2 = new Categoria(5,$Tema->Id);
$Post2->Crear($i,$user,"Post de tema","Probando un post dentro de un tema");
var_dump($Categoria);
$i++;
?>