<?php
$cookie_name = 'siteAuth';
function decrypt($key,$encrypted)
	{
		return $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
	}
if(isSet($cookie_name))
{
	// Check if the cookie exists
	if(isSet($_COOKIE['siteAuth'])&&isSet($_COOKIE['m'])&&isSet($_COOKIE['h']))
		{
		parse_str($_COOKIE['m']);
		parse_str($_COOKIE['h']);
		// Make a verification
		$key = "events";
		$usr = decrypt($key,$m);
		$_POST['email'] = $usr;
		$_POST['pass'] = $h;
		$_POST['remember'] = 'on';
		include_once "login.php";
		}
}
?>


<html>
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link rel="stylesheet" href="css/style_login.css" />
</head>
<body>
<div id="logo">
<div id="imagenbanner" class="back"><img src="css/eventsico.png" id="foto" style="float:left;"/><div style="float:left;padding:10%;font-size:20px;text-width:bold;color:#C00;">E-vents</div></div>
</div>
<div id="formulario">
<form method="post" action="login.php" class="Elform">
	<fieldset class="login">
		<legend>Log in</legend>
		<div>
			<INPUT type="text" name="email" id="email" placeholder="email">
			<INPUT type="password" name="pass" placeholder="Password">
		</div>
	</fieldset>
<div style="float:right;display:box;margin-top:10px;">	
<input type="checkbox" name="remember"><label>Remember me</label>
	<button class="loginbutton"type="submit" id="submit-go">Login</button>
	<input type=button onclick="window.location.href='Register.html'" class="loginbutton" value="Register"/>
</div>
</form>
</div>
<div id="login">
<h3 id="header"><p id="title">Tu Agenda Social</p></h3>
<div id="planeta"><img src="imagen.png"></img></div>
</div>
</body>
</html>