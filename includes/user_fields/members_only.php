<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: members_only.php
| Author: Fangree_Craig
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
require_once "../../maincore.php";
require_once DESIGNS."templates/header.php";
include LOCALE.LOCALESET."user_fields/user_privacy.php";

if (iMEMBER){ redirect(BASEDIR."index.php"); }

opentable($locale['uf_privacy_007']);

echo"<div class='admin-message'>".$locale['uf_privacy_006']."</div>";

closetable();

require_once DESIGNS."templates/footer.php";
?>