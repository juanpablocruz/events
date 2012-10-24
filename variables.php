<?php
		$id = $_SESSION['k_UserId'];
		$query = mysql_query("SELECT * FROM members WHERE UserId = '$id'");
		$firstrow = mysql_fetch_array($query);
		$_SESSION["k_email"] = $firstrow['Email'];
		$_SESSION["k_phone"] = $firstrow['Phone'];
		$_SESSION["k_LastName"] = $firstrow['LastName'];
		$_SESSION["k_Birthday"] = $firstrow['Birthday'];
		$_SESSION["k_RegisterDay"] = $firstrow['RegisterDay'];
		$_SESSION["k_LastLog"] = $firstrow['LastLog'];
		$_SESSION["k_Adress"] = $firstrow['Adress'];
?>