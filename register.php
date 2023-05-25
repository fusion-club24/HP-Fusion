<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: register.php
| Author: Hans Kristian Flaatten {Starefossen}
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
require_once "maincore.php";
require_once DESIGNS."templates/header.php";
require_once CLASSES."UserFields.class.php";
require_once CLASSES."UserFieldsInput.class.php";
include LOCALE.LOCALESET."user_fields.php";

//passwordstrange start
add_to_head("<script type='text/javascript' src='".INCLUDES."js/pws.js'></script>");
add_to_head("<style type='text/css'>
.P91PWS_C, .P91PWS_1, .P91PWS_2, .P91PWS_3, .P91PWS_4, .P91PWS_6 {height: 5px;margin-top: 3px;padding: 0;margin: 0;}
.P91PWS_C {background-color: #ffffff;width: 100%;}
.P91PWS_O {margin: 1px 0 3px 0;}
.P91PWS_1 {background-color: #339900;width: 100%;}
.P91PWS_2 {background-color: #99FF00;width: 85%;}
.P91PWS_3 {background-color: #FFCC00;width: 60%;}
.P91PWS_4 {background-color: #996900;width: 45%;}
.P91PWS_5 {background-color: #993300;width: 30%;}
.P91PWS_6 {background-color: #FF0000;width: 10%;}
</style>");

if (iMEMBER || !$settings['enable_registration']) { redirect("index.php"); }

//Stop Forum Spam
include "stopspam_check.php";

$errors = array();
if (isset($_GET['email']) && isset($_GET['code'])) {
	if (!preg_check("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $_GET['email'])) {
		redirect("register.php?error=activate");
	}
	if (!preg_check("/^[0-9a-z]{40}$/", $_GET['code'])) { redirect("register.php?error=activate"); }
	$result = dbquery(
		"SELECT user_info FROM ".DB_NEW_USERS."
		WHERE user_code='".$_GET['code']."' AND user_email='".$_GET['email']."'
		LIMIT 1"
	);
	if (dbrows($result)) {
		add_to_title($locale['global_200'].$locale['u155']);

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
		$result = dbquery("DELETE FROM ".DB_NEW_USERS." WHERE user_code='".$_GET['code']."' LIMIT 1");
		//admin email start	
		require_once INCLUDES."sendmail_include.php";
		sendemail($settings['siteusername'], $settings['siteemail'], $settings['siteusername'], $settings['siteemail'], $locale['u224'], "".$locale['u220']."\n ".$locale['u221']."".$user_info['user_name']."".$locale['u222']."".$settings['sitename']."".$locale['u223']."");

		opentable($locale['u155']);
		if ($settings['admin_activation'] == "1") {
			echo "<div style='text-align:center'><br />\n".$locale['u171']."<br /><br />\n".$locale['u162']."<br /><br />\n</div>\n";
		} else {
			echo "<div style='text-align:center'><br />\n".$locale['u171']."<br /><br />\n".$locale['u161']."<br /><br />\n</div>\n";
		}
		closetable();
	} else {
		redirect("index.php");
	}
} elseif (isset($_POST['register'])) {
	$userInput = new UserFieldsInput();
	$userInput->validation 				= $settings['display_validation'];
	$userInput->emailVerification 		= $settings['email_verification'];
	$userInput->adminActivation 		= $settings['admin_activation'];
	$userInput->skipCurrentPass 		= true;
	$userInput->registration			= true;
	$userInput->saveInsert();
	$userInput->displayMessages();
	$errors 							= $userInput->getErrorsArray();
	unset($userInput);
}

if ((!isset($_POST['register']) && !isset($_GET['code'])) || (isset($_POST['register']) && count($errors) > 0)) {
	opentable($locale['u101']);
	$userFields 						= new UserFields();
	$userFields->postName 				= "register";
	$userFields->postValue 				= $locale['u101'];
	$userFields->displayValidation 		= $settings['display_validation'];
	$userFields->displayTerms 			= $settings['enable_terms'];
	$userFields->showAdminPass 			= false;
	$userFields->showAvatarInput 		= false;
	$userFields->skipCurrentPass 		= true;
	$userFields->registration			= true;
	$userFields->errorsArray 			= $errors;
	$userFields->displayInput();
	closetable();
}

require_once DESIGNS."templates/footer.php";
?>