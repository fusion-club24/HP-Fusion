<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: user_quassel-stat_include.php
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

if ($profile_method == "input") {
	//Nothing here
} elseif ($profile_method == "display") {
	include_once MODULS."HP_quasselbox_panel/infusion_db.php";
	echo "<tr>\n";
	echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_quassel-stat']."</td>\n";
	echo "<td align='right' class='tbl1'>".number_format(dbcount("(quassel_id)", DB_HP_QUASSELBOX, "quassel_name='".$user_data['user_id']."'"))."</td>\n";
	echo "</tr>\n";
} elseif ($profile_method == "validate_insert") {
	//Nothing here
} elseif ($profile_method == "validate_update") {
	//Nothing here
}
?>