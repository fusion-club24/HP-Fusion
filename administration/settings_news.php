<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: settings_news.php
| Author: Starefossen
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

if (!checkRights("S8") || !defined("iAUTH") || !isset($_GET['aid']) || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

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
		$message = $locale['903'];
	}
	if (isset($message)) {
		echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n";
	}
}

if (isset($_POST['savesettings'])) {
	$error = 0;
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['news_image_link']) ? $_POST['news_image_link'] : "0")."' WHERE settings_name='news_image_link'");
	if (!$result) { $error = 1; }
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['news_image_frontpage']) ? $_POST['news_image_frontpage'] : "0")."' WHERE settings_name='news_image_frontpage'");
	if (!$result) { $error = 1; }
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['news_image_readmore']) ? $_POST['news_image_readmore'] : "0")."' WHERE settings_name='news_image_readmore'");
	if (!$result) { $error = 1; }
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['news_thumb_ratio']) ? $_POST['news_thumb_ratio'] : "0")."' WHERE settings_name='news_thumb_ratio'");
	if (!$result) { $error = 1; }
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['news_thumb_w']) ? $_POST['news_thumb_w'] : "100")."' WHERE settings_name='news_thumb_w'");
	if (!$result) { $error = 1; }
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['news_thumb_h']) ? $_POST['news_thumb_h'] : "100")."' WHERE settings_name='news_thumb_h'");
	if (!$result) { $error = 1; }
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['news_photo_w']) ? $_POST['news_photo_w'] : "400")."' WHERE settings_name='news_photo_w'");
	if (!$result) { $error = 1; }
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['news_photo_h']) ? $_POST['news_photo_h'] : "300")."' WHERE settings_name='news_photo_h'");
	if (!$result) { $error = 1; }
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['news_photo_max_w']) ? $_POST['news_photo_max_w'] : "1800")."' WHERE settings_name='news_photo_max_w'");
	if (!$result) { $error = 1; }
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['news_photo_max_h']) ? $_POST['news_photo_max_h'] : "1600")."' WHERE settings_name='news_photo_max_h'");
	if (!$result) { $error = 1; }
	if ($_POST['news_photo_max_b'] <= $upload_max_filesize) {
		$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(isnum($_POST['news_photo_max_b']) ? $_POST['news_photo_max_b'] : "150000")."' WHERE settings_name='news_photo_max_b'");
	} else {
		$error = 2;	
	}
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".($_POST['news_closed'] == 0 || $_POST['news_closed'] == 1 ? $_POST['news_closed'] : "0")."' WHERE settings_name='news_closed'");
	if (!$result) { $error = 1; }
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".stripinput($_POST['news_closed_reason'])."' WHERE settings_name='news_closed_reason'");
	if (!$result) { $error = 1; }
	redirect(FUSION_SELF.$aidlink."&error=".$error);
}

$settings2 = array();
$result = dbquery("SELECT * FROM ".DB_SETTINGS);
while ($data = dbarray($result)) {
	$settings2[$data['settings_name']] = $data['settings_value'];
}

opentable($locale['400']);
echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='500' class='center'>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['951']."</td>\n";
echo "<td width='50%' class='tbl'><select name='news_image_link' class='textbox'>\n";
echo "<option value='0'".($settings2['news_image_link'] == 0 ? " selected='selected'" : "").">".$locale['952']."</option>\n";
echo "<option value='1'".($settings2['news_image_link'] == 1 ? " selected='selected'" : "").">".$locale['953']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['957']."</td>\n";
echo "<td width='50%' class='tbl'><select name='news_image_frontpage' class='textbox'>\n";
echo "<option value='0'".($settings2['news_image_frontpage'] == 0 ? " selected='selected'" : "").">".$locale['959']."</option>\n";
echo "<option value='1'".($settings2['news_image_frontpage'] == 1 ? " selected='selected'" : "").">".$locale['960']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['958']."</td>\n";
echo "<td width='50%' class='tbl'><select name='news_image_readmore' class='textbox'>\n";
echo "<option value='0'".($settings2['news_image_readmore'] == 0 ? " selected='selected'" : "").">".$locale['959']."</option>\n";
echo "<option value='1'".($settings2['news_image_readmore'] == 1 ? " selected='selected'" : "").">".$locale['960']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl2' align='center' colspan='2'>".$locale['950']."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['954']."</td>\n";
echo "<td width='50%' class='tbl'><select name='news_thumb_ratio' class='textbox'>\n";
echo "<option value='0'".($settings2['news_thumb_ratio'] == 0 ? " selected='selected'" : "").">".$locale['955']."</option>\n";
echo "<option value='1'".($settings2['news_thumb_ratio'] == 1 ? " selected='selected'" : "").">".$locale['956']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['601']."<br /><span class='small2'>".$locale['604']."</span></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='news_thumb_w' value='".$settings2['news_thumb_w']."' maxlength='3' class='textbox' style='width:40px;' /> x\n";
echo "<input type='text' name='news_thumb_h' value='".$settings2['news_thumb_h']."' maxlength='3' class='textbox' style='width:40px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['602']."<br /><span class='small2'>".$locale['604']."</span></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='news_photo_w' value='".$settings2['news_photo_w']."' maxlength='3' class='textbox' style='width:40px;' /> x\n";
echo "<input type='text' name='news_photo_h' value='".$settings2['news_photo_h']."' maxlength='3' class='textbox' style='width:40px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['603']."<br /><span class='small2'>".$locale['604']."</span></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='news_photo_max_w' value='".$settings2['news_photo_max_w']."' maxlength='4' class='textbox' style='width:40px;' /> x\n";
echo "<input type='text' name='news_photo_max_h' value='".$settings2['news_photo_max_h']."' maxlength='4' class='textbox' style='width:40px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['605']."<br /><span class='small2'>".$locale['931']."</span></td>\n";
echo "<td width='50%' class='tbl'>".$locale['942']." ".$upload_max_filesize." = ".parsebytesize($upload_max_filesize)."<br /><input type='text' name='news_photo_max_b' value='".$settings2['news_photo_max_b']."' maxlength='10' class='textbox' style='width:100px;' /> = ".parsebytesize($settings2['news_photo_max_b'])."</td>\n";
echo "</tr>\n<tr>\n";

echo "<td width='50%' class='tbl'>".$locale['961']."<br /></td>\n";
echo "<td width='50%' class='tbl'><select name='news_closed' size='1' class='textbox' style='width:100px;'>";
echo "<option value='1' ".($settings['news_closed'] ? "selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0' ".(!$settings['news_closed'] ? "selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl' valign='top'>".$locale['962']."<br /><span class='small2'>".$locale['963']."</span></td>\n";
echo "<td width='50%' class='tbl' valign='top'><textarea name='news_closed_reason' cols='50' rows='6' class='textbox' style='width:230px;'>".$settings2['news_closed_reason']."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'></td><td width='50%' class='tbl'>\n";
echo display_bbcodes("100%", "news_closed_reason", "settingsform")."</td>\n";
echo "</tr>\n<tr>\n";

echo "<td align='center' colspan='2' class='tbl'><br />\n";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' />\n</td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

require_once DESIGNS."templates/footer.php";
?>
