<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: HP_user_info_panel.php
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

// Check if locale file is available matching the current site locale setting.
if (file_exists(MODULS."HP_user_info_panel/locale/".$settings['locale'].".php")) {
	// Load the locale file matching the current site locale setting.
	include MODULS."HP_user_info_panel/locale/".$settings['locale'].".php";
} else {
	// Load the infusion's default locale file.
	include MODULS."HP_user_info_panel/locale/English.php";
}

add_to_head("<style type='text/css'>
.userinfo {background-color: #808080;color: #FFF;font-size: 11px;padding: 4px;}
.userinfo_am {width:19px;height:16px;background-color: #FF0000;color: #FFFFFF;font-size: 11px;text-align: center;border-radius: 2px;display: inline-block;}
</style>");

if (iMEMBER) {
	openside($userdata['user_name']);
	
		$msg_count = dbcount("(message_id)", DB_MESSAGES, "message_to='".$userdata['user_id']."' AND message_read='0'AND message_folder='0'");

		echo "<center><img border='0' src='".($userdata['user_avatar'] != "" ? BASEDIR."images/avatars/".$userdata['user_avatar'] : BASEDIR."images/avatars/noavatar100.png")."'></center>\n";
		echo "<table align='center' width='100%' cellpadding='3' cellspacing='1' >
			<tr>
				<td colspan='4' align='center' valign='bottom'><small><b>".$locale['hpui_001']."</b></small></td>
              </tr>
		</table>";
		echo "<table align='center' width='100%' cellpadding='3' cellspacing='1' >";
			if ($msg_count) {
				echo "<tr>
					<td colspan='4' align='center' valign='bottom'><a href='".BASEDIR."messages.php?folder=inbox'><img src='".MODULS."HP_user_info_panel/images/newpm.gif' alt='PN Inbox' border='0' style='padding:3px;'><br /><small><b>".sprintf($locale['global_125'], $msg_count).($msg_count == 1 ? $locale['global_126'] : $locale['global_127'])."</b></small></a></td>
				</tr>";
			}
			echo "<tr>
				<td class='tbl2' width='33%' align='center' valign='bottom'><a href='".BASEDIR."messages.php?folder=inbox' title='PN Eingang'><img src='".MODULS."HP_user_info_panel/images/pn_inbox.png' alt='PN Inbox' border='0' style='padding:3px;'></a></td>
				<td class='tbl2' width='33%' align='center' valign='bottom'><a href='".BASEDIR."messages.php?folder=outbox' title='PN Ausgang'><img src='".MODULS."HP_user_info_panel/images/pn_outbox.png' alt='PN Outbox' border='0' style='padding:3px;'></a></td>
				<td class='tbl2' cwidth='33%' align='center' valign='bottom'><a href='".BASEDIR."messages.php?folder=archive' title='PN Archiv'><img src='".MODULS."HP_user_info_panel/images/pn_archive.png' alt='PN Archive' border='0' style='padding:3px;'></a></td>
			</tr><tr>
				<td class='tbl2' width='33%' align='center' valign='bottom'><a href='".BASEDIR."profile.php?lookup=".$userdata['user_id']."'><img src='".MODULS."HP_user_info_panel/images/profil.png' alt='".$locale['hpui_002']."' border='0' style='padding:3px;' title='".$locale['hpui_002']."'></a></td>
				<td class='tbl2' width='33%' align='center' valign='bottom'><a href='".BASEDIR."edit_profile.php'><img src='".MODULS."HP_user_info_panel/images/edit.png' alt='".$locale['hpui_003']."' border='0' style='padding:3px;' title='".$locale['hpui_003']."'></a></td>
				<td class='tbl2' width='33%' align='center' valign='bottom'><a href='".BASEDIR."members.php'><img src='".MODULS."HP_user_info_panel/images/members.png' alt='".$locale['hpui_004']."' border='0' style='padding:3px;' title='".$locale['hpui_004']."'></a></td>
			</tr><tr>
				<td class='tbl2' width='33%' align='center' valign='bottom'><a href='".BASEDIR."news.php'><img src='".MODULS."HP_user_info_panel/images/news.png' alt='".$locale['hpui_005']."' border='0' style='padding:3px;' title='".$locale['hpui_005']."'></a></td>
				<td class='tbl2' width='33%' align='center' valign='bottom'></td>
				<td class='tbl2' width='33%' align='center' valign='bottom'><a href='".BASEDIR."setuser.php?logout=yes'><img src='".MODULS."HP_user_info_panel/images/logout.png' alt='".$locale['hpui_006']."' border='0' style='padding:3px;' title='".$locale['hpui_006']."'></a></td>
			</tr>
		</table>";
		if (iADMIN && (iUSER_RIGHTS != "" || iUSER_RIGHTS != "C")) {
			include LOCALE.LOCALESET."admin/main.php";
     
			echo "<table align='center' width='100%' cellpadding='3' cellspacing='1' >
				<tr>
					<td colspan='5' align='center' valign='bottom'><small><b>Administration</b></small></td>
				</tr><tr>
					<td class='tbl2' width='20%' align='center' valign='bottom'><a href='".ADMIN."index.php".$aidlink."&amp;pagenum=1' title='".$locale['ac01']."'><img src='".MODULS."HP_user_info_panel/images/admin_content.png' alt='".$locale['ac01']."' border='0' style='padding:3px;'></a></td>
					<td class='tbl2' width='20%' align='center' valign='bottom'><a href='".ADMIN."index.php".$aidlink."&amp;pagenum=2' title='".$locale['ac02']."'><img src='".MODULS."HP_user_info_panel/images/admin_user.png' alt='".$locale['ac02']."' border='0' style='padding:3px;'></a></td>
					<td class='tbl2' width='20%' align='center' valign='bottom'><a href='".ADMIN."index.php".$aidlink."&amp;pagenum=3' title='".$locale['ac03']."'><img src='".MODULS."HP_user_info_panel/images/admin_main.png' alt='".$locale['ac03']."' border='0' style='padding:3px;'></a></td>
					<td class='tbl2' width='20%' align='center' valign='bottom'><a href='".ADMIN."index.php".$aidlink."&amp;pagenum=4' title='".$locale['ac04']."'><img src='".MODULS."HP_user_info_panel/images/admin_infusion.png' alt='".$locale['ac04']."' border='0' style='padding:3px;'></a></td>
					<td class='tbl2' width='20%' align='center' valign='bottom'><a href='".ADMIN."index.php".$aidlink."&amp;pagenum=5' title='".$locale['ac05']."'><img src='".MODULS."HP_user_info_panel/images/infusions.png' alt='".$locale['ac05']."' border='0' style='padding:3px;'></a></td>
				</tr>
			</table>";
			 
		}
		if (iADMIN && checkrights("SU")) {
			$subm_count = dbcount("(submit_id)", DB_SUBMISSIONS);

			if ($subm_count) {
				echo "<div style='text-align:center;margin-top:5px;'>\n";
					echo "<a href='".ADMIN."submissions.php".$aidlink."'>".($subm_count == 1 ? $locale['global_128'] : $locale['global_129']).":</a> ";
					echo "<span class='userinfo_am'>".$subm_count."</span><br />\n";
				echo "</div>\n";
			}
		}
	
		if (iADMIN && checkrights("M") && $settings['admin_activation'] == "1") {
			$unactive = dbcount("(user_id)", DB_USERS, "user_status='2'");
			if ($unactive >= 1) {
				echo "<div style='text-align:center;margin-top:5px;'>\n";
					echo "<a href='".ADMIN."members.php".$aidlink."&amp;status=2'>".$locale['global_015'].": </a>";
					echo "<span class='userinfo_am'>".$unactive."</span>\n";
				echo "</div>\n";
			}
		}
	
		if (iADMIN && checkrights("UU")) {
			$unactive_email = dbcount("(user_code)", DB_NEW_USERS);
			if ($unactive_email >= 1) {
				echo "<div style='text-align:center;margin-top:5px;'>\n";
					echo "<a href='".ADMIN."unactivated_users.php".$aidlink."'>".$locale['hpui_007']." </a>";
					echo "<span class='userinfo_am'>".$unactive_email."</span>\n";
				echo "</div>\n";
			}
		}
	closeside();
} else {
	if (!preg_match('/login.php/i',FUSION_SELF)) {
		$action_url = FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : "");
		if (isset($_GET['redirect']) && strstr($_GET['redirect'], "/")) {
			$action_url = cleanurl(urldecode($_GET['redirect']));
		}
		openside($locale['global_100']);
			echo "<div align='center'>
				".(isset($loginerror) ? $loginerror : "")."
				<form name='loginform' method='post' action='".$action_url."'>";
					if (isset($settings['login_type']) &&  $settings['login_type']=='0') {
						echo $locale['global_101'];
					} elseif (isset($settings['login_type']) &&  $settings['login_type']=='1') {
						echo $locale['global_109'];
					} else {
						echo $locale['global_110'];
					}
					echo "<br />
					<input type='text' name='user_name' class='textbox' style='width:100px'><br />
					".$locale['global_102']."<br />
					<input type='password' name='user_pass' class='textbox' style='width:100px'><br />
					<input type='checkbox' name='remember_me' value='y' title='".$locale['global_103']."' style='vertical-align:middle;'>
					<input type='submit' name='login' value='".$locale['global_104']."' class='button'><br />
				</form>
				<br />\n";
				if ($settings['enable_registration']) {
					echo "".$locale['global_105']."<br /><br />\n";
				}
				echo $locale['global_106']."
			</div>\n";
		closeside();
	}
}
?>