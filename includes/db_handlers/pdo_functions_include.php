<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: pdo_functions_include.php
| Author: Yodix
| Author: Joakim Falk (Domi)
| Author: Harlekin
| Author: Kanuuu
| Modified for HP-Fusion by Harlekin
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) {
    die("Access Denied");
}
// PDO variable
$pdo = NULL;

$mysql_queries_count = 0;
$mysql_queries_time = array();

// MySQL database functions
function dbquery($query) {
	global $pdo, $mysql_queries_count, $mysql_queries_time;
	/** @var PDO $pdo */
	$mysql_queries_count++;
	$query_time = get_microtime();
	$result = $pdo->prepare($query);
	$query_time = substr((get_microtime()-$query_time), 0, 7);
	$mysql_queries_time[$mysql_queries_count] = array($query_time, $query);
	if (!$result) {
		print_r($result->errorInfo());
		return FALSE;
	} else {
		$result->execute();
		return $result;
	}
}

function dbquery_exec($query) {
	global $pdo, $mysql_queries_count, $mysql_queries_time;
	/** @var PDO $pdo */
	$mysql_queries_count++;
	$query_time = get_microtime();
	$result = $pdo->exec($query);
	$query_time = substr((get_microtime()-$query_time), 0, 7);
	$mysql_queries_time[$mysql_queries_count] = array($query_time, $query);
	return $result;
}

function dbcount($field, $table, $conditions = "") {
	global $pdo, $mysql_queries_count, $mysql_queries_time;
	/** @var PDO $pdo */
	$mysql_queries_count++;
	$cond = ($conditions ? " WHERE ".$conditions : "");
	$query_time = get_microtime();
	$result = $pdo->prepare("SELECT COUNT".$field." FROM ".$table.$cond);
	$query_time = substr((get_microtime()-$query_time), 0, 7);
	$mysql_queries_time[$mysql_queries_count] = array($query_time, "SELECT COUNT".$field." FROM ".$table.$cond);
	if (!$result) {
		print_r($result->errorInfo());
		return FALSE;
	} else {
		$result->execute();
		return $result->fetchColumn();
	}
}

function dbresult($query, $row) {
	global $mysql_queries_count; $mysql_queries_count++;
	for ($i = 0; $i < $row; $i++) {
		$query->fetchColumn();
	}
	return $query->fetchColumn();
}

function dbrows($query) {
	return $query->rowCount();
}

function dbarray($query) {
	global $pdo;
	/** @var PDO $pdo */
	$query->setFetchMode(PDO::FETCH_ASSOC);
	return $query->fetch();
}

function dbarraynum($query) {
	global $pdo;
	/** @var PDO $pdo */
	$query->setFetchMode(PDO::FETCH_NUM);
	return $query->fetch();
}

function db_exists($table) {
    global $pdo;
    /** @var PDO $pdo */
    $query = $pdo->prepare('SHOW TABLES LIKE :table');
    $query->bindParam(':table', $table);
    return $query->execute();
}

function dbconnect($db_host, $db_user, $db_pass, $db_name) {
	global $pdo;
	/** @var PDO $pdo */
	try {
		$pdo = new PDO("mysql:host=".$db_host.";dbname=".$db_name.";encoding=utf8", $db_user, $db_pass);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $error) {
		die("<strong>Unable to select MySQL database</strong><br />".$error->getMessage());
	}
}

if(!function_exists("mysql_close")) {
	function mysql_close() {
		global $pdo;
		/** @var PDO $pdo */
		return $pdo = null;
	}
}

if(!function_exists("mysql_insert_id")) {
	function mysql_insert_id() {
		global $pdo;
    /** @var PDO $pdo */
		return $pdo->lastInsertId();
	}
}

if(!function_exists("mysql_get_server_info")) {
	function mysql_get_server_info() {
		global $pdo;
		/** @var PDO $pdo */
		return $pdo->query('select version()')->fetchColumn();
	}
}

if(!function_exists("mysql_query")) {
	function mysql_query($rawQuery) {
		return dbquery($rawQuery);
	}
}

if(!function_exists("mysql_real_escape_string")) {
	function mysql_real_escape_string($unescaped_string, $connection = null) {
		global $pdo;
    /** @var PDO $pdo */
		return $pdo->quote($unescaped_string);
	}
}
if(!function_exists("mysql_connect")) {
	function mysql_connect($db_host, $db_user, $db_pass) {
		global $db_name;
		dbconnect($db_host, $db_user, $db_pass, $db_name);
	}
}

if(!function_exists("mysql_select_db")) {
	function mysql_select_db($name) {
		return TRUE;
	}
}

if(!function_exists("mysql_field_name")) {
	function mysql_field_name($result, $field_offset) {
		$columns = [];
		for ($i = 0; $i < $result->columnCount(); $i++) {
			$col = $result->getColumnMeta($i);
			$columns[] = $col['name'];
		}
		return $columns;
	}
}

if(!function_exists("mysql_result")) {
	function mysql_result($result, $column){
		$query = $result->fetch();
		return $query[$column];
	}
}

if(!function_exists("mysql_free_result")) {
	function mysql_free_result($result) {
		return $result->closeCursor();
	}
}

if(!function_exists("mysql_num_rows")) {
	function mysql_num_rows($result) {
		return dbrows($result);
	}
}

if(!function_exists("mysql_affected_rows")) {
	function mysql_affected_rows($connection = null) {
		global $pdo;
		/** @var PDO $pdo */
		return $pdo->rowCount();
	}
}

if(!function_exists("mysql_data_seek")) {
	function mysql_data_seek($query, $rownum){
		global $pdo;
		$query->setFetchMode(PDO::FETCH_ORI_ABS);
		if (!$query) {
			print_r($query->errorInfo());
			return FALSE;
		} else {
			$query->execute();
		return $query->setFetchMode(PDO::FETCH_ORI_ABS);
		}
	}
}

function dbdataseek($query, $rownum) {
    global $pdo;
    $query->setFetchMode(PDO::FETCH_ORI_ABS);
	if (!$query) {
		print_r($query->errorInfo());
		return FALSE;
	} else {
		$query->execute();
		return $query->setFetchMode(PDO::FETCH_ORI_ABS);
	}
}

?>