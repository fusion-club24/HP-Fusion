<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: stopspam_check.php
| Author: Michael Hollmayer (firemike)
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
error_reporting(E_ALL);
if (isset($_POST['user_email'])){
$email = trim(strip_tags($_POST['user_email']));

//own Blacklists not redirect to StopForumSpam
$result = dbquery("SELECT blacklist_email  FROM ".DB_BLACKLIST." WHERE blacklist_email='".$email."' AND blacklist_reason='StopForumSpam'");

	if (dbrows($result)) 	{
		redirect ("stopspam.php?mail=".$email);
	}else{
		if (in_array('curl', get_loaded_extensions())) { //curl check
			$ch = curl_init();
			$timeout = 5;
			curl_setopt($ch, CURLOPT_URL, "http://www.stopforumspam.com/api?email=".$email);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$file_content = curl_exec($ch);
			curl_close($ch);
	
			$xml_string = $file_content;
			$xml = new SimpleXMLElement($xml_string);
			if($xml->appears == 'yes'){
				$result = dbquery("INSERT INTO ".DB_BLACKLIST." (blacklist_user_id, blacklist_email, blacklist_reason, blacklist_datestamp) VALUES ('1', '".$email."', 'StopForumSpam', '".time()."')");
				redirect ("stopspam.php?mail=".$email);
			}
		}
	}
}
?>