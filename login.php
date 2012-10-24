<?php 
require_once "system/User.class.php";
require_once "system/connect.php";

if( empty($_POST['email'])||empty($_POST['pass']))
{
	echo "Esta vacio";
}
else
{
	$email = $_POST['email'];
	if(isSet($_COOKIE['siteAuth']))
	{
		$pass = $_POST['pass'];
	}
	else
	{
		$pass = has($_POST['pass']);
	}
	if(empty($_POST['remember']))
		{
			$remember = "off";
		}
	else
		{
			$remember = $_POST['remember'];
		}
		
	$datetime = new DateTime;
	$time = $datetime->format('Y/m/d H:i:s');


	$SelectEmailq = mysql_query("SELECT `Email` FROM `members` WHERE `Email`='$email'")
			or die (mysql_error());
	if(mysql_num_rows($SelectEmailq)==0)	//Checks if the user exists
	{
		echo "User doesn't exists";
	}
	else
	{
		$SelectActiveq = mysql_query("SELECT `Active` FROM `members` WHERE `Email`='$email'");
		$active = mysql_fetch_row($SelectActiveq);
		
		if($active=='0')	//Checks if the account is activated
		{
			echo "Account not activated, check the email.";
		}
		else
		{
			$qSelectData = mysql_query('SELECT Password, Email, Name,Phone, UserId FROM members WHERE Email=\''.$email.'\'')
				or die(mysql_error());
			if($Datarray = mysql_fetch_array($qSelectData))
			{
				if($Datarray["Password"] == $pass)	//Checks if the password is correct.
				{
					if($remember == "on")
					{
						session_start();
						$cookie_name = 'siteAuth';
						$cookie_time = (3600 * 24 * 30);
						$key = "events";
						$m = encrypt($key,$email);

						setcookie ($cookie_name,'on',time() + $cookie_time,'','',false,true);
						setcookie ('m','m='.$m,time() + $cookie_time,'','',false,true);
						setcookie ('h','h='.$pass,time() + $cookie_time,'','',false,true);
						$id = $Datarray['UserId'];
						$EnterLog = mysql_query("INSERT INTO login (UserId,Date) VALUES ('$id','$time')")
							or die ("Couldn’t insert login into log.");
						
						$name = $Datarray['Name'];
						$status = mysql_query("UPDATE members SET Online = 1 & LastLogin = $time WHERE Name = '$name'");
						$user = new Usuario($id);
						SaveUser($user);
						$_SESSION["k_username"] = $name;
						$_SESSION["k_UserId"]= $id;
						header("location:main.php?p=inicio");	
					 }
					 else
					{
						session_start();
						$id = $Datarray['UserId'];
						$EnterLog = mysql_query("INSERT INTO login (UserId,Date) VALUES ('$id','$time')")
							or die ("Couldn’t insert login into log.");
						
						$name = $Datarray['Name'];
						$status = mysql_query("UPDATE members SET Online = 1 & LastLogin = $time WHERE Name = '$name'");
						$user = new Usuario($id);
						SaveUser($user);
						$_SESSION["k_username"] = $name;
						$_SESSION["k_UserId"]= $id;
						header("location:main.php?p=inicio");				 
					}
				}
				else
				{	
					echo $pass;
					echo "Wrong pass";
				}
				
			}
		}
	}
}
/*
Encrypts the entered password.
@param string $parametro
@return string hash of $parametro
*/
function has($parametro)
{
	return (hash('whirlpool',$parametro));
}
function encrypt($key,$string)
    {
       return $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
    }

?>