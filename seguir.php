<?php session_start();
if (isset($_SESSION['CLASE'])) {
  }
  else header("location:index.php");
		require_once $basepath."/connect.php";
		require_once $basepath."/user.class.php";
		require_once $basepath."/eventsfunctions.php";		
		require_once $basepath."/mensaje.class.php";
$id = $_SESSION['k_UserId'];
$follow = $_GET['user'];
$grupo = $_GET['grupo'];
$error = '';
$sCheckStatus = "SELECT Open,IdGroup,UserId FROM groups JOIN members ON groups.IdCreator = members.UserId WHERE members.Name = '$follow' AND groups.Group_Name = '$grupo'";
$qCheckStatus = mysql_query($sCheckStatus) or $error .= DebugQuery('qCheckStatus',$sCheckStatus,'Seguir.php');
if($error == ''){
$row = mysql_fetch_array($qCheckStatus);
if($row['UserId'] != $id)
{
	$relations[] = '';
	$i = 0;
	$idGroup = $row['IdGroup'];
	$sCheckRelation = "SELECT * FROM relations WHERE IdFollower = $id AND IdGroup = $idGroup";
	$qCheckRelation = mysql_query($sCheckRelation) or $error .= DebugQuery('qCheckRelation',$sCheckRelation,'Seguir.php');
	while($coincidence = mysql_fetch_array($qCheckRelation)){
		$relations[$i] = $coincidence['IdFollower'];
		$i++;
	}
	if(!in_array($id,$relations)){
		if($row['Open'] == 1)
		{		
			$idFollowed = $row['UserId'];
			
			$qFollow = mysql_query("INSERT INTO `relations`(`IdFollower`, `IdFollowed`, `IdGroup`) VALUES ($id,$idFollowed,$idGroup)");
			SendSysMessage($id,$idFollowed,$idGroup,1);
			echo
				"<SCRIPT LANGUAGE='javascript'>
					 location.href = 'main.php?p=contactos';
				 </script>";
		 }
		 else
		 {
		 /*	echo "<SCRIPT LANGUAGE='javascript'>
			$('#FollowRequest').load(
				'private.php?id='".$row['UserId']."'&idg='".$row['IdGroup']."'&grupo ='".$grupo."'
			 )</script>";*/
			echo $grupo;
			$mensaje = new MessageBox();
			echo $mensaje->NewMessage($row['UserId'],$row['IdGroup'],0);
		 }
	 }
	else{
		echo "Ya le sigues";
	} 
}
else
	 {
		echo
			"<SCRIPT LANGUAGE='javascript'>
				alert('No te sigas');
				location.href = 'main.php?p=contactos';
			 </script>";
	 }
}
else{
echo $error;
}
?>