<?php
class MessageBox{
	public function  __construct() {
		$basepath = "system";
		require_once $basepath."/connect.php";
		require_once $basepath."/user.class.php";
	}
	function Destination($IdReader)
		{
			$id = $_SESSION["k_UserId"];
			$aListppl[]='';
			$i=0;
			if($IdReader == 0){
				$qGetFollowers = mysql_query("SELECT * FROM relations WHERE IdFollowed = $id OR IdFollower = $id");	
				$ToList= "<ul>";
				while ($aFollower = mysql_fetch_array($qGetFollowers)){
					if($aFollower['IdFollowed']==$id)
					{
						$tNombreGrupo = $aFollower["IdGroup"];
						$iFollowerId = $aFollower["IdFollower"];
						if(!in_array($iFollowerId,$aListppl)){
							$aListppl[$i]=$iFollowerId;
							$i++;
							$qSelectName = mysql_query("SELECT Name,UserId FROM members WHERE UserId = $iFollowerId");
							$aNames = mysql_fetch_row($qSelectName);
							$ToList.= "<li><div><INPUT TYPE=radio NAME='whom' value=".$aNames[1].">".$aNames[0]."</div></li>";					
						}
					}
					if($aFollower['IdFollower']==$id)
					{
						$tNombreGrupo = $aFollower["IdGroup"];
						$iFollowerId = $aFollower["IdFollowed"];
						if(!in_array($iFollowerId,$aListppl)){
							$aListppl[$i]=$iFollowerId;
							$i++;
							$qSelectName = mysql_query("SELECT Name,UserId FROM members WHERE UserId = $iFollowerId");
							$aNames = mysql_fetch_row($qSelectName);
							$ToList.= "<li><div><INPUT TYPE=radio NAME='whom' value=".$aNames[1].">".$aNames[0]."</div></li>";
							$ToList.="<input type='hidden' value=0 name='Type'>";
						}
					}	
				}
				$ToList.= "</ul>";
			}
			else{
				$qGetUser = mysql_query("SELECT Name,LastName FROM members WHERE UserId =$IdReader ");
				$User = mysql_fetch_array($qGetUser);
				$ToList="<input type='hidden' value=".$IdReader." name='whom'><label>Peticion de amistad a: ".$User['Name']." ".$User['LastName']."</label>";
				$ToList.="<input type='hidden' value=5 name='Type'>";
			}
			return $ToList;
		}

	function NewMessage($IdReader,$IdGroup,$Type){
		
		$mensaje =
			"<div id='NewMessage' class='Message' onclick= apagar(event,1)><form action='sendmessage.php' method='POST' onclick= mantener(event)>
			<div id='Destination' onclick= mantener(event)>";
			$mensaje.= $this->Destination($IdReader,$Type);
			
			$mensaje.="</div>
			<div id='ElMensaje' class='Message'>
				<TEXTAREA NAME='Text' COLS=40 ROWS=6></TEXTAREA>
			</div>";
		if($IdGroup != 0){
			$mensaje .= "<input type='hidden' value=$IdGroup name='IdGroup'>";
		}
		$mensaje.= "	
			<input type='SUBMIT' value='send'>
			</form></div>";
		return $mensaje;	
	}
}

?>