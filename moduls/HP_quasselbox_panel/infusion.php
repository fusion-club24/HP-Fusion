<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: infusion.php
| Author: Harlekin
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

include MODULS."HP_quasselbox_panel/infusion_db.php";

// Check if locale file is available matching the current site locale setting.
if (file_exists(MODULS."HP_quasselbox_panel/locale/".$settings['locale'].".php")) {
	// Load the locale file matching the current site locale setting.
	include MODULS."HP_quasselbox_panel/locale/".$settings['locale'].".php";
} else {
	// Load the infusion's default locale file.
	include MODULS."HP_quasselbox_panel/locale/English.php";
}

// Infusion general information
$inf_title = $locale['HPQB_title'];
$inf_description = $locale['HPQB_desc'];
$inf_version = "1.00";
$inf_developer = "Harlekin";
$inf_email = "harlekin67@gmx.de";
$inf_weburl = "http://harlekinpower.de";

$inf_folder = "HP_quasselbox_panel"; // The folder in which the infusion resides.

// Delete any items not required below.
$inf_newtable[1] = DB_HP_QUASSELBOX." (
quassel_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
quassel_name VARCHAR(50) NOT NULL DEFAULT '',
quassel_read_access SMALLINT(5) UNSIGNED NOT NULL,
quassel_message VARCHAR(200) NOT NULL DEFAULT '',
quassel_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
quassel_ip VARCHAR(45) NOT NULL DEFAULT '',
quassel_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
quassel_hidden TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
PRIMARY KEY (quassel_id),
KEY quassel_datestamp (quassel_datestamp)
) ENGINE=MyISAM;";

$inf_newtable[2] = DB_HP_QUASSELBOX_LIKES." (
rating_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
post_id SMALLINT(5) UNSIGNED NOT NULL,
user_id SMALLINT(5) UNSIGNED NOT NULL,
rating_value TINYINT(1) UNSIGNED NOT NULL,
PRIMARY KEY (rating_id)
) ENGINE=MyISAM ;";

$inf_newtable[3] = DB_HP_QUASSELBOX_SETTINGS." (
settings_name VARCHAR(100) NOT NULL DEFAULT '',
settings_value TEXT NOT NULL,
PRIMARY KEY (settings_name)
) ENGINE=MyISAM ;";

$inf_insertdbrow[1] = DB_PANELS." (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES('".$locale['HPQB_title']."', 'HP_quasselbox_panel', '', '4', '3', 'file', '0', '0', '1')";
$inf_insertdbrow[2] = DB_HP_QUASSELBOX_SETTINGS." (settings_name, settings_value) VALUES('visible_quassels', '5')";
$inf_insertdbrow[3] = DB_HP_QUASSELBOX_SETTINGS." (settings_name, settings_value) VALUES('visible_arch_quassels', '10')";
$inf_insertdbrow[4] = DB_HP_QUASSELBOX_SETTINGS." (settings_name, settings_value) VALUES('guest_quassels', '0')";
$inf_insertdbrow[5] = DB_HP_QUASSELBOX_SETTINGS." (settings_name, settings_value) VALUES('vote_quassels', '0')";
$inf_insertdbrow[6] = DB_HP_QUASSELBOX_SETTINGS." (settings_name, settings_value) VALUES('note_quassels', '0')";
$inf_insertdbrow[7] = DB_HP_QUASSELBOX_SETTINGS." (settings_name, settings_value) VALUES('note_text1', 'Keine Supportfragen!')";
$inf_insertdbrow[8] = DB_HP_QUASSELBOX_SETTINGS." (settings_name, settings_value) VALUES('note_text2', 'Keine Supportfragen in der Quasselbox!')";
$inf_insertdbrow[9] = DB_HP_QUASSELBOX_SETTINGS." (settings_name, settings_value) VALUES('color_qbname', 'ff0000')";
$inf_insertdbrow[10] = DB_HP_QUASSELBOX_SETTINGS." (settings_name, settings_value) VALUES('color_qbdate', 'red.png')";
$inf_insertdbrow[11] = DB_HP_QUASSELBOX_SETTINGS." (settings_name, settings_value) VALUES('color_textarea', 'ffffff')";
//$inf_insertdbrow[2] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('visible_quassels', '5', '".$inf_folder."')";
//$inf_insertdbrow[3] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES('guest_quassels', '0', '".$inf_folder."')";

$inf_droptable[1] = DB_HP_QUASSELBOX;
$inf_droptable[2] = DB_HP_QUASSELBOX_LIKES;
$inf_droptable[3] = DB_HP_QUASSELBOX_SETTINGS;

$inf_deldbrow[1] = DB_PANELS." WHERE panel_filename='".$inf_folder."'";
//$inf_deldbrow[2] = DB_SETTINGS_INF." WHERE settings_inf='".$inf_folder."'";

$inf_adminpanel[1] = array(
	"title" => $locale['HPQB_admin1'],
	"image" => "quassel.gif",
	"panel" => "HP_quasselbox_admin.php",
	"rights" => "HPQB"
);
?>