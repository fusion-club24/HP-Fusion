<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: HP_quasselbox_admin.php
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
require_once DESIGNS."templates/admin_header.php";
include MODULS."HP_quasselbox_panel/inc/set.php";
include MODULS."HP_quasselbox_panel/inc/qb_style.php";
include MODULS."HP_quasselbox_panel/infusion_db.php";

// Check if locale file is available matching the current site locale setting.
if (file_exists(MODULS."HP_quasselbox_panel/locale/".$settings['locale'].".php")) {
	// Load the locale file matching the current site locale setting.
	include MODULS."HP_quasselbox_panel/locale/".$settings['locale'].".php";
} else {
	// Load the infusion's default locale file.
	include MODULS."HP_quasselbox_panel/locale/German.php";
}

if (!checkrights("HPQB") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../../index.php"); }

$nav = "<table cellpadding='0' cellspacing='0' class='tbl-border' align='center' style='width:460px; margin-bottom:20px; text-align:center;'>\n<tr>\n";
$nav .= "<td class='".(!isset($_GET['page']) || $_GET['page'] != "settings" ? "tbl2" : "tbl1")."'><a href='".FUSION_SELF.$aidlink."'>".$locale['HPQB_050']."</a></td>\n";
$nav .= "<td class='".(isset($_GET['page']) && $_GET['page'] == "settings" ? "tbl2" : "tbl1")."'><a href='".FUSION_SELF.$aidlink."&amp;page=settings'>".$locale['HPQB_051']."</a></td>\n";
$nav .= "</tr>\n</table>\n";

include_once INCLUDES."bbcode_include.php";

if (!isset($_GET['page']) || $_GET['page'] != "settings") {
	if (isset($_GET['status']) && !isset($message)) {
		if ($_GET['status'] == "su") {
			$message = $locale['HPQB_052'];
		} elseif ($_GET['status'] == "del") {
			$message = $locale['HPQB_053'];
		}
		if ($message) {	echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; }
	} elseif ((isset($_GET['action']) && $_GET['action'] == "delete") && (isset($_GET['quassel_id']) && isnum($_GET['quassel_id']))) {
		$result = dbquery("DELETE FROM ".DB_HP_QUASSELBOX." WHERE quassel_id='".$_GET['quassel_id']."'");
		$result = dbquery("DELETE FROM ".DB_HP_QUASSELBOX_LIKES." WHERE post_id='".$_GET['quassel_id']."'");
		redirect(FUSION_SELF.$aidlink."&status=del");
	} else {
		if (isset($_POST['savequassel']) && (isset($_GET['quassel_id']) && isnum($_GET['quassel_id']))) {
			$quassel_message = str_replace("\n", " ", $_POST['quassel_message']);
			$quassel_message = preg_replace("/^(.{255}).*$/", "$1", $quassel_message);
			$quassel_message = preg_replace("/([^\s]{25})/", "$1\n", $quassel_message);
			$quassel_message = stripinput($quassel_message);
			$quassel_message = str_replace("\n", "<br />", $quassel_message);
			if ($quassel_message) {
				$result = dbquery("UPDATE ".DB_HP_QUASSELBOX." SET quassel_message='$quassel_message' WHERE quassel_id='".$_GET['quassel_id']."'");
				redirect(FUSION_SELF.$aidlink."&status=su");
			} else {
				redirect(FUSION_SELF.$aidlink);
			}
		}
		if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['quassel_id']) && isnum($_GET['quassel_id']))) {
			$result = dbquery("SELECT quassel_id, quassel_message FROM ".DB_HP_QUASSELBOX." WHERE quassel_id='".$_GET['quassel_id']."'");
			if (dbrows($result)) {
				$data = dbarray($result);
				opentable($locale['HPQB_054']);
				echo "<form name='editform' method='post' action='".FUSION_SELF.$aidlink."&amp;quassel_id=".$data['quassel_id']."'>\n";
					echo "<table cellpadding='0' cellspacing='0' class='center'>\n";
						echo "<tr>\n";
							echo "<td class='tbl'>".$locale['HPQB_055']."</td>\n";
						echo "</tr>\n<tr>\n";
							echo "<td class='tbl'><textarea name='quassel_message' cols='60' rows='3' class='textbox' style='width:250px;'>".str_replace("<br />", "", $data['quassel_message'])."</textarea></td>\n";
						echo "</tr>\n<tr>\n";
							echo "<td class='tbl' align='center'>".display_bbcodes("150px;", "quassel_message", "editform", "smiley|b|u|url|color")."</td>\n";
						echo "</tr>\n<tr>\n";
							echo "<td align='center' class='tbl'><input type='submit' name='savequassel' value='".$locale['HPQB_056']."' class='button' /></td>\n";
						echo "</tr>\n";
					echo "</table>\n";
				echo "</form>";
				closetable();
			} else {
				redirect(FUSION_SELF.$aidlink);
			}
		}
		opentable($locale['HPQB_054']);
		echo $nav;
		$result = dbquery("SELECT * FROM ".DB_HP_QUASSELBOX);
		$rows = dbrows($result);
		if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
		if ($rows != 0) {
			$i = 0;
			$result = dbquery(
				"SELECT q.quassel_id, q.quassel_name, q.quassel_read_access, q.quassel_message, q.quassel_datestamp, q.quassel_ip, u.user_id, u.user_name, u.user_status
				FROM ".DB_HP_QUASSELBOX." q
				LEFT JOIN ".DB_USERS." u ON q.quassel_name=u.user_id
				ORDER BY quassel_datestamp DESC LIMIT ".$_GET['rowstart'].",20"
			);
			echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border center'>\n";
				while ($data = dbarray($result)) {
					echo "<tr>\n";
						echo "<td class='".($i % 2 == 0 ? "tbl1" : "tbl2")."'>";
							echo "<span class='comment-name'>";
								if ($data['user_name']) {
									echo "<span class='slink'>".profile_link($data['user_id'], $data['user_name'], $data['user_status'])."</span>";
								} else {
									echo $data['quassel_name'];
								}
							echo "</span>\n<";
							echo "span class='small'>".$locale['HPQB_057'].showdate("longdate", $data['quassel_datestamp'])."</span><br />\n";
				
							//Usesergroup Message Icon start
							if ($data['quassel_read_access'] == 103) {
								$img_goup = "<img src='".MODULS."HP_quasselbox_panel/images/s_admin.png' title='".$locale['HPQB_100']."' style='vertical-align:top'>";
							} elseif ($data['quassel_read_access'] == 102) {
								$img_goup = "<img src='".MODULS."HP_quasselbox_panel/images/admin.png' title='".$locale['HPQB_101']."' style='vertical-align:top'>";
							} elseif ($data['quassel_read_access'] == 101) {
								$img_goup = "<img src='".MODULS."HP_quasselbox_panel/images/member.png' title='I".$locale['HPQB_102']."' style='vertical-align:top'>";
							} elseif ($data['quassel_read_access'] == 0) {
								$img_goup = "<img src='".MODULS."HP_quasselbox_panel/images/guest.png' title='".$locale['HPQB_103']."' style='vertical-align:top'>";
							//Ownn Usergroups start
							
							
							
							//Ownn Usergroups stop
							} else {
								$img_goup = "<img src='".MODULS."HP_quasselbox_panel/images/unknown.png' title='".$locale['HPQB_104']."' style='vertical-align:top'>";
							}
							//Usesergroup Message Icon stop
				
							echo "<div class='quasselbox'>".$img_goup." ".parsesmileys(parseubb(nl2br($data['quassel_message'])))."";
								echo "<table width='100%' cellpadding='0' cellspacing='1' class='tbl-border'>";
									echo "<hr>";
									echo "<tr>";
										echo "<td width='40px'>";
											echo "<img src='".MODULS."HP_quasselbox_panel/images/v_up.png' title='".$locale['HPQB_014']."' alt='".$locale['HPQB_014']."' style='vertical-align:middle;'/> ".number_format(dbcount("(rating_id)", DB_HP_QUASSELBOX_LIKES, "post_id='".$data['quassel_id']."' AND rating_value=1"))."";
										echo "</td>";
										echo "<td>";
											$result_vote = dbquery(
												"SELECT user_id, post_id FROM ".DB_HP_QUASSELBOX_LIKES."
												WHERE post_id='".$data['quassel_id']."' AND rating_value='1'"
											);
											if (dbrows($result_vote)) {
												$aa = 1;
												while ($data_vote = dbarray($result_vote)) {
													$voteusername = dbarray(dbquery("SELECT user_id, user_name FROM ".DB_USERS." WHERE user_id='".$data_vote['user_id']."'"));
													$sep = ($aa == 1 ? "" : ", ");
													$urluservote = "<a href='".BASEDIR."profile.php?lookup=".$data_vote['user_id']."' target='_blank'>".$voteusername['user_name']."</a>";
													echo $sep.($urluservote);
													$aa++;
												}
											}
										echo "</td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td width='40px'><img src='".MODULS."HP_quasselbox_panel/images/v_down.png' title='".$locale['HPQB_015']."' alt='".$locale['HPQB_015']."' style='vertical-align:middle;'/> ".number_format(dbcount("(rating_id)", DB_HP_QUASSELBOX_LIKES, "post_id='".$data['quassel_id']."' AND rating_value=2"))."</td>";
										echo "<td>";
											$result_vote = dbquery(
												"SELECT user_id, post_id FROM ".DB_HP_QUASSELBOX_LIKES."
												WHERE post_id='".$data['quassel_id']."' AND rating_value='2'"
											);
											if (dbrows($result_vote)) {
												$aa = 1;
												while ($data_vote = dbarray($result_vote)) {
													$voteusername = dbarray(dbquery("SELECT user_id, user_name FROM ".DB_USERS." WHERE user_id='".$data_vote['user_id']."'"));
													$sep = ($aa == 1 ? "" : ", ");
													$urluservote = "<a href='".BASEDIR."profile.php?lookup=".$data_vote['user_id']."' target='_blank'>".$voteusername['user_name']."</a>";
													echo $sep.($urluservote);
													$aa++;
												}
											}
										echo "</td>";
									echo "</tr>";
								echo "</table>";
							echo "</div>\n";
		
							echo "<strong>".$locale['HPQB_082'].$data['quassel_ip']."</strong>\n";
							echo " [<a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;quassel_id=".$data['quassel_id']."'><img src='".MODULS."HP_quasselbox_panel/images/edit.png' title='".$locale['HPQB_017']."' alt='".$locale['HPQB_017']."' style='vertical-align:middle;'/></a>]\n";
							echo " [<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;quassel_id=".$data['quassel_id']."' onclick=\"return confirm('".$locale['HPQB_018']."');\"><img src='".MODULS."HP_quasselbox_panel/images/delete.png' title='".$locale['HPQB_019']."' alt='".$locale['HPQB_019']."' style='vertical-align:middle;'/></a>]<br /><br />";
						echo "</td>\n";
					echo "</tr>\n";
					$i++;
				}
			echo "</table>\n";
		} else {
			echo "<div style='text-align:center'><br />\n".$locale['HPQB_021']."<br /><br />\n</div>\n";
		}
		echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($_GET['rowstart'],20,$rows,3,FUSION_SELF.$aidlink."&amp;")."\n</div>\n";
		closetable();
	}
} else {
	include INCLUDES."infusions_include.php";
	if (isset($_POST['qb_theme'])) {
		if (isset($_POST['color_textarea'])) {
			$result = dbquery("UPDATE ".DB_HP_QUASSELBOX_SETTINGS." SET settings_value='".$_POST['color_textarea']."' WHERE settings_name='color_textarea'");
		}
		if (isset($_POST['color_qbname'])) {
			$result = dbquery("UPDATE ".DB_HP_QUASSELBOX_SETTINGS." SET settings_value='".$_POST['color_qbname']."' WHERE settings_name='color_qbname'");
		}
		if (isset($_POST['color_qbdate'])) {
			$result = dbquery("UPDATE ".DB_HP_QUASSELBOX_SETTINGS." SET settings_value='".$_POST['color_qbdate']."' WHERE settings_name='color_qbdate'");
		}
	}
	
	if (isset($_POST['qb_settings'])) {
		if (isset($_POST['visible_quassels']) && isnum($_POST['visible_quassels'])) {
			$result = dbquery("UPDATE ".DB_HP_QUASSELBOX_SETTINGS." SET settings_value='".(isnum($_POST['visible_quassels']) && $_POST['visible_quassels'] > 0 ? $_POST['visible_quassels'] : "5")."' WHERE settings_name='visible_quassels'");
		}
		if (isset($_POST['visible_arch_quassels']) && isnum($_POST['visible_arch_quassels'])) {
			$result = dbquery("UPDATE ".DB_HP_QUASSELBOX_SETTINGS." SET settings_value='".(isnum($_POST['visible_arch_quassels']) && $_POST['visible_arch_quassels'] > 0 ? $_POST['visible_arch_quassels'] : "10")."' WHERE settings_name='visible_arch_quassels'");
		}
		if (isset($_POST['guest_quassels']) && ($_POST['guest_quassels'] == 1 || $_POST['guest_quassels'] == 0)) {
			$result = dbquery("UPDATE ".DB_HP_QUASSELBOX_SETTINGS." SET settings_value='".(isnum($_POST['guest_quassels']) && $_POST['guest_quassels'] > 0 ? $_POST['guest_quassels'] : "0")."' WHERE settings_name='guest_quassels'");
		}
		if (isset($_POST['vote_quassels']) && ($_POST['vote_quassels'] == 1 || $_POST['vote_quassels'] == 0)) {
			$result = dbquery("UPDATE ".DB_HP_QUASSELBOX_SETTINGS." SET settings_value='".(isnum($_POST['vote_quassels']) && $_POST['vote_quassels'] > 0 ? $_POST['vote_quassels'] : "0")."' WHERE settings_name='vote_quassels'");
		}
		if (isset($_POST['note_quassels']) && isnum($_POST['note_quassels'])) {
			$result = dbquery("UPDATE ".DB_HP_QUASSELBOX_SETTINGS." SET settings_value='".(isnum($_POST['note_quassels']) && $_POST['note_quassels'] > 0 ? $_POST['note_quassels'] : "0")."' WHERE settings_name='note_quassels'");
		}
		if (isset($_POST['note_text1'])) {
			$result = dbquery("UPDATE ".DB_HP_QUASSELBOX_SETTINGS." SET settings_value='".$_POST['note_text1']."' WHERE settings_name='note_text1'");
		}
		if (isset($_POST['note_text2'])) {
			$result = dbquery("UPDATE ".DB_HP_QUASSELBOX_SETTINGS." SET settings_value='".$_POST['note_text2']."' WHERE settings_name='note_text2'");
		}
		
		redirect(FUSION_SELF.$aidlink."&amp;page=settings&amp;status=update_ok");
	}

	if (isset($_POST['qb_delete_old']) && isset($_POST['num_days']) && isnum($_POST['num_days'])) {
		$deletetime = time() - ($_POST['num_days'] * 86400);
		$numrows = dbcount("(quassel_id)", DB_HP_QUASSELBOX, "quassel_datestamp < '".$deletetime."'");
		$result = dbquery("DELETE FROM ".DB_HP_QUASSELBOX." WHERE quassel_datestamp < '".$deletetime."'");
		redirect(FUSION_SELF.$aidlink."&amp;page=settings&amp;status=delall&numr=$numrows");
	}


	if (isset($_GET['status'])) {
		if ($_GET['status'] == "delall" && isset($_GET['numr']) && isnum($_GET['numr'])) {
			$message = number_format(intval($_GET['numr']))." ".$locale['HPQB_058'];
		} elseif ($_GET['status'] == "update_ok") {
			$message = $locale['HPQB_059'];
		}
	}
	if (isset($message) && $message != "") {
		echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n";
	}
	
	$arrowimage = makefileopts(makefilelist(MODULS."HP_quasselbox_panel/images/arrow/", ".|..|index.php|Thumbs.db"), $set_qbdate_color);
	
	opentable($locale['HPQB_051']);
	echo $nav;
	echo "<form method='post' action='".FUSION_SELF.$aidlink."&amp;page=settings'>\n";
		echo "<div style='width:460px; text-align:left; margin:0 auto; padding:4px;' class='tbl-border tbl1'>\n";
			echo "<strong>".$locale['HPQB_060']."</strong>\n";
		echo "</div>\n";
		echo "<div style='width:460px; text-align:center; margin:0 auto; padding:4px;' class='tbl-border tbl1'>\n";
			echo $locale['HPQB_061']." <select name='num_days' class='textbox' style='width:50px'>\n";
			echo "<option value=''>---</option>\n";
			echo "<option value='90'>90</option>\n";
			echo "<option value='60'>60</option>\n";
			echo "<option value='30'>30</option>\n";
			echo "<option value='20'>20</option>\n";
			echo "<option value='10'>10</option>\n";
			echo "</select>".$locale['HPQB_062']." <br />";
			echo "<span style='margin:4px; display:block;'><input type='submit' name='qb_delete_old' value='".$locale['HPQB_064']."' onclick=\"return confirm('".$locale['HPQB_063']."');\" class='button' /></span>";
		echo "</div>\n";
	echo "</form>\n";
	
	echo "<form method='post' action='".FUSION_SELF.$aidlink."&amp;page=settings'>\n";
		echo "<table cellpadding='0' cellspacing='0' align='center' class='tbl-border' style='width:460px; margin-top:20px;'>\n";
			echo "<tr>";
				echo "<td class='tbl1' colspan='2' style='text-align:left;'><strong>".$locale['HPQB_065']."</strong></td>\n";
			echo "</tr>\n<tr>\n";
				echo "<td class='tbl1' width='150px'>".$locale['HPQB_066']."<br /><small>".$locale['HPQB_067']."</small></td>\n";
				echo "<td class='tbl1'><input type='text' name='visible_quassels' class='textbox' value='".$set_visible_quassels."' style='width:50px;' /></td>\n";
			echo "</tr>\n<tr>\n";
				echo "<td class='tbl1' width='150px'>".$locale['HPQB_066']."<br /><small>".$locale['HPQB_067a']."</small></td>\n";
				echo "<td class='tbl1'><input type='text' name='visible_arch_quassels' class='textbox' value='".$set_visible_arch_quassels."' style='width:50px;' /></td>\n";
			echo "</tr>\n<tr>\n";
				echo "<td class='tbl1' width='150px'>".$locale['HPQB_068']."</td>\n";
				echo "<td class='tbl1'><select name='guest_quassels' size='1' class='textbox'>";
				echo "<option value='1' ".($set_guest_quassels == 1 ? "selected='selected'" : "").">".$locale['HPQB_069']."</option>\n";
				echo "<option value='0'".($set_guest_quassels == 0 ? "selected='selected'" : "").">".$locale['HPQB_070']."</option>\n";
				echo "</select></td>\n";
			echo "</tr>\n<tr>\n";
				echo "<td class='tbl1' width='150px'>".$locale['HPQB_071']."</td>\n";
				echo "<td class='tbl1'><select name='vote_quassels' size='1' class='textbox'>";
				echo "<option value='1' ".($set_vote_quassels == 1 ? "selected='selected'" : "").">".$locale['HPQB_069']."</option>\n";
				echo "<option value='0'".($set_vote_quassels == 0 ? "selected='selected'" : "").">".$locale['HPQB_070']."</option>\n";
				echo "</select></td>\n";
			echo "</tr>\n<tr>\n";
				echo "<td class='tbl1' width='150px'>".$locale['HPQB_072']."</td>\n";
				echo "<td class='tbl1'><select name='note_quassels' size='1' class='textbox'>";
				echo "<option value='1' ".($set_note_quassels == 1 ? "selected='selected'" : "").">".$locale['HPQB_069']."</option>\n";
				echo "<option value='0'".($set_note_quassels == 0 ? "selected='selected'" : "").">".$locale['HPQB_070']."</option>\n";
				echo "</select></td>\n";
			echo "</tr>\n<tr>\n";
				echo "<td class='tbl1 width='150px''>".$locale['HPQB_073']."</td>\n";
				echo "<td class='tbl1'><input type='text' name='note_text1' class='textbox' value='".$set_note_t1_quassels."' style='width:280px;' /></td>\n";
			echo "</tr>\n<tr>\n";
				echo "<td class='tbl1' width='150px'>".$locale['HPQB_074']."</td>\n";
				echo "<td class='tbl1'><input type='text' name='note_text2' class='textbox' value='".$set_note_t2_quassels."' style='width:280px;' /></td>\n";
			echo "</tr>\n<tr>\n";
				echo "<td class='tbl1' colspan='2' style='text-align:center;'><input type='submit' name='qb_settings' value='".$locale['HPQB_064']."' class='button' /></td>\n";
			echo "</tr>\n";
		echo "</table>\n";
	echo "</form>\n";
	
	echo "<form method='post' action='".FUSION_SELF.$aidlink."&amp;page=settings'>\n";
		echo "<table cellpadding='0' cellspacing='0' align='center' class='tbl-border' style='width:460px; margin-top:20px;'>\n";
			echo "<tr>";
				echo "<td class='tbl1' colspan='2' style='text-align:left;'><strong>".$locale['HPQB_075']."</strong> <small>".$locale['HPQB_076']."</small></td>\n";
			echo "</tr><tr>\n";
				echo "<td class='tbl1' width='150px'>".$locale['HPQB_077']."<span style='background-color:#$set_textarea_color;'>&nbsp;&nbsp;&nbsp;&nbsp;</span></td>\n";
				echo "<td class='tbl1'><input type='text' name='color_textarea' class='textbox' value='".$set_textarea_color."' style='width:50px;' /></td>\n";
			echo "</tr>\n<tr>\n";
				echo "<td class='tbl1' width='150px'>".$locale['HPQB_078']."<span style='background-color:#$set_qbname_color;'>&nbsp;&nbsp;&nbsp;&nbsp;</span></td>\n";
				echo "<td class='tbl1'><input type='text' name='color_qbname' class='textbox' value='".$set_qbname_color."' style='width:50px;' /></td>\n";
			echo "</tr>\n<tr>\n";
				echo "<td class='tbl1' width='150px'>".$locale['HPQB_079']."<img src='".MODULS."HP_quasselbox_panel/images/arrow/".$set_qbdate_color."' title='".$locale['HPQB_080']."' style='vertical-align:middle'></td>";
				echo "<td class='tbl1'><select name='color_qbdate' class='textbox' style='width:150px;'>".$arrowimage."</select></td>";
			echo "</tr><tr>";
				echo "<td colspan='2' class='tbl1'>";
					echo "<strong>".$locale['HPQB_081']."<strong><br />";
					$directory = "images/arrow";
					$allfiles = scandir($directory);          
					foreach ($allfiles as $file) {
						$fileinfo = pathinfo($directory."/".$file);
						$size = ceil(filesize($directory."/".$file)/1024);
						if ($file != "." && $file != ".."  && $file != "_notes" && $fileinfo['basename'] != "Thumbs.db") {
							$imagetype= array("jpg", "jpeg", "gif", "png");
							if(in_array($fileinfo['extension'],$imagetype)) {
								echo "<div class='arrowgalerie'><img src='".$fileinfo['dirname']."/".$fileinfo['basename']."' alt=".$fileinfo['basename']."' title='".$fileinfo['basename']."' /><br /><center><span>".$fileinfo['basename']."</span></center></div>";
							}
						};
					};
				echo "</td>";
			echo "</tr><tr>";
				echo "<td class='tbl1' colspan='2' style='text-align:center;'><input type='submit' name='qb_theme' value='".$locale['HPQB_064']."' class='button' /></td>\n";
			echo "</tr>\n";
		echo "</table>\n";
	echo "</form>\n";
	closetable();
}

require_once DESIGNS."templates/footer.php";
?>