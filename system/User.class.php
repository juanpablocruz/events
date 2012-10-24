<?php
require_once "connect.php";

/*
 * This Class is designed to control the user data. 
 * $Data is a matrix which has: "Perfil","Grupos","Eventos","Mensajes", and "Friends" 
 * data from the database to avoid unnecesary queries to it.
 * 
 * ---------------------------------------------------------------------------------------*
 * 
 * Its diferents functions take care of updating each field and 
 * Listen() is intended to keep peeking the database for the last message and checks if
 * its a new one or there's no news. If the message is new it means that there are at least 
 * one update so it calls Update() which update the necesary fields.
 * 
 * The instantation of this class takes the UserId argument.
 * 
 * ----------------------------------------------------------------------------------------*
 * There are two functions outside the class which manages the class movement through
 * sessions.
 * 
 * SaveUser($user) takes the instantiated class as argument and then saves 
 * the object in the session variable "CLASE"
 * 
 * LoadUser just takes the variable from the session, reconverts it into an object of 
 * Usuario and the returns it.
 * -------------------------------------------------------------*
 * Estructure:
 * Data-> |-Friends ->| Sigo -->| id
 * 		  |			  |			| Name
 *  	  |			  |			| LastName
 *  	  |			  |			| Email
 *  	  |			  |			| Grupo --> |idGroup
 * 	 	  |			  |						|idCreator
 * 	 	  |			  |						|Group_Name
 * 	 	  |			  |						|type
 * 		  |		      | Seguido-->| id
 * 		  |			  |			  | Name
 *  	  |			  |			  | LastName
 *  	  |			  |			  | Email
 *  	  |			  |			  | Grupo --> |idGroup
 * 	 	  |			  |				    	  |idCreator
 * 	 	  |			  |						  |Group_Name
 * 	 	  |			  |						  |type
 * 		  |
 *        |-Perfil -> |UserId
 * 		  |			  |Password
 * 		  |			  |Name
 *  	  | 		  |LastName
 *  	  |			  |Phone
 *  	  |			  |Birthday
 *  	  |			  |Email 
 * 		  |			  |Img
 * 		  |			  |RegisterDay
 *  	  |			  |Auth_Key
 *  	  |			  |Active
 *  	  |			  |Online
 *  	  |			  |LastLog  
 *  	  |			  |Adress  
 *        |
 *        |-Eventos ->|IdEvent => array
 * 		  |			  |UserId => array
 * 		  |			  |Status => array
 *        |
 *        |-Mensajes->|IdMessage => array
 * 		  |			  |IdWriter => array
 * 		  |			  |IdReader => array
 * 		  |			  |Status => array
 * 		  |			  |Type => array
 *        |
 *        |-Grupos    |Mios ->|IdGroup => array
 * 		  |			  |		  |IdCreator => array
 * 		  |			  |		  |Open => array
 * 		  |			  |		  |Group_Name => array
 * 		  |			  |		  |Type => array
 * 		  |           |	
 * 		  |           |Amigos->|IdGroup
 * 		  |			  |		   |IdOwner
 * 		  |           |        |Group_Name	
 * 		  |           |        |type	 
 * 
 * -------------------------------------------------------------*
 * Ussage:														*
 * -----------																*
 * SaveUser($User); This line saves the object (PageA)			*
 * $LoadedUser = LoadUser(); This line loads the object (PageB)	*
 * 																*
 * -------------------------------------------------------------*
 * 
 * ---------------------------------------------------------------------------------------*
 * 
 * @author		Juan Pablo Cruz <pablo@cruzf.net>
 * @copyright (c) 2012 E-vents.
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * 
 */

class Usuario{
	public $Data;
	public $id;
	private $iterador = 0;
	public $Listadeamigos =array();
	
	public function __construct($id = 8){
		if(!isset($_SESSION))
			session_start();
		$this->id = $id;
		if(!isset($Data["Perfil"]))
			$this->Data["Friends"] = array();
			$this->Data["Friends"]["Sigo"] = array();
			$this->Data["Friends"]["Seguido"] = array();
			$this->Fill_Data();
			$this->Fill_Messages();
			$this->Fill_Events();
			$this->Fill_Groups();	
	}
	
	private function Fill_Data($tabla = "members",$IdField = "UserId")
	{
		switch($tabla){
			case "members":
				$Seccion = "Perfil";
				break;
			case "events":
				$Seccion = "Eventos";
				break;
			case "messages":
				$Seccion = "Mensajes";
				break;
			case "groups":
				$Seccion = "Grupos";
				break;
			default:
				Exit();
		}
		$querystring = "SELECT * FROM ".$tabla." WHERE ".$IdField." = ".$this->id;
		$query = mysql_query($querystring);
		
		while($campos = mysql_fetch_field($query)){
			$listadecampos[$campos->name] = $campos->name;
		}
		while($fetch_data = mysql_fetch_array($query)){	
			foreach($listadecampos as $nombre)
				if($Seccion!="Perfil")
					if($Seccion!="Grupos")
						$this->Data[$Seccion][$nombre][$this->iterador]= $fetch_data[$nombre];
					else
						$this->Data[$Seccion]["Mios"][$nombre][$this->iterador]= $fetch_data[$nombre];
				else
					$this->Data[$Seccion][$nombre]= $fetch_data[$nombre];				
			if($Seccion!="Perfil")
				$this->iterador++;
		}
	}
	private function Fill_Groups_Followed(){
		$this->Data["Grupos"]["Amigos"] = array();
		$i = 0;
		foreach($this->Data["Friends"]["Sigo"] as $amigo){
			$this->Data["Grupos"]["Amigos"][$i] = $amigo["Grupo"];
			$this->Data["Grupos"]["Amigos"][$i]["IdOwner"] = $amigo[0];
			$i++;
		}
	}
	
	private function Fill_Relations($idgroup = false){
		if(!$idgroup)
		{
			$i = 0;
			$query = mysql_query("SELECT * FROM relations JOIN members on relations.IdFollower = members.UserId WHERE relations.IdFollowed = ".$this->id);

			while($friend = mysql_fetch_array($query))
			{
				$this->Data["Friends"]["Seguido"][$i] = array("Name"=>$friend["Name"],"LastName"=>$friend["LastName"],"Email"=>$friend["Email"]);
				array_push($this->Listadeamigos,$friend["Name"]." ".$friend["LastName"]);
				$grupo = mysql_fetch_array(mysql_query("SELECT * FROM groups WHERE IdGroup = ".$friend["IdGroup"]));
				$this->Data["Friends"]["Seguido"][$i]["Grupo"] = array($friend["IdGroup"],$grupo["Group_Name"],$grupo["Open"]);				
				$i++;
			}
			$i = 0;
			$query = mysql_query("SELECT * FROM relations JOIN members on relations.IdFollowed = members.UserId WHERE relations.IdFollower = ".$this->id);
			while($friend = mysql_fetch_array($query))
			{
				$this->Data["Friends"]["Sigo"][$i] = array($friend["UserId"],"Name"=>$friend["Name"],"LastName"=>$friend["LastName"],$friend["Email"]);
				array_push($this->Listadeamigos,$friend["Name"]." ".$friend["LastName"]);
				$grupo = mysql_fetch_array(mysql_query("SELECT * FROM groups WHERE IdGroup = ".$friend["IdGroup"]));
				$this->Data["Friends"]["Sigo"][$i]["Grupo"] = array($friend["IdGroup"],$grupo["Group_Name"],$grupo["Open"]);
				$i++;
			}			
		}
		$this->Fill_Groups_Followed();
	}
	public function Fill_Messages(){
		$this->iterador=0;
		$this->Fill_Data("messages","IdWriter");
		$this->Fill_Data("messages","IdReader");
		
	}
	public function Fill_Events(){
		$this->iterador=0;
		$this->Fill_Data("events","UserId");	
	}
	public function Fill_Groups(){
		$this->iterador=0;
		$this->Fill_Data("groups","IdCreator");
		$this->Fill_Relations();
	}
	public function Update(){
		$User->Fill_Messages();
		$User->Fill_Events();
		$User->Fill_Groups();
	}
	public function Listen(){
		$query = mysql_query("SELECT * FROM messages WHERE IdReader =".$this->id." OR IdWriter =".$this->id." ORDER BY IdMessage DESC");
		$mensaje = mysql_fetch_array($query);
		if(!in_array($mensaje["IdMessage"],$this->Data["Mensajes"]["IdMessage"])){
			$this->Update();
			echo "Ey hay novedades";
		}
	}
}
/*-------------------------------------------------
 * Functions to pass the object through sessions.
 -------------------------------------------------*/
 
function SaveUser($user){
	$_SESSION["CLASE"] = serialize($user);
}

function LoadUser(){
	return unserialize($_SESSION["CLASE"]);
}

/*------------------------------------------------*/

//$User = new Usuario(PUT HERE YOUR ID);
//$User = new Usuario();


//echo "<H1>CONTENIDO DE Data DE LA CLASE Usuario</H1>";
//var_dump($User->Data["Friends"]);

//USE THIS FUNCTION TO LISTEN FOR UPDATES
//$User->Listen();

/*For passing the user through pages.(PageA -> PageB)
SaveUser($User); This line saves the object (PageA)
$LoadedUser = LoadUser(); This line loads the object (PageB)
*/
?>
