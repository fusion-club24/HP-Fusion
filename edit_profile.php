<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: edit_profile.php
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
require_once "maincore.php";
require_once DESIGNS."templates/header.php";
require_once CLASSES."UserFields.class.php";
require_once CLASSES."UserFieldsInput.class.php";
include LOCALE.LOCALESET."user_fields.php";

//passwordstrange start
add_to_head("<script type='text/javascript' src='".INCLUDES."js/pws.js'></script>");
add_to_head("<style type='text/css'>
.P91PWS_C, .P91PWS_1, .P91PWS_2, .P91PWS_3, .P91PWS_4, .P91PWS_6 {
	height: 5px;
	margin-top: 3px;
	padding: 0;
	margin: 0;
}
.P91PWS_C {
	background-color: #ffffff;
	width: 100%;
}
.P91PWS_O {
	//color: #666666;
	margin: 1px 0 3px 0;
}
.P91PWS_1 {
	background-color: #339900;
	width: 100%;
}
.P91PWS_2 {
	background-color: #99FF00;
	width: 85%;
}
.P91PWS_3 {
	background-color: #FFCC00;
	width: 60%;
}
.P91PWS_4 {
	background-color: #996900;
	width: 45%;
}
.P91PWS_5 {
	background-color: #993300;
	width: 30%;
}
.P91PWS_6 {
	background-color: #FF0000;
	width: 10%;
}
</style>");
//passwordstrange stop

if (!iMEMBER) { redirect("index.php"); }

add_to_title($locale['global_200'].$locale['u102']);

$errors = array();

if (isset($_POST['update_profile'])) {
	$userInput 						= new UserFieldsInput();
	$userInput->setUserNameChange($settings['userNameChange']);
	$userInput->verifyNewEmail		= true;
	$userInput->userData 			= $userdata;
	$userInput->saveUpdate();
	$userInput->displayMessages();
	$errors 						= $userInput->getErrorsArray();

	if (empty($errors) && $userInput->themeChanged()) redirect(FUSION_SELF);

	$userdata = dbarray(dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$userdata['user_id']."'"));
	unset($userInput);
} elseif (isset($_GET['code']) && $settings['email_verification'] == "1") {
	$userInput 						= new UserFieldsInput();
	$userInput->verifyCode($_GET['code']);
	$userInput->displayMessages();

	$userdata = dbarray(dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$userdata['user_id']."'"));
	unset($userInput);
}

opentable($locale['u102']);
if ($settings['email_verification'] == "1") {
	$result = dbquery("SELECT user_email FROM ".DB_EMAIL_VERIFY." WHERE user_id='".$userdata['user_id']."'");
	if (dbrows($result)) {
		$data = dbarray($result);
		echo "<div class='tbl2' style='text-align:center; width:500px; margin: 5px auto 10px auto;'>".sprintf($locale['u200'], $data['user_email'])."\n<br />\n".$locale['u201']."\n</div>\n";
	}
}
echo "<div style='text-align:center; margin-bottom: 10px;'>".$locale['u100']."</div>";
$userFields 						= new UserFields();
$userFields->postName 				= "update_profile";
$userFields->postValue 				= $locale['u105'];
$userFields->userData 				= $userdata;
$userFields->errorsArray 			= $errors;
$userFields->setUserNameChange($settings['userNameChange']);
$userFields->displayInput();
closetable();

require_once DESIGNS."templates/footer.php";
?>