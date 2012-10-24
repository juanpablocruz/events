<?php session_start();
if (isset($_SESSION['CLASE'])) {
  }
  else header("location:index.php");
		$basepath = "system";
		require_once $basepath."/connect.php";
		require_once $basepath."/debug.php";
		require_once $basepath."/view.php";
 
if(isset($_GET['name'])){
$strin = $_GET['name'];
$strin = str_replace('_',' ',$strin);
}

if(isset($_GET['id'])){
	$id = $_GET['id'];
	}
	else{
		$id = 0;
	}
if($id!=0)
{
	$sql = "SELECT * FROM `members` WHERE `UserId` = ".$id;
	$qGetUser = mysql_query($sql) or die($sql);
	$User = mysql_fetch_array($qGetUser);
	$fImage = view($id);
	$error = '';
	echo "<img id='contactimg' src='".$fImage."'><p>";
	echo $User['Name']." ".$User['LastName']."</p>";
	$sGetEverybody = "SELECT * FROM members JOIN groups ON members.UserId = groups.IdCreator WHERE UserId = $id";
	$qGetEverybody = mysql_query($sGetEverybody)
	or $error .= DebugQueries('qGetEverybody',$sGetEverybody,'ViewPerfil');
	/* A editar para ver los grupos como bolas en contactos.php*/
	if($error == ''){
	$grupos[] = '';
	$i=0;
	while ($miembro = mysql_fetch_array($qGetEverybody))
		{
			$grupos[$i] = $miembro['Group_Name'];
			$i++;
			echo "<div class='Group'>".$miembro['Group_Name']."</div><a href='seguir.php?grupo=".$miembro['Group_Name']."&user=".$miembro['Name']."'>seguir</a>";
			echo "\t".$miembro['Open']."<br>";
		}
	$grupos=json_encode($grupos);	
	echo "
		<script>
			changebola(".$id.",".$grupos.");
		</script>
	";			
	}
	
	else{
		echo $error;
	}
}
else{

	$qGetUser = mysql_query("SELECT * FROM members WHERE Name LIKE '$strin'");
	$User= mysql_fetch_array($qGetUser);
	$id = $User['UserId'];
	$fImage = view($id);
	echo "<div class='perfilcontact'><div class='imageperfil'><img src=".$fImage."></div></div>";
	echo $User['Name']." ".$User['LastName']."<br>";
	/* A editar para ver los grupos como bolas en contactos.php*/
	$qGetEverybody = mysql_query("SELECT * FROM members JOIN groups ON members.UserId = groups.IdCreator WHERE UserId = $id");
	while ($miembro = mysql_fetch_array($qGetEverybody))
		{
			echo "\t<a href='seguir.php?grupo=".$miembro['Group_Name']."&user=".$miembro['Name']."'>".$miembro['Group_Name']."</a>";
			echo "\t".$miembro['Open']."<br>";
		}
	
}
  
?>