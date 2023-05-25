<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: settings_users.php
| Author: Paul Beuk (muscapaul)
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

if (!checkrights("S9") || !defined("iAUTH") || !isset($_GET['aid']) || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

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

if (isset($_POST['savesettings'])) {
	$error = 0;

	if ($_POST['enable_deactivation'] == '0') {
		$result = dbquery("UPDATE ".DB_USERS." SET user_status='0' WHERE user_status='5'");
		if (!$result) { $error = 1; }
	}

	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['enable_deactivation']) ? $_POST['enable_deactivation'] : "0")."' WHERE settings_name='enable_deactivation'");
	if (!$result) { $error = 1; }
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['deactivation_period']) ? $_POST['deactivation_period'] : "365")."' WHERE settings_name='deactivation_period'");
	if (!$result) { $error = 1; }
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['deactivation_response']) ? $_POST['deactivation_response'] : "14")."' WHERE settings_name='deactivation_response'");
	if (!$result) { $error = 1; }
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['deactivation_action']) ? $_POST['deactivation_action'] : "0")."' WHERE settings_name='deactivation_action'");
	if (!$result) { $error = 1; }
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['del_user_action']) ? $_POST['del_user_action'] : "0")."' WHERE settings_name='del_user_action'");
	if (!$result) { $error = 1; }
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['hide_userprofiles']) ? $_POST['hide_userprofiles'] : "0")."' WHERE settings_name='hide_userprofiles'");
	if (!$result) { $error = 1; }
	if ($_POST['avatar_filesize'] <= $upload_max_filesize) {
		$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['avatar_filesize']) ? $_POST['avatar_filesize'] : "15000")."' WHERE settings_name='avatar_filesize'");
	} else {
		$error = 2;	
	}
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['avatar_width']) ? $_POST['avatar_width'] : "100")."' WHERE settings_name='avatar_width'");
	if (!$result) { $error = 1; }
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['avatar_height']) ? $_POST['avatar_height'] : "100")."' WHERE settings_name='avatar_height'");
	if (!$result) { $error = 1; }
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['avatar_ratio']) ? $_POST['avatar_ratio'] : "0")."' WHERE settings_name='avatar_ratio'");
	if (!$result) { $error = 1; }
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['userNameChange']) ? $_POST['userNameChange'] : "0")."' WHERE settings_name='userNameChange'");
	if (!$result) { $error = 1; }
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['userthemes']) ? $_POST['userthemes'] : "0")."' WHERE settings_name='userthemes'");
	if (isset($_POST['userthemes']) && $_POST['userthemes'] == '0') {
		$result = dbquery("UPDATE ".DB_USERS." SET user_theme='Default'");
	}
	if (!$result) { $error = 1; }
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['multiple_logins']) ? $_POST['multiple_logins'] : "0")."' WHERE settings_name='multiple_logins'");
	if (!$result) { $error = 1; }
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['login_type']) ? $_POST['login_type'] : "0")."' WHERE settings_name='login_type'");
	if (!$result) { $error = 1; }

	redirect(FUSION_SELF.$aidlink."&error=".$error);
}

if (isset($_GET['error']) && isnum($_GET['error']) && !isset($message)) {
	if ($_GET['error'] == 0) {
		$message = $locale['900'];
	} elseif ($_GET['error'] == 1) {
		$message = $locale['901'];
	} elseif ($_GET['error'] == 2) {
		$message = $locale['903'];
	}
	if (isset($message)) {
		echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n";
	}
}

opentable($locale['400']);
echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='500' class='center'>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['1002']."</td>\n";
echo "<td class='tbl' width='50%'><select name='enable_deactivation' class='textbox'>\n";
echo "<option value='0'".($settings['enable_deactivation'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "<option value='1'".($settings['enable_deactivation'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['1003']."<br /><span class='small2'>(".$locale['1004'].")</span></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='deactivation_period' value='".$settings['deactivation_period']."' maxlength='3' class='textbox' style='width:30px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['1005']."<br /><span class='small2'>(".$locale['1006'].")</span></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='deactivation_response' value='".$settings['deactivation_response']."' maxlength='3' class='textbox' style='width:30px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['1011']."</td>\n";
echo "<td class='tbl' width='50%'><select name='deactivation_action' class='textbox'>\n";
echo "<option value='0'".($settings['deactivation_action'] == "0" ? " selected='selected'" : "").">".$locale['1012']."</option>\n";
echo "<option value='1'".($settings['deactivation_action'] == "1" ? " selected='selected'" : "").">".$locale['1013']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl2' align='center' colspan='2'>".$locale['1007']."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['1015']."</td>\n";
echo "<td class='tbl' width='50%'><select name='del_user_action' class='textbox'>\n";
echo "<option value='0'".($settings['del_user_action'] == "0" ? " selected='selected'" : "").">".$locale['1016']."</option>\n";
echo "<option value='1'".($settings['del_user_action'] == "1" ? " selected='selected'" : "").">".$locale['1017']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['673']."</td>\n";
echo "<td width='50%' class='tbl'><select name='hide_userprofiles' class='textbox'>\n";
echo "<option value='1'".($settings['hide_userprofiles'] == 1 ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['hide_userprofiles'] == 0 ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['1008']."<br /><span class='small2'>(".$locale['1009'].")</span></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='avatar_width' value='".$settings['avatar_width']."' maxlength='3' class='textbox' style='width:40px;' /> x\n";
echo "<input type='text' name='avatar_height' value='".$settings['avatar_height']."' maxlength='3' class='textbox' style='width:40px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['1010']."<br /><span class='small2'>".$locale['931']."</span></td>\n";
echo "<td width='50%' class='tbl'>".$locale['942']." ".$upload_max_filesize." = ".parsebytesize($upload_max_filesize)."<br /><input type='text' name='avatar_filesize' value='".$settings['avatar_filesize']."' maxlength='10' class='textbox' style='width:100px;' /> = ".parsebytesize($settings['avatar_filesize'])."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['1001']."</td>\n";
echo "<td width='50%' class='tbl'><select name='avatar_ratio' class='textbox'>\n";
echo "<option value='0'".($settings['avatar_ratio'] == 0 ? " selected='selected'" : "").">".$locale['955']."</option>\n";
echo "<option value='1'".($settings['avatar_ratio'] == 1 ? " selected='selected'" : "").">".$locale['956']."</option>\n";
echo "</select>\n</td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['691']."?</td>\n";
echo "<td width='50%' class='tbl'><select name='userNameChange' class='textbox'>\n";
echo "<option value='1'".($settings['userNameChange'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['userNameChange'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['668']."?</td>\n";
echo "<td width='50%' class='tbl'><select name='userthemes' class='textbox'>\n";
echo "<option value='1'".($settings['userthemes'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['userthemes'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['1014']."<br /><span class='small2'>(".$locale['1014a'].")</span></td>\n";
echo "<td width='50%' class='tbl'><select name='multiple_logins' class='textbox'>\n";
echo "<option value='1'".($settings['multiple_logins'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['multiple_logins'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['1018']."</td>\n";
echo "<td width='50%' class='tbl'><select name='login_type' class='textbox'>\n";
echo "<option value='0'".($settings['login_type'] == "0" ? " selected='selected'" : "").">".$locale['1019']."</option>\n";
echo "<option value='1'".($settings['login_type'] == "1" ? " selected='selected'" : "").">".$locale['1020']."</option>\n";
echo "<option value='2'".($settings['login_type'] == "2" ? " selected='selected'" : "").">".$locale['1021']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />\n";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

require_once DESIGNS."templates/footer.php";
?>
