<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: stopspam.php
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
require_once "maincore.php";
require_once DESIGNS."templates/header.php";

include LOCALE.LOCALESET."stopspam.php";

opentable($locale['sp001']);
echo "<div align='center'><img src='".IMAGES."spam.png'></div>";
echo "<center>".$locale['sp002']."<a href='http://www.stopforumspam.com/'>Stop Forum Spam</a>".$locale['sp003']."<br>";
echo "".$locale['sp004']."<a href='http://www.stopforumspam.com/search/".$_GET['mail']."' target='_blank'>".$locale['sp005']."</a></center>";
closetable();

require_once DESIGNS."templates/footer.php";
?>
