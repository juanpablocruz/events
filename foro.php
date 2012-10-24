<?php session_start();
if (!isset($_SESSION['CLASE']))header("location:index.php");
		$basepath = "system";
		require_once $basepath."/connect.php";
		require_once $basepath."/view.php";
		require_once $basepath."/user.class.php";
		require_once $basepath."/eventsfunctions.php";
		$grupo = '';
		$error = '';
		$Id = $_GET['idUser'];
		if(isset($_GET['grupo']))$grupo = $_GET['grupo'];
		$sGrupoId = "SELECT IdGroup FROM groups WHERE Group_Name = '$grupo' AND IdCreator = $Id";
		$qGrupoId = mysql_fetch_array(mysql_query($sGrupoId))
			or $error .= DebugQueries('qGrupoId',$sGrupoId,'CreatePost');
		if($error == ''){
			$IdGroup = $qGrupoId['IdGroup'];	
			$lista = GetPosts($IdGroup);
			if($lista[0]){
			$IdWrit = $lista[0]['IdWriter'];
			$sGetName = "SELECT Name,LastName FROM members WHERE UserId = $IdWrit";
			$qGetName = mysql_query($sGetName)
			or $error .= DebugQueries('qGetName',$sGetName,'Select User');
			
			$a =1;
			}
			else{
			$a = 0;
			}
		if($grupo != '' && $error == ''){
			
			echo "<h3>Foro de ".$grupo."</h3>
			<section id='dcomentarios'><header class='pHeader'>Comentarios</header>
			";
			if($a==1)
			{while($aName = mysql_fetch_array($qGetName))
			echo "<article>".$lista[0]['Text']." de ".$aName['Name']." ".$aName['LastName']."</article>";
			}
			else{
			echo "sin comentarios";
			}
			echo "
			</section>
			<section id='Foro'>
			<header class='pHeader'>Escribir un comentario: </header>
			<form method='POST' action='postmessage.php'>
				<TEXTAREA id='posText' NAME='Text' COLS=40 ROWS=6></TEXTAREA>
				<input id='postSend' type='submit' value='POST'>
				<input type='hidden' name='grupo' value=".$grupo.">
			</form>
			</section";
			}
		else{
			echo $error = Debug('variable $_GET',"foro.php",$grupo);
		}
		
		}
		else{
		echo $error;
		}

		
?>