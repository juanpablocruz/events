<?php
require_once "system/User.class.php";
require_once "system/connect.php";
require_once "Debug.php";

function GetEvents()
{
	/*
	Get all events that has not passed yet
	returns: array -> array[i]['campo']
	*/
	$datetime = new DateTime;
	$date = $datetime->format('Y/m/d H:i:s');
	$arrayEvents = array( );
	$error = '';
	$id = $_SESSION['k_UserId'];
	$szGetEvents = "SELECT * FROM `events` JOIN listevents ON events.IdEvent = listevents.IdEvent WHERE events.UserId = $id AND listevents.ExpireDate > '$date'";
	$qGetEvents = mysql_query($szGetEvents)
		or die($error=DebugQueries('$qGetEvents',$szGetEvents,'GetEvents'));
	if($error=='')	{
		for($i=0;$i<mysql_num_rows($qGetEvents); $arrayEvents[$i++]= mysql_fetch_array($qGetEvents));
	}
	else{
	echo $error;
	}	
	return $arrayEvents;
}
function GetDaysEvents($day)
{
	/*
	Get the events happening on the supplied date.
	Relies on ExpireDate being the start date of the event.
	*/
	$id = $_SESSION['k_UserId'];
	$error = '';
	$szdaysEvents = "SELECT * FROM `events` JOIN listevents ON events.IdEvent = listevents.IdEvent WHERE events.UserId = '$id' AND DATE(listevents.ExpireDate) = DATE(from_unixtime($day))";
	$daysEvents = mysql_query($szdaysEvents)or die($error=DebugQueries('$daysEvents',$szdaysEvents,'GetDaysEvents'));	
	$arrayEvents = array();
	if($error=='')	{
		for($i=0; $i < mysql_num_rows($daysEvents); $arrayEvents[$i++] = mysql_fetch_array($daysEvents));
	}
	else{
	echo $error;
	}
	return $arrayEvents;
}
function GetNearEvents($arrayEvents,$day)
{
	/*
	Get next events
	@param array -> array[i]['campo']
	returns: array -> array[1][0]['campo']
	*/
	//añadir criba 
	$NearEvents[]='';
	$date1 = new DateTime;
	$date1->add(new DateInterval('P'.$day.'D'));
	$date = $date1->format('Y/m/d');
	$i = 0;
	$j = 0;
	while($i<sizeof($arrayEvents))
	{	
		if($arrayEvents[0])
		{
			$fecha = explode(' ',$arrayEvents[$i]['ExpireDate']);
			if(strtotime($fecha[0]) == strtotime($date) AND ($arrayEvents[$i]['Status'] == 0 OR $arrayEvents[$i]['Status'] == 2))
				{
					$NearEvents[$j] = $arrayEvents[$i];	
					$j++;
				}	
		}
		$i++;	
	}
	return $NearEvents;
}
function GetNotifications()
{
	$Notificaciones[0] = '';
	$Notificaciones[1] = '';
	$id = $id = $_SESSION['k_UserId'];
	$i = 0;
	/*Get New User and system messages
	//A message Type = 0 means that the message was sent from another user
	//A message Type = 1 means that the message is a system message and carries relations information
	//where IdCreator stands for who is now following you and Status = 0 means that the notification is new
	//and Text carries the group with the new follower.
	//A message Type = 2 indicates that its a system message and carries friends news described in Text field
	*/
	$error='';
	$szGetNewMessages = "SELECT messages.IdMessage,messages.IdWriter,messages.IdReader,messages.Type,listmessages.Text FROM messages JOIN listmessages ON
									listmessages.IdMessage = messages.IdMessage WHERE (messages.IdReader = $id OR  messages.IdWriter = $id) AND Status = 0";
	$qGetNewMessages = mysql_query($szGetNewMessages)
			
			or $error = DebugQueries('qGetNewMessages',$szGetNewMessages,'GetNotifications');
	
	if($error==''){
	while($NewMessage = mysql_fetch_array($qGetNewMessages))
		{	
			if($NewMessage['Type'] != 5){
				if($NewMessage['Type'] == 0 && $NewMessage['IdWriter'] != $id)
				{
				$Notificaciones[0][$i] = $NewMessage;
				$i++;
				}
				if($NewMessage['Type'] != 0)
				{
				$Notificaciones[0][$i] = $NewMessage;
				$i++;
				}
				}
		}
		}
		else{
		echo $error;
		}
	//Get New events
	$j=0;
	$error1='';
	$szGetNewEvents = "SELECT * FROM events JOIN listevents ON events.IdEvent = listevents.IdEvent WHERE UserId = $id AND Status = 0";
	$qGetNewEvents = mysql_query($szGetNewEvents)
		or $error1 = DebugQueries('qGetNewEvents',$szGetNewEvents,'GetNotifications');
	if($error1==''){
	while($NewEvent = mysql_fetch_array($qGetNewEvents))
		{
			$Notificaciones[1][$j] = $NewEvent;
			$j++;		
		}
		}
		else{
		echo $error1;
		}
	return $Notificaciones;
}
function GetNewMessages()
{
	$id = $_SESSION['k_UserId'];
	$i = 0;
	$error='';
	$ListMessages[]='';
	$szMessage = "SELECT * FROM messages WHERE IdReader = $id AND status = 0 AND Type =0";
	$qMessage =mysql_query($szMessage)
		or $error = DebugQueries('qMessage',$szMessage,'GetNewMessages');
	if($error==''){
		while ($message = mysql_fetch_array($qMessage)) 
		{
			$ListMessages[$i] = $message;
			
			$i++;
		}
	}
	else{
		echo $error;
	}
		return $ListMessages;
}
function GetFriendshipR()
{
	$id = $_SESSION['k_UserId'];
	$i = 0;
	$error='';
	$ListMessages[]='';
	$szMessage = "SELECT * FROM messages JOIN listmessages ON messages.IdMessage = listmessages.IdMessage 
	WHERE messages.IdReader = $id AND messages.status = 0 AND messages.Type =5";
	
	$qMessage =mysql_query($szMessage)
		or $error=DebugQueries('qMessage',$szMessage,'SendSysMessage');
	
	if($error==''){
		while ($message = mysql_fetch_array($qMessage)) 
		{
			$ListMessages[$i] = $message;
			
			$i++;
		}
	}
	else{
		echo $error;
	}
	return $ListMessages;
}
function GetFollowers()
{
	/*
		Get Followers number
		returns: int
	*/
	$id = $_SESSION['k_UserId'];
	$j = 0;
	$aListppl[]='';
	$error = '';
	$szFollowed = "SELECT * FROM relations WHERE IdFollowed = $id";
	$qFollowed =mysql_query($szFollowed)
		or $error=DebugQueries('qFollowed',$szFollowed,'GetFollowers');
	if($error == ''){	
		while ($User = mysql_fetch_array($qFollowed))
		{
			if(!in_array($User['IdFollower'],$aListppl)){
			$aListppl[$j] = $User['IdFollower'];
			$j++;
			}
		}
	}
	else{
		echo $error;
	}	
	return $j;
};

function GetFollowed()
{
	/*
		Get Followed users number
		returns: int
	*/
	$id = $_SESSION['k_UserId'];
	$j = 0;
	$aListppl[]='';
	$error = '';
	$szFollower = "SELECT * FROM relations WHERE IdFollower = $id";
	$qFollower =mysql_query($szFollower)or $error=DebugQueries('qFollower',$szFollower,'GetFollowed');
	if($error == ''){
		while ($User = mysql_fetch_array($qFollower))
		{
			if(!in_array($User['IdFollowed'],$aListppl)){
			$aListppl[$j] = $User['IdFollowed'];
			$j++;
			}
		}
	}
	else{
		echo $error;
	}		
	return $j;
};
function SendSysMessage($IdFollower,$IdFollowed,$IdGroup,$type)
{
	/*
	Sends a system message in order to make notifications.
	@params	$IdFollower int
	@params $IdFollowed int
	@params $IdGroup int
	@params $type int (a type of 1 stands for relation activity and a type of 2 stands for friends activity)
	return: none
	*/
	$error = '';
	$szGetInfo = "SELECT Group_Name FROM groups WHERE IdGroup = $IdGroup";
	$qGetInfo = mysql_query($szGetInfo) or $error=DebugQueries('qGetInfo',$szGetInfo,'SendSysMessage');
	if($error==''){
	$qGName = mysql_fetch_array($qGetInfo);
	$GroupName = $qGName['Group_Name'];
	$datetime = new DateTime;
	$date = $datetime->format('Y/m/d H:i:s');
	$szSendMsg1 = "INSERT INTO `listmessages`(`IdWriter`, `IdReader`, `DateWrited`, `Text`)
							VALUES ('$IdFollower','$IdFollowed','$date','$GroupName')";
	$SendMsg1 = mysql_query($szSendMsg1)
							or $error=DebugQueries('SendMsg1',$szSendMsg1,'SendSysMessage');
	if($error==''){	
	$szGetIdMsg = "SELECT IdMessage FROM listmessages WHERE IReader = $IdFollowed AND IdWriter = $IdFollower AND DateWrited = '$date'";
	$qGetIdMsg = mysql_fetch_array(mysql_query($szGetIdMsg))or $error=DebugQueries('qGetIdMsg',$szGetIdMsg,'SendSysMessage');
	$IdMsg = $qGetIdMsg['IdMessage'];
	if($error==''){
	$SendMsg1 = mysql_query("INSERT INTO `messages`(`IdMessage`, `IdWriter`, `IdReader`, `Status`, `Type`) 
							VALUES ('$IdMsg','$IdFollower','$IdFollowed','0','$type')");
		}
	}
	}
	else{
		echo $error;
	}		
};
function SendUserMessage($id,$User,$IdGroup,$Text,$Type)
{
	/*
	Sends a user message.
	@params	$id int
	@params $User int
	@params $Text string
	@params $Type int A type of 0 stands for user message, a type of 5 stands for a friendship request
					  a type of 3 stands for a post in a forum.
	return: none
	*/
	$datetime = new DateTime;
	$date = $datetime->format('Y/m/d H:i:s');
	$errors='';
	if ($Type==5)$Text.="_".$IdGroup;
	$szSendMsg1 = "INSERT INTO `listmessages`(IdWriter, IdReader, DateWrited, `Text`)
							VALUES ($id,$User,'$date','$Text')";
	$SendMsg1 = mysql_query($szSendMsg1)
		or ($errors=DebugQueries('SendMsg1',$szSendMsg1,'SendUserMessage'));
		
	$szGetIdMsg ="SELECT IdMessage FROM listmessages WHERE IdReader = $User AND IdWriter = $id AND DateWrited = '$date'";	
	$qGetIdMsg = mysql_fetch_array(mysql_query($szGetIdMsg))
	 or $errors =DebugQueries('qGetIdMsg',$szGetIdMsg,'SendUserMessage');
	 
	$IdMsg = $qGetIdMsg['IdMessage'];
	$szSendMsg2 = "INSERT INTO `messages`(IdMessage, IdWriter, IdReader, Status, Type) 
							VALUES ($IdMsg,$id,$User,0,$Type)";
	$SendMsg2 = mysql_query($szSendMsg2)
		or ($errors=DebugQueries('SendMsg2',$szSendMsg2,'SendUserMessage'));
	return $errors;						
};
function ViewMessage($IdMessage)
{
/*
in progress
*/
echo $IdMessage;
};
function GetMessageList()
{
	/*
	returns: array of messages
	*/
	$id = $_SESSION['k_UserId']; 
	$Messages[] ='';
	$i = 0;
	$error = '';
	$szMessage = "SELECT * FROM messages JOIN listmessages ON messages.IdMessage = listmessages.IdMessage 
	WHERE messages.IdReader = $id AND messages.Type =0";
	
	$qMessage =mysql_query($szMessage)
		or $error =DebugQueries('qMessage',$szMessage,'GetMessageList');
		
	if($error==''){
		while ($message = mysql_fetch_array($qMessage)) 
		{
			$Messages[$i] = $message;
			
			$i++;
		}
	}
	else{
		echo $error;
	}
	//echo $error =DebugQueries('qMessage',$szMessage,'GetMessageList');
	return $Messages;
}
function CreateNewEvent($Id, $Name, $Description,$ExpireDate, $Place, $DMovil, $DMail, $DEvents){
	$error='';
	$datetime = new DateTime;
	$StartDate = $datetime->format('Y/m/d H:i:s');
	$szEvent = "INSERT INTO `listevents`(IdCreator, `Name`, `Description`, StartDate, ExpireDate, `Place`, DMovil, DMail, DEvents)
								VALUES ($Id, '$Name', '$Description', '$StartDate', '$ExpireDate', '$Place', $DMovil, $DMail, $DEvents)";
	$CreateEvent= mysql_query($szEvent)
					or $error .=DebugQueries('CreateEvent',$szEvent,'CreateNewEvent');
	if($error != '') echo $error;
	return $StartDate;
}
function SendNewEvent($Id, $User, $Status, $StartDate){
	$error = '';
	$qGetIdEvent = mysql_fetch_array(mysql_query("SELECT IdEvent FROM listevents WHERE IdCreator = $Id AND StartDate = '$StartDate' "));
	$IdEvent=$qGetIdEvent['IdEvent'];
	$szEvent = "INSERT INTO `events`(IdEvent, UserId, Status) VALUES ($IdEvent, $User, $Status)";
	$CreateEvent= mysql_query($szEvent )
					or $error .=DebugQueries('CreateEvent',$szEvent,'SendNewEvent');
	return $error;
	}
function AceptRequest($IdMessage)
{
	$errors='';
	$qGetMsgInfo = mysql_query("SELECT * FROM listmessages WHERE IdMessage = $IdMessage")or ($errors++);
	$qInfo = mysql_fetch_array($qGetMsgInfo);
	$IdGroup = explode("_",$qInfo['Text']);
	$IdGroup = $IdGroup[1];
	$IdFollower = $qInfo['IdWriter'];
	$IdFollowed = $qInfo['IdReader'];
	$szInsert = "INSERT INTO relations (`IdFollower`, `IdFollowed`, `IdGroup`, `Status`) VALUES($IdFollower,$IdFollowed,$IdGroup,0)";
	$qInsert = mysql_query($szInsert)
			or $error =DebugQueries('qInsert',$szInsert,'AceptRequest');
	if($error == ''){		
		$szAccepted = "DELETE FROM listmessages WHERE IdMessage = $IdMessage";
		$qAccepted = mysql_query($szAccepted)or $error =DebugQueries('qAccepted',$szAccepted,'AceptRequest');
		if($error == ''){
			$szAccepted1 = 	"DELETE FROM messages WHERE IdMessage = $IdMessage";
			$qAccepted1 = mysql_query($szAccepted1)
			or $error =DebugQueries('qAccepted1',$szAccepted1,'AceptRequest');
			if($error == ''){
			SendSysMessage($IdFollower,$IdFollowed,$IdGroup,1);
			}
		}
	}
	return $errors;
}
function SendPost($Post,$IdFollower,$IdGroup,$date,$id,$error){
	
	$sIdMsg = "SELECT IdMessage FROM listmessages WHERE IdWriter = $id AND DateWrited = '$date'";
	$qIdMsg = mysql_fetch_array(mysql_query($sIdMsg))
		or $error .= DebugQueries('qIdMsg',$sIdMsg,'SendPost');
	if($error==''){	
	$IdMessage = $qIdMsg['IdMessage'];
	$sInForo = "INSERT INTO `foro`(`IdGroup`, `IdMessage`, `UserId`) VALUES ($IdGroup,$IdMessage,$IdFollower)";
	$qInForo = mysql_query($sInForo)
		or $error .= DebugQueries('qInForo',$sInForo,'SendPost');
	}
	return $error;
}
function CreatePost($Post,$Id){
	$error ='';
	$grupo = $Post['grupo'];
	$sGrupoId = "SELECT IdGroup FROM groups WHERE Group_Name = '$grupo' AND IdCreator = $Id";
	$qGrupoId = mysql_fetch_array(mysql_query($sGrupoId))
		or $error .= DebugQueries('qGrupoId',$sGrupoId,'CreatePost');
	if($error == ''){
	$IdGroup = $qGrupoId['IdGroup'];
	$sGetFollowers = "SELECT * FROM members JOIN relations ON members.UserId=relations.IdFollower
					WHERE relations.IdGroup = $IdGroup";
	$qGetFollowers = mysql_query($sGetFollowers)
		or $error .= DebugQueries('qGetFollowers',$sGetFollowers,'CreatePost');
	if($error == '')
		{
		while($qGetFollower = mysql_fetch_array($qGetFollowers))
			{
				if($error ==''){
					$Text = $Post['Text'];
					$datetime = new DateTime;
					$date = $datetime->format('Y/m/d H:i:s');
					$sInList = "INSERT INTO `listmessages`(`IdReader`,`IdWriter`, `DateWrited`, `Text`) VALUES ($IdGroup,$Id,'$date','$Text')";
					$qInList = mysql_query($sInList)
						or $error .= DebugQueries('qInList',$sInList,'CreatePost');
					if($error==''){
						$IdFollower = $qGetFollower['UserId'];
						$error .= SendPost($Post,$IdFollower,$IdGroup,$date,$Id,$error);
					}
				}
			}
		}
	}
	return $error;	
}
function GetPosts($IdGroup){
	/*
	returns: array of messages
	*/
	$id = $_SESSION['k_UserId']; 
	$Messages[] ='';
	$i = 0;
	$error = '';
	$szMessage = "SELECT * FROM foro WHERE IdForo = $IdGroup";
	
	$qMessage =mysql_query($szMessage)
		or $error =DebugQueries('qMessage',$szMessage,'GetMessageList');
		
	if($error==''){
		while ($message = mysql_fetch_array($qMessage)) 
		{
			$Messages[$i] = $message;
			
			$i++;
		}
	}
	else{
		echo $error;
	}
	//echo $error =DebugQueries('qMessage',$szMessage,'GetMessageList');
	return $Messages;
}
?>