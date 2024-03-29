<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: lostpassword.php
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
require_once INCLUDES."sendmail_include.php";
include LOCALE.LOCALESET."lostpassword.php";

if (iMEMBER) redirect("index.php");

require_once CLASSES."PasswordAuth.class.php";
require_once CLASSES."LostPassword.class.php";

add_to_title($locale['global_200'].$locale['400']);
opentable($locale['400']);

$obj = new LostPassword;
if (isset($_GET['user_email']) && isset($_GET['account'])) {
   $obj->checkPasswordRequest($_GET['user_email'], $_GET['account']);
   $obj->displayOutput();
} elseif (isset($_POST['send_password'])) {
   $obj->sendPasswordRequest($_POST['email']);
   $obj->displayOutput();
} else {
   $obj->renderInputForm();
   $obj->displayOutput();
}

closetable();

require_once DESIGNS."templates/footer.php";
?>