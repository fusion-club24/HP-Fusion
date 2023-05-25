<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: user_gender_include.php
| Author: Digitanium
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
if (!defined("IN_FUSION")) { die("Access Denied"); }

if ($profile_method == "input") {
	echo "<tr>\n";
	echo "<td class='tbl'>".$locale['uf_gender_001'].":</td>\n";
	echo "<td class='tbl'>";
	echo "<input type='radio' name='user_gender' value='0'".(IsSeT($user_data['user_gender']) && $user_data['user_gender'] == "0" ? " checked='checked'" : "")." /> ".$locale['uf_gender_004']." ";
	echo "<input type='radio' name='user_gender' value='1'".(IsSeT($user_data['user_gender']) && $user_data['user_gender'] == "1" ? " checked='checked'" : "")." /> ".$locale['uf_gender_002']." ";
	echo "<input type='radio' name='user_gender' value='2'".(IsSeT($user_data['user_gender']) && $user_data['user_gender'] == "2" ? " checked='checked'" : "")." /> ".$locale['uf_gender_003']."</td>\n";
	echo "</tr>\n";
} elseif ($profile_method == "display") {
	if ($user_data['user_gender'] && ($user_data['user_gender'] == 1 || $user_data['user_gender'] == 2)) {
		echo "<tr>\n";
		echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_gender_001']."</td>\n";
		echo "<td align='right' class='tbl1'>".($user_data['user_gender'] == 1 ? $locale['uf_gender_002'] : $locale['uf_gender_003']);
		echo "</td>\n</tr>\n";
	} else {
	echo "<tr>\n";
	echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_gender_001']."</td>\n";
	echo "<td align='right' class='tbl1'>".$locale['uf_gender_004']."";
	echo "</td>\n</tr>\n";
	}
} elseif ($profile_method == "validate_insert") {
	$db_fields .= ", user_gender";
	$db_values .= ", '".(isset($_POST['user_gender']) && isnum($_POST['user_gender']) ? $_POST['user_gender'] : "0")."'";
} elseif ($profile_method == "validate_update") {
	$db_values .= ", user_gender='".(isset($_POST['user_gender']) && isnum($_POST['user_gender']) ? $_POST['user_gender'] : "0")."'";
}
?>