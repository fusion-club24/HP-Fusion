<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: user_privacy_include.php
| Author: Fangree Productions
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
if (!defined("IN_FUSION")) { die("Access Denied"); }

if ($profile_method == "input") {
		$user_privacy = isset($user_data['user_privacy']) ? $user_data['user_privacy'] : "";
		if ($this->isError()) { $user_privacy = isset($_POST['user_privacy']) ? stripinput($_POST['user_privacy']) : $user_privacy; }
		
// Boxover Javascript
	echo "<script src='".INCLUDES."js/forum_prev.js' type='text/javascript'></script>\n";
	echo "<tr>\n";
    echo "<td class='tbl".$this->getErrorClass("user_privacy")."'><label for='user_privacy'>".$locale['uf_privacy_001'].$required."</label></td>\n";
    echo "<td class='tbl".$this->getErrorClass("user_privacy")."'>";
    echo "<select name='user_privacy' class='textbox'>\n";
    echo "<option value=''".(empty($user_privacy) ? ' selected=selected' : '').">".$locale['uf_privacy_002']."</option>\n";
    echo "<option value='101'".($user_privacy == '101' ? ' selected=selected' : '').">".$locale['uf_privacy_003']."</option>\n";
    echo "<option value='102'".($user_privacy == '102' ? ' selected=selected' : '').">".$locale['uf_privacy_005']."</option>\n";
    echo "</select></td>\n";
	echo "</tr>\n";
	
	if ($required) { $this->setRequiredJavaScript("user_privacy", $locale['uf_privacy_error']); }
	
  } elseif ($profile_method == "display") {
  
	global $userdata;
	
	  //If Admin or Users Own Profile Show this
	if(!iGUEST) {
     if (iADMIN && checkrights("C") || $userdata['user_id'] == $user_data['user_id']){
	    if ($user_data['user_privacy']) {
          echo "<tr>\n";
          echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_privacy']." </td>\n";
	    echo "<td align='right' class='tbl1'>";
       if ($user_data['user_privacy'] == 101) { echo $locale['uf_privacy_009']; }elseif ($user_data['user_privacy'] == 102) { echo $locale['uf_privacy_010']; }
    echo "</td>\n</tr>\n";
        }else{
		echo "<tr>\n";
          echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_privacy']." </td>\n";
	    echo "<td align='right' class='tbl1'>";
		echo $locale['uf_privacy_002'];
		echo "</td>\n</tr>\n";}
       }
    }
   
     // Members Only Can View Profiles
	if ($user_data['user_privacy'] == 101 && !iMEMBER){ redirect(INCLUDES."user_fields/members_only.php"); }
	 // Admins Only Can View Profiles
    if ($user_data['user_privacy'] == 102 && iGUEST && !iADMIN){ redirect(INCLUDES."user_fields/admins_only.php"); }
	if ($user_data['user_privacy'] == 102 && !iADMIN && $userdata['user_id'] != $user_data['user_id']){ redirect(INCLUDES."user_fields/admins_only.php?lookup=".$_GET['lookup'].""); }
	
 
 // Insert and update
} elseif ($profile_method == "validate_insert"  || $profile_method == "validate_update") {
	// Get input data
if (isset($_POST['user_privacy']) && stripinput($_POST['user_privacy'] || $this->_isNotRequired("user_privacy"))) {
		// Set update or insert user data
		$this->_setDBValue("user_privacy", stripinput($_POST['user_privacy']));
	} else {
		$this->_setError("user_privacy", $locale['uf_privacy_error'], true);	
	}
}
?>