<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: footer_includes.php
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

//Add your custom include files for the footer here

//ScoreSystem start
$score_install = dbarray(dbquery("SELECT inf_title FROM ".DB_PREFIX."infusions WHERE inf_folder='scoresystem_panel'"));
$score_inst = $score_install['inf_title'];
if ((file_exists(MODULS."scoresystem_panel/infusion.php")) && ($score_inst == 'ScoreSystem')) {
require_once MODULS."scoresystem_panel/scoresystem_footer_include.php";
}
//ScoreSystem stop

?>