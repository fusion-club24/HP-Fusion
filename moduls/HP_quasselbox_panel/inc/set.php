<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: set.php
| Author: Harlekin
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once MODULS."HP_quasselbox_panel/infusion_db.php";

//can guest post
$set_quasselbox_guest = dbarray(dbquery("SELECT settings_value FROM ".DB_HP_QUASSELBOX_SETTINGS." WHERE settings_name='guest_quassels'"));
$set_guest_quassels = $set_quasselbox_guest['settings_value'];
//how many posta on panel
$set_quasselbox_visible = dbarray(dbquery("SELECT settings_value FROM ".DB_HP_QUASSELBOX_SETTINGS." WHERE settings_name='visible_quassels'"));
$set_visible_quassels = $set_quasselbox_visible['settings_value'];
//how many posta in archive
$set_quasselbox_arch_visible = dbarray(dbquery("SELECT settings_value FROM ".DB_HP_QUASSELBOX_SETTINGS." WHERE settings_name='visible_arch_quassels'"));
$set_visible_arch_quassels = $set_quasselbox_arch_visible['settings_value'];
//vote allows
$set_quasselbox_vote = dbarray(dbquery("SELECT settings_value FROM ".DB_HP_QUASSELBOX_SETTINGS." WHERE settings_name='vote_quassels'"));
$set_vote_quassels = $set_quasselbox_vote['settings_value'];
//admin message on/off
$set_quasselbox_note = dbarray(dbquery("SELECT settings_value FROM ".DB_HP_QUASSELBOX_SETTINGS." WHERE settings_name='note_quassels'"));
$set_note_quassels = $set_quasselbox_note['settings_value'];
//admin message side panel
$set_quasselbox_note_t1 = dbarray(dbquery("SELECT settings_value FROM ".DB_HP_QUASSELBOX_SETTINGS." WHERE settings_name='note_text1'"));
$set_note_t1_quassels = $set_quasselbox_note_t1['settings_value'];
//admin message middle panel and archiv
$set_quasselbox_note_t2 = dbarray(dbquery("SELECT settings_value FROM ".DB_HP_QUASSELBOX_SETTINGS." WHERE settings_name='note_text2'"));
$set_note_t2_quassels = $set_quasselbox_note_t2['settings_value'];
//color textinput
$set_color_textarea = dbarray(dbquery("SELECT settings_value FROM ".DB_HP_QUASSELBOX_SETTINGS." WHERE settings_name='color_textarea'"));
$set_textarea_color = $set_color_textarea['settings_value'];
//color background user
$set_color_qbname = dbarray(dbquery("SELECT settings_value FROM ".DB_HP_QUASSELBOX_SETTINGS." WHERE settings_name='color_qbname'"));
$set_qbname_color = $set_color_qbname['settings_value'];
//color arrow
$set_color_qbdate = dbarray(dbquery("SELECT settings_value FROM ".DB_HP_QUASSELBOX_SETTINGS." WHERE settings_name='color_qbdate'"));
$set_qbdate_color = $set_color_qbdate['settings_value'];

?>