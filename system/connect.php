<?php 
interface db
{
	public function connect($server,$username,$password,$db);
	public function close();
	public function error();
	
}
class mysqldb implements db
{
	private $server;
	private $username;
	private $password;
	private $dbname;
	private $link;
	private $database;
	
	public function connect($server,$username,$password,$db)
	{
		$this->link=mysql_connect($server,$username,$password);
		$this->database=mysql_select_db($db,$this->link);
	}
	public function close()
	{
		return mysql_close($this->link);
	}
	public function error()
	{
		return mysql_error($this->link);
	}
}
$MysqlDb	=	new mysqldb();
$MysqlDb->connect('localhost','root','','events');
?>
