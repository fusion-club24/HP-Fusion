<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: HP_quasselbox_panel.php
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
if (!defined("IN_FUSION")) { die("Access Denied"); }

include_once MODULS."HP_quasselbox_panel/infusion_db.php";
include MODULS."HP_quasselbox_panel/inc/set.php";
include MODULS."HP_quasselbox_panel/inc/qb_sp_style.php";
include_once INCLUDES."infusions_include.php";

// Check if locale file is available matching the current site locale setting.
if (file_exists(MODULS."HP_quasselbox_panel/locale/".$settings['locale'].".php")) {
	// Load the locale file matching the current site locale setting.
	include MODULS."HP_quasselbox_panel/locale/".$settings['locale'].".php";
} else {
	// Load the infusion's default locale file.
	include MODULS."HP_quasselbox_panel/locale/German.php";
}

$quassel_access_control = true;

$link = FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : "");
$link = preg_replace("^(&amp;|\?)q_action=(edit|delete)&amp;quassel_id=\d*^", "", $link);
$sep = stristr($link, "?") ? "&amp;" : "?";
$quassel_link = ""; $quassel_message = "";
//delete post start
if (iMEMBER && (isset($_GET['q_action']) && $_GET['q_action'] == "delete") && (isset($_GET['quassel_id']) && isnum($_GET['quassel_id']))) {
	if ((iADMIN && checkrights("HPQB")) || (iMEMBER && dbcount("(quassel_id)", DB_HP_QUASSELBOX, "quassel_id='".$_GET['quassel_id']."' AND quassel_name='".$userdata['user_id']."'"))) {
		$result = dbquery("DELETE FROM ".DB_HP_QUASSELBOX." WHERE quassel_id='".$_GET['quassel_id']."'".(iADMIN ? "" : " AND quassel_name='".$userdata['user_id']."'"));
		$result = dbquery("DELETE FROM ".DB_HP_QUASSELBOX_LIKES." WHERE post_id='".$_GET['quassel_id']."'");
	}
	redirect(FUSION_SELF);
}
//delete post stop

//vote start
if (iMEMBER && (isset($_GET['action']) && $_GET['action'] == "panel_plus") && (isset($_GET['quassel_id']) && isnum($_GET['quassel_id']))) {
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
		redirect(FUSION_SELF);
	}
}

if (iMEMBER && (isset($_GET['action']) && $_GET['action'] == "panel_minus") && (isset($_GET['quassel_id']) && isnum($_GET['quassel_id']))) {
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
		redirect(FUSION_SELF);
	}
}
//vote stop

if (!function_exists("qbwrap")) {
	function qbwrap($text) {
		global $locale;

		$i = 0; $tags = 0; $chars = 0; $res = "";

		$str_len = strlen($text);

		for ($i = 0; $i < $str_len; $i++) {
			$chr = mb_substr($text, $i, 1, $locale['charset']);
			if ($chr == "<") {
				if (mb_substr($text, ($i + 1), 6, $locale['charset']) == "a href" || mb_substr($text, ($i + 1), 3, $locale['charset']) == "img") {
					$chr = " ".$chr;
					$chars = 0;
				}
				$tags++;
			} elseif ($chr == "&") {
				if (mb_substr($text, ($i + 1), 5, $locale['charset']) == "quot;") {
					$chars = $chars - 5;
				} elseif (mb_substr($text, ($i + 1), 4, $locale['charset']) == "amp;" || mb_substr($text, ($i + 1), 4, $locale['charset']) == "#39;" || mb_substr($text, ($i + 1), 4, $locale['charset']) == "#92;") {
					$chars = $chars - 4;
				} elseif (mb_substr($text, ($i + 1), 3, $locale['charset']) == "lt;" || mb_substr($text, ($i + 1), 3, $locale['charset']) == "gt;") {
					$chars = $chars - 3;
				}
			} elseif ($chr == ">") {
				$tags--;
			} elseif ($chr == " ") {
				$chars = 0;
			} elseif (!$tags) {
				$chars++;
			}

			if (!$tags && $chars == 18) {
				$chr .= "<br />";
				$chars = 0;
			}
			$res .= $chr;
		}

		return $res;
	}
}

openside($locale['HPQB_001'],true, "on");
echo '<script type="text/javascript">
	function toggle_hpqsp(id) {
		spanid = "toggle_hpqsp_"+id;
		val = document.getElementById(spanid).style.display;
		if (val == "none") {
			document.getElementById(spanid).style.display = "block";
		} else {
			document.getElementById(spanid).style.display = "none";
		}
	}
</script>';
echo "<center><a class='button' href='javascript:toggle_hpqsp(\"input\")'>".$locale['HPQB_023']."</a></center><br />";
if (iMEMBER && (isset($_GET['q_action']) && $_GET['q_action'] == "edit") && (isset($_GET['quassel_id']) && isnum($_GET['quassel_id']))) {
	echo "<div id='toggle_hpqsp_input'>\n";
} else {
	echo "<div id='toggle_hpqsp_input' style='display:none'>\n";
}

if ((iMEMBER && $set_note_quassels == "1") || (iGUEST && $set_guest_quassels == "1" && $set_note_quassels == "1")) {
	echo"<div class='quasselbox_msg' align='center'><img src='".MODULS."HP_quasselbox_panel/images/stop.png' style='width:16px; vertical-align:middle;'> <strong>$set_note_t1_quassels</strong></div>\n";
}
echo "<!-- ".$locale['HPQB_title']." ".$locale['HPQB_version']." by ".$locale['HPQB_dev']." ".$locale['HPQB_url']."-->";
if (iMEMBER || $set_guest_quassels == "1") {
	include_once INCLUDES."bbcode_include.php";
	if (isset($_POST['post_quassel'])) {
		$flood = false;
		if (iMEMBER) {
			$quassel_name = $userdata['user_id'];
		} elseif ($set_guest_quassels == "1") {
			$quassel_name = trim(stripinput($_POST['quassel_name']));
			$quassel_name = preg_replace("(^[+0-9\s]*)", "", $quassel_name);
			if (isnum($quassel_name)) { $quassel_name = ""; }
			include_once INCLUDES."captchas/securimage/securimage.php";
			$securimage = new Securimage();
			if (!isset($_POST['qb_captcha_code']) || $securimage->check($_POST['qb_captcha_code']) == false) { redirect($link); }
		}
		$quassel_message = str_replace("\n", " ", $_POST['quassel_message']);
		$quassel_message = preg_replace("/^(.{200}).*$/", "$1", $quassel_message);
		$quassel_message = trim(stripinput(censorwords($quassel_message)));
		$quassel_read_access = ( (isset($_POST['quassel_read_access']) && isNum($_POST['quassel_read_access']) ) ? $_POST['quassel_read_access'] : 0 );
		if (iMEMBER && (isset($_GET['q_action']) && $_GET['q_action'] == "edit") && (isset($_GET['quassel_id']) && isnum($_GET['quassel_id']))) {
			$comment_updated = false;
			if ((iADMIN && checkrights("HPQB")) || (iMEMBER && dbcount("(quassel_id)", DB_HP_QUASSELBOX, "quassel_id='".$_GET['quassel_id']."' AND quassel_name='".$userdata['user_id']."'"))) {
				if ($quassel_message) {
					$result = dbquery("UPDATE ".DB_HP_QUASSELBOX." SET quassel_message='$quassel_message', quassel_read_access='$quassel_read_access' WHERE quassel_id='".$_GET['quassel_id']."'".(iADMIN ? "" : " AND quassel_name='".$userdata['user_id']."'"));
				}
			}
			redirect($link);
		} elseif ($quassel_name && $quassel_message) {
			require_once INCLUDES."flood_include.php";
			if (!flood_control("quassel_datestamp", DB_HP_QUASSELBOX, "quassel_ip='".USER_IP."'")) {
				if (iMEMBER) {
				$result = dbquery("INSERT INTO ".DB_HP_QUASSELBOX." (quassel_name, quassel_read_access, quassel_message, quassel_datestamp, quassel_ip, quassel_ip_type, quassel_hidden) VALUES ('$quassel_name', '$quassel_read_access', '$quassel_message', '".time()."', '".USER_IP."', '".USER_IP_TYPE."', '0')");
				} else {
				$result = dbquery("INSERT INTO ".DB_HP_QUASSELBOX." (quassel_name, quassel_message, quassel_datestamp, quassel_ip, quassel_ip_type, quassel_hidden) VALUES ('Gast_$quassel_name', '$quassel_message', '".time()."', '".USER_IP."', '".USER_IP_TYPE."', '0')");
				}
				if (iMEMBER) {
					if (defined("SCORESYSTEM")) {
						score_positive("SHBOX");
					}
				}
			}
		}
		redirect($link);
	}
	if (iMEMBER && (isset($_GET['q_action']) && $_GET['q_action'] == "edit") && (isset($_GET['quassel_id']) && isnum($_GET['quassel_id']))) {
		$esresult = dbquery(
			"SELECT tq.quassel_id, tq.quassel_name, tq.quassel_read_access, tq.quassel_message, tu.user_id, tu.user_name
			FROM ".DB_HP_QUASSELBOX." tq
			LEFT JOIN ".DB_USERS." tu ON tq.quassel_name=tu.user_id
			WHERE tq.quassel_id='".$_GET['quassel_id']."'"
		);
		if (dbrows($esresult)) {
			$esdata = dbarray($esresult);
			if ((iADMIN && checkrights("HPQB")) || (iMEMBER && $esdata['quassel_name'] == $userdata['user_id'] && isset($esdata['user_name']))) {
				if ((isset($_GET['q_action']) && $_GET['q_action'] == "edit") && (isset($_GET['quassel_id']) && isnum($_GET['quassel_id']))) {
					$edit_url = $sep."q_action=edit&amp;quassel_id=".$esdata['quassel_id'];
				} else {
					$edit_url = "";
				}
				$quassel_link = $link.$edit_url;
				$quassel_message = $esdata['quassel_message'];
				$quassel_read_access = $esdata['quassel_read_access'];
			}
		} else {
			$quassel_link = $link;
			$quassel_message = "";
			$quassel_read_access = 0;
		}
	} else {
		$quassel_link = $link;
		$quassel_message = "";
		$quassel_read_access = 0;
	}
	

	echo "<script type='text/javascript'>
	function textHPQBCounter(textarea, counterID, maxLen) {
	cnt = document.getElementById(counterID);
	if (textarea.value.length > maxLen)
	{
	textarea.value = textarea.value.substring(0,maxLen);
	}
	cnt.innerHTML = maxLen - textarea.value.length;
	}
	</script>";


	echo "<a id='edit_quassel' name='edit_quassel'></a>\n";
	echo "<form name='quassel_form' method='post' action='".$quassel_link."'>\n";
		if (iGUEST) {
			echo $locale['HPQB_002']."<br />\n";
			echo "<input type='text' name='quassel_name' value='' class='textbox' maxlength='30' style='width:150px' /><br />\n";
			echo "<br />\n";
		}
		if (iMEMBER) {
			echo "<div align='center' valign='middle'><small>".$locale['HPQB_003']."<span id='count_display' style='padding:1px 3px 1px 3px; border:1px solid;'>200</span></small><br /><textarea class='textbox' name='quassel_message' rows='4' cols='20' style=\"width:175px; background: #$set_textarea_color url(".MODULS."HP_quasselbox_panel/images/qb_panel.png) no-repeat scroll 0% 0%; width: 150px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;\" onfocus=\"if(this.value=='')this.value='';\" onblur=\"if(this.value=='')this.value=='';\" onKeyDown=\"textHPQBCounter(this,'count_display',200);\" onKeyUp=\"textHPQBCounter(this,'count_display',200);\" placeholder='".$locale['HPQB_022']."".$userdata['user_name']."'>".$quassel_message.(empty($quassel_message)?"":"")."</textarea>\n</div>\n";
		} else {
			echo "<div align='center' valign='middle'><small>".$locale['HPQB_003']."<span id='count_display' style='padding:1px 3px 1px 3px; border:1px solid;'>200</span></small><br /><textarea class='textbox' name='quassel_message' rows='4' cols='20' style=\"width:175px; background: #$set_textarea_color url(".MODULS."HP_quasselbox_panel/images/qb_panel.png) no-repeat scroll 0% 0%; width: 150px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;\" onfocus=\"if(this.value=='')this.value='';\" onblur=\"if(this.value=='')this.value=='';\" onKeyDown=\"textHPQBCounter(this,'count_display',200);\" onKeyUp=\"textHPQBCounter(this,'count_display',200);\" placeholder='".$locale['HPQB_022']."Gast'>".$quassel_message.(empty($quassel_message)?"":"")."</textarea>\n</div>\n";
		}
		echo "<center>";
		echo display_bbcodes("160px;", "quassel_message", "quassel_form", "smiley|b|u|i|color")."\n";
		echo "</center>";
		if (iGUEST) {
			echo $locale['HPQB_004']."<br />\n";
			echo "<img id='qb_captcha' src='".INCLUDES."captchas/securimage/securimage_show.php' alt='' /><br />\n";
			echo "<a href='".INCLUDES."captchas/securimage/securimage_play.php'><img src='".INCLUDES."captchas/securimage/images/audio_icon.gif' alt='' class='tbl-border' style='margin-bottom:1px' /></a>\n";
			echo "<a href='#' onclick=\"document.getElementById('qb_captcha').src = '".INCLUDES."captchas/securimage/securimage_show.php?sid=' + Math.random(); return false\"><img src='".INCLUDES."captchas/securimage/images/refresh.gif' alt='' class='tbl-border' /></a><br />\n";
			echo $locale['HPQB_005']."<br />\n<input type='text' name='qb_captcha_code' class='textbox' style='width:100px' />";
		}
		//thanks to Rolly8-HL
		if(iMEMBER && $quassel_access_control) {                                           
			echo "<center><small>".$locale['HPQB_006']."</small>";
			echo "<br /><select class='textbox' name='quassel_read_access'>";
			$option_list = "";                                                                          
			$options = getusergroups(); 
			if(!iADMIN){
				$option_list .= "<option value='0' ".($quassel_read_access  == "0" ? " selected='selected'" : "").">".$locale['user0']."</option>\n";
				$option_list .= "<option value='101' ".($quassel_read_access  == "101" ? " selected='selected'" : "").">".$locale['user1']."</option>\n";
				foreach($options as $key => $option) {
					if (in_array($option['0'], explode(".", iUSER_GROUPS))) {
						$sel = ($quassel_read_access == $option['0'] ? " selected='selected'" : "");
						$option_list .= "<option value='".$option['0']."' ".$sel.">".$option['1']."</option>\n";
					}
				}
			} else {
				$option_list = "";
				$options = getusergroups();
				foreach($options as $key => $option) {
					$sel = ($quassel_read_access == $option['0'] ? " selected='selected'" : "");
					$option_list .= "<option value='".$option['0']."' ".$sel.">".$option['1']."</option>\n";
				}
			}
			echo $option_list."</select></center>";
		}
		echo "<center><input type='submit' name='post_quassel' value='".$locale['HPQB_007']."' class='button' />\n";
		echo "<input type='reset' name='reset' value='".$locale['HPQB_008']."' class='button' /></center>\n";
	echo "</form>\n<br />\n";
} else {
	echo "<div class='quasselbox_msg_info' align='center'><img src='".MODULS."HP_quasselbox_panel/images/info.png' style='width:16px; vertical-align:middle;'> ".$locale['HPQB_009']."</div>\n";
}
echo "</div>";

$numrows = dbcount("(quassel_id)", DB_HP_QUASSELBOX, "quassel_hidden='0'");
if (iMEMBER) {
	$result = dbquery(
		"SELECT tq.quassel_id, tq.quassel_name, tq.quassel_read_access, tq.quassel_message, tq.quassel_datestamp, tu.user_id, tu.user_name, tu.user_avatar, tu.user_status, tu.user_lastvisit
		FROM ".DB_HP_QUASSELBOX." tq
		LEFT JOIN ".DB_USERS." tu ON tq.quassel_name=tu.user_id
		WHERE quassel_hidden='0' AND ".groupaccess('quassel_read_access')."  || tq.quassel_name='".$userdata['user_id']."'
		ORDER BY tq.quassel_datestamp DESC LIMIT 0,".$set_visible_quassels
	);
} else {
	$result = dbquery(
		"SELECT tq.quassel_id, tq.quassel_name, tq.quassel_read_access, tq.quassel_message, tq.quassel_datestamp, tu.user_id, tu.user_name, tu.user_avatar, tu.user_status, tu.user_lastvisit
		FROM ".DB_HP_QUASSELBOX." tq
		LEFT JOIN ".DB_USERS." tu ON tq.quassel_name=tu.user_id
		WHERE quassel_hidden='0' AND ".groupaccess('quassel_read_access')."
		ORDER BY tq.quassel_datestamp DESC LIMIT 0,".$set_visible_quassels
	);
}
if (dbrows($result)) {
	$i = 0;
	while ($data = dbarray($result)) {
		echo "<div class='quasselboxname'>";
			echo "<table width='100%'>";
				echo "<tr>";
					echo "<td width='35px' valign=''>";
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
							echo '<a class="side" title="@'.($data['user_name']?$data['user_name']:$data['quassel_name']).'" href="javascript:insertText(\'quassel_message\',\'[b]@'.($data['user_name']?$data['user_name']:$data['quassel_name']).':[/b] \', \'quassel_form\');" ><img src="'.MODULS.'HP_quasselbox_panel/images/at.png" style="width:13px; vertical-align:middle;"></a>';
						}
						echo "<br>";
						
					echo "</td>";
				echo "</tr>";
			echo "</table>";
		echo "</div>\n";
		
		echo "<div class='quasselboxdate'>".showdate("shortdate", $data['quassel_datestamp'])."</div>";
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
		
		
		
		//ownn usergroups stop
		} else {
			$img_goup = "<img src='".MODULS."HP_quasselbox_panel/images/unknown.png' title='".$locale['HPQB_104']."' style='vertical-align:top'>";
		}
		//groug message icon stop
		echo "<div class='quasselbox'>".$img_goup." ".qbwrap(parsesmileys(parseubb(nl2br($data['quassel_message']), "b|i|u|img|color|url|youtube")))."";
		//vote start
		if ($set_vote_quassels == 1) {
			//have i vote start
			if (iMEMBER) {
				$result_ivote = dbarray(dbquery("SELECT rating_value FROM ".DB_HP_QUASSELBOX_LIKES."
				WHERE post_id='".$data['quassel_id']."' AND user_id='".$userdata['user_id']."'"));
				if ($result_ivote['rating_value'] == 1) {
					$ivote_up = "<img src='".MODULS."HP_quasselbox_panel/images/v_up.png' width='13px' title='".$locale['HPQB_010']."' alt='".$locale['HPQB_010']."' style='vertical-align:middle;'/>";
				} else {
					$ivote_up = "<img src='".MODULS."HP_quasselbox_panel/images/up.png' width='13px' title='".$locale['HPQB_011']."' alt='".$locale['HPQB_011']."' style='vertical-align:middle;'/>";
				}
		
				if ($result_ivote['rating_value'] == 2) {
					$ivote_down = "<img src='".MODULS."HP_quasselbox_panel/images/v_down.png' width='13px' title='".$locale['HPQB_012']."' alt='".$locale['HPQB_012']."' style='vertical-align:middle;'/>";
				} else {
					$ivote_down = "<img src='".MODULS."HP_quasselbox_panel/images/down.png' title='".$locale['HPQB_013']."' width='13px' alt='".$locale['HPQB_013']."' style='vertical-align:middle;'/>";
				}
			}
			//have i vote stop
			
			echo "<hr>";
			echo "<table width='100%' cellpadding='0' cellspacing='1'>";
			if (iMEMBER) {
				echo "<tr><td>";
				echo "<a href='".FUSION_SELF."?action=panel_plus&amp;quassel_id=".$data['quassel_id']."' style='text-decoration: none; font-size: 13px;color: #000000;'>".$ivote_up."</a>
				<strong>|</strong>
				<a href='".FUSION_SELF."?action=panel_minus&amp;quassel_id=".$data['quassel_id']."' style='text-decoration: none; font-size: 13px;color: #000000;'>".$ivote_down."</a>\n";
				echo "</td>";
				echo "<td align='right'><img src='".MODULS."HP_quasselbox_panel/images/v_up.png' width='13px' title='".$locale['HPQB_014']."' alt='".$locale['HPQB_014']."' style='vertical-align:middle;'/>".number_format(dbcount("(rating_id)", DB_HP_QUASSELBOX_LIKES, "post_id='".$data['quassel_id']."' AND rating_value=1"))."
				<strong>|</strong>
				<img src='".MODULS."HP_quasselbox_panel/images/v_down.png' width='13px' title='".$locale['HPQB_015']."' alt='".$locale['HPQB_015']."' style='vertical-align:middle;'/>".number_format(dbcount("(rating_id)", DB_HP_QUASSELBOX_LIKES, "post_id='".$data['quassel_id']."' AND rating_value=2"))."</td></tr>";
			} else {
				echo "<tr><td>";
				echo "<span style='color:red'><strong>".$locale['HPQB_016']."</strong></span>";
				echo "</td>";
				echo "<td align='right'><img src='".MODULS."HP_quasselbox_panel/images/v_up.png' width='13px' title='".$locale['HPQB_014']."' alt='".$locale['HPQB_014']."' style='vertical-align:middle;'/>".number_format(dbcount("(rating_id)", DB_HP_QUASSELBOX_LIKES, "post_id='".$data['quassel_id']."' AND rating_value=1"))."
				<strong>|</strong>
				<img src='".MODULS."HP_quasselbox_panel/images/v_down.png' width='13px' title='".$locale['HPQB_015']."' alt='".$locale['HPQB_015']."' style='vertical-align:middle;'/>".number_format(dbcount("(rating_id)",DB_HP_QUASSELBOX_LIKES, "post_id='".$data['quassel_id']."' AND rating_value=2"))."</td></tr>";
			}
			echo "</table>";
		}
		//vote stop
		echo "</div>\n";
		echo "<div align='right'>";
		if ((iADMIN && checkrights("HPQB")) || (iMEMBER && $data['quassel_name'] == $userdata['user_id'] && isset($data['user_name']))) {
        echo "[<a href='".$link.$sep."q_action=edit&amp;quassel_id=".$data['quassel_id']."#edit_quassel"."' class='side'><img src='".MODULS."HP_quasselbox_panel/images/edit.png' title='".$locale['HPQB_017']."' alt='".$locale['HPQB_017']."' style='vertical-align:middle;'/></a>]\n";
		echo " [<a href='".$link.$sep."q_action=delete&amp;quassel_id=".$data['quassel_id']."' onclick=\"return confirm('".$locale['HPQB_018']."');\" class='side'><img src='".MODULS."HP_quasselbox_panel/images/delete.png' title='".$locale['HPQB_019']."' alt='".$locale['HPQB_019']."' style='vertical-align:middle;'/></a>]\n";
			}
		echo "</div>";
		$i++;
		if ($i != $numrows) { echo "<br />\n"; }
	}
	if ($numrows > $set_visible_quassels) {
		echo "<div align='center'>\n<a href='".MODULS."HP_quasselbox_panel/HP_quasselboxx_archive.php' class='button'>".$locale['HPQB_020']."</a>\n</div>\n";
	}
} else {
	echo "<div>".$locale['HPQB_021']."</div>\n";
}
echo "<div align='right'>\n<a href='".$locale['HPQB_url']."' target='_blank' title='".$locale['HPQB_title']." ".$locale['HPQB_version']." by ".$locale['HPQB_dev']."'>&copy;</a>\n</div>\n";
closeside();
?>