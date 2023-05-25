<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: footer.php
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

if (!defined("IN_FUSION")) { die("Access Denied"); }

require_once INCLUDES."footer_includes.php";

define("CONTENT", ob_get_contents());
ob_end_clean();
render_page(false);

// temp ban
$usr_inactive = dbcount("(user_id)", DB_USERS, "user_status='3' AND user_actiontime!='0' AND user_actiontime < '".time()."'");
if ($usr_inactive) {
	require_once INCLUDES."suspend_include.php";
	require_once INCLUDES."sendmail_include.php";

	$result = dbquery(
		"SELECT user_id, user_name, user_email FROM ".DB_USERS."
		WHERE user_status='3' AND user_actiontime!='0' AND user_actiontime < '".time()."'
		LIMIT 1"
	);
	$data = dbarray($result);
	$result2 = dbquery("UPDATE ".DB_USERS." SET user_status='0', user_actiontime='0' WHERE user_id='".$data['user_id']."'");
	$subject = $locale['global_451'];
	$message = str_replace("USER_NAME", $data['user_name'], $locale['global_452']);
	$message = str_replace("LOST_PASSWORD", $settings['siteurl']."lostpassword.php", $message);
	sendemail($data['user_name'], $data['user_email'], $settings['siteusername'], $settings['siteemail'], $subject, $message);
	unsuspend_log($data['user_id'], 3, $locale['global_450'], true);	
}

// Cron Job (6 MIN)
if ($settings['cronjob_hour'] < (time()-360)) {
	$result = dbquery("DELETE FROM ".DB_FLOOD_CONTROL." WHERE flood_timestamp < '".(time()-360)."'");
	$result = dbquery("DELETE FROM ".DB_CAPTCHA." WHERE captcha_datestamp < '".(time()-360)."'");
	$result = dbquery("DELETE FROM ".DB_USERS." WHERE user_joined='0' AND user_ip='0.0.0.0' and user_level='103'");
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".time()."' WHERE settings_name='cronjob_hour'");
}

// Cron Job (24 HOUR)
if ($settings['cronjob_day'] < (time()-86400)) {
	$new_time = time();

	$result = dbquery("DELETE FROM ".DB_THREAD_NOTIFY." WHERE notify_datestamp < '".(time()-1209600)."'");
	$result = dbquery("DELETE FROM ".DB_NEW_USERS." WHERE user_datestamp < '".(time()-86400)."'");
	$result = dbquery("DELETE FROM ".DB_EMAIL_VERIFY." WHERE user_datestamp < '".(time()-86400)."'");


	$usr_deactivate = dbcount("(user_id)", DB_USERS, "user_actiontime < '".time()."' AND user_actiontime!='0' AND user_status='7'");
	if ($usr_deactivate) {
		$result = dbquery(
			"SELECT user_id, user_name, user_email, user_avatar, user_ip FROM ".DB_USERS."
			WHERE user_actiontime < '".time()."' AND user_actiontime!='0' AND user_status='7'
			LIMIT 10"
		);
		if ($settings['deactivation_action'] == 0) {
			while ($data = dbarray($result)) {
				$result = dbquery("UPDATE ".DB_USERS." SET user_actiontime='0', user_status='6' WHERE user_id='".$data['user_id']."'");
				redirect(FUSION_SELF);
			}
		} else {
			while ($data = dbarray($result)) {
				$user_id = $data['user_id'];
				$set_del_user_action = dbarray(dbquery("SELECT settings_value FROM ".DB_SETTINGS." WHERE settings_name='del_user_action'"));
				$del_user_action = $set_del_user_action['settings_value'];
				include INCLUDES."user_deletes_include.php";

			}
		}
		if ($usr_deactivate > 10) { $new_time = $settings['cronjob_day']; }
	}

	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".$new_time."' WHERE settings_name='cronjob_day'");
}

// Error handling
if (iADMIN  && checkrights("ERRO") && count($_errorHandler) > 0) {
	echo "<div class='admin-message'>".str_replace("[ERROR_LOG_URL]", ADMIN."errors.php".$aidlink, $locale['err_101'])."</div>\n";
}

echo "</body>\n</html>\n";

$output = ob_get_contents();
if (ob_get_length() !== FALSE){
	ob_end_clean();
}
echo handle_output($output);

if (ob_get_length() !== FALSE){
	ob_end_flush();
}

$pdo = NULL;
?>