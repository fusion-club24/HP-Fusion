<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: omments_show.php
| Author: Harlekin
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

include LOCALE.LOCALESET."admin/comments.php";
include LOCALE.LOCALESET."comments_show.php";

add_to_title($locale['global_200'].$locale['hpce008']);

define("SAFEMODE", @ini_get("safe_mode") ? true : false);

opentable($locale['hpce008']);
	
echo"<span style=\"font-size: small;\">".$locale['hpce012']."</span><span style=\"font-size: small; color: #ff0000;\"><strong>".dbcount("(comment_id)", DB_COMMENTS)."</strong></span><span style=\"font-size: small;\">".$locale['hpce013']."</span>";
echo"<p>";
$i = 0;
if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
$result = dbquery(
"SELECT * FROM ".DB_COMMENTS." LEFT JOIN ".DB_USERS."
ON ".DB_COMMENTS.".comment_name=".DB_USERS.".user_id
ORDER BY comment_datestamp DESC LIMIT ".$_GET['rowstart'].",".$locale['hpce009'].""
);
if (dbrows($result)) {
	echo "<div align='left' style='margin-top:5px;'>\n".makepagenav($_GET['rowstart'],$locale['hpce009'],dbcount("(comment_id)", DB_COMMENTS),3)."\n</div><br />\n";
	echo "<table width='100%' align='center' cellpadding='0' cellspacing='1' class='tbl-border'>\n";
		while ($data = dbarray($result)) {
			echo "<tr>\n<td class='".($i% 2==0?"tbl1":"tbl2")."'><span class='comment-name'>";
				if (!empty($data['user_avatar']) && file_exists(IMAGES."avatars/".$data['user_avatar'])){
					echo "<img height='30' width='30'align='left'  src='".IMAGES."avatars/".$data['user_avatar']."' alt='".$data['user_name']."' />&nbsp;";
				} else {
					echo "<img height='30' align='left' width='30' src='".IMAGES."avatars/noavatar100.png' alt='".($data['user_name'] ? $data['user_name'] : $data['comment_name'])."' />&nbsp;";	
				}
				if ($data['user_name']) {
					echo "<strong><span class='comment-name'>".profile_link($data['comment_name'], $data['user_name'], $data['user_status'])."</span></strong>";
				} else {
					echo $data['comment_name'];
				}

				$comment_message = nl2br(parseubb(parsesmileys ($data['comment_message'])));
				$comment_item_id = $data['comment_item_id'];
				$comment_type = $data['comment_type'];
				echo "</span>";
				echo " am<span class='small'> ".showdate("longdate", $data['comment_datestamp'])." | <strong>".number_format(dbcount("(comment_id)", DB_COMMENTS, "comment_name='".$data['user_id']."'"))."</strong> ".$locale['hpce014']." |";
				if (checkrights("C")) {
					echo "<strong>".$locale['432']." </strong>".$data['comment_ip']." | <a href='".ADMIN."comments.php".$aidlink."&amp;ctype=".$data['comment_type']."&amp;cid=".$data['comment_item_id']."'> <img style='vertical-align:middle;' src='".IMAGES."edit_del.png' alt='".$locale['hpce011']."' title='".$locale['hpce011']."'/></a>";
				}
				echo"</span>  <p>
				&nbsp;".$comment_message."<br />";

				//sorted by comennt type
				//Articles
				if ($data['comment_type'] == "A") {
					echo "&nbsp;<p align='right'><span style='margin-right:10px'><a class='button' href='".BASEDIR."readarticle.php?article_id=".$data['comment_item_id']."'>".$locale['hpce002']."</a></span><br /><br />";
				}
				//Comments for TI Blog System 
				else if ($data['comment_type'] == "BS") {
					echo "&nbsp;<p align='right'><span style='margin-right:10px'><a class='button' href='".BASEDIR."blog.php?page=blog_id&amp;id=".$data['comment_item_id']."'>".$locale['hpce022']."</a></span><br /><br />";
				}
				//Custom Page
				else if ($data['comment_type'] == "C") {
					echo "&nbsp;<p align='right'><span style='margin-right:10px'><a class='button' href='".BASEDIR."site.php?page_id=".$data['comment_item_id']."'>".$locale['hpce004']."</a></span><br /><br />";
				}
				//Downloads
				else if ($data['comment_type'] == "D") {
					$resultdl = dbquery("SELECT download_cat FROM ".DB_PREFIX."downloads WHERE download_cat='".$data['comment_item_id']."'");
					$datadl = dbarray($resultdl);
					echo "&nbsp;<p align='right'><span style='margin-right:10px'><a class='button' href='".BASEDIR."downloads.php?download_cat=".$datadl['download_cat']."&amp;download_id=".$data['comment_item_id']."'>".$locale['hpce005']."</a></span><br /><br />";
				}
				//Comments for DF Development Infusion 1.03 
				else if ($data['comment_type'] == "DV") {
					echo "&nbsp;<p align='right'><span style='margin-right:10px'><a class='button' href='".MODULS."df_development_infusion/development.php?dev_id=".$data['comment_item_id']."'>".$locale['hpce024']."</a></span><br /><br />";
				}
				//Comments for FAQ System 
				else if ($data['comment_type'] == "FS") {
					$resultfs = dbquery("SELECT faq_cat FROM ".DB_PREFIX."hp_faqs WHERE faq_id='".$data['comment_item_id']."'");
					$datac = dbarray($resultfs);
					echo "&nbsp;<p align='right'><span style='margin-right:10px'><a class='button' href='".MODULS."HP_faq_system/faqs.php?cat_id=".$datac['faq_cat']."&amp;faq_id=".$data['comment_item_id']."'>".$locale['hpce023']."</a></span><br /><br />";
				}
				//Varcade 
				else if ($data['comment_type'] == "G") {
					echo "&nbsp;<p align='right'><span style='margin-right:10px'><a class='button' href='".MODULS."varcade/arcade.php?p=1&game=".$data['comment_item_id']."'>".$locale['hpce019']."</a></span><br /><br />";
				}
				//News
				else if ($data['comment_type'] == "N") {
					echo "&nbsp;<p align='right'><span style='margin-right:10px'><a class='button' href='".BASEDIR."news.php?readmore=".$data['comment_item_id']."'>".$locale['hpce001']."</a></span><br /><br />";
				}
				//Nickpages 
				else if ($data['comment_type'] == "NP") {
					echo "&nbsp;<p align='right'><span style='margin-right:10px'><a class='button' href='".BASEDIR."nickpage.php?user=".$data['comment_name']."'>".$locale['hpce017']."</a></span><br /><br />";
				}
				//Photogallery
				else if ($data['comment_type'] == "P") {
					echo "&nbsp;<p align='right'><span style='margin-right:10px'><a class='button' href='".BASEDIR."photogallery.php?photo_id=".$data['comment_item_id']."'>".$locale['hpce003']."</a></span><br /><br />";
				}
				//Profile Tagwall 
				else if ($data['comment_type'] == "PF") {
					echo "&nbsp;<p align='right'><span style='margin-right:10px'><a class='button' href='".BASEDIR."profile.php?lookup=".$data['comment_item_id']."'>".$locale['hpce006']."</a></span><br /><br />";
				}
				//Tutorial System 1.0.5 
				else if ($data['comment_type'] == "T") {
					$resultt = dbquery("SELECT tutorial_cat FROM ".DB_PREFIX."tutorials WHERE tutorial_id='".$data['comment_item_id']."'");
					$datat = dbarray($resultt);
					echo "&nbsp;<p align='right'><span style='margin-right:10px'><a class='button' href='".MODULS."tutorials_system/tutorials.php?cat_id=".$datat['tutorial_cat']."&amp;tutorial_id=".$data['comment_item_id']."'>".$locale['hpce021']."</a></span><br /><br />";
				}
				//HP Tutorial Panel 
				else if ($data['comment_type'] == "TP") {
					$resulttp = dbquery("SELECT tutorial_cat FROM ".DB_PREFIX."hp_tutorials WHERE tutorial_id='".$data['comment_item_id']."'");
					$datatp = dbarray($resulttp);
					echo "&nbsp;<p align='right'><span style='margin-right:10px'><a class='button' href='".BASEDIR."HP_tutorials.php?cat_id=".$datatp['tutorial_cat']."&amp;tutorial_id=".$data['comment_item_id']."'>".$locale['hpce021']."</a></span><br /><br />";
				}
				//Comments for Userphotogallery 
				else if ($data['comment_type'] == "U") {
					echo "&nbsp;<p align='right'><span style='margin-right:10px'><a class='button' href='".BASEDIR."userphotogallery.php?photo_id=".$data['comment_item_id']."'>".$locale['hpce018']."</a></span><br /><br />";
				}
				//Comments for MG User Fotoalbum
				else if ($data['comment_type'] == "UF") {
					echo "&nbsp;<p align='right'> <a class='button' href='".MODULS."mg_user_fotoalbum_panel/mg_user_fotoalbum.php?album_user_id=".getUFAUser($data['comment_item_id'])."&amp;album_id=".$data['comment_item_id']."#c".$data['comment_id']."'>".$locale['hpce020']."</a></span><br /><br />";
				}
				//Witzesammlung 
				else if ($data['comment_type'] == "W") {
					echo "&nbsp;<p align='right'><span style='margin-right:10px'><a class='button' href='".MODULS."witze_sammlung/witze_sammlung.php?section=kat&kategorie=&witz=".$data['comment_item_id']."'>".$locale['hpce016']."</a></span><br /><br />";
				}
				//No Comments link
				else {
					echo "&nbsp;<p align='right'> ".$locale['hpce015'].".";
				}
			echo "</td>\n</tr>\n";
			$i++;
		}
	echo "</table>\n";
	// No Commtents link 2 
} else {
	echo "<center><br />".$locale['hpce007']."<br /><br /></center>\n";
}
echo "<br /><div align='left' style='margin-top:5px;'>\n".makepagenav($_GET['rowstart'],$locale['hpce009'],dbcount("(comment_id)", DB_COMMENTS),3)."\n</div>\n";

function getUFAUser($id) {
	if (file_exists(MODULS."mg_user_fotoalbum_panel/infusion_db.php")) {
		include MODULS."mg_user_fotoalbum_panel/infusion_db.php";
		$result = dbquery("SELECT album_user_id FROM ".MG_UFA_ALBEN." WHERE album_id='".$id."'");
		if (dbrows($result)) {
			$data = dbarray($result);
			$ufa_user = $data['album_user_id'];
		} else {
			$ufa_user = "";
		}
	} else {
		$ufa_user = "";
	}
	return $ufa_user;
}

closetable();

require_once DESIGNS."templates/footer.php";
?>