<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: captcha_display.php
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

add_to_head("<style>
input[type='submit']:disabled {
    opacity: 0.25;
}

.loading_container{
margin-bottom: -22px;
}

.loading_circle {
	background-color: rgba(0,0,0,0);
	border:5px solid rgba(0,183,229,0.9);
	opacity:.9;
	border-right:5px solid rgba(0,0,0,0);
	border-left:5px solid rgba(0,0,0,0);
	border-radius:50px;
	width:50px;
	height:50px;
	#margin:0 auto;
	-moz-animation:spinPulse 1s infinite ease-in-out;
	-webkit-animation:spinPulse 1s infinite linear;
}
.loading_circle1 {
	background-color: rgba(0,0,0,0);
	border:5px solid rgba(0,183,229,0.9);
	opacity:.9;
	border-left:5px solid rgba(0,0,0,0);
	border-right:5px solid rgba(0,0,0,0);
	border-radius:50px;
	width:30px;
	height:30px;
	#margin:0 auto;
	margin-left: 10px;
	position:relative;
	top:-50px;
	-moz-animation:spinoffPulse 1s infinite linear;
	-webkit-animation:spinoffPulse 1s infinite linear;
}

@-moz-keyframes spinPulse {
	0% { -moz-transform:rotate(160deg); opacity:0; box-shadow:0 0 1px #2187e7;}
	50% { -moz-transform:rotate(145deg); opacity:1; }
	100% { -moz-transform:rotate(-320deg); opacity:0; }
}
@-moz-keyframes spinoffPulse {
	0% { -moz-transform:rotate(0deg); }
	100% { -moz-transform:rotate(360deg);  }
}
@-webkit-keyframes spinPulse {
	0% { -webkit-transform:rotate(160deg); opacity:0; box-shadow:0 0 1px #2187e7; }
	50% { -webkit-transform:rotate(145deg); opacity:1;}
	100% { -webkit-transform:rotate(-320deg); opacity:0; }
}
@-webkit-keyframes spinoffPulse {
	0% { -webkit-transform:rotate(0deg); }
	100% { -webkit-transform:rotate(360deg); }
}
</style>");

$_CAPTCHA_HIDE_INPUT = TRUE;
echo "<div class='loading_container'><div class='loading_content'><div class='loading_circle'></div><div class='loading_circle1'></div></div></div>";
add_to_head("<script type='text/javascript' src='https://www.google.com/recaptcha/api.js?hl=".$locale['xml_lang']."' async defer></script>");
echo "<div class='g-recaptcha'  data-theme='".($settings['recaptcha_theme']=='dark' ? 'dark' : 'light')."' data-sitekey='".$settings['recaptcha_public']."'></div>\n";
add_to_footer("<script type=\"text/javascript\">
$(document).ready(function() {
	$('.g-recaptcha').hide();
	$('.g-recaptcha').delay(".$settings['recaptcha_wait']."000).fadeIn(1000);
	$('.loading_container').show();
	$('.loading_container').delay(".$settings['recaptcha_wait']."000).fadeOut(0);
});
</script>");

