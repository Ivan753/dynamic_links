<?php
/*
 * класс-обёртка для работы с MySQL
*/
class Sql{

	public $db_login;
	public $db_pass;
	public $db_name;
	public $db_host;
	public $link_db;

	function __construct($db_login, $db_pass, $db_name, $db_host){

		$this->link_db = mysqli_connect($db_host, $db_login, $db_pass, $db_name);

		$this->db_host = $db_host;
		$this->db_login = $db_login;
		$this->db_pass = $db_pass;
		$this->db_name = $db_name;

		$this->link_db->query("SET NAMES utf8");
		$this->link_db->query("SET CHARSET utf8");
	}

	public function query($a){
		return $this->link_db->query($a);
	}

	public function row($query){
		return $query->fetch_array();
	}

	public function num($query){
		return $query->num_rows;
	}

	public function go(){
		$this->link_db = mysqli_connect($db_host, $db_login, $db_pass, $db_name);
		$this->link_db->query("SET NAMES utf8");
		$this->link_db->query("SET CHARSET utf8");
	}

}
?>
