<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: user_delete_include.php
| Author: Rolly8-HL
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

// Display user field input
if ($profile_method == "input") {
	if (FUSION_SELF == "edit_profile.php"){
		if ((!iADMIN) && ($userdata['user_id'] != 2)) {
			echo "<tr>\n";
				echo "<td class='tbl'><label for='user_delete'>".$locale['uf_delete']."</label></td>\n";
				echo "<td class='tbl' align='right'>";
				echo "<a class='button' href='edit_profile.php?delete' onclick='return DeleteMember();'>".$locale['uf_delete_btn']."</a></td>\n";
			echo "</tr>\n";
		}

		if (isset($_GET["delete"])) {
			$set_del_user_action = dbarray(dbquery("SELECT settings_value FROM ".DB_SETTINGS." WHERE settings_name='del_user_action'"));
			$del_user_action = $set_del_user_action['settings_value'];
			$user_id = $userdata['user_id'];
	
			$result = dbquery("SELECT user_id, user_name, user_email, user_avatar, user_ip FROM ".DB_USERS." WHERE user_id='".$user_id."' AND user_level<'103'");
			if (dbrows($result)) {
				$data = dbarray($result);
			
				// include anomyze or delete user
				include INCLUDES."user_deletes_include.php";
			}
		redirect("index.php");
		}

	echo "<script type='text/javascript'>"."\n"."function DeleteMember(username) {\n";
	echo "return confirm('".$locale['uf_delete_info']."');\n}\n</script>\n";
	}
// Display in profile
} elseif ($profile_method == "display") {
		
// Insert and update
} elseif ($profile_method == "validate_insert"  || $profile_method == "validate_update") {
	
}
?>