<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: settings_dl.php
| Author: Hans Kristian Flaatten (Starefossen)
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
require_once "../maincore.php";
require_once INCLUDES."bbcode_include.php";

if (!checkrights("S11") || !defined("iAUTH") || !isset($_GET['aid']) || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

require_once DESIGNS."templates/admin_header.php";
include LOCALE.LOCALESET."admin/settings.php";

function get_upload_max_filesize()
{
	$size = trim(ini_get("upload_max_filesize"));

	if(!empty($size)) {
		$last = strtolower(substr($size, -1));

		switch($last) {
		case 'g': return intval($size) * 1073741824;
		case 'm': return intval($size) * 1048576;
		case 'k': return intval($size) * 1024;
		}
	}

	return intval($size);
}
$upload_max_filesize = get_upload_max_filesize();

if (isset($_GET['error']) && isnum($_GET['error']) && !isset($message)) {
	if ($_GET['error'] == 0) {
		$message = $locale['900'];
	} elseif ($_GET['error'] == 1) {
		$message = $locale['901'];
	} elseif ($_GET['error'] == 2) {
		$message = $locale['global_182'];
	} elseif ($_GET['error'] == 3) {
		$message = $locale['903'];
	}
	if (isset($message)) {
		echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n";
	}
}

if (isset($_POST['savesettings'])) {
	$error = 0;
	if (check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
		if ($_POST['download_max_b'] <= $upload_max_filesize) {
			$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['download_max_b']) ? $_POST['download_max_b'] : "150000")."' WHERE settings_name='download_max_b'");
		} else {
			$error = 3;	
		}
		$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".stripinput($_POST['download_types'])."' WHERE settings_name='download_types'");
		if (!$result) { $error = 1; }
		$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['download_screen_max_w']) ? $_POST['download_screen_max_w'] : "0")."' WHERE settings_name='download_screen_max_w'");
		if (!$result) { $error = 1; }
		$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['download_screen_max_h']) ? $_POST['download_screen_max_h'] : "0")."' WHERE settings_name='download_screen_max_h'");
		if (!$result) { $error = 1; }
		if ($_POST['download_screen_max_b'] <= $upload_max_filesize) {
			$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['download_screen_max_b']) ? $_POST['download_screen_max_b'] : "0")."' WHERE settings_name='download_screen_max_b'");
		} else {
			$error = 3;	
		}
		$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['download_thumb_max_h']) ? $_POST['download_thumb_max_h'] : "100")."' WHERE settings_name='download_thumb_max_h'");
		if (!$result) { $error = 1; }
		$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['download_thumb_max_w']) ? $_POST['download_thumb_max_w'] : "100")."' WHERE settings_name='download_thumb_max_w'");
		if (!$result) { $error = 1; }
		$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".($_POST['download_screenshot'] == 0 || $_POST['download_screenshot'] == 1 ? $_POST['download_screenshot'] : "0")."' WHERE settings_name='download_screenshot'");
		if (!$result) { $error = 1; }
		$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".($_POST['download_closed'] == 0 || $_POST['download_closed'] == 1 ? $_POST['download_closed'] : "0")."' WHERE settings_name='download_closed'");
		if (!$result) { $error = 1; }
		$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".stripinput($_POST['download_closed_reason'])."' WHERE settings_name='download_closed_reason'");
		if (!$result) { $error = 1; }
		set_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "");
		redirect(FUSION_SELF.$aidlink."&error=".$error, true);
	} else {
		redirect(FUSION_SELF.$aidlink."&error=2");
	}
}

$settings2 = array();
$result = dbquery("SELECT * FROM ".DB_SETTINGS);
while ($data = dbarray($result)) {
	$settings2[$data['settings_name']] = $data['settings_value'];
}

opentable($locale['400']);
echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='600' class='center'>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['930']."<br /><span class='small2'>".$locale['931']."</span></td>\n";
echo "<td width='50%' class='tbl'>".$locale['942']." ".$upload_max_filesize." = ".parsebytesize($upload_max_filesize)."<br /><input type='text' name='download_max_b' value='".$settings2['download_max_b']."' maxlength='150' class='textbox' style='width:100px;' /> = ".parsebytesize($settings2['download_max_b'])."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['932']."<br /><span class='small2'>".$locale['933']."</span></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='download_types' value='".$settings2['download_types']."' maxlength='150' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['934']."<br /><span class='small2'>".$locale['935']."</span></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='download_screen_max_w' value='".$settings2['download_screen_max_w']."' maxlength='4' class='textbox' style='width:40px;' /> x\n";
echo "<input type='text' name='download_screen_max_h' value='".$settings2['download_screen_max_h']."' maxlength='4' class='textbox' style='width:40px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['936']."<br /><span class='small2'>".$locale['931']."</span></td>\n";
echo "<td width='50%' class='tbl'>".$locale['942']." ".$upload_max_filesize." = ".parsebytesize($upload_max_filesize)."<br /><input type='text' name='download_screen_max_b' value='".$settings2['download_screen_max_b']."' maxlength='10' class='textbox' style='width:100px;' /> = ".parsebytesize($settings2['download_screen_max_b'])."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['937']."<br /><span class='small2'>".$locale['935']."</span></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='download_thumb_max_w' value='".$settings2['download_thumb_max_w']."' maxlength='4' class='textbox' style='width:40px;' /> x\n";
echo "<input type='text' name='download_thumb_max_h' value='".$settings2['download_thumb_max_h']."' maxlength='4' class='textbox' style='width:40px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['938']."<br /></td>\n";
echo "<td width='50%' class='tbl'><select name='download_screenshot' size='1' class='textbox' style='width:100px;'>";
echo "<option value='1' ".($settings['download_screenshot'] ? "selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0' ".(!$settings['download_screenshot'] ? "selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['939']."<br /></td>\n";
echo "<td width='50%' class='tbl'><select name='download_closed' size='1' class='textbox' style='width:100px;'>";
echo "<option value='1' ".($settings['download_closed'] ? "selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0' ".(!$settings['download_closed'] ? "selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl' valign='top'>".$locale['940']."<br /><span class='small2'>".$locale['941']."</span></td>\n";
echo "<td width='50%' class='tbl' valign='top'><textarea name='download_closed_reason' cols='50' rows='6' class='textbox' style='width:230px;'>".$settings2['download_closed_reason']."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'></td><td width='50%' class='tbl'>\n";
echo display_bbcodes("100%", "download_closed_reason", "settingsform")."</td>\n";
echo "</tr>\n<tr>\n";
if (!check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
	echo "<td class='tbl'>".$locale['853']."</td>\n";
	echo "<td class='tbl'><input type='password' name='admin_password' value='".(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")."' class='textbox' style='width:150px;' autocomplete='off' /></td>\n";
	echo "</tr>\n<tr>\n";
}
echo "<td align='center' colspan='2' class='tbl'><br />\n";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

require_once DESIGNS."templates/footer.php";
?>