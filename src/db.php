<?php

class db implements ISingleton {

	private static $instance = null;
	private static $db = null;

	function __construct() {
		self::$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
	}

	static function GetInstance() {
		if (self::$instance == null) {
			self::$instance = new db();
		}
		return self::$instance;
	}

	static function select($table, $condition = false) {
		$query = "SELECT * FROM $table";
		if ($condition != false) {
			$query .= " WHERE $condition";
		}
		$query .= ";";
		return self::$db->query($query);
	}

	static function insert($table, $row) {
		$keys = "";
		$values = "";
		foreach ($row as $key => $col) {
			$keys .= "$key,";
			$col = self::$db->real_escape_string($col);
			$col = htmlentities($col);
			$values .= "'$col',";
		}
		$keys = substr($keys, 0, strlen($keys) - 1);
		$values = substr($values, 0, strlen($values) - 1);
		$query = "INSERT INTO $table($keys) VALUES($values);";
//		echo $query;
		return self::$db->query($query) or die(self::$db->error);
	}

	static function update($table, $row, $where) {
		$query = "UPDATE $table SET ";
		foreach ($row as $key => $col) {
			$col = self::$db->real_escape_string($col);
			$col = htmlentities($col);
			$query .= "$key = '$col',";
		}
		$query = substr($query, 0, strlen($query) - 1);
		$query .= " WHERE $where;";
//		echo $query . "<br/>";
		self::$db->query($query) or die(self::$db->error);
	}

	static function create($array) {
		$query = "CREATE TABLE ";
		$query .= $array["name"];
		$query .= "(";
		foreach ($array["cols"] as $col) {
			$query .= $col["name"] . " " . $col["type"] . " " . $col["additional"] . ",";
		}
		$query = substr($query, 0, strlen($query) - 1);
//		echo "<br/>$query";
		$query .= ") ENGINE = " . $array["engine"] . " CHARSET = " . $array["charset"] . " COLLATE = " . $array["collate"] . ";";
//		echo "<pre>$query</pre>";
		$res = self::$db->query($query);
//		echo self::$db->affected_rows;
	}

}