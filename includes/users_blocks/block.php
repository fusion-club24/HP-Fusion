<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: block.php
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

require_once "../../maincore.php";
require_once DESIGNS."templates/header.php";

if (!defined("IN_FUSION")) { die("Access Denied"); }

include LOCALE.LOCALESET."users_blocks.php";

if(isset($_GET['blockid'])){
	$blockid = intval($_GET['blockid']);
	if ($blockid=="") {
		redirect(FUSION_SELF);
	}
	
	$access = dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$blockid."'");
	$dat = dbarray($access);
		
	if($dat['user_level'] == "102" OR $dat['user_level'] == "103"){	
		redirect("blocked.php?adm=1");
	}
	
	$result = dbquery("INSERT INTO ".DB_USERS_BLOCKS." (user_id, blocked_user_id) VALUES('".$userdata['user_id']."', '".$blockid."')");
	redirect(FUSION_SELF);
}

if(isset($_GET['eblockid'])){
	$eblockid = intval($_GET['eblockid']);
	if($eblockid=="") {
		redirect(FUSION_SELF);
	}

	$result = dbquery("DELETE FROM ".DB_USERS_BLOCKS." WHERE user_id='".$userdata['user_id']."' AND blocked_user_id='".$eblockid."'");
	redirect(FUSION_SELF);
}

opentable($locale['usbs_001']);

	$result = dbquery("SELECT * FROM ".DB_USERS_BLOCKS." WHERE user_id='".$userdata['user_id']."'");
	echo '<table border="0" align="center" width="100%">';
		if(dbrows($result) == 0) {
			echo "<tr>
				<td colspan='0'>".$locale['usbs_002']."</td>\n";
			echo "</tr>\n";
		}

		while($data = dbarray($result)) {
			$result2 = dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$data['blocked_user_id']."'");
			$data2 = dbarray($result2);

			echo "<tr>\n";
				echo "<td class='tbl2'><a href='".BASEDIR."profile.php?lookup=".$data2['user_id']."'>".$data2['user_name']."</a></td>\n";
				echo "<td class='tbl2'><a href='block.php?eblockid=".$data2['user_id']."'>".$locale['usbs_003']."</a></td>\n";
			echo "</tr>\n";
		}
	echo "</table>";	
	
closetable();


require_once DESIGNS."templates/footer.php";


?>