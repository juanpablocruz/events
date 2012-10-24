<?php 
session_start();
		$basepath = "system";
		require_once $basepath."/connect.php";
// Borramos toda la sesion
$id = $_SESSION['k_UserId'];
$datetime = new DateTime;
$time = $datetime->format('Y/m/d H:i:s');
$status = mysql_query("UPDATE members SET Online = 0 & LastLog = $time WHERE UserId = '$id'");
unset($_COOKIE['siteAuth']);
unset($_COOKIE['h']);
unset($_COOKIE['m']); 
setcookie ('siteAuth','',time()-3600,'','',false,true);
setcookie ('m','',time()-3600,'','',false,true);
setcookie ('h','',time()-3600,'','',false,true);
session_destroy();
?>
<SCRIPT LANGUAGE="javascript">
location.href = "index.php";
</SCRIPT>
