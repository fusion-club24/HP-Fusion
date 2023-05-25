<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: profile.php
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
include LOCALE.LOCALESET."user_fields.php";

add_to_head("<style type='text/css'>
.avatar_rotate {
	cursor:pointer;
}
.avatar_rotate:hover {
	-moz-transition: all 0.6s ease-in 0s;
	-webkit-transition: all 0.6s ease-in 0s;
	-o-transition: all 0.6s ease-in 0s;
	-ms-transition: all 0.6s ease-in 0s;
	transition: all 0.6s ease-in 0s;
	-moz-transform: scale(1.2) rotate(360deg);
	-webkit-transform: scale(1.2) rotate(360deg);
	-o-transform: scale(1.2) rotate(360deg);
	-ms-transform: scale(1.2) rotate(360deg);
	transform: scale(1.2) rotate(360deg);
}
</style>");

if ($settings['hide_userprofiles'] && !iMEMBER) { redirect(BASEDIR."login.php"); }

if (isset($_GET['lookup']) && isnum($_GET['lookup'])) {
	//Profil Block start
	include INCLUDES."users_blocks/block_function.php";
	//Profil Block stop

	$result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$_GET['lookup']."' LIMIT 1");

	if (dbrows($result)) { $user_data = dbarray($result); } else { redirect("index.php"); }

	$status = array($locale['status0'], $locale['status1'], $locale['status8'], $locale['status3'], $locale['status4'], $locale['status5'], $locale['status6'], $locale['status7']);

	$visible_arr = array(0, 3, 7);
	if (iADMIN && ($user_data['user_status'] == 1 || $user_data['user_status'] == 3)) {
		$suspend = dbarray(dbquery(
			"SELECT suspend_reason FROM ".DB_SUSPENDS." WHERE suspended_user='".$_GET['lookup']."' ORDER BY suspend_date DESC LIMIT 1"
		));
	}
	if ((!iADMIN || !checkrights("M")) && !in_array($user_data['user_status'], $visible_arr)) { redirect("index.php"); }

	if (iADMIN && checkrights("UG") && $user_data['user_id'] != $userdata['user_id']) {
		if ((isset($_POST['add_to_group'])) && (isset($_POST['user_group']) && isnum($_POST['user_group']))) {
			if (!preg_match("(^\.{$_POST['user_group']}$|\.{$_POST['user_group']}\.|\.{$_POST['user_group']}$)", $user_data['user_groups'])) {
				$result = dbquery("UPDATE ".DB_USERS." SET user_groups='".$user_data['user_groups'].".".$_POST['user_group']."' WHERE user_id='".$user_data['user_id']."'");
			}
			redirect(FUSION_SELF."?lookup=".$user_data['user_id']);
		}
		if ((isset($_GET['remove_group']) && isNum($_GET['remove_group']) && $_GET['remove_group'] == 1) && (isset($_GET['user_group']) && isnum($_GET['user_group'])) && $user_data['user_level'] < $userdata['user_level']) {
			if (preg_match("(^\.{$_GET['user_group']}$|\.{$_GET['user_group']}\.|\.{$_GET['user_group']}$)", $user_data['user_groups'])) {
				$user_groups = preg_replace(array("(^\.{$_GET['user_group']}$)","(\.{$_GET['user_group']}\.)","(\.{$_GET['user_group']}$)"), array("",".",""), $user_data['user_groups']);
				$result = dbquery("UPDATE ".DB_USERS." SET user_groups='".$user_groups."' WHERE user_id='".$user_data['user_id']."'");
			}
			redirect(FUSION_SELF."?lookup=".$user_data['user_id']);
		}
	}

	add_to_title($locale['global_200'].$locale['u103'].$locale['global_201'].$user_data['user_name']);
	opentable($locale['u104']." ".$user_data['user_name']);
		echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border center'>\n";
			echo "<tr>\n";
				echo "<td width='75%' class='tbl2'>";
					echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border center'>\n";
						echo "<tr>\n";
							echo "<td width='20%' class='tbl2' valign='top'>";
								echo "<table>";
									echo "<tr>\n";
										if ($user_data['user_avatar'] && file_exists(IMAGES."avatars/".$user_data['user_avatar'])) {
											echo "<td width='1%' rowspan='6' valign='top' align='center' class='tbl profile_user_avatar'><!--profile_user_avatar--><img class='avatar_rotate' src='".IMAGES."avatars/".$user_data['user_avatar']."' alt='' /></td>\n";
										} else {
											echo "<td width='1%' rowspan='6' valign='top' align='center' class='tbl profile_user_avatar'><!--profile_user_avatar--><img class='avatar_rotate' src='".IMAGES."avatars/noavatar100.png' alt=''/></td>\n";
										}
									echo "</tr>\n";
								echo "</table>";
							echo "</td>\n";
							echo "<td width='80%' class='tbl2'>";
								echo "<table>";
									echo "<tr>\n";
										echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u068']."</td>\n";
										echo "<td align='right' class='tbl1 profile_user_name'><!--profile_user_name-->".$user_data['user_name']."</td>\n";
									echo "</tr>\n<tr>\n";
										echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u063']."</td>\n";
										echo "<td align='right' class='tbl1 profile_user_level'><!--profile_user_level-->".getuserlevel($user_data['user_level'])."</td>\n";
									echo "</tr>\n";
									if (iADMIN) {
										echo "<tr>\n";
											echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u055']."</td>\n";
											echo "<td align='right' class='tbl1 profile_user_status'><!--profile_user_status-->".$status[$user_data['user_status']]."</td>\n";
										echo "</tr>\n";
										if ($user_data['user_status'] == 1 || $user_data['user_status'] == 3 && $suspend['suspend_reason'] != "") {
											echo "<tr>\n";
												echo "<td valign='top' width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u056']."</td>\n";
												echo "<td align='right' class='tbl1 profile_user_reason'><!--profile_user_reason-->".$suspend['suspend_reason']."</td>\n";
											echo "</tr>\n";
										}
									}
									if ($user_data['user_hide_email'] != "1" || iADMIN) {
										echo "<tr>\n";
											echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u064']."</td>\n";
											echo "<td align='right' class='tbl1'>".hide_email($user_data['user_email'])."</td>\n";
										echo "</tr>\n";
									}
									echo "<tr>\n";
										echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u040']."</td>\n";
										echo "<td align='right' class='tbl1'>".showdate("shortdate", $user_data['user_joined'])."</td>\n";
									echo "</tr>\n<tr>\n";
										echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u041']."</td>\n";
										echo "<td align='right' class='tbl1'>".($user_data['user_lastvisit'] ? showdate("shortdate", $user_data['user_lastvisit']) : $locale['u042'])."</td>\n";
									echo "</tr>\n";
								echo "</table>";
							echo "</td>\n";
						echo "</tr>\n";
					echo "</table>";
	
					echo "<div style='margin:5px'></div>\n";
	
					echo "<table cellpadding='0' cellspacing='1' width='100%' class='profile tbl-border center'>\n";
						if ((iMEMBER && $userdata['user_id'] != $user_data['user_id']) && ($user_data['user_id'] != 2)) {
							echo "<tr>";
								echo "<td class='user_profile_opts tbl2' align='center'><a class='button' href='messages.php?msg_send=".$user_data['user_id']."' title='".$locale['u043']."'>".$locale['u043']."</a>\n";
								if (iADMIN && checkrights("M") && $user_data['user_level'] != "103" && $user_data['user_id'] != "1") {
									echo " - <a class='button' href='".ADMIN."members.php".$aidlink."&amp;step=log&amp;user_id=".$_GET['lookup']."'>".$locale['u054']."</a>";
								}
								echo "</td>\n";
							echo "</tr>\n";
						}
					echo "</table>\n";

					echo "<div style='margin:5px'></div>\n";

					$profile_method = "display"; $i = 0; $user_cats = array(); $user_fields = array(); $ob_active = false;
					$result2 = dbquery(
						"SELECT * FROM ".DB_USER_FIELDS." tuf
						INNER JOIN ".DB_USER_FIELD_CATS." tufc ON tuf.field_cat = tufc.field_cat_id
						ORDER BY field_cat_order, field_order"
					);
					
					if (dbrows($result2)) {
						while($data2 = dbarray($result2)) {
							if ($i != $data2['field_cat']) {
								if ($ob_active) {
									$user_fields[$i] = ob_get_contents();
									ob_end_clean();
									$ob_active = false;
								}
								$i = $data2['field_cat'];
								$user_cats[] = array(
									"field_cat_name" => $data2['field_cat_name'],
									"field_cat" => $data2['field_cat']
								);
							}
							if (!$ob_active) {
								ob_start();
								$ob_active = true;
							}
							if (file_exists(LOCALE.LOCALESET."user_fields/".$data2['field_name'].".php")) {
								include LOCALE.LOCALESET."user_fields/".$data2['field_name'].".php";
							}
							if (file_exists(INCLUDES."user_fields/".$data2['field_name']."_include.php")) {
								include INCLUDES."user_fields/".$data2['field_name']."_include.php";
							}
						}
					}

					if ($ob_active) {
						$user_fields[$i] = ob_get_contents();
						ob_end_clean();
					}
	
					$i = 1;
					foreach ($user_cats as $category) {
						if (array_key_exists($category['field_cat'], $user_fields) && $user_fields[$category['field_cat']]) {
							echo "<!--userfield_precat_".$i."-->\n";
							echo "<div style='margin:5px'></div>\n";
							echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border center'>\n";
								echo "<tr>\n";
									echo "<td colspan='2' class='tbl2'><strong>".$category['field_cat_name']."</strong></td>\n";
								echo "</tr>\n".$user_fields[$category['field_cat']];
							echo "</table>\n";
							$i++;
						}
					}
					if (!is_null($user_fields) && count($user_fields) > 0){
						echo "<!--userfield_end-->\n";
					}

					if (iADMIN && checkrights("M")) {
						echo "<div style='margin:5px'></div>\n";
						echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border center'>\n";
							echo "<tr>\n";
								echo "<td colspan='2' class='tbl2'><strong>".$locale['u048']."</strong></td>\n";
							echo "</tr>\n<tr>\n";
								echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u049']."</td>\n";
								echo "<td align='right' class='tbl1'><a href='https://geoiptool.com/de/?ip=".$user_data['user_ip']."' target='_blank' title='".$locale['u074']."'>".$user_data['user_ip']."</a></td>\n";
							echo "</tr>\n";
						echo "</table>\n";
					}

					if ($user_data['user_groups']) {
						echo "<div style='margin:5px'></div>\n";
						echo "<table cellpadding='0' cellspacing='1' width='100%' class='center tbl-border'>\n";
							echo "<tr>\n";
								echo "<td class='tbl2'><strong>".$locale['u057']."</strong></td>\n";
							echo "</tr>\n<tr>\n";
								echo "<td class='tbl1'>\n";
									$user_groups = (strpos($user_data['user_groups'], ".") == 0 ? explode(".", substr($user_data['user_groups'], 1)) : explode(".", $user_data['user_groups']));
									for ($i = 0; $i < count($user_groups); $i++) {
										echo "<div style='float:left'><a href='".FUSION_SELF."?group_id=".$user_groups[$i]."'>".getgroupname($user_groups[$i])."</a></div>";
										if (iADMIN && checkrights("M") && $user_data['user_id'] != $userdata['user_id'] && $user_data['user_level'] < $userdata['user_level']) {
											echo "<div style='float:right;'><a class='button' href='".FUSION_SELF."?lookup=".$user_data['user_id']."&user_group=".$user_groups[$i]."&remove_group=1' onclick=\"return confirm('".$locale['u117']."');\">".$locale['u115'].": ".getgroupname($user_groups[$i], true)." ".$locale['u116']."</a></div>";
										}
										echo "<div style='float:none;clear:both'></div>\n";
									}
								echo "</td>\n";
							echo "</tr>\n";
						echo "</table>\n";
					}
					if (iADMIN && checkrights("M") && $user_data['user_id'] != $userdata['user_id']) {
						$user_groups_opts = "";
						if ($user_data['user_level'] <= 102) {
							echo "<div style='margin:5px'></div>\n";
							echo "<form name='admin_form' method='post' action='".FUSION_SELF."?lookup=".$user_data['user_id']."'>\n";
								echo "<table cellpadding='0' cellspacing='0' width='100%' class='center tbl-border'>\n";
									echo "<tr>\n";
										echo "<td class='tbl2' colspan='2'><strong>".$locale['u058']."</strong></td>\n";
									echo "</tr>\n<tr>\n";
										echo "<td class='tbl1'><!--profile_admin_options-->\n";
											echo "<a href='".ADMIN."members.php".$aidlink."&amp;step=edit&amp;user_id=".$user_data['user_id']."'>".$locale['u069']."</a>\n";
											if ($user_data['user_id'] != 2) {
											echo " ::<a href='".ADMIN."members.php".$aidlink."&amp;action=1&amp;user_id=".$user_data['user_id']."'>".$locale['u070']."</a> ::\n";
											echo "<a href='".ADMIN."members.php".$aidlink."&amp;action=3&amp;user_id=".$user_data['user_id']."'>".$locale['u071']."</a> ::\n";
											echo "<a href='".ADMIN."members.php".$aidlink."&amp;step=delete&amp;status=0&amp;user_id=".$user_data['user_id']."' onclick=\"return confirm('".$locale['u073']."');\">".$locale['u072']."</a>";
											}
										echo "</td>\n";
										$result = dbquery("SELECT group_id, group_name FROM ".DB_USER_GROUPS." ORDER BY group_name");
										if (dbrows($result)) {
											while ($data2 = dbarray($result)) {
												if (!preg_match("(^{$data2['group_id']}|\.{$data2['group_id']}\.|\.{$data2['group_id']}$)", $user_data['user_groups'])) {
													$user_groups_opts .= "<option value='".$data2['group_id']."'>".$data2['group_name']."</option>\n";
												}
											}
											if (iADMIN && checkrights("UG") && $user_groups_opts) {
												echo "<td align='right' class='tbl1'>".$locale['u061'].":\n";
													echo "<select name='user_group' class='textbox' style='width:100px'>\n".$user_groups_opts."</select>\n";
													echo "<input type='submit' name='add_to_group' value='".$locale['u059']."' class='button'  onclick=\"return confirm('".$locale['u060']."');\" />";
												echo "</td>\n";
											}
										}
									echo "</tr>\n";
								echo "</table>\n";
							echo "</form>\n";
						}
					}
					
				echo "</td><td width='25%' class='tbl2' valign='top'>";
					// Right Side start
					// Forum start
					function almost_null($number){
						$rounded = number_format(round($number, 2));
						if($rounded == 0 && $number > 0){
							$rounded = "<1";
						}
						return $rounded;
					}
					
					$user_id = isnum($_GET['lookup']) ? $_GET['lookup'] : 0;
		
					if($user_id){
						list($name, $posts, $age) = dbarraynum(dbquery("SELECT user_name, user_posts, user_joined FROM ".DB_USERS." WHERE user_id=".$user_id));
						$posts = empty($posts) ? 0 : $posts;
						list($threads) = dbarraynum(dbquery("SELECT COUNT(thread_id) FROM ".DB_THREADS." WHERE thread_author=".$user_id));
						$threads = empty($threads) ? 0 : $threads;
			
						$threadspday = almost_null($threads/((time() - $age)/(3600*24)));
						$postspday = almost_null($posts/((time() - $age)/(3600*24)));
			
						list($ranked_higher) = dbarraynum(dbquery("SELECT COUNT(user_id) FROM ".DB_USERS." WHERE user_posts>".$posts));
						$rank = $ranked_higher+1;
						list($allposts) = dbarraynum(dbquery("SELECT SUM(forum_postcount) FROM ".DB_FORUMS));
						$percentage = empty($posts) || empty($allposts) ? 0 : ($posts*100.0)/$allposts;
						$percentage = almost_null($percentage);

						foreach(array("threads", "posts") as $type){
							$other_type = $type=="threads"? "posts" : "threads";
							if($type == "threads"){
								if(!isset($_GET['show']) || (isset($_GET['show']) && $_GET['show'] != "posts")){
									$visibility = "";
								}else{
									$visibility = "style='display: none;'";
								}
							}else{
								if(isset($_GET['show']) && $_GET['show'] == "posts"){
									$visibility = "";
								}else{
									$visibility = "style='display: none;'";
								}
							}
							if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
							$where = $type == "threads" ? "tt.thread_author='$user_id' GROUP BY tt.thread_id, post_id" : "tp.post_author='".$user_id."'";
				
							$rows_res = dbquery(
								"SELECT post_id FROM ".DB_POSTS." tp
								INNER JOIN ".DB_FORUMS." tf ON tp.forum_id=tf.forum_id
								INNER JOIN ".DB_THREADS." tt ON tp.thread_id=tt.thread_id
								WHERE ".groupaccess('tf.forum_access')." AND $where
								ORDER BY tp.post_datestamp DESC"
							);
			
							$result = dbquery(
								"SELECT tp.forum_id, tp.thread_id, tp.post_id, tp.post_author, tp.post_datestamp, tf.forum_name, tf.forum_access, tt.thread_subject
								FROM ".DB_POSTS." tp
								INNER JOIN ".DB_FORUMS." tf ON tp.forum_id=tf.forum_id
								INNER JOIN ".DB_THREADS." tt ON tp.thread_id=tt.thread_id
								WHERE ".groupaccess('tf.forum_access')." AND $where
								ORDER BY tp.post_datestamp DESC LIMIT 0,10"
							);
				
							echo "<script type='text/javascript'>
								$(document).ready(function(){
									$('#forum_panel_".$other_type."_toggle').click(function() {
										$('#forum_panel_".$other_type."').show();
										$('#forum_panel_".$type."').hide();
										return false;
									});
								});
							</script>
							<div id='forum_panel_".$type."' ".$visibility.">";
								if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
								echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n\t";
									echo "<tr>";
										if ($type == "threads") {
											echo "<th class='tbl1'>".$locale['u075']."<br /><a href='".FUSION_SELF."?lookup=".$user_id."&amp;show=".$other_type."' id='forum_panel_".$other_type."_toggle'>".$locale['u076']."</a></th>";
										} else {
											echo "<th class='tbl1'>".$locale['u077']."<br /><a href='".FUSION_SELF."?lookup=".$user_id."&amp;show=".$other_type."' id='forum_panel_".$other_type."_toggle'>".$locale['u078']."</a></th>";
										}
									echo "</tr>\n";
									$rows = dbrows($rows_res);
									if ($rows) {
										$i=0;
										while ($data = dbarray($result)) {
											$i++; $row = $i%2 ? "class='tbl1'" : "class='tbl2'";
											echo "<tr>\n\t";
												echo "<td width='100%' $row>".$locale['u079']." <a href='".FORUM."viewthread.php?thread_id=".$data['thread_id']."&amp;pid=".$data['post_id']."#post_".$data['post_id']."' title='".$data['thread_subject']."'>".trimlink($data['thread_subject'], 15)."</a><br />".$locale['u080']." ".trimlink($data['forum_name'], 15)."</td>\n";
											echo "</tr>\n";
										}
									} else {
										echo "<tr>";
											if ($type == "threads") {
												echo "<td style='text-align:center' class='tbl1'>".$locale['u081']."</td>";
											} else {
												echo "<td style='text-align:center' class='tbl1'>".$locale['u082']."</td>";
											}
										echo "</tr>\n";
									}
								echo "</table>\n";
								
								echo "<div style='margin:5px'></div>\n";
								
								echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>";
									echo "<tr><td class='tbl1'>".sprintf("".$locale['u083']."", $name, number_format($rank), $percentage)."</td></tr>";
								echo "</table>\n";
							echo "</div>\n";
						}
					}
					// Forum stop
					
					echo "<div style='margin:5px'></div>\n";
					
					// Do you know?
					if (!isset($user_data['user_privacy'])) {
					$result = dbquery("SELECT user_id, user_name, user_level, user_avatar FROM ".DB_USERS." WHERE user_id !='".$user_data['user_id']."' AND user_status = '0' ORDER BY rand() DESC LIMIT 0,2");
					} else {
					$result = dbquery("SELECT user_id, user_name, user_level, user_avatar, user_privacy, user_status FROM ".DB_USERS." WHERE user_id !='".$user_data['user_id']."' AND user_privacy != '102' AND user_status = '0' ORDER BY rand() DESC LIMIT 0,2");
					}
					
					if (dbrows($result) != 0) {
						$data=dbarray($result);
						while ($data = dbarray($result)) {      
							$colors = array(
								103 => "#FF0000",
								102 => "#008000",
								101 => "#666666"
							);
							echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border center'>\n";
								echo "<tr>";
									echo "<td class='tbl2'>";
										echo "<div align='center'>
											<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border center'>\n";
												echo "<div class='tbl1'><strong>".$locale['u084']."</strong></div>\n";
												echo "<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."' title='".$data['user_name']."'><b>".trimlink ($data['user_name'], 18)."</b></a><br />".getuserlevel($data['user_level'] ? $data['user_level'] : "".$locale['u085']."", 20)."<br />";
												if ($data['user_avatar'] && file_exists(IMAGES."avatars/".$data['user_avatar'])) {
													echo "<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."' title='".$data['user_name']."'><img src='".IMAGES."avatars/".$data['user_avatar']."' alt='".$data['user_name']."' /></a>";
												} else {
													echo "<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."' title='".$data['user_name']."'><img src='".IMAGES."avatars/noavatar100.png' alt='".$data['user_name']."' /></a>";
												}	
											echo "</table>";
										echo "</div>";
									echo "</td>";
								echo "</tr>";
							echo "</table>";
						}
					}
					// Right side stop
				echo "</td>\n";
			echo "</tr>\n";
		echo "</table>";
	closetable();

} elseif (isset($_GET['group_id']) && isnum($_GET['group_id'])) {
	$result = dbquery("SELECT group_id, group_name FROM ".DB_USER_GROUPS." WHERE group_id='".$_GET['group_id']."'");
	if (dbrows($result)) {
		$data = dbarray($result);
		$result = dbquery("SELECT user_id, user_name, user_level, user_status FROM ".DB_USERS." WHERE user_groups REGEXP('^\\\.{$_GET['group_id']}$|\\\.{$_GET['group_id']}\\\.|\\\.{$_GET['group_id']}$') ORDER BY user_level DESC, user_name");
		opentable($locale['u110']);
			echo "<table cellpadding='0' cellspacing='0' width='100%'>\n";
				echo "<tr>\n";
					echo "<td align='center' colspan='2' class='tbl1'><strong>".$data['group_name']."</strong> (".sprintf((dbrows($result) == 1 ? $locale['u111'] : $locale['u112']), dbrows($result)).")</td>\n";
				echo "</tr>\n<tr>\n";
					echo "<td class='tbl2'><strong>".$locale['u113']."</strong></td>\n";
					echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['u114']."</strong></td>\n";
				echo "</tr>\n";
				while ($data = dbarray($result)) {
					$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2"); $i++;
					echo "<tr>\n";
						echo "<td class='$cell_color'>\n".profile_link($data['user_id'], $data['user_name'], $data['user_status'])."</td>\n";
						echo "<td align='center' width='1%' class='$cell_color' style='white-space:nowrap'>".getuserlevel($data['user_level'])."</td>\n";
					echo "</tr>";
				}
			echo "</table>\n";
		closetable();
	} else {
		redirect("index.php");
	}
} else {
	redirect(BASEDIR."index.php");
}
require_once DESIGNS."templates/footer.php";
?>