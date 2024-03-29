<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: setup.php
| Author: Nick Jones (Digitanium)
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
define("FUSION_SELF", basename($_SERVER['PHP_SELF']));
define("IN_FUSION", true);

if (isset($_POST['localeset']) && file_exists("locale/".$_POST['localeset']) && is_dir("locale/".$_POST['localeset'])) {
	include "locale/".$_POST['localeset']."/setup.php";
} else {
	$_POST['localeset'] = "German";
	include "locale/German/setup.php";
}

if ((isset($_POST['step']) && $_POST['step'] == "7") || (isset($_GET['step']) && $_GET['step'] == "7")) {
	header("Location: index.php");
}

echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='".$locale['xml_lang']."' lang='".$locale['xml_lang']."'>\n";
echo "<head>\n<title>".$locale['title']."</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."' />\n";
echo "<link rel='stylesheet' href='designs/templates/setup_styles.css' type='text/css' />\n";
echo "</head>\n<body>\n";

echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
echo "<td class='full-header'><img src='images/hp_logo.png' alt='Harlekin-Power' /></td>\n";
echo "</tr>\n</table>\n";

echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
echo "<td class='sub-header'>".$locale['sub-title']."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td colspan='2' style='height:10px;background-color:#DD4A38;'></td>\n";
echo "</tr>\n</table>\n";

echo "<br /><br />\n";

echo "<form name='setupform' method='post' action='setup.php'>\n";
echo "<table align='center' cellpadding='0' cellspacing='1' width='450' class='tbl-border'>\n<tr>\n";
echo "<td class='tbl2'><strong>";

if (isset($_POST['step']) && preg_match("/^[2-6]$/", $_POST['step'])) {
	echo $locale['00' . $_POST['step']];
} else {
	echo $locale['001'];
}

echo "</strong></td>\n</tr>\n<tr>\n<td class='tbl1' style='text-align:center'>\n";

if (!isset($_POST['step']) || $_POST['step'] == "" || $_POST['step'] == "1") {
	$locale_files = makefilelist("locale/", ".svn|.|..", true, "folders");
	$locale_list = makefileopts($locale_files);
	echo $locale['010']."<br /><br />";
	echo "<select name='localeset' class='textbox' style='margin-top:5px'>\n";
	echo $locale_list."</select><br /><br />\n";
	//echo $locale['011']."\n";
	echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
	echo "<input type='hidden' name='step' value='2' />\n";
	echo "<input type='submit' name='next' value='".$locale['007']."' class='button' />\n";
}

if (isset($_POST['step']) && $_POST['step'] == "2") {
	if (!file_exists("config.php")) {
		if (file_exists("_config.php") && function_exists("rename")) {
			@rename("_config.php", "config.php");
		} else {
			$handle = fopen("config.php", "w");
			fclose($handle);
		}
	}

	$check_arr = array(
		"administration/db_backups" => false,
		"forum/attachments" => false,
		"downloads" => false,
		"downloads/images" => false,
		"downloads/submissions/" => false,
		"downloads/submissions/images" => false,
		"ftp_upload" => false,
		"images" => false,
		"images/imagelist.js" => false,
		"images/articles" => false,
		"images/avatars" => false,
		"images/news" => false,
		"images/news/thumbs" => false,
		"images/news_cats" => false,
		"images/photoalbum" => false,
		"images/photoalbum/submissions" => false,
		"images/weblink_sites" => false,
		"config.php" => false,
		"robots.txt" => false
	);

	$write_check = true; $check_display = "";

	foreach ($check_arr as $key => $value) {
		if (file_exists($key) && is_writable($key)) {
			$check_arr[$key] = true;
		} else {
			if (file_exists($key) && function_exists("chmod") && @chmod($key, 0777) && is_writable($key)) {
				$check_arr[$key] = true;
			} else {
				$write_check = false;
			}
		}
		$check_display .= "<tr>\n<td class='tbl1'>".$key."</td>\n";
		$check_display .= "<td class='tbl1' style='text-align:right'>".($check_arr[$key] == true ? "<span class='passed'>".$locale['023']."</span>" : "<span class='failed'>".$locale['024']."</span>")."</td>\n</tr>\n";
	}

	echo $locale['020']."<br /><br />\n";
	echo "<table align='center' cellpadding='0' cellspacing='0' width='100%'>\n".$check_display."\n</table><br /><br />\n";
	if ($write_check) {
		echo $locale['021']."\n";
		echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
		echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."' />\n";
		echo "<input type='hidden' name='step' value='3' />\n";
		echo "<input type='submit' name='next' value='".$locale['007']."' class='button' />\n";
	} else {
		echo $locale['022']."\n";
		echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
		echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."' />\n";
		echo "<input type='hidden' name='step' value='2' />\n";
		echo "<input type='submit' name='next' value='".$locale['025']."' class='button' />\n";
	}
}

if (isset($_POST['step']) && $_POST['step'] == "3") {
	function createRandomPrefix ($length = 5) {
		$chars = array("abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ", "123456789");
		$count = array((strlen($chars[0]) - 1), (strlen($chars[1]) - 1));
		$prefix = "";
		for ($i = 0; $i < $length; $i++) {
			$type = mt_rand(0, 1);
			$prefix .= substr($chars[$type], mt_rand(0, $count[$type]), 1);
		}
		return $prefix;
	}
	$db_prefix = "power".createRandomPrefix()."_";
	$cookie_prefix  = "power".createRandomPrefix()."_";
	$db_host = (isset($_POST['db_host']) ? stripinput(trim($_POST['db_host'])) : "localhost");
	$db_user = (isset($_POST['db_user']) ? stripinput(trim($_POST['db_user'])) : "");
	$db_name = (isset($_POST['db_name']) ? stripinput(trim($_POST['db_name'])) : "");
	$pdo_enabled = (isset($_POST['pdo_enabled']) ? stripinput(trim($_POST['pdo_enabled'])) : "");//new for pdo
	$db_prefix = (isset($_POST['db_prefix']) ? stripinput(trim($_POST['db_prefix'])) : $db_prefix);
	$cookie_prefix = (isset($_POST['cookie_prefix']) ? stripinput(trim($_POST['cookie_prefix'])) : $cookie_prefix);
	$db_error = (isset($_POST['db_error']) && isnum($_POST['db_error']) ? $_POST['db_error'] : "0");
	$field_class = array("", "", "", "", "");
	if ($db_error > "0") {
		$field_class[2] = " tbl-error";
		if ($db_error == 1) {
			$field_class[1] = " tbl-error";
			$field_class[2] = " tbl-error";
		} elseif ($db_error == 2) {
			$field_class[3] = " tbl-error";
		} elseif ($db_error == 3) {
			$field_class[4] = " tbl-error";
		} elseif ($db_error == 7) {
			if ($db_host == "") { $field_class[0] = " tbl-error"; }
			if ($db_user == "") { $field_class[1] = " tbl-error"; }
			if ($db_name == "") { $field_class[3] = " tbl-error"; }
			if ($db_prefix == "") { $field_class[4] = " tbl-error"; }
		}
	}

	echo $locale['030']."<br /><br />\n";
	echo "<table align='center' cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
	echo "<td class='tbl1'>".$locale['031']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='text' value='".$db_host."' name='db_host' class='textbox".$field_class[0]."' style='width:200px' /></td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['032']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='text' value='".$db_user."' name='db_user' class='textbox".$field_class[1]."' style='width:200px' /></td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['033']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='password' value='' name='db_pass' class='textbox".$field_class[2]."' style='width:200px' /></td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['034']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='text' value='".$db_name."' name='db_name' class='textbox".$field_class[3]."' style='width:200px' /></td>\n</tr>\n";
	//new for pdo start
	echo "<input name='pdo_enabled'  type='hidden' value='1' />\n";
	echo "<tr>\n<td class='tbl1'>".$locale['035']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='text' value='".$db_prefix."' name='db_prefix' class='textbox".$field_class[4]."' style='width:200px' /></td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['036']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='text' value='".$cookie_prefix."' name='cookie_prefix' class='textbox' style='width:200px' /></td>\n</tr>\n";
	echo "</table>\n";
	echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
	echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."' />\n";
	echo "<input type='hidden' name='step' value='4' />\n";
	echo "<input type='submit' name='next' value='".$locale['007']."' class='button' />\n";
}

if (isset($_POST['step']) && $_POST['step'] == "4") {
	$db_host = (isset($_POST['db_host']) ? stripinput(trim($_POST['db_host'])) : "");
	$db_user = (isset($_POST['db_user']) ? stripinput(trim($_POST['db_user'])) : "");
	$db_pass = (isset($_POST['db_pass']) ? stripinput(trim($_POST['db_pass'])) : "");
	$db_name = (isset($_POST['db_name']) ? stripinput(trim($_POST['db_name'])) : "");
	$pdo_enabled = (isset($_POST['pdo_enabled']) ? stripinput(trim($_POST['pdo_enabled'])) : "");//neu pdo
	$db_prefix = (isset($_POST['db_prefix']) ? stripinput(trim($_POST['db_prefix'])) : "");
	$cookie_prefix = (isset($_POST['cookie_prefix']) ? stripinput(trim($_POST['cookie_prefix'])) : "power_");
	if ($db_prefix != "") {
		$db_prefix_last = $db_prefix[strlen($db_prefix)-1];
		if ($db_prefix_last != "_") { $db_prefix = $db_prefix."_"; }
	}
	if ($cookie_prefix != "") {
		$cookie_prefix_last = $cookie_prefix[strlen($cookie_prefix)-1];
		if ($cookie_prefix_last != "_") { $cookie_prefix = $cookie_prefix."_"; }
	}

	if ($db_host != "" && $db_user != "" && $db_name != "" && $db_prefix != "") {
		require_once "includes/db_handlers/pdo_functions_include.php";
		$pdo = NULL;
		try {
			$pdo = new PDO("mysql:host=".$db_host.";encoding=utf8", $db_user, $db_pass);
			$db_connect = $pdo;
		} catch (PDOException $error) {
		}
		if ($db_connect) {
			$pdo = NULL;
			try {
				$pdo = new PDO("mysql:host=".$db_host.";dbname=".$db_name.";encoding=utf8", $db_user, $db_pass);
				$db_select = $pdo;
			} catch (PDOException $error) {
			}
			if ($db_select) {
				if (dbrows(dbquery("SHOW TABLES LIKE '".str_replace("_", "\_", $db_prefix)."%'")) == "0") {
					$table_name = uniqid($db_prefix, false); $can_write = true;
					$result = dbquery("CREATE TABLE ".$table_name." (test_field VARCHAR(10) NOT NULL) ENGINE=MYISAM;");
					if (!$result) { $can_write = false; }
					$result = dbquery("DROP TABLE ".$table_name);
					if (!$result) { $can_write = false; }
					if ($can_write) {
						$config = "<?php\n";
						$config .= "// database settings\n";
						$config .= "\$db_host = \"".$db_host."\";\n";
						$config .= "\$db_user = \"".$db_user."\";\n";
						$config .= "\$db_pass = \"".$db_pass."\";\n";
						$config .= "\$db_name = \"".$db_name."\";\n";
						$config .= "\$db_prefix = \"".$db_prefix."\";\n";
						$config .= "\$pdo_enabled = '".$pdo_enabled."';\n";//neu pdo
						$config .= "define(\"DB_PREFIX\", \"".$db_prefix."\");\n";
						$config .= "define(\"COOKIE_PREFIX\", \"".$cookie_prefix."\");\n";
						$config .= "?>";
						$temp = fopen("config.php","w");
						if (fwrite($temp, $config)) {
							fclose($temp);
							$fail = false;
							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."admin");
							$result = dbquery("CREATE TABLE ".$db_prefix."admin (
							admin_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							admin_rights CHAR(4) NOT NULL DEFAULT '',
							admin_image VARCHAR(50) NOT NULL DEFAULT '',
							admin_title VARCHAR(50) NOT NULL DEFAULT '',
							admin_link VARCHAR(100) NOT NULL DEFAULT 'reserved',
							admin_page TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
							PRIMARY KEY (admin_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."admin_resetlog");
							$result = dbquery("CREATE TABLE ".$db_prefix."admin_resetlog (
							reset_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							reset_admin_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '1',
							reset_timestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
							reset_sucess TEXT NOT NULL,
							reset_failed TEXT NOT NULL,
							reset_admins VARCHAR(8) NOT NULL DEFAULT '0',
							reset_reason VARCHAR(255) NOT NULL,
							PRIMARY KEY (reset_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."articles");
							$result = dbquery("CREATE TABLE ".$db_prefix."articles (
							article_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							article_cat MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							article_subject VARCHAR(200) NOT NULL DEFAULT '',
							article_snippet TEXT NOT NULL,
							article_article TEXT NOT NULL,
							article_draft TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							article_breaks CHAR(1) NOT NULL DEFAULT '',
							article_name MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '1',
							article_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
							article_reads MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							article_allow_comments TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
							article_allow_ratings TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
							PRIMARY KEY (article_id),
							KEY article_cat (article_cat),
							KEY article_datestamp (article_datestamp),
							KEY article_reads (article_reads)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."article_cats");
							$result = dbquery("CREATE TABLE ".$db_prefix."article_cats (
							article_cat_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							article_cat_name VARCHAR(100) NOT NULL DEFAULT '',
							article_cat_description VARCHAR(200) NOT NULL DEFAULT '',
							article_cat_sorting VARCHAR(50) NOT NULL DEFAULT 'article_subject ASC',
							article_cat_access TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY (article_cat_id),
							KEY article_cat_access (article_cat_access)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."bbcodes");
							$result = dbquery("CREATE TABLE ".$db_prefix."bbcodes (
							bbcode_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							bbcode_name VARCHAR(20) NOT NULL DEFAULT '',
							bbcode_order SMALLINT(5) UNSIGNED NOT NULL,
							PRIMARY KEY (bbcode_id),
							KEY bbcode_order (bbcode_order)
							) ENGINE=MYISAM;");
							
							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."bbcodes_rightsgroups");
							$result = dbquery("CREATE TABLE ".$db_prefix."bbcodes_rightsgroups (
							id INT UNSIGNED NOT NULL AUTO_INCREMENT,
							gid INT(8) UNSIGNED NOT NULL DEFAULT '0',
							perm TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							bbcode VARCHAR(100) DEFAULT NULL,
							PRIMARY KEY(id)
							) ENGINE=MYISAM;");
							
							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."bbcode_rightsusers");
							$result = dbquery("CREATE TABLE ".$db_prefix."bbcode_rightsusers (
							id INT UNSIGNED NOT NULL AUTO_INCREMENT,
							uid INT(8) UNSIGNED NOT NULL DEFAULT '0',
							perm TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							bbcode VARCHAR(100) DEFAULT NULL,
							PRIMARY KEY(id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."blacklist");
							$result = dbquery("CREATE TABLE ".$db_prefix."blacklist (
							blacklist_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							blacklist_user_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							blacklist_ip VARCHAR(45) NOT NULL DEFAULT '',
							blacklist_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
							blacklist_email VARCHAR(100) NOT NULL DEFAULT '',
							blacklist_username VARCHAR(200) NOT NULL DEFAULT '',
							blacklist_reason TEXT NOT NULL,
							blacklist_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY (blacklist_id),
							KEY blacklist_ip_type (blacklist_ip_type)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."captcha");
							$result = dbquery("CREATE TABLE ".$db_prefix."captcha (
							captcha_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
							captcha_ip VARCHAR(45) NOT NULL DEFAULT '',
							captcha_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
							captcha_encode VARCHAR(32) NOT NULL DEFAULT '',
							captcha_string VARCHAR(15) NOT NULL DEFAULT '',
							KEY captcha_datestamp (captcha_datestamp)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."comments");
							$result = dbquery("CREATE TABLE ".$db_prefix."comments (
							comment_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							comment_item_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							comment_type CHAR(2) NOT NULL DEFAULT '',
							comment_name VARCHAR(50) NOT NULL DEFAULT '',
							comment_message TEXT NOT NULL,
							comment_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
							comment_ip VARCHAR(45) NOT NULL DEFAULT '',
							comment_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
							comment_hidden TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY (comment_id),
							KEY comment_datestamp (comment_datestamp)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."custom_pages");
							$result = dbquery("CREATE TABLE ".$db_prefix."custom_pages (
							page_id MEDIUMINT(8) NOT NULL AUTO_INCREMENT,
							page_title VARCHAR(200) NOT NULL DEFAULT '',
							page_breaks CHAR(1) NOT NULL DEFAULT '',
							page_access TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
							page_content TEXT NOT NULL,
							page_allow_comments TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							page_allow_ratings TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY (page_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."download_cats");
							$result = dbquery("CREATE TABLE ".$db_prefix."download_cats (
							download_cat_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							download_cat_name VARCHAR(100) NOT NULL DEFAULT '',
							download_cat_description TEXT NOT NULL,
							download_cat_sorting VARCHAR(50) NOT NULL DEFAULT 'download_title ASC',
							download_cat_access TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY (download_cat_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."downloads");
							$result = dbquery("CREATE TABLE ".$db_prefix."downloads (
							download_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							download_user MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							download_homepage VARCHAR(100) NOT NULL DEFAULT '',
							download_title VARCHAR(100) NOT NULL DEFAULT '',
							download_description_short VARCHAR(255) NOT NULL,
							download_description TEXT NOT NULL,
							download_image VARCHAR(100) NOT NULL DEFAULT '',
							download_image_thumb VARCHAR(100) NOT NULL DEFAULT '',
							download_url VARCHAR(200) NOT NULL DEFAULT '',
							download_file VARCHAR(100) NOT NULL DEFAULT '',
							download_cat MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							download_license VARCHAR(50) NOT NULL DEFAULT '',
							download_copyright VARCHAR(250) NOT NULL DEFAULT '',
							download_os VARCHAR(50) NOT NULL DEFAULT '',
							download_version VARCHAR(20) NOT NULL DEFAULT '',
							download_filesize VARCHAR(20) NOT NULL DEFAULT '',
							download_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
							download_count INT(10) UNSIGNED NOT NULL DEFAULT '0',
							download_allow_comments TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							download_allow_ratings TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY (download_id),
							KEY download_datestamp (download_datestamp)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."errors");
							$result = dbquery("CREATE TABLE ".$db_prefix."errors (
							error_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							error_level SMALLINT(5) UNSIGNED NOT NULL,
							error_message TEXT NOT NULL,
							error_file VARCHAR(255) NOT NULL,
							error_line SMALLINT(5) NOT NULL,
							error_page VARCHAR(200) NOT NULL,
							error_user_level SMALLINT(3) NOT NULL,
							error_user_ip VARCHAR(45) NOT NULL DEFAULT '',
							error_user_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
							error_status tinyint(1) NOT NULL DEFAULT '0',
							error_timestamp INT(10) NOT NULL,
							PRIMARY KEY (error_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."faq_cats");
							$result = dbquery("CREATE TABLE ".$db_prefix."faq_cats (
							faq_cat_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							faq_cat_name VARCHAR(200) NOT NULL DEFAULT '',
							faq_cat_description VARCHAR(250) NOT NULL DEFAULT '',
							PRIMARY KEY(faq_cat_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."faqs");
							$result = dbquery("CREATE TABLE ".$db_prefix."faqs (
							faq_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							faq_cat_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							faq_question VARCHAR(200) NOT NULL DEFAULT '',
							faq_answer TEXT NOT NULL,
							PRIMARY KEY(faq_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."flood_control");
							$result = dbquery("CREATE TABLE ".$db_prefix."flood_control (
							flood_ip VARCHAR(45) NOT NULL DEFAULT '',
							flood_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
							flood_timestamp INT(5) UNSIGNED NOT NULL DEFAULT '0',
							KEY flood_timestamp (flood_timestamp)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forum_attachments");
							$result = dbquery("CREATE TABLE ".$db_prefix."forum_attachments (
							attach_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							thread_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							post_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							attach_name VARCHAR(100) NOT NULL DEFAULT '',
							attach_ext VARCHAR(5) NOT NULL DEFAULT '',
							attach_size INT(20) UNSIGNED NOT NULL DEFAULT '0',
							attach_count INT(10) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY (attach_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forum_ranks");
							$result = dbquery("CREATE TABLE ".$db_prefix."forum_ranks (
							rank_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							rank_title VARCHAR(100) NOT NULL DEFAULT '',
							rank_image VARCHAR(100) NOT NULL DEFAULT '',
							rank_posts iNT(10) UNSIGNED NOT NULL DEFAULT '0',
							rank_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							rank_apply SMALLINT(5) UNSIGNED NOT NULL DEFAULT '101',
							PRIMARY KEY (rank_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forum_poll_options");
							$result = dbquery("CREATE TABLE ".$db_prefix."forum_poll_options (
							thread_id MEDIUMINT(8) UNSIGNED NOT NULL,
							forum_poll_option_id SMALLINT(5) UNSIGNED NOT NULL,
							forum_poll_option_text VARCHAR(150) NOT NULL,
							forum_poll_option_votes SMALLINT(5) UNSIGNED NOT NULL,
							KEY thread_id (thread_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forum_poll_voters");
							$result = dbquery("CREATE TABLE ".$db_prefix."forum_poll_voters (
							thread_id MEDIUMINT(8) UNSIGNED NOT NULL,
							forum_vote_user_id MEDIUMINT(8) UNSIGNED NOT NULL,
							forum_vote_user_ip VARCHAR(45) NOT NULL,
							forum_vote_user_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
							KEY thread_id (thread_id,forum_vote_user_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forum_polls");
							$result = dbquery("CREATE TABLE ".$db_prefix."forum_polls (
							thread_id MEDIUMINT(8) UNSIGNED NOT NULL,
							forum_poll_title VARCHAR(250) NOT NULL,
							forum_poll_start INT(10) UNSIGNED DEFAULT NULL,
							forum_poll_length INT(10) UNSIGNED NOT NULL,
							forum_poll_votes SMALLINT(5) UNSIGNED NOT NULL,
							KEY thread_id (thread_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forums");
							$result = dbquery("CREATE TABLE ".$db_prefix."forums (
							forum_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							forum_cat MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							forum_name VARCHAR(50) NOT NULL DEFAULT '',
							forum_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
							forum_description TEXT NOT NULL,
							forum_moderators TEXT NOT NULL,
							forum_access TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
							forum_post SMALLINT(3) UNSIGNED DEFAULT '101',
							forum_reply SMALLINT(3) UNSIGNED DEFAULT '101',
							forum_poll SMALLINT(3) UNSIGNED NOT NULL DEFAULT '0',
							forum_vote SMALLINT(3) UNSIGNED NOT NULL DEFAULT '0',
							forum_attach SMALLINT(3) UNSIGNED NOT NULL DEFAULT '0',
							forum_attach_download SMALLINT(3) UNSIGNED NOT NULL DEFAULT'0',
							forum_lastpost INT(10) UNSIGNED NOT NULL DEFAULT '0',
							forum_postcount MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							forum_threadcount MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							forum_lastuser MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							forum_merge TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY (forum_id),
							KEY forum_order (forum_order),
							KEY forum_lastpost (forum_lastpost),
							KEY forum_postcount (forum_postcount),
							KEY forum_threadcount (forum_threadcount)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."infusions");
							$result = dbquery("CREATE TABLE ".$db_prefix."infusions (
							inf_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							inf_title VARCHAR(100) NOT NULL DEFAULT '',
							inf_folder VARCHAR(100) NOT NULL DEFAULT '',
							inf_version VARCHAR(10) NOT NULL DEFAULT '0',
							PRIMARY KEY (inf_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."messages");
							$result = dbquery("CREATE TABLE ".$db_prefix."messages (
							message_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							message_to MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							message_from MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							message_subject VARCHAR(100) NOT NULL DEFAULT '',
							message_message TEXT NOT NULL,
							message_smileys CHAR(1) NOT NULL DEFAULT '',
							message_read TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							message_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
							message_folder TINYINT(1) UNSIGNED NOT NULL DEFAULT  '0',
							PRIMARY KEY (message_id),
							KEY message_datestamp (message_datestamp)
							) ENGINE=MYISAM;");

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."messages_options");
							$result = dbquery("CREATE TABLE ".$db_prefix."messages_options (
							user_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							pm_email_notify tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
							pm_save_sent tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
							pm_inbox SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
							pm_savebox SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
							pm_sentbox SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
							PRIMARY KEY (user_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."news");
							$result = dbquery("CREATE TABLE ".$db_prefix."news (
							news_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							news_subject VARCHAR(200) NOT NULL DEFAULT '',
							news_image VARCHAR(100) NOT NULL DEFAULT '',
							news_image_t1 VARCHAR(100) NOT NULL DEFAULT '',
							news_image_t2 VARCHAR(100) NOT NULL DEFAULT '',
							news_cat MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							news_news TEXT NOT NULL,
							news_extended TEXT NOT NULL,
							news_breaks CHAR(1) NOT NULL DEFAULT '',
							news_name MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '1',
							news_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
							news_start INT(10) UNSIGNED NOT NULL DEFAULT '0',
							news_end INT(10) UNSIGNED NOT NULL DEFAULT '0',
							news_visibility TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
							news_reads INT(10) UNSIGNED NOT NULL DEFAULT '0',
							news_draft TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							news_sticky TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							news_allow_comments TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
							news_allow_ratings TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
							PRIMARY KEY (news_id),
							KEY news_datestamp (news_datestamp),
							KEY news_reads (news_reads)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."news_cats");
							$result = dbquery("CREATE TABLE ".$db_prefix."news_cats (
							news_cat_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							news_cat_name VARCHAR(100) NOT NULL DEFAULT '',
							news_cat_image VARCHAR(100) NOT NULL DEFAULT '',
							PRIMARY KEY (news_cat_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."new_users");
							$result = dbquery("CREATE TABLE ".$db_prefix."new_users (
							user_code VARCHAR(40) NOT NULL,
							user_name VARCHAR(30) NOT NULL,
							user_email VARCHAR(100) NOT NULL,
							user_datestamp INT(10) UNSIGNED DEFAULT '0' NOT NULL,
							user_info TEXT NOT NULL,
							KEY user_datestamp (user_datestamp)
							) ENGINE=MYISAM;");

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."email_verify");
							$result = dbquery("CREATE TABLE ".$db_prefix."email_verify (
							user_id MEDIUMINT(8) NOT NULL,
							user_code VARCHAR(32) NOT NULL,
							user_email VARCHAR(100) NOT NULL,
							user_datestamp INT(10) UNSIGNED DEFAULT '0' NOT NULL,
							KEY user_datestamp (user_datestamp)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."ratings");
							$result = dbquery("CREATE TABLE ".$db_prefix."ratings (
							rating_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							rating_item_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							rating_type CHAR(1) NOT NULL DEFAULT '',
							rating_user MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							rating_vote TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							rating_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
							rating_ip VARCHAR(45) NOT NULL DEFAULT '',
							rating_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
							PRIMARY KEY (rating_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."online");
							$result = dbquery("CREATE TABLE ".$db_prefix."online (
							online_user VARCHAR(50) NOT NULL DEFAULT '',
							online_ip VARCHAR(45) NOT NULL DEFAULT '',
							online_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
							online_lastactive INT(10) UNSIGNED NOT NULL DEFAULT '0'
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."panels");
							$result = dbquery("CREATE TABLE ".$db_prefix."panels (
							panel_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							panel_name VARCHAR(100) NOT NULL DEFAULT '',
							panel_filename VARCHAR(100) NOT NULL DEFAULT '',
							panel_content TEXT NOT NULL,
							panel_side TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
							panel_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
							panel_type VARCHAR(20) NOT NULL DEFAULT '',
							panel_access TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
							panel_display TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							panel_status TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							panel_url_list TEXT NOT NULL,
							panel_restriction TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY (panel_id),
							KEY panel_order (panel_order)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."photo_albums");
							$result = dbquery("CREATE TABLE ".$db_prefix."photo_albums (
							album_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							album_title VARCHAR(100) NOT NULL DEFAULT '',
							album_description TEXT NOT NULL,
							album_thumb VARCHAR(100) NOT NULL DEFAULT '',
							album_user MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							album_access SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
							album_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
							album_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY (album_id),
							KEY album_order (album_order),
							KEY album_datestamp (album_datestamp)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."photos");
							$result = dbquery("CREATE TABLE ".$db_prefix."photos (
							photo_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							album_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							photo_title VARCHAR(100) NOT NULL DEFAULT '',
							photo_description TEXT NOT NULL,
							photo_filename VARCHAR(100) NOT NULL DEFAULT '',
							photo_thumb1 VARCHAR(100) NOT NULL DEFAULT '',
							photo_thumb2 VARCHAR(100) NOT NULL DEFAULT '',
							photo_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
							photo_user MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							photo_views INT(10) UNSIGNED NOT NULL DEFAULT '0',
							photo_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
							photo_allow_comments tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
							photo_allow_ratings tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
							PRIMARY KEY (photo_id),
							KEY photo_order (photo_order),
							KEY photo_datestamp (photo_datestamp)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."votes");
							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."poll_votes");
							$result = dbquery("CREATE TABLE ".$db_prefix."poll_votes (
							vote_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							vote_user MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							vote_opt SMALLINT(2) UNSIGNED NOT NULL DEFAULT '0',
							poll_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY (vote_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."polls");
							$result = dbquery("CREATE TABLE ".$db_prefix."polls (
							poll_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							poll_title VARCHAR(200) NOT NULL DEFAULT '',
							poll_opt_0 VARCHAR(200) NOT NULL DEFAULT '',
							poll_opt_1 VARCHAR(200) NOT NULL DEFAULT '',
							poll_opt_2 VARCHAR(200) NOT NULL DEFAULT '',
							poll_opt_3 VARCHAR(200) NOT NULL DEFAULT '',
							poll_opt_4 VARCHAR(200) NOT NULL DEFAULT '',
							poll_opt_5 VARCHAR(200) NOT NULL DEFAULT '',
							poll_opt_6 VARCHAR(200) NOT NULL DEFAULT '',
							poll_opt_7 VARCHAR(200) NOT NULL DEFAULT '',
							poll_opt_8 VARCHAR(200) NOT NULL DEFAULT '',
							poll_opt_9 VARCHAR(200) NOT NULL DEFAULT '',
							poll_started INT(10) UNSIGNED NOT NULL DEFAULT '0',
							poll_ended INT(10) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY (poll_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."posts");
							$result = dbquery("CREATE TABLE ".$db_prefix."posts (
							forum_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							thread_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							post_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							post_message TEXT NOT NULL,
							post_showsig TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							post_smileys TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
							post_author MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							post_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
							post_ip VARCHAR(45) NOT NULL DEFAULT '',
							post_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
							post_edituser MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							post_edittime INT(10) UNSIGNED NOT NULL DEFAULT '0',
							post_editreason TEXT NOT NULL,
							post_hidden TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							post_locked TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY (post_id),
							KEY thread_id (thread_id),
							KEY post_datestamp (post_datestamp)
							) ENGINE=MYISAM;");
							
							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."score_account");
							$result = dbquery("CREATE TABLE ".$db_prefix."score_account (
							acc_user_id MEDIUMINT(8) NOT NULL,
							acc_score MEDIUMINT(8) NOT NULL DEFAULT '0',
							PRIMARY KEY (acc_user_id)
							) ENGINE=MYISAM;");
							
							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."score_ban");
							$result = dbquery("CREATE TABLE ".$db_prefix."score_ban (
							ban_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							ban_user_id MEDIUMINT(8) NOT NULL DEFAULT '0',
							ban_time_start INT(10) NOT NULL DEFAULT '0',
							ban_time_stop INT(10) NOT NULL DEFAULT '0',
							ban_text TEXT NOT NULL,
							ban_admin_id MEDIUMINT(8) NOT NULL DEFAULT '0',
							PRIMARY KEY (ban_id)
							) ENGINE=MYISAM;");
							
							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."score_score");
							$result = dbquery("CREATE TABLE ".$db_prefix."score_score (
							sco_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							sco_aktion VARCHAR(5) NOT NULL DEFAULT '-',
							sco_titel TEXT NOT NULL,
							sco_score INT(9) NOT NULL DEFAULT '0',
							sco_max INT(6) NOT NULL DEFAULT '5',
							sco_status TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							sco_power TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY (sco_id)
							) ENGINE=MYISAM;");
							
							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."score_settings");
							$result = dbquery("CREATE TABLE ".$db_prefix."score_settings (
							set_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
							set_delete TINYINT(4) UNSIGNED NOT NULL DEFAULT '0',
							set_panel TINYINT(4) UNSIGNED NOT NULL DEFAULT '5',
							set_user_transfer TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							set_user_tra_sco INT(9) NOT NULL DEFAULT '5',
							set_units TEXT NOT NULL,
							set_user_chance TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							set_sco_power TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							set_top_user MEDIUMINT(8) NOT NULL DEFAULT '0',
							set_data TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							set_tceoxdte TEXT NOT NULL,
							set_tdeaxtte INT(10) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY (set_id)
							) ENGINE=MYISAM;");
							
							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."score_transfer");
							$result = dbquery("CREATE TABLE ".$db_prefix."score_transfer (
							tra_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							tra_user_id MEDIUMINT(8) NOT NULL DEFAULT '0',
							tra_titel TEXT NOT NULL,
							tra_typ CHAR(1) NOT NULL DEFAULT 'O',
							tra_aktion VARCHAR(5) NOT NULL DEFAULT '-',
							tra_score INT(9) NOT NULL DEFAULT '0',
							tra_status TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							tra_time INT(10) NOT NULL DEFAULT '0',
							PRIMARY KEY (tra_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."settings");
							$result = dbquery("CREATE TABLE ".$db_prefix."settings (
							settings_name VARCHAR(200) NOT NULL DEFAULT '',
							settings_value TEXT NOT NULL,
							PRIMARY KEY (settings_name)
							) ENGINE=MYISAM");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."settings_inf");
							$result = dbquery("CREATE TABLE ".$db_prefix."settings_inf (
							settings_name VARCHAR(200) NOT NULL DEFAULT '',
							settings_value TEXT NOT NULL,
							settings_inf VARCHAR(200) NOT NULL DEFAULT '',
							PRIMARY KEY (settings_name)
							) ENGINE=MYISAM");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."site_links");
							$result = dbquery("CREATE TABLE ".$db_prefix."site_links (
							link_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							link_cat SMALLINT(5) NOT NULL DEFAULT '0',
							link_name VARCHAR(200) NOT NULL DEFAULT '',
							link_url VARCHAR(200) NOT NULL DEFAULT '',
							link_visibility TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
							link_position TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
							link_window TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							link_order SMALLINT(2) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY (link_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."smileys");
							$result = dbquery("CREATE TABLE ".$db_prefix."smileys (
							smiley_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							smiley_code VARCHAR(50) NOT NULL,
							smiley_image VARCHAR(100) NOT NULL,
							smiley_text VARCHAR(100) NOT NULL,
							PRIMARY KEY (smiley_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."submissions");
							$result = dbquery("CREATE TABLE ".$db_prefix."submissions (
							submit_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							submit_type CHAR(1) NOT NULL,
							submit_user MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
							submit_datestamp INT(10) UNSIGNED DEFAULT '0' NOT NULL,
							submit_criteria TEXT NOT NULL,
							PRIMARY KEY (submit_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."suspends");
							$result = dbquery("CREATE TABLE ".$db_prefix."suspends (
							suspend_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							suspended_user MEDIUMINT(8) UNSIGNED NOT NULL,
							suspending_admin MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '1',
							suspend_ip VARCHAR(45) NOT NULL DEFAULT '',
							suspend_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
							suspend_date INT(10) NOT NULL DEFAULT '0',
							suspend_reason TEXT NOT NULL,
							suspend_type TINYINT(1) NOT NULL DEFAULT '0',
							reinstating_admin MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '1',
							reinstate_reason TEXT NOT NULL,
							reinstate_date INT(10) NOT NULL DEFAULT '0',
							reinstate_ip VARCHAR(45) NOT NULL DEFAULT '',
							reinstate_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
							PRIMARY KEY (suspend_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."threads");
							$result = dbquery("CREATE TABLE ".$db_prefix."threads (
							forum_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							thread_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							thread_subject VARCHAR(100) NOT NULL DEFAULT '',
							thread_author MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							thread_views MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							thread_lastpost INT(10) UNSIGNED NOT NULL DEFAULT '0',
							thread_lastpostid MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							thread_lastuser MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							thread_postcount SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
							thread_poll TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							thread_sticky TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							thread_locked TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							thread_hidden TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY (thread_id),
							KEY thread_postcount (thread_postcount),
							KEY thread_lastpost (thread_lastpost),
							KEY thread_views (thread_views)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."thread_notify");
							$result = dbquery("CREATE TABLE ".$db_prefix."thread_notify (
							thread_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							notify_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
							notify_user MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							notify_status tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
							KEY notify_datestamp (notify_datestamp)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."user_field_cats");
							$result = dbquery("CREATE TABLE ".$db_prefix."user_field_cats (
							field_cat_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT ,
							field_cat_name VARCHAR(200) NOT NULL ,
							field_cat_order SMALLINT(5) UNSIGNED NOT NULL ,
							PRIMARY KEY (field_cat_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."user_fields");
							$result = dbquery("CREATE TABLE ".$db_prefix."user_fields (
							field_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							field_name VARCHAR(50) NOT NULL,
							field_cat MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '1',
 							field_required TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							field_log TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							field_registration TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
 							field_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY (field_id),
							KEY field_order (field_order)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."user_groups");
							$result = dbquery("CREATE TABLE ".$db_prefix."user_groups (
							group_id TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
							group_name VARCHAR(100) NOT NULL,
							group_description VARCHAR(200) NOT NULL,
							PRIMARY KEY (group_id)
							) ENGINE=MYISAM;");
							
							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."user_dellog");
							$result = dbquery("CREATE TABLE ".$db_prefix."user_dellog (
							userdellog_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							userdellog_user_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							userdellog_user_name VARCHAR(30) NOT NULL DEFAULT '',
							userdellog_user_email VARCHAR(100) NOT NULL DEFAULT '',
							userdellog_user_web VARCHAR(200) NOT NULL DEFAULT '',
							userdellog_user_ip VARCHAR(45) NOT NULL DEFAULT '0.0.0.0',
							userdellog_timestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY (userdellog_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."user_log");
							$result = dbquery("CREATE TABLE ".$db_prefix."user_log (
							userlog_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							userlog_user_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							userlog_field VARCHAR(50) NOT NULL DEFAULT '',
							userlog_value_new TEXT NOT NULL,
							userlog_value_old TEXT NOT NULL,
							userlog_timestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY (userlog_id),
							KEY userlog_user_id (userlog_user_id),
							KEY userlog_field (userlog_field)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."users");
							$result = dbquery("CREATE TABLE ".$db_prefix."users (
							user_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							user_name VARCHAR(30) NOT NULL DEFAULT '',
							user_algo VARCHAR(10) NOT NULL DEFAULT 'sha256',
							user_salt VARCHAR(40) NOT NULL DEFAULT '',
							user_password VARCHAR(64) NOT NULL DEFAULT '',
							user_admin_algo VARCHAR(10) NOT NULL DEFAULT 'sha256',
							user_admin_salt VARCHAR(40) NOT NULL DEFAULT '',
							user_admin_password VARCHAR(64) NOT NULL DEFAULT '',
							user_email VARCHAR(100) NOT NULL DEFAULT '',
							user_hide_email TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
							user_offset CHAR(5) NOT NULL DEFAULT '0',
							user_avatar VARCHAR(100) NOT NULL DEFAULT '',
							user_posts SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
							user_threads TEXT NOT NULL,
							user_joined INT(10) UNSIGNED NOT NULL DEFAULT '0',
							user_lastvisit INT(10) UNSIGNED NOT NULL DEFAULT '0',
							user_ip VARCHAR(45) NOT NULL DEFAULT '0.0.0.0',
							user_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
							user_rights TEXT NOT NULL,
							user_groups TEXT NOT NULL,
							user_level TINYINT(3) UNSIGNED NOT NULL DEFAULT '101',
							user_status TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
							user_actiontime INT(10) UNSIGNED NOT NULL DEFAULT '0',
							user_theme VARCHAR(100) NOT NULL DEFAULT 'Default',
							user_location VARCHAR(50) NOT NULL DEFAULT '',
							user_birthdate DATE NOT NULL DEFAULT '1901-01-01',
							user_gender TINYINT(1) NOT NULL DEFAULT '0',
							user_skype VARCHAR(100) NOT NULL DEFAULT '',
							user_aim VARCHAR(16) NOT NULL DEFAULT '',
							user_icq VARCHAR(15) NOT NULL DEFAULT '',
							user_yahoo VARCHAR(100) NOT NULL DEFAULT '',
							user_web VARCHAR(200) NOT NULL DEFAULT '',
							user_privacy TINYINT(1) NOT NULL DEFAULT '0',
							user_blocks TINYINT(1) NOT NULL DEFAULT '0',
							user_delete VARCHAR(16) NOT NULL DEFAULT '',
							user_sig TEXT NOT NULL,
							PRIMARY KEY (user_id),
							KEY user_name (user_name),
							KEY user_joined (user_joined),
							KEY user_lastvisit (user_lastvisit)
							) ENGINE=MYISAM;");
							
							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."users_blocks");
							$result = dbquery("CREATE TABLE ".$db_prefix."users_blocks (
							block_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							user_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
							blocked_user_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY (block_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."weblink_cats");
							$result = dbquery("CREATE TABLE ".$db_prefix."weblink_cats (
							weblink_cat_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							weblink_cat_name VARCHAR(100) NOT NULL DEFAULT '',
							weblink_cat_image VARCHAR(100) NOT NULL DEFAULT '',
							weblink_cat_description TEXT NOT NULL,
							weblink_cat_sorting VARCHAR(50) NOT NULL DEFAULT 'weblink_name ASC',
							weblink_cat_access TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY(weblink_cat_id)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."weblinks");
							$result = dbquery("CREATE TABLE ".$db_prefix."weblinks (
							weblink_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
							weblink_name VARCHAR(100) NOT NULL DEFAULT '',
							weblink_description TEXT NOT NULL,
							weblink_url VARCHAR(200) NOT NULL DEFAULT '',
							weblink_cat MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
							weblink_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
							weblink_count SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
							PRIMARY KEY(weblink_id),
							KEY weblink_datestamp (weblink_datestamp),
							KEY weblink_count (weblink_count)
							) ENGINE=MYISAM;");

							if (!$result) { $fail = true; }

							if (!$fail) {
								echo "<br />\n".$locale['040']."<br /><br />\n";
								echo $locale['041']."<br /><br />\n";
								echo $locale['042']."<br /><br />\n";
								$success = true;
								$db_error = 6;
							} else {
								echo "<br />\n".$locale['040']."<br /><br />\n";
								echo $locale['041']."<br /><br />\n";
								echo "<strong>".$locale['043']."</strong> ".$locale['048']."<br /><br />\n";
								$success = false;
								$db_error = 0;
							}
						} else {
							echo "<br />\n".$locale['040']."<br /><br />\n";
							echo "<strong>".$locale['043']."</strong> ".$locale['046']."<br />\n";
							echo "<span class='small'>".$locale['047']."</span><br /><br />\n";
							$success = false;
							$db_error = 5;
						}
					} else {
						echo "<br />\n".$locale['040']."<br /><br />\n";
						echo "<strong>".$locale['043']."</strong> ".$locale['054']."<br />\n";
						echo "<span class='small'>".$locale['055']."</span><br /><br />\n";
						$success = false;
						$db_error = 4;
					}
				} else {
					echo "<br />\n<strong>".$locale['043']."<strong> ".$locale['052']."<br />\n";
					echo "<span class='small'>".$locale['053']."</span><br /><br />\n";
					$success = false;
					$db_error = 3;
				}
			} else {
				echo "<br />\n<strong>".$locale['043']."<strong> ".$locale['050']."<br />\n";
				echo "<span class='small'>".$locale['051']."</span><br /><br />\n";
				$success = false;
				$db_error = 2;
			}
		} else {
			echo "<br />\n<strong>".$locale['043']."<strong> ".$locale['044']."<br />\n";
			echo "<span class='small'>".$locale['045']."</span><br /><br />\n";
			$success = false;
			$db_error = 1;
		}
	} else {
		echo "<br />\n<strong>".$locale['043']."<strong> ".$locale['056']."<br />\n";
		echo "<span class='small'>".$locale['057']."</span><br /><br />\n";
		$success = false;
		$db_error = 7;
	}
	echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
	echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."' />\n";
	if ($success) {
		echo "<input type='hidden' name='step' value='5' />\n";
		echo "<input type='submit' name='next' value='".$locale['007']."' class='button' />\n";
	} else {
		echo "<input type='hidden' name='step' value='3' />\n";
		echo "<input type='hidden' name='db_host' value='".$db_host."' />\n";
		echo "<input type='hidden' name='db_user' value='".$db_user."' />\n";
		echo "<input type='hidden' name='db_name' value='".$db_name."' />\n";
		echo "<input type='hidden' name='db_prefix' value='".$db_prefix."' />\n";
		echo "<input type='hidden' name='db_error' value='".$db_error."' />\n";
		echo "<input type='submit' name='next' value='".$locale['008']."' class='button' />\n";
	}
}

if (isset($_POST['step']) && $_POST['step'] == "5") {
	$username = (isset($_POST['username']) ? stripinput(trim($_POST['username'])) : "");
	$email = (isset($_POST['email']) ? stripinput(trim($_POST['email'])) : "");
	$error_pass = (isset($_POST['error_pass']) && isnum($_POST['error_pass']) ? $_POST['error_pass'] : "0");
	$error_name = (isset($_POST['error_name']) && isnum($_POST['error_name']) ? $_POST['error_name'] : "0");
	$error_mail = (isset($_POST['error_mail']) && isnum($_POST['error_mail']) ? $_POST['error_mail'] : "0");

	$field_class = array("", "", "", "", "", "");
	if ($error_pass == "1" || $error_name == "1" || $error_mail == "1") {
		$field_class = array("", " tbl-error", " tbl-error", " tbl-error", " tbl-error", "");
		if ($error_name == 1) { $field_class[0] = " tbl-error"; }
		if ($error_mail == 1) { $field_class[5] = " tbl-error"; }
	}

	echo $locale['060']."<br /><br />\n";
	echo "<table align='center' cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
	echo "<td class='tbl1'>".$locale['061']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='text' name='username' value='".$username."' maxlength='30' class='textbox".$field_class[0]."' style='width:200px' /></td></tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['062']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='password' name='password1' maxlength='64' class='textbox".$field_class[1]."' style='width:200px' /></td></tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['063']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='password' name='password2' maxlength='64' class='textbox".$field_class[2]."' style='width:200px' /></td></tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['064']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='password' name='admin_password1' maxlength='64' class='textbox".$field_class[3]."' style='width:200px' /></td></tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['065']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='password' name='admin_password2' maxlength='64' class='textbox".$field_class[4]."' style='width:200px' /></td></tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['066']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='text' name='email' value='".$email."' maxlength='100' class='textbox".$field_class[5]."' style='width:200px' /></td></tr>\n";
	echo "</table>\n";
	echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
	echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."' />\n";
	echo "<input type='hidden' name='step' value='6' />\n";
	echo "<input type='submit' name='next' value='".$locale['007']."' class='button' />\n";
}

if (isset($_POST['step']) && $_POST['step'] == "6") {
	require_once "config.php";
	require_once "includes/db_handlers/pdo_functions_include.php";
	$pdo = NULL;
	try {
		$pdo = new PDO("mysql:host=".$db_host.";dbname=".$db_name.";encoding=utf8", $db_user, $db_pass);
		$db_connect = $pdo;
		$db_select = "True";
	} catch (PDOException $e) {
		$db_connect = "False";
		$db_select = "False";
	}
	$error = ""; $error_pass = "0"; $error_name = "0"; $error_mail = "0"; $settings['password_algorithm'] = "sha256";

	$username = (isset($_POST['username']) ? stripinput(trim($_POST['username'])) : "");
	if ($username == "") {
		$error .= $locale['070b']."<br /><br />\n";
		$error_name = "1";
	} elseif (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username)) {
		$error .= $locale['070']."<br /><br />\n";
		$error_name = "1";
	}

	require_once "includes/classes/PasswordAuth.class.php";

	$userPassword = ""; $adminPassword = "";

	$userPass = new PasswordAuth();
	$userPass->inputNewPassword = (isset($_POST['password1']) ? stripinput(trim($_POST['password1'])) : "");
	$userPass->inputNewPassword2 = (isset($_POST['password2']) ? stripinput(trim($_POST['password2'])) : "");
	$returnValue = $userPass->isValidNewPassword();
	if ($returnValue == 0) {
		$userPassword = $userPass->getNewHash();
		$userSalt = $userPass->getNewSalt();
	} elseif ($returnValue == 2) {
		$error .= $locale['071']."<br /><br />\n";
		$error_pass = "1";
	} elseif ($returnValue == 3) {
		$error .= $locale['072']."<br /><br />\n";
	}

	$adminPass = new PasswordAuth();
	$adminPass->inputNewPassword = (isset($_POST['admin_password1']) ? stripinput(trim($_POST['admin_password1'])) : "");
	$adminPass->inputNewPassword2 = (isset($_POST['admin_password2']) ? stripinput(trim($_POST['admin_password2'])) : "");
	$returnValue = $adminPass->isValidNewPassword();
	if ($returnValue == 0) {
		$adminPassword = $adminPass->getNewHash();
		$adminSalt = $adminPass->getNewSalt();
	} elseif ($returnValue == 2) {
		$error .= $locale['073']."<br /><br />\n";
		$error_pass = "1";
	} elseif ($returnValue == 3) {
		$error .= $locale['075']."<br /><br />\n";
	}

	if ($userPass->inputNewPassword == $adminPass->inputNewPassword) {
			$error .= $locale['074']."<br /><br />\n";
			$error_pass = "1";
	}

	$email = (isset($_POST['email']) ? stripinput(trim($_POST['email'])) : "");

 	if ($email == "") {
		$error .= $locale['076b']."<br /><br />\n";
		$error_mail = "1";
	} elseif (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
		$error .= $locale['076']."<br /><br />\n";
		$error_mail = "1";
	}

	$rows = dbrows(dbquery("SELECT user_id FROM ".$db_prefix."users"));

	if ($error == "") {
		if ($rows == 0) {
			$siteurl = getCurrentURL();
			$url = parse_url($siteurl);
			//Settings
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('sitename', 'Harlekin-Power Powered Website')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('siteurl', '".$siteurl."')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('site_protocol', '".$url['scheme']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('site_database', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('site_host', '".$url['host']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('site_port', '".(isset($url['port']) ? $url['port'] : "")."')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('site_path', '".(isset($url['path']) ? $url['path'] : "")."')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('sitebanner', 'images/hp_logo.png')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('sitebanner1', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('sitebanner2', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('siteemail', '".$email."')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('siteusername', '".$username."')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('siteintro', '<div style=\'text-align:center\'>".$locale['230']."</div>')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('description', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('keywords', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('footer', '<div style=\'text-align:center\'>Copyright &copy; ".@date("Y")."</div>')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('opening_page', 'start.php')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_thumb_ratio', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_image_link', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_thumb_w', '100')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_thumb_h', '100')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_photo_max_w', '1800')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_photo_max_h', '1600')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_photo_max_b', '150000')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_closed', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_closed_reason', '".$locale['251']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('locale', '".stripinput($_POST['localeset'])."')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('theme', 'HP_Fusion_Red')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('font_awe', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('font_et', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('default_search', 'all')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('exclude_left', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('exclude_upper', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('exclude_lower', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('exclude_right', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('shortdate', '".$locale['shortdate']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('longdate', '".$locale['longdate']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('forumdate', '".$locale['forumdate']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('newsdate', '".$locale['newsdate']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('subheaderdate', '".$locale['subheaderdate']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('timeoffset', '0.0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('serveroffset', '0.0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('numofthreads', '15')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('forum_ips', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('attachmax', '150000')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('attachmax_count', '5')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('attachtypes', '.gif,.jpg,.png,.zip,.rar,.tar,.7z')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('thread_notify', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('forum_ranks', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('forum_edit_lock', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('forum_edit_timelimit', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('forum_editpost_to_lastpost', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('forum_last_posts_reply', '10')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('forum_closed', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('forum_closed_reason', '".$locale['252']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('enable_registration', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('email_verification', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('admin_activation', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('display_validation', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('enable_deactivation', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('deactivation_period', '365')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('deactivation_response', '14')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('enable_terms', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('license_agreement', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('license_lastupdate', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('thumb_w', '100')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('thumb_h', '100')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_w', '400')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_h', '300')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_max_w', '1800')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_max_h', '1600')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_max_b', '512000')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('thumb_compression', 'gd2')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('thumbs_per_row', '4')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('thumbs_per_page', '12')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_watermark', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_watermark_image', 'images/watermark.png')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_watermark_text', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_watermark_text_color1', 'FF6600')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_watermark_text_color2', 'FFFF00')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_watermark_text_color3', 'FFFFFF')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_watermark_save', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_closed', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('photo_closed_reason', '".$locale['253']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('article_closed', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('article_closed_reason', '".$locale['254']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('bbcode_editor_enabled', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('smtp_host', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('smtp_port', '25')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('smtp_username', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('smtp_password', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('bad_words_enabled', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('bad_words', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('bad_word_replace', '****')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('guestposts', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('comments_enabled', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('ratings_enabled', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('del_user_action', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('hide_userprofiles', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('userthemes', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('newsperpage', '11')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('flood_interval', '15')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('counter', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('version', 'HP-Fusion 1.3.3')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('maintenance', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('maintenance_message', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('download_max_b', '512000')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('download_types', '.pdf,.gif,.jpg,.png,.zip,.rar,.tar,.bz2,.7z')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('download_closed', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('download_closed_reason', '".$locale['250']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('articles_per_page', '15')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('downloads_per_page', '15')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('links_per_page', '15')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('comments_per_page', '10')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('comments_sorting', 'ASC')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('comments_avatar', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('avatar_width', '100')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('avatar_height', '100')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('avatar_filesize', '15000')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('avatar_ratio', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('cronjob_day', '".time()."')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('cronjob_hour', '".time()."')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('flood_autoban', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('visitorcounter_enabled', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('rendertime_enabled', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('popular_threads_timeframe', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('maintenance_level', '102')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_photo_w', '400')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_photo_h', '300')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_image_frontpage', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('news_image_readmore', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('deactivation_action', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('captcha', 'securimage2')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('password_algorithm', 'sha256')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('default_timezone', 'Europe/Berlin')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('userNameChange', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('download_screen_max_b', '150000')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('download_screen_max_w', '1024')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('download_screen_max_h', '768')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('recaptcha_public', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('recaptcha_private', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('recaptcha_theme', 'light')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('recaptcha_wait', '5')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('download_screenshot', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('download_thumb_max_w', '100')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('download_thumb_max_h', '100')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('multiple_logins', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('login_type', '0')");
			$result = dbquery("INSERT INTO ".$db_prefix."settings (settings_name, settings_value) VALUES ('smtp_auth', '0')");
			//Admin Rights
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('AD', 'admins.gif', '".$locale['080']."', 'administrators.php', '2')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('APWR', 'admin_pass.gif', '".$locale['128']."', 'admin_reset.php', '2')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('AC', 'article_cats.gif', '".$locale['081']."', 'article_cats.php', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('A', 'articles.gif', '".$locale['082']."', 'articles.php', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('SB', 'banners.gif', '".$locale['083']."', 'banners.php', '3')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('BB', 'bbcodes.gif', '".$locale['084']."', 'bbcodes.php', '3')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('BBR', 'bbr.gif', '".$locale['084b']."', 'bbcode_rights_a.php', '3')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('B', 'blacklist.gif', '".$locale['085']."', 'blacklist.php', '2')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('C', '', '".$locale['086']."', 'reserved', '2')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('CP', 'c-pages.gif', '".$locale['087']."', 'custom_pages.php', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('DB', 'db_backup.gif', '".$locale['088']."', 'db_backup.php', '3')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('DC', 'dl_cats.gif', '".$locale['089']."', 'download_cats.php', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('D', 'dl.gif', '".$locale['090']."', 'downloads.php', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('ERRO', 'errors.gif', '".$locale['129']."', 'errors.php', '3')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('FQ', 'faq.gif', '".$locale['091']."', 'faq.php', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('F', 'forums.gif', '".$locale['092']."', 'forums.php', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('IM', 'images.gif', '".$locale['093']."', 'images.php', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('I', 'infusions.gif', '".$locale['094']."', 'infusions.php', '3')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('IP', '', '".$locale['095']."', 'reserved', '3')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('M', 'members.gif', '".$locale['096']."', 'members.php', '2')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('NC', 'news_cats.gif', '".$locale['097']."', 'news_cats.php', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('N', 'news.gif', '".$locale['098']."', 'news.php', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('P', 'panels.gif', '".$locale['099']."', 'panels.php', '3')");

			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('PH', 'photoalbums.gif', '".$locale['100']."', 'photoalbums.php', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('PI', 'phpinfo.gif', '".$locale['101']."', 'phpinfo.php', '3')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('PO', 'polls.gif', '".$locale['102']."', 'polls.php', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('SL', 'site_links.gif', '".$locale['104']."', 'site_links.php', '3')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('SM', 'smileys.gif', '".$locale['105']."', 'smileys.php', '3')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('SU', 'submissions.gif', '".$locale['106']."', 'submissions.php', '2')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('U', 'upgrade.gif', '".$locale['107']."', 'upgrade.php', '3')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('UG', 'user_groups.gif', '".$locale['108']."', 'user_groups.php', '2')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('UU', 'unactivated_users.gif', '".$locale['096a']."', 'unactivated_users.php', '2')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('UDL', 'user_dellog.gif', '".$locale['096b']."', 'userdellog.php', '2')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('WC', 'wl_cats.gif', '".$locale['109']."', 'weblink_cats.php', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('W', 'wl.gif', '".$locale['110']."', 'weblinks.php', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S1', 'settings.gif', '".$locale['111']."', 'settings_main.php', '4')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S2', 'settings_time.gif', '".$locale['112']."', 'settings_time.php', '4')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S3', 'settings_forum.gif', '".$locale['113']."', 'settings_forum.php', '4')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S4', 'registration.gif', '".$locale['114']."', 'settings_registration.php', '4')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S5', 'settings_photo.gif', '".$locale['115']."', 'settings_photo.php', '4')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S6', 'settings_misc.gif', '".$locale['116']."', 'settings_misc.php', '4')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S7', 'settings_pm.gif', '".$locale['117']."', 'settings_messages.php', '4')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S8', 'settings_news.gif', '".$locale['121']."', 'settings_news.php', '4')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S9', 'settings_users.gif', '".$locale['122']."', 'settings_users.php', '4')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S10', 'settings_ipp.gif', '".$locale['124']."', 'settings_ipp.php', '4')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S11', 'settings_dl.gif', '".$locale['123']."', 'settings_dl.php', '4')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S12', 'security.gif', '".$locale['125']."', 'settings_security.php', '4')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('UF', 'user_fields.gif', '".$locale['118']."', 'user_fields.php', '2')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('FR', 'forum_ranks.gif', '".$locale['119']."', 'forum_ranks.php', '2')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('UFC', 'user_fields_cats.gif', '".$locale['120']."', 'user_field_cats.php', '2')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('UL', 'user_fields.gif', '".$locale['129a']."', 'user_log.php', '2')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('ROB', 'robots.gif', '".$locale['129b']."', 'robots.php', '3')");
			$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('PFSS', 'scoresystem.gif', '".$locale['300']."', '../moduls/scoresystem_panel/scoresystem_admin.php', '5')");
			//Site Admin
			$result = dbquery(
				"INSERT INTO ".$db_prefix."users (
					user_name, user_algo, user_salt, user_password, user_admin_algo, user_admin_salt, user_admin_password, user_email, user_hide_email, user_offset,
					user_avatar, user_posts, user_threads, user_joined, user_lastvisit, user_ip,
					user_rights,
					user_groups, user_level, user_status, user_theme, user_location, user_birthdate, user_aim,
					user_icq, user_yahoo, user_web, user_sig
				) VALUES (
					'".$username."', 'sha256', '".$userSalt."', '".$userPassword."', 'sha256', '".$adminSalt."', '".$adminPassword."', '".$email."', '1', '0',
					'',  '0', '', '".time()."', '0', '0.0.0.0',
					'A.AC.AD.APWR.B.BB.BBR.C.CP.DB.DC.D.ERRO.FQ.F.FR.IM.I.IP.M.N.NC.P.PFSS.PH.PI.PO.ROB.SL.S1.S2.S3.S4.S5.S6.S7.S8.S9.S10.S11.S12.SB.SM.SU.UDL.UF.UFC.UG.UL.UU.U.W.WC',
					'', '103', '0', 'Default', '', '1901-01-01', '', '', '', '', ''
				)"
			);
			//Mr X
			/*$result = dbquery(
				"INSERT INTO ".$db_prefix."users (
					user_name, user_algo, user_salt, user_password, user_admin_algo, user_admin_salt, user_admin_password, user_email, user_hide_email, user_location, user_birthdate, user_web, user_privacy, user_theme, user_offset, user_avatar, user_joined, user_lastvisit, user_sig, user_posts, user_ip, user_rights, user_groups, user_level, user_status
				) VALUES (
					'".$locale['260']."', 'sha256', '".$userSalt."', '".$userPassword."', 'sha256', '".$adminSalt."', '".$adminPassword."', '".$locale['261']."', '1', '".$locale['262']."', '1901-01-01', '".$locale['263']."', '102', 'Default', '0', 'unknown_user.png', '".time()."', '".time()."', '[b]".$locale['264']."[/b]', '0', '127.0.0.1', '', '', '103', '0'
				)"
			);*/
			$result = dbquery(
				"INSERT INTO ".$db_prefix."users (
					user_name, user_algo, user_salt, user_password, user_admin_algo, user_admin_salt, user_admin_password, user_email, user_hide_email, user_offset,
					user_avatar, user_posts, user_threads, user_joined, user_lastvisit, user_ip,
					user_rights,
					user_groups, user_level, user_status, user_theme, user_location, user_birthdate, user_aim,
					user_icq, user_yahoo, user_web, user_sig
				) VALUES (
					'".$locale['260']."', 'sha256', '".$userSalt."', '".$userPassword."', 'sha256', '".$adminSalt."', '".$adminPassword."', '".$locale['261']."', '1', '0',
					'unknown_user.png',  '0', '', '".time()."', '0', '0.0.0.0',
					'',
					'', '103', '0', 'Default', '', '1901-01-01', '', '', '', '', '[b]".$locale['264']."[/b]'
				)"
			);

			$result = dbquery("INSERT INTO ".$db_prefix."messages_options (user_id, pm_email_notify, pm_save_sent, pm_inbox, pm_savebox, pm_sentbox) VALUES ('0', '0', '1', '20', '20', '20')");
			//BB-Codes
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('smiley', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('b', '2')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('i', '3')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('u', '4')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('url', '5')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('mail', '6')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('img', '7')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('center', '8')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('small', '9')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('code', '10')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('quote', '11')");
			//BB-Codes Rights
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('103', '1', 'smiley')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('103', '1', 'b')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('103', '1', 'i')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('103', '1', 'u')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('103', '1', 'url')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('103', '1', 'mail')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('103', '1', 'img')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('103', '1', 'center')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('103', '1', 'small')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('103', '1', 'code')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('103', '1', 'quote')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('102', '1', 'smiley')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('102', '1', 'b')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('102', '1', 'i')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('102', '1', 'u')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('102', '1', 'url')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('102', '1', 'mail')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('102', '1', 'img')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('102', '1', 'center')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('102', '1', 'small')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('102', '1', 'code')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('102', '1', 'quote')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('101', '1', 'smiley')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('101', '1', 'b')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('101', '1', 'i')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('101', '1', 'u')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('101', '1', 'url')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('101', '1', 'mail')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('101', '1', 'img')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('101', '1', 'center')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('101', '1', 'small')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('101', '1', 'code')");
			$result = dbquery("INSERT INTO ".$db_prefix."bbcodes_rightsgroups (gid, perm, bbcode) VALUES ('101', '1', 'quote')");
			//Infusions
			$result = dbquery("INSERT INTO ".$db_prefix."infusions (inf_title, inf_folder, inf_version) VALUES ('ScoreSystem', 'scoresystem_panel', '1.2')");
			//Smileys
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':smile:', 'smile.gif', '".$locale['210']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':annoy:', 'annoy.gif', '".$locale['210a']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':flower:', 'flower.gif', '".$locale['210b']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':lol:', 'lol.gif', '".$locale['210c']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':roll:', 'roll.gif', '".$locale['210d']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':wink:', 'wink.gif', '".$locale['211']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':argue:', 'argue.gif', '".$locale['211a']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':secret:', 'secret.gif', '".$locale['211b']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':love:', 'love.gif', '".$locale['211c']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':sarcasm:', 'sarcasm.gif', '".$locale['211d']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':sad:', 'sad.gif', '".$locale['212']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':blushing:', 'blushing.gif', '".$locale['212a']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':happy:', 'happy.gif', '".$locale['212b']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':music:', 'music.gif', '".$locale['212c']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':stress:', 'stress.gif', '".$locale['212d']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':frown:', 'frown.gif', '".$locale['213']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':bugs:', 'bugs.gif', '".$locale['213a']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':happyness:', 'happyness.gif', '".$locale['213b']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':nose:', 'nose.gif', '".$locale['213c']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':up:', 'up.gif', '".$locale['213d']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':shock:', 'shock.gif', '".$locale['214']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':bye:', 'bye.gif', '".$locale['214a']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':hilarious:', 'hilarious.gif', '".$locale['214b']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':no:', 'no.gif', '".$locale['214c']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':wait:', 'wait.gif', '".$locale['214d']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':pfft:', 'pfft.gif', '".$locale['215']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':clap:', 'clap.gif', '".$locale['215a']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':hmm:', 'hmm.gif', '".$locale['215b']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':pc:', 'pc.gif', '".$locale['215c']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':xmas:', 'xmas.gif', '".$locale['215d']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':cool:', 'cool.gif', '".$locale['216']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':comfort:', 'comfort.gif', '".$locale['216a']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':dog:', 'dog.gif', '".$locale['216b']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':pic:', 'pic.gif', '".$locale['216c']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':yum:', 'yum.gif', '".$locale['216d']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':grin:', 'grin.gif', '".$locale['217']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':cry:', 'cry.gif', '".$locale['217a']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':np:', 'np.gif', '".$locale['217b']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':pray:', 'pray.gif', '".$locale['217c']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':zip:', 'zip.gif', '".$locale['217d']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':angry:', 'angry.gif', '".$locale['218']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':cup:', 'cup.gif', '".$locale['218a']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':kiss:', 'kiss.gif', '".$locale['218b']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':realsad:', 'realsad.gif', '".$locale['218c']."')");
			$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':ewink:', 'ewink.gif', '".$locale['218d']."')");
			//News Cats
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['180']."', 'bugs.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['181']."', 'downloads.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['182']."', 'games.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['183']."', 'graphics.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['184']."', 'hardware.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['185']."', 'journal.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['186']."', 'members.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['187']."', 'mods.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['188']."', 'movies.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['189']."', 'network.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['190']."', 'news.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['191']."', 'hp-fusion.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['192']."', 'security.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['193']."', 'software.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['194']."', 'themes.gif')");
			$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['195']."', 'windows.gif')");
			//Panels
			$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status, panel_url_list) VALUES ('".$locale['160']."', 'HP_side_navi_panel', '', '1', '1', 'file', '0', '0', '1', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status, panel_url_list) VALUES ('".$locale['161']."', 'online_users_panel', '', '1', '2', 'file', '0', '0', '1', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status, panel_url_list) VALUES ('".$locale['162']."', 'forum_threads_panel', '', '1', '3', 'file', '0', '0', '0', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status, panel_url_list) VALUES ('".$locale['163']."', 'latest_articles_panel', '', '1', '4', 'file', '0', '0', '0', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status, panel_url_list) VALUES ('".$locale['164']."', 'welcome_message_panel', '', '2', '1', 'file', '0', '0', '1', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status, panel_url_list) VALUES ('".$locale['165']."', 'forum_threads_list_panel', '', '2', '2', 'file', '0', '0', '0', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status, panel_url_list) VALUES ('".$locale['166']."', 'HP_user_info_panel', '', '4', 1, 'file', '0', '0', '1', '')");
			$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status, panel_url_list) VALUES ('".$locale['167']."', 'member_poll_panel', '', '4', '2', 'file', '0', '0', '0', '')");
			//Site Links Navigation
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_cat, link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('0', '".$locale['130']."', 'index.php', '0', '2', '0', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_cat, link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('0', '".$locale['145']."', '', '0', '2', '0', '2')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_cat, link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('2', '".$locale['131']."', 'articles.php', '0', '2', '0', '6')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_cat, link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('2', '".$locale['133']."', 'faq.php', '0', '2', '0', '7')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_cat, link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('2', '".$locale['134']."', 'forum/index.php', '0', '2', '0', '8')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_cat, link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('2', '".$locale['148']."', 'comments_show.php', '0', '2', '0', '9')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_cat, link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('2', '".$locale['136a']."', 'news.php', '0', '2', '0', '10')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_cat, link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('2', '".$locale['136']."', 'news_cats.php', '0', '2', '0', '11')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_cat, link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('2', '".$locale['138']."', 'photogallery.php', '0', '1', '0', '12')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_cat, link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('2', '".$locale['137']."', 'weblinks.php', '0', '2', '0', '13')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_cat, link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('0', '".$locale['132']."', '', '0', '2', '0', '3')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_cat, link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('11', '".$locale['132']."', 'downloads.php', '0', '2', '0', '15')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_cat, link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('0', '".$locale['146']."', '', '101', '2', '0', '4')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_cat, link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('13', '".$locale['140']."', 'submit.php?stype=l', '101', '2', '0', '16')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_cat, link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('13', '".$locale['141']."', 'submit.php?stype=n', '101', '1', '0', '17')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_cat, link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('13', '".$locale['142']."', 'submit.php?stype=a', '101', '2', '0', '18')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_cat, link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('13', '".$locale['143']."', 'submit.php?stype=p', '101', '2', '0', '19')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_cat, link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('13', '".$locale['144']."', 'submit.php?stype=d', '101', '2', '0', '20')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_cat, link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('0', '".$locale['147']."', '', '0', '2', '0', '5')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_cat, link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('19', '".$locale['135']."', 'contact.php', '0', '2', '0', '23')");
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_cat, link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('19', '".$locale['139']."', 'search.php', '0', '2', '0', '24')");
			//Userfield Cats
			$result = dbquery("INSERT INTO ".$db_prefix."user_field_cats (field_cat_id, field_cat_name, field_cat_order) VALUES (1, '".$locale['220']."', 1)");
			$result = dbquery("INSERT INTO ".$db_prefix."user_field_cats (field_cat_id, field_cat_name, field_cat_order) VALUES (2, '".$locale['221']."', 2)");
			$result = dbquery("INSERT INTO ".$db_prefix."user_field_cats (field_cat_id, field_cat_name, field_cat_order) VALUES (3, '".$locale['222']."', 3)");
			$result = dbquery("INSERT INTO ".$db_prefix."user_field_cats (field_cat_id, field_cat_name, field_cat_order) VALUES (4, '".$locale['223']."', 4)");
			//Userfields
			$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_required, field_order) VALUES ('user_location', '2', '0', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_required, field_order) VALUES ('user_birthdate', '2', '0', '2')");
			$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_required, field_order) VALUES ('user_gender', '2', '0', '3')");
			$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_required, field_order) VALUES ('user_sig', '2', '0', '4')");
			$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_required, field_order) VALUES ('user_skype', '1', '0', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_required, field_order) VALUES ('user_aim', '1', '0', '2')");
			$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_required, field_order) VALUES ('user_icq', '1', '0', '3')");
			$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_required, field_order) VALUES ('user_yahoo', '1', '0', '4')");
			$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_required, field_order) VALUES ('user_web', '1', '0', '5')");
			$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_required, field_order) VALUES ('user_privacy', '3', '0', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_required, field_order) VALUES ('user_blocks', '3', '0', '2')");
			$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_required, field_order) VALUES ('user_offset', '3', '0', '3')");
			$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_cat, field_required, field_order) VALUES ('user_delete', '3', '0', '4')");
			//Ranks
			$result = dbquery("INSERT INTO ".$db_prefix."forum_ranks VALUES (1, '".$locale['200']."', 'rank_super_admin.png', 0, '1', 103)");
			$result = dbquery("INSERT INTO ".$db_prefix."forum_ranks VALUES (2, '".$locale['201']."', 'rank_admin.png', 0, '1', 102)");
			$result = dbquery("INSERT INTO ".$db_prefix."forum_ranks VALUES (3, '".$locale['202']."', 'rank_mod.png', 0, '1', 104)");
			$result = dbquery("INSERT INTO ".$db_prefix."forum_ranks VALUES (4, '".$locale['203']."', 'rank0.png', 0, '0', 101)");
			$result = dbquery("INSERT INTO ".$db_prefix."forum_ranks VALUES (5, '".$locale['204']."', 'rank1.png', 10, '0', 101)");
			$result = dbquery("INSERT INTO ".$db_prefix."forum_ranks VALUES (6, '".$locale['205']."', 'rank2.png', 50, '0', 101)");
			$result = dbquery("INSERT INTO ".$db_prefix."forum_ranks VALUES (7, '".$locale['206']."', 'rank3.png', 200, '0', 101)");
			$result = dbquery("INSERT INTO ".$db_prefix."forum_ranks VALUES (8, '".$locale['207']."', 'rank4.png', 500, '0', 101)");
			$result = dbquery("INSERT INTO ".$db_prefix."forum_ranks VALUES (9, '".$locale['208']."', 'rank5.png', 1000, '0', 101)");
			//ScoreSystem
			$result = dbquery("INSERT INTO ".$db_prefix."score_score (sco_id, sco_aktion, sco_titel, sco_score, sco_max, sco_status, sco_power) VALUES ('1', 'GBUCH', '".$locale['301']."', '2', '1', '1', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."score_score (sco_id, sco_aktion, sco_titel, sco_score, sco_max, sco_status, sco_power) VALUES ('2', 'ARTIK', '".$locale['302']."', '2', '5', '0', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."score_score (sco_id, sco_aktion, sco_titel, sco_score, sco_max, sco_status, sco_power) VALUES ('3', 'NEWS', '".$locale['303']."', '2', '5', '0', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."score_score (sco_id, sco_aktion, sco_titel, sco_score, sco_max, sco_status, sco_power) VALUES ('4', 'FOVOT', '".$locale['304']."', '2', '2', '0', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."score_score (sco_id, sco_aktion, sco_titel, sco_score, sco_max, sco_status, sco_power) VALUES ('5', 'FOBEI', '".$locale['305']."', '2', '5', '0', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."score_score (sco_id, sco_aktion, sco_titel, sco_score, sco_max, sco_status, sco_power) VALUES ('6', 'FOTRD', '".$locale['306']."', '5', '3', '0', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."score_score (sco_id, sco_aktion, sco_titel, sco_score, sco_max, sco_status, sco_power) VALUES ('7', 'SHBOX', '".$locale['307']."', '2', '5', '0', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."score_score (sco_id, sco_aktion, sco_titel, sco_score, sco_max, sco_status, sco_power) VALUES ('8', 'GAMEK', '".$locale['308']."', '5', '5', '0', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."score_score (sco_id, sco_aktion, sco_titel, sco_score, sco_max, sco_status, sco_power) VALUES ('9', 'GAMEG', '".$locale['309']."', '5', '5', '0', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."score_score (sco_id, sco_aktion, sco_titel, sco_score, sco_max, sco_status, sco_power) VALUES ('10', 'GAMEH', '".$locale['310']."', '10', '5', '0', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."score_score (sco_id, sco_aktion, sco_titel, sco_score, sco_max, sco_status, sco_power) VALUES ('11', 'FOTOS', '".$locale['311']."', '2', '5', '0', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."score_score (sco_id, sco_aktion, sco_titel, sco_score, sco_max, sco_status, sco_power) VALUES ('12', 'LINKS', '".$locale['312']."', '2', '5', '0', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."score_score (sco_id, sco_aktion, sco_titel, sco_score, sco_max, sco_status, sco_power) VALUES ('13', 'PNSEN', '".$locale['313']."', '1', '2', '0', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."score_score (sco_id, sco_aktion, sco_titel, sco_score, sco_max, sco_status, sco_power) VALUES ('14', 'DOWNL', '".$locale['314']."', '10', '5', '0', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."score_score (sco_id, sco_aktion, sco_titel, sco_score, sco_max, sco_status, sco_power) VALUES ('15', 'LOGIN', '".$locale['315']."', '1', '3', '0', '1')");
			$result = dbquery("INSERT INTO ".$db_prefix."score_settings (set_id, set_delete, set_panel, set_user_transfer, set_user_tra_sco, set_units, set_user_chance, set_sco_power, set_top_user) VALUES ('1', '0', '5', '0', '5', '".$locale['316']."', '0', '0', '0')");
		}
		
		// create .htaccess
		$settings = array();
		$result = dbquery("SELECT * FROM ".$db_prefix."settings");
		if (dbrows($result)) {
			while ($data = dbarray($result)) {
				$settings[$data['settings_name']] = $data['settings_value'];
			}
		} else {
			die("Settings do not exist, please check your config.php file or run setup.php again.");
		}
		
		$htc = "";
		if (!file_exists(".htaccess")) {
			if (file_exists("_htaccess") && function_exists("rename")) {
				@rename("_htaccess", ".htaccess");
			} else {
			// create a file.
				$handle = fopen(".htaccess", "w");
				fclose($handle);
			}
		}

		//htaccess rules
		$htc = "RewriteEngine On\r\n\r\n";
		
		$htc .= "#Security\r\n";
		$htc .= "ServerSignature Off\r\n\r\n";

		$htc .= "#Secure htaccess file\r\n";
		$htc .= "<Files .htaccess>\r\n";
		$htc .= " order allow,deny\r\n";
		$htc .= " deny from all\r\n";
		$htc .= "</Files>\r\n\r\n";

		$htc .= "#Protect config.php\r\n";
		$htc .= "<Files config.php>\r\n";
		$htc .= " order allow,deny\r\n";
		$htc .= " deny from all\r\n";
		$htc .= "</Files>\r\n\r\n";
		
		$htc .= "#ErrorDocuments file\r\n";
		$htc .= "ErrorDocument 400 ".$settings['site_path']."error.php?code=400\r\n";
		$htc .= "ErrorDocument 401 ".$settings['site_path']."error.php?code=401\r\n";
		$htc .= "ErrorDocument 403 ".$settings['site_path']."error.php?code=403\r\n";
		$htc .= "ErrorDocument 404 ".$settings['site_path']."error.php?code=404\r\n";
		$htc .= "ErrorDocument 500 ".$settings['site_path']."error.php?code=500\r\n\r\n";
		
		$htc .= "#For files and directories that doesn'exit\r\n";
		$htc .= "<IfModule mod_rewrite.c>\r\n";
		$htc .= " RewriteCond %{REQUEST_FILENAME} !-f\r\n";
		$htc .= " RewriteCond %{REQUEST_FILENAME} !-d\r\n";
		$htc .= " RewriteRule ^(.*)$ /error.php?code=404 [R=301,L]\r\n";
		$htc .= "</IfModule>\r\n\r\n";
		
		$htc .= "#Security Announcements regarding ImageMime Exploits start\r\n";
		$htc .= "<FilesMatch '(?i).jpe?g$'>\r\n";
		$htc .= " ForceType image/jpeg\r\n";
		$htc .= "</FilesMatch>\r\n";
		$htc .= "<FilesMatch '(?i).gif$'>\r\n";
		$htc .= " ForceType image/gif\r\n";
		$htc .= "</FilesMatch>\r\n";
		$htc .= "<FilesMatch '(?i).png$'>\r\n";
		$htc .= " ForceType image/png\r\n";
		$htc .= "</FilesMatch>\r\n";
		
		$temp = fopen(".htaccess", "w");
		if (fwrite($temp, $htc)) {
			fclose($temp);
		}						

		if (function_exists("chmod")) { @chmod("config.php", 0644); }

		echo "<br />\n".$locale['240']."<br /><br />\n";
		echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
		echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."' />\n";
		echo "<input type='hidden' name='step' value='7' />\n";
		echo "<input type='submit' name='next' value='".$locale['009']."' class='button' />\n";
	} elseif ($rows == 0) {
		echo "<br />\n".$locale['077']."<br /><br />\n".$error;
		echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
		echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."' />\n";
		echo "<input type='hidden' name='error_pass' value='".$error_pass."' />\n";
		echo "<input type='hidden' name='error_name' value='".$error_name."' />\n";
		echo "<input type='hidden' name='error_mail' value='".$error_mail."' />\n";
		echo "<input type='hidden' name='username' value='".$username."' />\n";
		echo "<input type='hidden' name='email' value='".$email."' />\n";
		echo "<input type='hidden' name='step' value='5' />\n";
		echo "<input type='submit' name='back' value='".$locale['008']."' class='button' />\n";
	} else {
		echo "<br />\n".$locale['240']."<br /><br />\n";
		echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
		echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."' />\n";
		echo "<input type='hidden' name='step' value='7' />\n";
		echo "<input type='submit' name='next' value='".$locale['009']."' class='button' />\n";
	}
}

echo "</td>\n</tr>\n";
echo "</table>\n</form>\n";

echo "<br />";

echo "</body>\n</html>\n";

// Calculate script start/end time
function get_microtime() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec+(float)$sec);
}

// Strip Input Function, prevents HTML in unwanted places
function stripinput($text) {
	if (ini_get('magic_quotes_gpc')) $text = stripslashes($text);
	$search = array("\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
	$replace = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
	$text = str_replace($search, $replace, $text);
	return $text;
}

// Validate numeric input
function isnum($value) {
	if (!is_array($value)) {
		return (preg_match("/^[0-9]+$/", $value));
	} else {
		return false;
	}
}

// Create a list of files or folders and store them in an array
function makefilelist($folder, $filter, $sort=true, $type="files") {
	$res = array();
	$filter = explode("|", $filter);
	$temp = opendir($folder);
	while ($file = readdir($temp)) {
		if ($type == "files" && !in_array($file, $filter)) {
			if (!is_dir($folder.$file)) $res[] = $file;
		} elseif ($type == "folders" && !in_array($file, $filter)) {
			if (is_dir($folder.$file)) $res[] = $file;
		}
	}
	closedir($temp);
	if ($sort) sort($res);
	return $res;
}

// Create a selection list from an array created by makefilelist()
function makefileopts($files, $selected = "") {
	$res = "";
	for ($i=0; $i < count($files); $i++) {
		$sel = ($selected == $files[$i] ? " selected='selected'" : "");
		$res .= "<option value='".$files[$i]."'$sel>".$files[$i]."</option>\n";
	}
	return $res;
}

// Clean URL Function, prevents entities in server globals
function cleanurl($url) {
	$bad_entities = array("&", "\"", "'", '\"', "\'", "<", ">", "(", ")", "*");
	$safe_entities = array("&amp;", "", "", "", "", "", "", "", "", "");
	$url = str_replace($bad_entities, $safe_entities, $url);
	return $url;
}

// Get Current URL
function getCurrentURL() {
#	$s = ( empty($_SERVER["HTTPS"]) ? "" : ($_SERVER["HTTPS"] == "on") ? "s" : "");
    $s = (empty( $_SERVER[ 'HTTPS' ] ) ? '' : (( $_SERVER[ 'HTTPS' ] == 'on' ) ? 's' : '')); // FIX php 8 version
	$protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
	return $protocol."://".$_SERVER['SERVER_NAME'].$port.(str_replace(basename(cleanurl($_SERVER['PHP_SELF'])), "", $_SERVER['REQUEST_URI']));
}

function strleft($s1, $s2) {
	return substr($s1, 0, strpos($s1, $s2));
}
?>
