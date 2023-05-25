<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: site.php
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
require_once INCLUDES."comments_include.php";
require_once INCLUDES."ratings_include.php";
include LOCALE.LOCALESET."custom_pages.php";

if (!isset($_GET['site_id']) || !isnum($_GET['site_id'])) { redirect("index.php"); }
if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }

$cp_result = dbquery("SELECT * FROM ".DB_CUSTOM_PAGES." WHERE page_id='".$_GET['site_id']."'");
if (dbrows($cp_result)) {
	$cp_data = dbarray($cp_result);
	add_to_title($locale['global_200'].$cp_data['page_title']);
	echo "<!--custompages-pre-content-->\n";
	opentable($cp_data['page_title']);
	if (checkgroup($cp_data['page_access'])) {
		 $cp_data['page_breaks'] == "y" ? $page_content = nl2br($cp_data['page_content']) : $page_content = $cp_data['page_content'];
		ob_start();
		eval("?>".stripslashes(parsesmileys(parseubb($page_content)))."<?php ");
		$custompage = ob_get_contents();
		ob_end_clean();
		$custompage = preg_split("/<!?--\s*pagebreak\s*-->/i", $custompage);
		$pagecount = count($custompage);
		echo $custompage[$_GET['rowstart']];
	} else {
		echo "<div class='admin-message' style='text-align:center'><br /><img style='border:0px; vertical-align:middle;' src ='".BASEDIR."images/warn.png' alt=''/><br /> ".$locale['400']."<br /><a href='index.php' onclick='javascript:history.back();return false;'>".$locale['403']."</a>\n<br /><br /></div>\n";
	}
} else {
	add_to_title($locale['global_200'].$locale['401']);
	echo "<!--custompages-pre-content-->\n";
	opentable($locale['401']);
	echo "<div style='text-align:center'><br />\n".$locale['402']."\n<br /><br /></div>\n";
}
closetable();
if (isset($pagecount) && $pagecount > 1) {
    echo "<div align='center' style='margin-top:5px;'>\n".makepagenav($_GET['rowstart'], 1, $pagecount, 3, FUSION_SELF."?site_id=".$_GET['site_id']."&amp;")."\n</div>\n";
}
echo "<!--custompages-after-content-->\n";
if (dbrows($cp_result) && checkgroup($cp_data['page_access'])) {
	if ($cp_data['page_allow_comments']) { showcomments("C", DB_CUSTOM_PAGES, "page_id", $_GET['site_id'],FUSION_SELF."?site_id=".$_GET['site_id']); }
	if ($cp_data['page_allow_ratings']) { showratings("C", $_GET['site_id'], FUSION_SELF."?site_id=".$_GET['site_id']); }
}

require_once DESIGNS."templates/footer.php";
?>
