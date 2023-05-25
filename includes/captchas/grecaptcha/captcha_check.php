<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: captcha_check.php
| Author: skpacman
| Modified for HP-Fusion by Rolly8-HL
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) {
    die("Access Denied");
}
require_once INCLUDES."captchas/grecaptcha/recaptchalib.php"; //a required library from Google
$resp = NULL;
$error = NULL;
// this is required to work with localhost
$googleArray = array(
    "ip" => $settings['siteurl'], //$_SERVER["REMOTE_ADDR"],
    "captcha" => !empty($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : FALSE,
    "secret" => $settings['recaptcha_private']
);
$reCaptcha = ReCaptcha::getInstance($googleArray['secret']);
$resp = $reCaptcha->verifyResponse($googleArray['ip'], $googleArray['captcha']);
if ($resp != NULL && $resp->success && $error == NULL) {
    $_CAPTCHA_IS_VALID = TRUE;
}

