<?
/*
 * класс-обёртка для работы с MySQL
*/
class Sql{
	
	public $db_login;
	public $db_pass;
	public $db_name;
	public $db_host;
	
	
	function __construct($db_login, $db_pass, $db_name, $db_host){
		
		$connect = mysql_connect($db_host, $db_login, $db_pass) or die(mysql_error());
		$select_db = mysql_select_db($db_name, $connect) or die(mysql_error());
		
		$this->db_host = $db_host;
		$this->db_login = $db_login;
		$this->db_pass = $db_pass;
		$this->db_name = $db_name;
		
	}
	
	public function query($a){
		return mysql_query($a);
	}
	
	public function row($query){
		return mysql_fetch_array($query);
	}
	
	public function num($query){
		return mysql_num_rows($query);
	}
	
	public function go(){
		$connect = mysql_connect($this->db_host, $this->db_login, $this->db_pass) or die(mysql_error());
		$select_db = mysql_select_db($this->db_name, $connect) or die(mysql_error());
	}
	
}
?>