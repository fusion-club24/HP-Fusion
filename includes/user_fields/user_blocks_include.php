<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: user_blocks_include.php
| Author: firemike
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

error_reporting(E_ALL);
if(iMEMBER){

if (isset($_GET['lookup'])){
	$lookup = $_GET['lookup'];
} elseif (isset($_GET['user_id'])){
	$lookup = $_GET['user_id'];
}

if ($profile_method == "input") {
	echo "<tr>\n";
		echo "<td class='tbl'>".$locale['uf_blocks_001']."</td>\n";
		echo "<td class='tbl'><a class='button' href='".INCLUDES."users_blocks/block.php'>".$locale['uf_blocks_004']."</a></td>\n";
	echo "</tr>\n";

} elseif ($profile_method == "display") {
	if($lookup == $userdata['user_id']){
		echo "<tr>\n";
			echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_blocks_001']."</td>\n";
			echo "<td align='right' class='tbl1'><a class='button' href='".INCLUDES."users_blocks/block.php'>".$locale['uf_blocks_004']."</a></td>\n";
		echo "</tr>\n";
	} else {
		$result = dbquery("SELECT * FROM ".DB_USERS_BLOCKS." WHERE user_id='".$userdata['user_id']."' AND blocked_user_id='".$lookup."'");
		if(dbrows($result) == 0){
			echo "<tr>\n";
				echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_blocks_001']."</td>\n";
				$access = dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$lookup."'");
				$dat = dbarray($access);
				if($dat['user_level'] == "102" OR $dat['user_level'] == "103"){
					echo "<td align='right' class='tbl1'><div class='button'>".$locale['uf_blocks_005']."</div></td>\n";
				} else {
					echo "<td align='right' class='tbl1'><a class='button' href='".INCLUDES."users_blocks/block.php?blockid=".$lookup."'>".$locale['uf_blocks_006']."</a></td>\n";
				}
			echo "</tr>\n";
		}else{
			echo "<tr>\n";
				echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_blocks_001']."</td>\n";
				echo "<td align='right' class='tbl1'><a class='button' href='".INCLUDES."users_blocks/block.php?eblockid=".$lookup."'>".$locale['uf_blocks_007']."</a></td>\n";
			echo "</tr>\n";
		}
	}
} elseif ($profile_method == "validate_insert") {
	$db_fields .= ", user_blocks";
	$db_values .= ", '".(isset($_POST['user_blocks']) && isnum($_POST['user_blocks']) ? $_POST['user_blocks'] : "0")."'";
} elseif ($profile_method == "validate_update") {
	$db_values .= ", user_blocks='".(isset($_POST['user_blocks']) && isnum($_POST['user_blocks']) ? $_POST['user_blocks'] : "0")."'";
}
}
?>