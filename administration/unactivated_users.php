<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: unactivated_users.php
| Author: Triton Revelle
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
require_once DESIGNS."templates/admin_header.php";

if (!checkrights("UU") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

include LOCALE.LOCALESET."admin/unactivated_users.php";

if (isset($_GET['code']) && isset($_GET['action'])) { 
	if (!preg_check("/^[0-9a-z]{40}$/", $_GET['code'])) { redirect(FUSION_SELF.$aidlink."&amp;error=code"); }
	$result = dbquery("SELECT * FROM ".DB_NEW_USERS." WHERE user_code='".$_GET['code']."'");
	$rows = dbrows($result);
	if ($rows == 0) {
		redirect(FUSION_SELF.$aidlink."&amp;error=empty");
	} else {
		switch ($_GET['action']) {
			case "activate":
				require_once LOCALE.LOCALESET."admin/members_email.php";
				require_once INCLUDES."sendmail_include.php";
				// getmequick at gmail dot com
				// http://www.php.net/manual/en/function.unserialize.php#71270
				function unserializeFix($var) {
			    	$var = preg_replace_callback('!s:(\d+):"(.*?)";!', function($matches) {
					return 's:'.strlen($matches[2]).':"'.$matches[2].'";';
					}, $var);
				return unserialize($var);
				}
				$data = dbarray($result);
				$user_info = unserializeFix(stripslashes($data['user_info']));
				$result = dbquery("INSERT INTO ".DB_USERS." (".$user_info['user_field_fields'].") VALUES (".$user_info['user_field_inputs'].")");
				if ($result == false) {redirect(FUSION_SELF.$aidlink."&amp;error=activate");}
				$result = dbquery("DELETE FROM ".DB_NEW_USERS." WHERE user_code='".$_GET['code']."' LIMIT 1");
				$subject = $locale['email_activate_subject'].$settings['sitename'];
				$email_message = str_replace("[USER_NAME]", $user_info['user_name'], $locale['email_activate_message']);
				if (sendemail($user_info['user_name'], $user_info['user_email'], $settings['siteusername'], $settings['siteemail'], $subject, $email_message)) {
					$message = str_replace("USER_NAME", $user_info['user_name'], $locale['UAU_400']);
				} else {
					redirect(FUSION_SELF.$aidlink."&amp;error=activate_send");
				}
				break;
			case "resend":
				$data = dbarray($result);
				require_once INCLUDES."sendmail_include.php";
				$activationUrl = $settings['siteurl']."register.php?email=".$data['user_email']."&code=".$data['user_code'];
				$email_message = str_replace("USER_NAME", $data['user_name'], $locale['UAU_202']);
				$email_message = str_replace("ACTIVATION_LINK", $activationUrl, $email_message);
				if (sendemail($data['user_name'],$data['user_email'],$settings['siteusername'], $settings['siteemail'], $locale['UAU_201'], $email_message)) {
					$message = str_replace("USER_NAME", $data['user_name'], $locale['UAU_200']);
				} else {
					redirect(FUSION_SELF.$aidlink."&amp;error=resend");
				}
				break;
			case "delete":
				$result = dbquery("DELETE FROM ".DB_NEW_USERS." WHERE user_code='".$_GET['code']."' LIMIT 1");
				if ($result != false) {
					$message = $locale['UAU_300'];
				} else {
					redirect(FUSION_SELF.$aidlink."&amp;error=delete");
				}
				break;
			default:
				redirect(FUSION_SELF.$aidlink."&amp;error=unknown");
		}
	if ($message) { echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; }
	}
} else if (isset($_GET['error'])) { //code, empty, delete, resend, activate_send, activate, unknown
	switch ($_GET['error']) {
	case "code":
		$message = $locale['UAU_500'];
		break;
	case "empty":
		$message = $locale['UAU_501'];
		break;
	case "delete":
		$message = $locale['UAU_502'];
		break;
	case "resend":
		$message = $locale['UAU_503'];
		break;
	case "activate_send":
		$message = $locale['UAU_504'];
		break;
	case "activate":
		$message = $locale['UAU_505'];
		break;
	case "unknown":
		$message = $locale['UAU_506'];
		break;
	default:
		$message = $locale['UAU_507'];
	}
	if ($message) { echo "<div id='close-message'><div class='warning'>".$message."</div></div>\n"; }
}
 
opentable($locale['UAU_001']);
	$result = dbquery("SELECT * FROM ".DB_NEW_USERS." ORDER BY user_datestamp");
	if (dbrows($result) !=0) {    
		echo "<table class='tbl-border' width='100%' cellspacing='1' cellpadding='0'>    
		<tr>
			<td class='tbl1' style='font-weight:bold'>".$locale['UAU_101']."</td>    
			<td class='tbl1' style='font-weight:bold'>".$locale['UAU_102']."</td>    
			<td class='tbl1' style='font-weight:bold'>".$locale['UAU_103']."</td>    
			<td class='tbl1' style='text-align:center; font-weight:bold'>".$locale['UAU_108']."</td>    
			<td class='tbl1' style='text-align:center; font-weight:bold'>".$locale['UAU_109']."</td>    
			<td class='tbl1' style='text-align:center; font-weight:bold'>".$locale['UAU_105']."</td>    
		</tr>";	
		$i = 0;
		while($data = dbarray($result))	{	    
			$cell_color = ($i++ % 2 == 0 ? "tbl2" : "tbl1");
			echo "<tr>
				<td class='$cell_color'>".$data['user_name']."</td>
				<td class='$cell_color'>".showdate("forumdate", $data['user_datestamp'])."</td>
				<td class='$cell_color'>".$data['user_email']."</td>
				<td class='$cell_color' style='text-align:center'><a href='".FUSION_SELF.$aidlink."&amp;action=activate&amp;code=".$data['user_code']."'><img src='".IMAGES."yes.png' alt='".$locale['UAU_108']."' /></a></td>
				<td class='$cell_color' style='text-align:center'><a href='".FUSION_SELF.$aidlink."&amp;action=resend&amp;code=".$data['user_code']."'><img src='".IMAGES."resend.png' alt='".$locale['UAU_109']."' /></a></td>
				<td class='$cell_color' style='text-align:center'><a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;code=".$data['user_code']."' onclick=\"return confirm('".$locale['UAU_106']."');\"><img src='".IMAGES."no.png' alt='".$locale['UAU_105']."' /></a></td>
			</tr>";	
			}	
		echo "</table>";
	}  else{	
		echo "<div style='text-align:center'>".$locale['UAU_107']."</div>";
	}
closetable();

require_once DESIGNS."templates/footer.php";
?>
