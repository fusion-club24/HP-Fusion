<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: upgrade.php
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
require_once "../maincore.php";

if (!checkrights("U") || !defined("iAUTH") || !isset($_GET['aid']) || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

require_once DESIGNS."templates/admin_header.php";
if (file_exists(LOCALE.LOCALESET."admin/upgrade.php")) {
	include LOCALE.LOCALESET."admin/upgrade.php";
} else {
	include LOCALE."English/admin/upgrade.php";
}
if(isset($_POST['upgrade_install'])) {
$result1 = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='HP-Fusion 1.3.3' WHERE settings_name='version'");
redirect(FUSION_SELF.$aidlink."");
}
opentable($locale['400']);
$hpf_version = "HP-Fusion 1.3.3";
    
    
    if ( $settings['version'] == $hpf_version ) {
	echo "<div style='text-align:center'><br />\n";
		echo $locale['401']."<br /><br />\n";
	echo "</div>\n";
    } else{
#$result1 = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='HP-Fusion 1.3.2' WHERE settings_name='version'");
echo "<center>Es ist ein Update vorhanden</center>";

echo "<center><form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<input type='submit' name='upgrade_install' value='Update Install' class='button' />\n";
echo "</form></center>\n";
}
closetable();

require_once DESIGNS."templates/footer.php";
?>
