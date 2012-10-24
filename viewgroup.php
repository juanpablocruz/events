<?php session_start();
if (isset($_SESSION['CLASE'])) {
  }
  else header("location:index.php");
		require_once $basepath."/connect.php";
		require_once $basepath."/view.php";
		require_once $basepath."/user.class.php";
	
	
$IdGroup = $_GET['id'];

$qGetGroup = mysql_query("SELECT * FROM members JOIN relations ON relations.IdFollower = members.UserId WHERE relations.IdGroup = $IdGroup");
while ($User= mysql_fetch_array($qGetGroup))
	{
		$id = $User['UserId'];
		$fImage = view($id);
		echo "<div><p class='imageperfil' style='height:30px'><img src=".$fImage."></p>";
		echo $User['Name']." ".$User['LastName']."</div>";
	}


?>