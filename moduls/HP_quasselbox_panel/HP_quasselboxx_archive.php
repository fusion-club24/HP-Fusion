<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: HP_quasselbox_archive.php
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
require_once "../../maincore.php";
require_once DESIGNS."templates/header.php";
include MODULS."HP_quasselbox_panel/inc/set.php";
include MODULS."HP_quasselbox_panel/inc/qb_style.php";
include_once MODULS."HP_quasselbox_panel/infusion_db.php";
include_once INCLUDES."infusions_include.php";

// Check if locale file is available matching the current site locale setting.
if (file_exists(MODULS."HP_quasselbox_panel/locale/".$settings['locale'].".php")) {
	// Load the locale file matching the current site locale setting.
	include MODULS."HP_quasselbox_panel/locale/".$settings['locale'].".php";
} else {
	// Load the infusion's default locale file.
	include MODULS."HP_quasselbox_panel/locale/German.php";
}

add_to_title($locale['global_200'].$locale['HPQB_040']);

$quassel_access_control = true;

$archive_quassel_link = ""; $archive_quassel_message = "";
//delete post start
if (iMEMBER && (isset($_GET['action']) && $_GET['action'] == "delete") && (isset($_GET['quassel_id']) && isnum($_GET['quassel_id']))) {
	if ((iADMIN && checkrights("HPQB")) || (iMEMBER && dbcount("(quassel_id)", DB_HP_QUASSELBOX, "quassel_id='".$_GET['quassel_id']."' AND quassel_name='".$userdata['user_id']."' AND quassel_hidden='0'"))) {
		$result = dbquery("DELETE FROM ".DB_HP_QUASSELBOX." WHERE quassel_id='".$_GET['quassel_id']."'".(iADMIN ? "" : " AND quassel_name='".$userdata['user_id']."'"));
		$result = dbquery("DELETE FROM ".DB_HP_QUASSELBOX_LIKES." WHERE post_id='".$_GET['quassel_id']."'");
	}
	redirect(FUSION_SELF);
}
//delete post stop

//vote start
if (iMEMBER && (isset($_GET['action']) && $_GET['action'] == "archive_plus") && (isset($_GET['quassel_id']) && isnum($_GET['quassel_id']))) {
	if (iMEMBER) {
		$exist = dbquery("SELECT * FROM ".DB_HP_QUASSELBOX_LIKES." WHERE user_id='".$userdata['user_id']."' AND post_id=".$_GET['quassel_id']);
		if (!dbrows($exist)) {
			$result = dbquery("INSERT INTO ".DB_HP_QUASSELBOX_LIKES." (rating_id, post_id, user_id, rating_value) VALUES ('', '".$_GET['quassel_id']."', '".$userdata['user_id']."', '1')");
			if (defined("SCORESYSTEM")) {
				$score_qbvt = dbarray(dbquery("SELECT sco_power FROM ".DB_PREFIX."score_score WHERE sco_aktion='QBVOT'"));
				$qbvt = $score_qbvt['sco_power'];
				if ((file_exists(MODULS."scoresystem_panel/infusion.php")) && ($qbvt == 1)) {
					score_positive("QBVOT");
				}
			}
		} else {
			$result = dbquery("UPDATE ".DB_HP_QUASSELBOX_LIKES." SET rating_value=1 WHERE user_id='".$userdata['user_id']."' AND post_id=".$_GET['quassel_id']);
		}
	}
}

if (iMEMBER && (isset($_GET['action']) && $_GET['action'] == "archive_minus") && (isset($_GET['quassel_id']) && isnum($_GET['quassel_id']))) {
	if (iMEMBER) {
		$exist = dbquery("SELECT * FROM ".DB_HP_QUASSELBOX_LIKES." WHERE user_id='".$userdata['user_id']."' AND post_id=".$_GET['quassel_id']);
		if (!dbrows($exist)) {
			$result = dbquery("INSERT INTO ".DB_HP_QUASSELBOX_LIKES." (rating_id, post_id, user_id, rating_value) VALUES ('', '".$_GET['quassel_id']."', '".$userdata['user_id']."', '2')");
			if (defined("SCORESYSTEM")) {
				$score_qbvt = dbarray(dbquery("SELECT sco_power FROM ".DB_PREFIX."score_score WHERE sco_aktion='QBVOT'"));
				$qbvt = $score_qbvt['sco_power'];
				if ((file_exists(MODULS."scoresystem_panel/infusion.php")) && ($qbvt == 1)) {
					score_positive("QBVOT");
				}
			}
		} else {
			$result = dbquery("UPDATE ".DB_HP_QUASSELBOX_LIKES." SET rating_value=2 WHERE user_id='".$userdata['user_id']."' AND post_id=".$_GET['quassel_id']);
		}
	}
}
//vote stop

opentable($locale['HPQB_040']);
echo '<script type="text/javascript">
	function toggle_hpqa(id) {
		spanid = "toggle_hpqa_"+id;
		val = document.getElementById(spanid).style.display;
		if (val == "none") {
			document.getElementById(spanid).style.display = "block";
		} else {
			document.getElementById(spanid).style.display = "none";
		}
	}
</script>';
echo "<center><a class='button' href='javascript:toggle_hpqa(\"input\")'>".$locale['HPQB_023']."</a></center><br />";
if (iMEMBER && (isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['quassel_id']) && isnum($_GET['quassel_id']))) {
	echo "<div id='toggle_hpqa_input'>\n";
} else {
	echo "<div id='toggle_hpqa_input' style='display:none'>\n";
}

if ((iMEMBER && $set_note_quassels == "1") || (iGUEST && $set_guest_quassels == "1" && $set_note_quassels == "1")) {
	echo"<div class='quasselbox_arch_msg' align='center'><img src='".MODULS."HP_quasselbox_panel/images/stop.png' style='width:16px; vertical-align:middle;'> <strong>$set_note_t2_quassels</strong></div>\n";
}
echo "<!-- ".$locale['HPQB_title']." ".$locale['HPQB_version']." by ".$locale['HPQB_dev']." ".$locale['HPQB_url']."-->";
if (iMEMBER || $set_guest_quassels == "1") {
	include_once INCLUDES."bbcode_include.php";
	if (isset($_POST['post_archive_quassel'])) {
		$flood = false;
		if (iMEMBER) {
			$archive_quassel_name = $userdata['user_id'];
		} elseif ($set_guest_quassels == "1") {
			$archive_quassel_name = trim(stripinput($_POST['archive_quassel_name']));
			$archive_quassel_name = preg_replace("(^[+0-9\s]*)", "", $archive_quassel_name);
			if (isnum($archive_quassel_name)) {
				$archive_quassel_name = "";
			}
			include_once INCLUDES."captchas/securimage/securimage.php";
			$securimage = new Securimage();
			if (!isset($_POST['captcha_code']) || $securimage->check($_POST['captcha_code']) == false) {
				redirect($link);
			}
		}
		$archive_quassel_message = str_replace("\n", " ", $_POST['archive_quassel_message']);
		$archive_quassel_message = preg_replace("/^(.{255}).*$/", "$1", $archive_quassel_message);
		$archive_quassel_message = trim(stripinput(censorwords($archive_quassel_message)));
		$archive_quassel_read_access = ( (isset($_POST['archive_quassel_read_access']) && isNum($_POST['archive_quassel_read_access']) ) ? $_POST['archive_quassel_read_access'] : 0 );
		if (iMEMBER && (isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['quassel_id']) && isnum($_GET['quassel_id']))) {
			$comment_updated = false;
			if ((iADMIN && checkrights("HPQB")) || (iMEMBER && dbcount("(quassel_id)", DB_HP_QUASSELBOX, "quassel_id='".$_GET['quassel_id']."' AND quassel_name='".$userdata['user_id']."' AND quassel_hidden='0'"))) {
				if ($archive_quassel_message) {
					$result = dbquery("UPDATE ".DB_HP_QUASSELBOX." SET quassel_message='$archive_quassel_message', quassel_read_access='".$archive_quassel_read_access."' WHERE quassel_id='".$_GET['quassel_id']."'".(iADMIN ? "" : " AND quassel_name='".$userdata['user_id']."'"));
				}
			}
			redirect(FUSION_SELF);
		} elseif ($archive_quassel_name && $archive_quassel_message) {
			require_once INCLUDES."flood_include.php";
			if (!flood_control("quassel_datestamp", DB_HP_QUASSELBOX, "quassel_ip='".USER_IP."'")) {
				if (iMEMBER) {
				$result = dbquery("INSERT INTO ".DB_HP_QUASSELBOX." (quassel_name, quassel_read_access, quassel_message, quassel_datestamp, quassel_ip, quassel_ip_type) VALUES ('$archive_quassel_name', '$archive_quassel_read_access', '$archive_quassel_message', '".time()."', '".USER_IP."', '".USER_IP_TYPE."')");
				} else {
				$result = dbquery("INSERT INTO ".DB_HP_QUASSELBOX." (quassel_name, quassel_message, quassel_datestamp, quassel_ip, quassel_ip_type) VALUES ('Gast_$archive_quassel_name', '$archive_quassel_message', '".time()."', '".USER_IP."', '".USER_IP_TYPE."')");
				}
				if (iMEMBER) {
					if (defined("SCORESYSTEM")) {
						score_positive("SHBOX");
					}
				}
			}
			redirect(FUSION_SELF);
		}
	}
	if (iMEMBER && (isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['quassel_id']) && isnum($_GET['quassel_id']))) {
		$esresult = dbquery(
			"SELECT tq.quassel_id, tq.quassel_name, tq.quassel_read_access, tq.quassel_message, tu.user_id, tu.user_name
			FROM ".DB_HP_QUASSELBOX." tq
			LEFT JOIN ".DB_USERS." tu ON tq.quassel_name=tu.user_id
			WHERE tq.quassel_id='".$_GET['quassel_id']."' AND quassel_hidden='0'"
		);
		if (dbrows($esresult)) {
			$esdata = dbarray($esresult);
			if ((iADMIN && checkrights("HPQB")) || (iMEMBER && $esdata['quassel_name'] == $userdata['user_id'] && isset($esdata['user_name']))) {
				if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['quassel_id']) && isnum($_GET['quassel_id']))) {
					$edit_url = "?action=edit&amp;quassel_id=".$esdata['quassel_id'];
				} else {
					$edit_url = "";
				}
				$archive_quassel_link = FUSION_SELF.$edit_url;
				$archive_quassel_message = $esdata['quassel_message'];
				$archive_quassel_read_access = $esdata['quassel_read_access'];
			}
		} else {
			$archive_quassel_link = FUSION_SELF;
			$archive_quassel_message = "";
			$archive_quassel_read_access = 0;
		}
	} else {
		$archive_quassel_link = FUSION_SELF;
		$archive_quassel_message = "";
		$archive_quassel_read_access = 0;
	}
	
	echo "<script type='text/javascript'>
	function textHPQB_arch_Counter(textarea, counterID, maxLen) {
	cnt = document.getElementById(counterID);
	if (textarea.value.length > maxLen)
	{
	textarea.value = textarea.value.substring(0,maxLen);
	}
	cnt.innerHTML = maxLen - textarea.value.length;
	}
	</script>";
	
	echo "<form name='archive_form' method='post' action='".$archive_quassel_link."'>\n";
		echo "<div style='text-align:center'>\n";
			if (iGUEST) {
				echo $locale['HPQB_002']."<br />\n";
				echo "<input type='text' name='archive_quassel_name' value='' class='textbox' maxlength='30' style='width:200px;' /><br />\n";
				echo "<br />\n";
			}
			if (iMEMBER) {
				echo "<div align='center' valign='middle'><small>".$locale['HPQB_003']."<span id='count_display1' style='padding : 1px 3px 1px 3px; border:1px solid;'>200</span></small><br /><textarea class='textbox' name='archive_quassel_message' rows='4' cols='20' style=\"width:305px; background: #$set_textarea_color url(".MODULS."HP_quasselbox_panel/images/qb_archiv.png) no-repeat scroll 0% 0%; width: 280px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;\" onfocus=\"if(this.value=='')this.value='';\" onblur=\"if(this.value=='')this.value=='';\" onKeyDown=\"textHPQ_arch_BCounter(this,'count_display1',200);\" onKeyUp=\"textHPQB_arch_Counter(this,'count_display1',200);\" placeholder='".$locale['HPQB_022']."".$userdata['user_name']."'>".$archive_quassel_message.(empty($archive_quassel_message)?"":"")."</textarea>\n</div>\n";
			} else {
				echo "<div align='center' valign='middle'><small>".$locale['HPQB_003']."<span id='count_display1' style='padding : 1px 3px 1px 3px; border:1px solid;'>200</span></small><br /><textarea class='textbox' name='archive_quassel_message' rows='4' cols='20' style=\"width:305px; background: #$set_textarea_color url(".MODULS."HP_quasselbox_panel/images/qb_archiv.png) no-repeat scroll 0% 0%; width: 280px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;\" onfocus=\"if(this.value=='')this.value='';\" onblur=\"if(this.value=='')this.value=='';\" onKeyDown=\"textHPQ_arch_BCounter(this,'count_display1',200);\" onKeyUp=\"textHPQB_arch_Counter(this,'count_display1',200);\" placeholder='".$locale['HPQB_022']."Gast'>".$archive_quassel_message.(empty($archive_quassel_message)?"":"")."</textarea>\n</div>\n";
			}
			echo "<div style='text-align:center'>".display_bbcodes("100%", "archive_quassel_message", "archive_form", "smiley|b|i|u|url|color")."</div>\n";
			if (iGUEST) {
				echo $locale['HPQB_004']."<br />\n";
				echo "<img id='captcha' src='".INCLUDES."captchas/securimage/securimage_show.php' alt='' /><br />\n";
				echo "<a href='".INCLUDES."captchas/securimage/securimage_play.php'><img src='".INCLUDES."captchas/securimage/images/audio_icon.gif' alt='' class='tbl-border' style='margin-bottom:1px' /></a>\n";
				echo "<a href='#' onclick=\"document.getElementById('captcha').src = '".INCLUDES."captchas/securimage/securimage_show.php?sid=' + Math.random(); return false\"><img src='".INCLUDES."captchas/securimage/images/refresh.gif' alt='' class='tbl-border' /></a><br />\n";
				echo $locale['HPQB_005']."<br />\n<input type='text' name='captcha_code' class='textbox' style='width:100px' /><br />\n";
			}

			//thanks to Rolly8-HL
			if(iMEMBER && $quassel_access_control) {
				echo "<small>".$locale['HPQB_006']."</small>";
				echo "<br /><select class='textbox' name='archive_quassel_read_access'>";                                  
				$option_list = "";
				$options = getusergroups(); 
				if(!iADMIN){
					$option_list .= "<option value='0' ".($archive_quassel_read_access  == "0" ? " selected='selected'" : "").">".$locale['user0']."</option>\n";
					$option_list .= "<option value='101' ".($archive_quassel_read_access  == "101" ? " selected='selected'" : "").">".$locale['user1']."</option>\n";
					foreach($options as $key => $option) {
						if (in_array($option['0'], explode(".", iUSER_GROUPS))) {  
							$sel = ($archive_quassel_read_access == $option['0'] ? " selected='selected'" : "");
							$option_list .= "<option value='".$option['0']."' ".$sel.">".$option['1']."</option>\n";
						}
					}
				} else {
					$option_list = "";
					$options = getusergroups(); 
					foreach($options as $key => $option) {
						$sel = ($archive_quassel_read_access == $option['0'] ? " selected='selected'" : "");
						$option_list .= "<option value='".$option['0']."' ".$sel.">".$option['1']."</option>\n";
					}
				}
				echo $option_list."</select>";
			}
			echo "<br /><input type='submit' name='post_archive_quassel' value='".$locale['HPQB_007']."' class='button' />\n";
			echo "<input type='reset' name='archive_reset' value='".$locale['HPQB_008']."' class='button' /></center>\n";
		echo "</div>\n";
	echo "</form>\n<br />\n";
} else {
	echo "<div class='quasselbox_arch_msg_info' align='center'><img src='".MODULS."HP_quasselbox_panel/images/info.png' style='width:16px; vertical-align:middle;'> ".$locale['HPQB_009']."</div>\n";
}
echo "</div>";

if (!iMEMBER  ) {
	$rows = dbcount("(quassel_id)", DB_HP_QUASSELBOX,"quassel_hidden='0' AND ".groupaccess('quassel_read_access')."");
} else {
	$rows = dbcount("(quassel_id)", DB_HP_QUASSELBOX,"quassel_hidden='0' AND ".groupaccess('quassel_read_access')."  || quassel_name='".$userdata['user_id']."'");
}

if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
if ($rows != 0) {
	if (iMEMBER) {
		$result = dbquery(
			"SELECT q.quassel_id, q.quassel_name, q.quassel_read_access, q.quassel_message, q.quassel_datestamp, u.user_id, u.user_name, u.user_avatar, u.user_status, u.user_lastvisit
			FROM ".DB_HP_QUASSELBOX." q
			LEFT JOIN ".DB_USERS." u ON q.quassel_name=u.user_id
			WHERE quassel_hidden='0' AND ".groupaccess('quassel_read_access')."  || q.quassel_name='".$userdata['user_id']."'
			ORDER BY q.quassel_datestamp DESC LIMIT ".$_GET['rowstart'].",".$set_visible_arch_quassels.""
		);
	} else {
		$result = dbquery(
			"SELECT q.quassel_id, q.quassel_name, q.quassel_read_access, q.quassel_message, q.quassel_datestamp, u.user_id, u.user_name, u.user_avatar, u.user_status, u.user_lastvisit
			FROM ".DB_HP_QUASSELBOX." q
			LEFT JOIN ".DB_USERS." u ON q.quassel_name=u.user_id
			WHERE quassel_hidden='0' AND ".groupaccess('quassel_read_access')."
			ORDER BY q.quassel_datestamp DESC LIMIT ".$_GET['rowstart'].",".$set_visible_arch_quassels.""
		);	
	}
	while ($data = dbarray($result)) {
		echo "<div class='quasselbox_arch_name'>";
			echo "<table width='100%'>";
				echo "<tr>";
					echo "<td width='40px' valign=''>";
						$lastseen = time() - $data['user_lastvisit'];
						if ($lastseen < 60) {
							echo "<img style='vertical-align:middle; border: 2px solid #00ff00; border-radius: 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px;' height='30' width='30' src='".($data['user_avatar'] ? IMAGES."avatars/".$data['user_avatar'] : IMAGES."avatars/noavatar100.png")."' alt='".$data['user_name']."'/>";
						} elseif ($lastseen <= 300) {
							echo "<img style='vertical-align:middle; border: 2px solid #FED700; border-radius: 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px;' height='30' width='30' src='".($data['user_avatar'] ? IMAGES."avatars/".$data['user_avatar'] : IMAGES."avatars/noavatar100.png")."' alt='".$data['user_name']."'/>";
						} elseif ($lastseen > 300) {
							echo "<img style='vertical-align:middle; border: 2px solid #FF0000; border-radius: 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px;' height='30' width='30' src='".($data['user_avatar'] ? IMAGES."avatars/".$data['user_avatar'] : IMAGES."avatars/noavatar100.png")."' alt='".$data['user_name']."'/>";
						} else {
							echo "<img style='vertical-align:middle; border: 2px solid #FF0000; border-radius: 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px;' height='30' width='30' src='".IMAGES."avatars/noavatar100.png' alt='".$data['quassel_name']."'/>";		
						}
					echo "</td><td valign='top'>";
						if ($data['user_name']) {
							echo "<span class='side'>".profile_link($data['quassel_name'], $data['user_name'], $data['user_status'])."</span>\n";
						} else {
							echo $data['quassel_name']."\n";
						}
						if(iMEMBER && $data['user_id'] != $userdata['user_id']) {
							echo "<a class='side' title='@".($data['user_name']?$data['user_name']:$data['quassel_name'])."' href='javascript:insertText(\"archive_quassel_message\",\"[b]@".($data['user_name']?$data['user_name']:$data['quassel_name']).":[/b] \", \"archive_form\");' ><img src='".MODULS."HP_quasselbox_panel/images/at.png' style='width:13px; vertical-align:middle;'></a>";
						}
						echo " ".$locale['HPQB_057'].showdate("longdate", $data['quassel_datestamp'])."\n";
						echo "<br>";
						
					echo "</td>";
				echo "</tr>";
			echo "</table>";
		echo "</div>\n";
		
		echo "<div class='quasselbox_arch_date'>".showdate("longdate", $data['quassel_datestamp'])."</div>";
		//groug message icon start
		if ($data['quassel_read_access'] == 103) {
			$img_goup = "<img src='".MODULS."HP_quasselbox_panel/images/s_admin.png' title='".$locale['HPQB_100']."' style='vertical-align:top'>";
		} elseif ($data['quassel_read_access'] == 102) {
			$img_goup = "<img src='".MODULS."HP_quasselbox_panel/images/admin.png' title='".$locale['HPQB_101']."' style='vertical-align:top'>";
		} elseif ($data['quassel_read_access'] == 101) {
			$img_goup = "<img src='".MODULS."HP_quasselbox_panel/images/member.png' title='".$locale['HPQB_102']."' style='vertical-align:top'>";
		} elseif ($data['quassel_read_access'] == 0) {
			$img_goup = "<img src='".MODULS."HP_quasselbox_panel/images/guest.png' title='".$locale['HPQB_103']."' style='vertical-align:top'>";
		//ownn usergroups start
		
		
		
		//ownn usergroups start
		} else {
			$img_goup = "<img src='".MODULS."HP_quasselbox_panel/images/unknown.png' title='".$locale['HPQB_104']."' style='vertical-align:top'>";
		}
		//groug message icon stop
		echo "<div class='quasselbox_arch'>".$img_goup." ".parsesmileys(parseubb(nl2br($data['quassel_message'])))."";
			//vote start
			if ($set_vote_quassels == 1) {
				//have i vote start
				if (iMEMBER) {
					$result_ivote = dbarray(dbquery("SELECT rating_value FROM ".DB_HP_QUASSELBOX_LIKES."
					WHERE post_id='".$data['quassel_id']."' AND user_id='".$userdata['user_id']."'"));
					if ($result_ivote['rating_value'] == 1) {
						$ivote_up = "<img src='".MODULS."HP_quasselbox_panel/images/v_up.png' title='".$locale['HPQB_010']."' alt='".$locale['HPQB_010']."' style='vertical-align:middle;'/>";
					} else {
						$ivote_up = "<img src='".MODULS."HP_quasselbox_panel/images/up.png' title='".$locale['HPQB_011']."' alt='".$locale['HPQB_010']."' style='vertical-align:middle;'/>";
					}
		
					if ($result_ivote['rating_value'] == 2) {
						$ivote_down = "<img src='".MODULS."HP_quasselbox_panel/images/v_down.png' title='".$locale['HPQB_012']."' alt='".$locale['HPQB_012']."' style='vertical-align:middle;'/>";
					} else {
						$ivote_down = "<img src='".MODULS."HP_quasselbox_panel/images/down.png' title='".$locale['HPQB_013']."' alt='".$locale['HPQB_013']."' style='vertical-align:middle;'/>";
					}
				}
				//have i vote stop
		
				echo "<table width='100%' cellpadding='0' cellspacing='1'>";
				echo "<hr>";
				if (iMEMBER) {
					echo "<tr><td width='50%' valign='top'>";
					echo "<strong>".$locale['HPQB_041']."</strong><br />";
					echo "<a href='".FUSION_SELF."?action=archive_plus&amp;quassel_id=".$data['quassel_id']."' style='text-decoration: none; font-size: 14px;color: #000000;'>".$ivote_up." ".$locale['HPQB_011']."</a>\n";
					echo "<strong>&nbsp;|&nbsp;</strong>";
					echo "<a href='".FUSION_SELF."?action=archive_minus&amp;quassel_id=".$data['quassel_id']."' style='text-decoration: none; font-size: 14px;color: #000000;'>".$ivote_down." ".$locale['HPQB_013']."</a>\n";
					echo "</td>";
					echo "<td align='right'><img src='".MODULS."HP_quasselbox_panel/images/v_up.png' title='".$locale['HPQB_014']."' alt='".$locale['HPQB_010']."' style='vertical-align:middle;'/> ".number_format(dbcount("(rating_id)", DB_HP_QUASSELBOX_LIKES, "post_id='".$data['quassel_id']."' AND rating_value=1"))."&nbsp;".$locale['HPQB_042']."<br /><img src='".MODULS."HP_quasselbox_panel/images/v_down.png' title='".$locale['HPQB_015']."' alt='".$locale['HPQB_015']."' style='vertical-align:middle;'/> ".number_format(dbcount("(rating_id)", DB_HP_QUASSELBOX_LIKES, "post_id='".$data['quassel_id']."' AND rating_value=2"))."&nbsp;".$locale['HPQB_042']."</td></tr>";
				} else {
					echo "<tr><td width='50%' valign='top'>";
					echo "<strong>".$locale['HPQB_041']."</strong><br />";
					echo "<span style='color:red'>".$locale['HPQB_043']."</span>";
					echo "</td>";
					echo "<td align='right'><img src='".MODULS."HP_quasselbox_panel/images/v_up.png' title='".$locale['HPQB_014']."' alt='".$locale['HPQB_014']."' style='vertical-align:middle;'/> ".number_format(dbcount("(rating_id)", DB_HP_QUASSELBOX_LIKES, "post_id='".$data['quassel_id']."' AND rating_value=1"))."&nbsp;".$locale['HPQB_042']."<br /><img src='".MODULS."HP_quasselbox_panel/images/v_down.png' title='".$locale['HPQB_015']."' alt='".$locale['HPQB_015']."' style='vertical-align:middle;'/> ".number_format(dbcount("(rating_id)",DB_HP_QUASSELBOX_LIKES, "post_id='".$data['quassel_id']."' AND rating_value=2"))."&nbsp;".$locale['HPQB_042']."</td></tr>";
				}
				echo "</table>";
			}
			//vote stop
		echo "</div>\n";
		echo "<div align='right'>";
			if ((iADMIN && checkrights("HPQB")) || (iMEMBER && $data['quassel_name'] == $userdata['user_id'] && isset($data['user_name']))) {
				echo "[<a href='".FUSION_SELF."?action=edit&amp;quassel_id=".$data['quassel_id']."'><img src='".MODULS."HP_quasselbox_panel/images/edit.png' title='".$locale['HPQB_017']."' alt='".$locale['HPQB_017']."' style='vertical-align:middle;'/></a>]\n";
				echo " [<a href='".FUSION_SELF."?action=delete&amp;quassel_id=".$data['quassel_id']."' onclick=\"return confirm('".$locale['HPQB_018']."');\"><img src='".MODULS."HP_quasselbox_panel/images/delete.png' title='".$locale['HPQB_019']."' alt='".$locale['HPQB_019']."' style='vertical-align:middle;'/></a>]\n";
			}
		echo "</div>";
		echo "<br><br>";
	}
} else {
	echo "<div style='text-align:center'><br />\n".$locale['HPQB_021']."<br /><br />\n</div>\n";
}

echo "<div align='center' style='margin-top:5px;'>\n".makepagenav($_GET['rowstart'], $set_visible_arch_quassels, $rows, 3, FUSION_SELF."?")."\n</div>\n";
echo "<div align='right'>\n<a href='".$locale['HPQB_url']."' target='_blank' title='".$locale['HPQB_title']." ".$locale['HPQB_version']." by ".$locale['HPQB_dev']."'>&copy;</a>\n</div>\n";
closetable();

require_once DESIGNS."templates/footer.php";
?>
