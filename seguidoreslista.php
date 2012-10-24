<?php session_start();
$basepath = "system";
		require_once $basepath."/connect.php";
		require_once $basepath."/user.class.php";

		
$grupo= $_GET['grupo'];
$id = $_GET['idUser'];
$qGetGroupId=mysql_query("SELECT IdGroup FROM groups WHERE Group_Name = '$grupo' AND IdCreator = '$id'");
$gruporow = mysql_fetch_array($qGetGroupId);
$idGroup = $gruporow['IdGroup'];
$query = ("SELECT Name,UserId FROM members JOIN relations ON members.UserId = relations.IdFollower WHERE relations.IdGroup = $idGroup AND relations.IdFollowed = $id");
$result = mysql_query($query);		
while($fila = mysql_fetch_array($result)){
	if($fila[0]!=NULL){
		$Name=$fila['Name'];
		$idUser = $fila['UserId'];
		echo "<div onclick=viewperfil(".$idUser.",".$Name.")>".$Name."</a></div>";
	}
}
?>
</body>