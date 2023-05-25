<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: admins_only.php
| Author: Fangree_Craig
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
include LOCALE.LOCALESET."user_fields/user_privacy.php";
include LOCALE.LOCALESET."user_fields/user_blocks.php";

opentable($locale['uf_privacy_007']);
	echo"<br><center><img src='".BASEDIR."images/stop256.png' alt='STOP'></center><br>";
	echo"<div class='admin-message'>".$locale['uf_privacy_008']."</div>";

	if(iMEMBER){
		$result = dbquery("SELECT * FROM ".DB_USERS_BLOCKS." WHERE user_id='".$userdata['user_id']."' AND blocked_user_id='".$_GET['lookup']."'");
		if(dbrows($result) == 0){
			echo "<table width='100%'>";
				echo "<tr>\n";
					echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_blocks_001']."</td>\n";
		
					$access = dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$_GET['lookup']."'");
					$dat = dbarray($access);
		
					if($dat['user_level'] == "102" OR $dat['user_level'] == "103"){
						echo "<td align='right' class='tbl1'><div class='button'>".$locale['uf_blocks_005']."</div>";
					}else{
						echo "<td align='right' class='tbl1'><a class='button' href='".INCLUDES."users_block/block.php?blockid=".$_GET['lookup']."'>".$locale['uf_blocks_006']."</a>";
					}
		
					echo "</td>\n";
				echo "</tr>\n";
			echo" </table>\n";
		}else{
			echo "<table width='100%'>";
				echo "<tr>\n";
					echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_blocks_001']."</td>\n";
					echo "<td align='right' class='tbl1'><a class='button' href='".INCLUDES."users_block/block.php?eblockid=".$_GET['lookup']."'>".$locale['uf_blocks_007']."</a></td>\n";
				echo "</tr>\n";
			echo "</table>\n";
		
		}
	}
closetable();

require_once DESIGNS."templates/footer.php";
?>