<?php
if(!isset($_SESSION))session_start();
if (!isset($_SESSION['CLASE']))header("location:index.php");

		$basepath = "system";
		require_once $basepath."/connect.php";
		require_once $basepath."/view.php";
		require_once $basepath."/user.class.php";
		require_once $basepath."/eventsfunctions.php";
		$User = LoadUser();
		require_once 'buscauser.php';
		
		header('Content-Type: text/html; charset=UTF-8');
		$fImage = view("$User->id");
?>
<!DOCTYPE html> 
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="css/style_perfil.css" />
	<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="js/qeslib.js"></script>
	</head>
	
	<body>
		<div id="dImagePerfil" class="foto"><?php echo"<img src=".$fImage.">"  ?></div>
		
		<div id="dInfo">
		<ul id="ulInfo">
			<p id="pCanceleditprof" class="cancel" style="float:right" onClick=canceleditprof()></p>
			<li>Nombre:<?php echo "<input name=perfilname id='inPerfil_val' class=perfil_val type=text readonly value='".$User->Data["Perfil"]["Name"]."' style='border:none'>";?></li>
			<li>Apellidos:<?php echo "<input name=perfillastname id='inPerfil_lastname' class=perfil_val type=text readonly value='".$User->Data["Perfil"]["LastName"]."' style='border:none'>";?></li>
			<li>Numero de movil: <?php if($User->Data["Perfil"]["Phone"]!=''){echo "<input name=perfilnumber class=perfil_val type=text readonly value=".$User->Data["Perfil"]["Phone"]." style='border:none'>";}
									else{echo "<input name=perfilnumber class=perfil_val type=text readonly value='' style='border:none' placeholder='phone'>";}?></li>
			<li>email:<?php echo "<input name=perfilemail class=perfil_val type=text readonly value=".$User->Data["Perfil"]["Email"]." style='border:none'>";?></li>
			<li>Cumpleaños:<?php echo "<input name=perfilbirthday class=perfil_val type=text readonly value=".$User->Data["Perfil"]["Birthday"]." style='border:none'>";?></li>
			<li>Dirección: <?php if($User->Data["Perfil"]["Adress"]!=''){echo "<input name=perfiladdress class=perfil_val type=text readonly value=".$User->Data["Perfil"]["Adress"]." style='border:none'>";}
									else{echo "<input name=perfiladress class=perfil_val type=text readonly value='' style='border:none' placeholder='adress'>";}?></li>
			<li>Registrado el:<?php echo "<input name=perfilregisterday class=perfil_val type=text readonly value=".$User->Data["Perfil"]["RegisterDay"]." style='border:none'>";?></li>
			<button id=bEdit onClick=edit()>Editar</button><button id=save onClick=save() style="visibility:hidden">save</button>
		</ul>
		<details id="dImgload">
			<summary class="slide">
			Cambiar imagen de perfil
				<!-- <a href="#" class="btn-slide">Cambiar imagen de perfil</a> -->
			</summary>
			
			<div id="dLoad_img">
			<form method="post" action="upload.php" enctype="multipart/form-data">
				<input type="file" name="file" id="inFile"/>
				<input type="submit" value="Upload Image" />
			</form>
			<?php
					$User = new Usuarios();
					$a->pruebaaaa("e");
					$a->buscarUsuario("fre");
					?>
			</div>
			
		</details>
		</div>
		
		
	</body>
</html>