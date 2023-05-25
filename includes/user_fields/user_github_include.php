<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: user_github_include.php
| Original Author: Hans Kristian Flaatten {Starefossen} (github userfield)
| Edited by skpacman for GitHub userfield
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

$icon = "<img src='".IMAGES."user_fields/github.png' width='24' alt='' style='vertical-align: middle;'>";

// Display user field input
if ($profile_method == "input") {
	$user_github = isset($user_data['user_github']) ? $user_data['user_github'] : "";
	if ($this->isError()) { $user_github = isset($_POST['user_github']) ? stripinput($_POST['user_github']) : $user_github; }
	
	echo "<tr>\n";
	echo "<td class='tbl".$this->getErrorClass("user_github")."'><label for='user_github'>".$icon." ".$locale['uf_github_desc'].$required."</label></td>\n";
	echo "<td class='tbl".$this->getErrorClass("user_github")."'>";
	echo "<input type='text' id='user_github' name='user_github' value='".$user_github."' maxlength='16' class='textbox' style='width:200px;' />";
	echo "</td>\n</tr>\n";

	if ($required) { $this->setRequiredJavaScript("user_github", $locale['uf_github_error']); }
	
// Display in profile
} elseif ($profile_method == "display") {
	if ($user_data['user_github']) {
		echo "<tr>\n";
		echo "<td class='tbl1'>".$icon." ".$locale['uf_github']."</td>\n";
		echo "<td align='right' class='tbl1'><a class='button' href='https://github.com/".$user_data['user_github']."/' target='_blank'>".$locale['uf_github_link']."</a></td>\n";
		echo "</tr>\n";
	}
	
// Insert or update
} elseif ($profile_method == "validate_insert"  || $profile_method == "validate_update") {
	// Get input data
	if (isset($_POST['user_github']) && ($_POST['user_github'] != "" || $this->_isNotRequired("user_github"))) {
		// Set update or insert user data
		$this->_setDBValue("user_github", stripinput(trim($_POST['user_github'])));
	} else {
		$this->_setError("user_github", $locale['uf_github_error'], true);	
	}
}
?>
