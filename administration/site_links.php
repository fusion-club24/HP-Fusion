<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: site_links.php
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
require_once "../maincore.php";

if (!checkrights("SL") || !defined("iAUTH") || !isset($_GET['aid']) || $_GET['aid'] != iAUTH) { redirect("../index.php"); }
require_once DESIGNS."templates/admin_header.php";
include LOCALE.LOCALESET."admin/sitelinks.php";

if (isset($_GET['action']) && $_GET['action'] == "add") {
	$__maincap = $locale['400'];
} elseif (isset($_GET['action']) && $_GET['action'] == "edit") {
	$__maincap = $locale['401'];
} elseif (isset($_GET['action']) && $_GET['action'] == "insert") {
	$__maincap = $locale['414'];
} elseif (isset($_GET['action']) && $_GET['action'] == "update") {
	$__maincap = $locale['414'];
} elseif (isset($_GET['action']) && $_GET['action'] == "delete") {
	$__maincap = $locale['460'];
} else {
	$__maincap = $locale['402'];
}

opentable($__maincap);

if (isset($_GET['status']) && !isset($message)) {
	if ($_GET['status'] == "sn") {
		$message = $locale['410'];
	} elseif ($_GET['status'] == "su") {
		$message = $locale['411'];
	} elseif ($_GET['status'] == "del") {
		$message = $locale['412'];
	}
	if ($message) {
		echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n";
	}
}

echo "<table align='center' cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n
	<tr>\n
		<td colspan='6' class='tbl1'>\n";
			// Here comes the actual content of the page
			$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
			$link_visibility = isset($_REQUEST['link_visibility']) ? $_REQUEST['link_visibility'] : "";
			$cat = isset($_REQUEST['cat']) ? $_REQUEST['cat'] : "";
			$newid = isset($_REQUEST['newid']) ? $_REQUEST['newid'] : "";
			$id = (isset($_REQUEST['id']) && isnum($_REQUEST['id'])) ? $_REQUEST['id'] : "";
			$link_window = ($data['link_window']=="1" ? " checked='checked'" : "");
			$i = 1;
			$result = dbquery("SELECT * FROM ".DB_SITE_LINKS." ORDER BY link_cat ASC, link_order ASC");
			while ($data = dbarray($result)){
				$result2 = dbquery("UPDATE ".DB_SITE_LINKS." SET link_order='$i' WHERE link_id='".$data['link_id']."'");
				$i++;
			}

			if (isset($_GET['action']) && $_GET['action'] == "refresh") {
				$i = 1;
				$result = dbquery("SELECT * FROM ".DB_SITE_LINKS." ORDER BY link_cat ASC, link_order ASC");
				while ($data = dbarray($result)){
					$result2 = dbquery("UPDATE ".DB_SITE_LINKS." SET link_order='$i' WHERE link_id='".$data['link_id']."'");
					$i++;
				}
			}
			echo "<center>[<a href='".FUSION_SELF.$aidlink."&amp;action=refresh&amp;pagenum=1'>".$locale['454']."</a>]</center>\n";
		echo "</td>\n
	</tr>\n";
	//  ###################### Move up ##########################################
	if ($action == "moveup") {
		$data = dbarray(dbquery("SELECT * FROM ".DB_SITE_LINKS." WHERE link_cat='$cat' AND link_order='".$_GET['order']."'"));
		$result = dbquery("UPDATE ".DB_SITE_LINKS." SET link_order=link_order+1 WHERE link_id='".$data['link_id']."'");
		$result = dbquery("UPDATE ".DB_SITE_LINKS." SET link_order=link_order-1 WHERE link_id='$id'");
	}

	//  ###################### Move down ##########################################
	if ($action == "movedown") {
		$data = dbarray(dbquery("SELECT * FROM ".DB_SITE_LINKS." WHERE link_cat='$cat' AND link_order='".$_GET['order']."'"));
		$result = dbquery("UPDATE ".DB_SITE_LINKS." SET link_order=link_order-1 WHERE link_id='".$data['link_id']."'");
		$result = dbquery("UPDATE ".DB_SITE_LINKS." SET link_order=link_order+1 WHERE link_id='$id'");
	}

	//  ###################### Add ##########################################
	if ($action == "add") {
		echo "<table align='center' class='tbl-border' cellspacing='1'>
			<tr>
				<td class='tbl1'>";
					$user_groups = getusergroups(); $access_opts = ""; $sel = "";
					foreach($user_groups as $key => $user_group) {
						$sel = ($link_visibility == $user_group['0'] ? " selected" : "");
						$access_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
					}

					$pos1_check = "";
					$pos2_check = " checked='checked'";
					$pos3_check = "";
					$window_check = "";

					if (isset($_GET['level'])) {
						$__level = 'border: 1px solid #F00;';
					} else {
						$__level = '';
					}

					echo "<form name='addmenu'  method='post' action='".FUSION_SELF.$aidlink."&amp;action=insert&amp;pagenum=1'>
						<table align='center' width='500' cellspacing='0' cellpadding='0' class='tbl'>\n
							<tr>\n
								<td colspan='2'>".$locale['420']."<br />
									<input type='text' name='link_name' class='textbox' style='border: 1px solid #F00;width: 285px;'>
								</td>\n
							</tr>\n<tr>\n
								<td colspan='2'>".$locale['421']."<br />
									<input type='text' name='link_url' rows='2' class='textbox' style=' ".$__level."  width:285px;'>
								</td>\n
							</tr>\n<tr>\n
								<td colspan='2'>".$locale['422']."<br />
									<select name='link_visibility' class='textbox' style='width:225px;'>".$access_opts."</select></td>\n
							</tr>\n<tr>\n
								<td colspan='2'><br /></td>\n
							</tr>\n<tr>\n
								<td valign='top' class='tbl'>".$locale['424']."</td>\n
								<td class='tbl'>
									<label><input type='radio' name='link_position' value='1'".$pos1_check." /> ".$locale['425']."</label><br />\n
									<label><input type='radio' name='link_position' value='2'".$pos2_check." /> ".$locale['426']."</label><br />\n
									<label><input type='radio' name='link_position' value='3'".$pos3_check." /> ".$locale['427']."</label><hr />\n
									<label><input type='checkbox' name='link_window' value='1'".$window_check." /> ".$locale['428']."</label>
								</td>\n
							</tr>\n<tr>\n
								<td>".$locale['423']."&nbsp;&nbsp;<input type='text' name='link_order'  rows='2' class='textbox' style='width:35px;'></td>\n
								<td align='center' colspan='2'>
									".$locale['430']."&nbsp;&nbsp;";
									
									$link_cat_opts = ""; $sel = "";
									$link_cat_opts .= "<option value='0'>".$locale['431']."</option>\n";
									$result_0 = dbquery("SELECT * FROM ".DB_SITE_LINKS." WHERE link_cat='0' AND link_url='' ORDER BY link_order");

									while ($data_0 = dbarray($result_0)) {
										$sel0 = ($data_0['link_id'] == $_GET['cat'] ? " selected='selected'" : "");
										$link_cat_opts .= "<option value='".$data_0['link_id']."'$sel0>".$data_0['link_name']."</option>\n";

										$result_1 = dbquery("SELECT * FROM ".DB_SITE_LINKS." WHERE link_cat='".$data_0['link_id']."' AND link_url='' ORDER BY  link_order , link_id ");

										while ($data_1 = dbarray($result_1)) {
											$sel1 = ($data_1['link_id'] == $_GET['cat'] ? " selected='selected'" : "");
											$link_cat_opts .= "<option value='".$data_1['link_id']."'$sel1>. ".$data_1['link_name']."</option>\n";
										}
									}
		
									echo "<select name='link_cat' class='textbox' style='width:150px;' >\n
									<option value='$link_cat_opts' ></option></select>\n
								</td>\n
							</tr>\n<tr>
								<td align='center' colspan='2'>
									<input type='submit' name='insert' value='".$locale['429']."' class='button'>
								</td>\n
							</tr>\n
						</table>\n
					</form>\n
				</td>\n
			</tr>\n
		</table>";
	}

	//  ###################### Edit ##########################################
	if ($action == "edit") {
		echo "<table align='center' class='tbl-border' cellspacing='1'>
			<tr>
				<td class='tbl1'>";
					$result = dbquery("SELECT * FROM ".DB_SITE_LINKS." WHERE link_id='".$id."'");
					$data = dbarray($result);
					$link_cat_select = $data['link_cat']; 	
					$link_name = $data['link_name'];
					$link_url = $data['link_url'];
					$link_cat = $data['link_cat'];
					$link_order = $data['link_order'];
					$link_visibility = $data['link_visibility'];
					$link_window = $data['link_window'];
					$window_check = ($data['link_window']=="1" ? " checked='checked'" : "");
					$pos1_check = ($data['link_position']=="1" ? " checked='checked'" : "");
					$pos2_check = ($data['link_position']=="2" ? " checked='checked'" : "");
					$pos3_check = ($data['link_position']=="3" ? " checked='checked'" : "");

					$user_groups = getusergroups(); $access_opts = "";
					foreach($user_groups as $key => $user_group) {
						$sel = ($link_visibility == $user_group['0'] ? " selected" : "");
						$access_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
					}
					echo "<form name='addmenu' method='post' action='".FUSION_SELF.$aidlink."&amp;action=update&amp;pagenum=1'>
						<table align='center' width='500' cellspacing='0' cellpadding='0' class='tbl'>\n
							<tr>\n
								<td colspan='2'>".$locale['420']."<br />
									<input type='text' name='link_name' value='$link_name' class='textbox' style='width:285px;'>
								</td>\n
							</tr>\n<tr>\n
								<td colspan='2'>".$locale['421']."<br />
									<input type='text' name='link_url' value='$link_url' rows='2' class='textbox' style='width:285px;'>
								</td>\n
							</tr>\n<tr>\n
								<td colspan='2'>".$locale['422']."<br />
									<select name='link_visibility' class='textbox' style='width:225px;'>".$access_opts."</select>
								</td>\n
							</tr>\n<tr>\n
								<td valign='top' class='tbl'>".$locale['424']."</td>\n
								<td class='tbl'>
									<label><input type='radio' name='link_position' value='1'".$pos1_check." /> ".$locale['425']."</label><br />\n
									<label><input type='radio' name='link_position' value='2'".$pos2_check." /> ".$locale['426']."</label><br />\n
									<label><input type='radio' name='link_position' value='3'".$pos3_check." /> ".$locale['427']."</label><hr />\n
									<label><input type='checkbox' name='link_window' value='1'".$window_check." /> ".$locale['428']."</label>
								</td>\n
							</tr>\n";
							echo "<input type='hidden' name='link_id' value='".$data['link_id']."'>";

							$link_cat_opts = ""; $sel = "";
							$link_cat_opts .= "<option value='0'>".$locale['431']."</option>\n";
							$result_0 = dbquery("SELECT * FROM ".DB_SITE_LINKS." WHERE link_cat='0' AND link_url='' ORDER BY link_order ");

							while ($data_0 = dbarray($result_0)) {
								$sel0 = ($data_0['link_id'] == $data['link_cat'] ? " selected='selected'" : "");
								$link_cat_opts .= "<option value='".$data_0['link_id']."'$sel0> ".$data_0['link_name']."</option>\n";

								$result_1 = dbquery("SELECT * FROM ".DB_SITE_LINKS." WHERE link_cat='".$data_0['link_id']."' AND link_url='' ORDER BY  link_order , link_id ");

								while ($data_1 = dbarray($result_1)) {
									$sel1 = ($data_1['link_id'] == $link_cat_select ? " selected='selected'" : "");
									$link_cat_opts .= "<option value='".$data_1['link_id']."'$sel1>. ".$data_1['link_name']."</option>\n";
								}
							}

							echo "<tr>\n
								<td>
									".$locale['423']."&nbsp;&nbsp;<input type='text' name='link_order' value='$link_order' rows='2' class='textbox' style='width:35px;'></td>\n<td align='center'>".$locale['430']."&nbsp;&nbsp;
									<select name='link_cat' class='textbox' style='width:150px;' >\n
									<option value='$link_cat_opts' ></option></select>\n
								</td>\n
							</tr>\n<tr>\n
								<td align='center' colspan='2'>
									<input type='submit' name='update' value='".$locale['429']."' class='button'>
								</td>\n
							</tr>\n
						</table>\n
					</form>\n
				</td>
			</tr>\n
		</table>\n";
	}

	//  ###################### Insert ##########################################
	if ($action == "insert") {
		echo "<table align='center' class='tbl-border' cellspacing='1'>\n
			<tr>\n
				<td class='tbl1'>";
					if ($_POST['link_name']!='' ) {
						$link_cat = $_POST['link_cat'];
						$link_name = stripinput($_POST['link_name']);
						$link_url = stripinput($_POST['link_url']);
						$link_visibility = $_POST['link_visibility'];
						$link_position = $_POST['link_position'];
						$link_window = isset($_POST['link_window']) ? $_POST['link_window'] : "0";
						$link_order = $_POST['link_order'];
	
						$result = dbquery("INSERT INTO ".DB_SITE_LINKS." VALUES('', '$link_cat', '$link_name', '$link_url', '$link_visibility', '$link_position', '$link_window', '$link_order')");
						redirect(FUSION_SELF.$aidlink."&amp;action=refresh&amp;pagenum=1&amp;status=sn");
					} else {
						echo "<center><br />".$locale['413']."<br /><br /></center>\n";
					}
				echo "</td>\n
			</tr>\n
		</table>\n";
	}

	//  ###################### Update ##########################################
	if ($action == "update") {
		echo "<table align='center' class='tbl-border' cellspacing='1'>\n
			<tr>\n
				<td class='tbl1'>";
					if ($_POST['link_name']!='' ) {
						$link_id = $_POST['link_id'];
						$link_name = stripinput($_POST['link_name']);
						$link_url = stripinput($_POST['link_url']);
						$link_cat = $_POST['link_cat'];
						$link_order = $_POST['link_order'];
						$link_visibility = $_POST['link_visibility'];
						$link_position = $_POST['link_position'];
						$link_window = isset($_REQUEST['link_window']) ? $_REQUEST['link_window'] : "";
	
						$result_nav00 = dbquery("UPDATE ".DB_SITE_LINKS." SET link_name='$link_name', link_cat='$link_cat', link_order='$link_order', link_url='$link_url', link_visibility='$link_visibility', link_position='$link_position', link_window='$link_window' WHERE link_id='$link_id'");

						redirect(FUSION_SELF.$aidlink."&amp;action=refresh&amp;status=su");
					} else {
						echo "<center><br />".$locale['413']."<br /><br /></center>\n";
					}
				echo "</td>\n
			</tr>\n
		</table>\n";
	};

	//  ###################### Delete ##########################################
	if ($action == "all_delete") {
		if (dbcount("(*)", DB_SITE_LINKS, "link_cat='".$_POST['link_id']."'") != 0) {
			$data__delete1 = dbquery("SELECT * FROM ".DB_SITE_LINKS." WHERE link_cat='".$_POST['link_id']."'");
			while($data__delete2 = dbarray($data__delete1)) {
				$result_delete2 = dbquery("DELETE FROM ".DB_SITE_LINKS." WHERE link_cat='".$data__delete2['link_id']."'");
			}
			$result_delete1 = dbquery("DELETE FROM ".DB_SITE_LINKS." WHERE link_cat='".$_POST['link_id']."'");
			$result_delete0 = dbquery("DELETE FROM ".DB_SITE_LINKS." WHERE link_id='".$_POST['link_id']."'");
		}
		redirect(FUSION_SELF.$aidlink."&amp;action=refresh&amp;pagenum=1&amp;status=del");
	}


	if ($action == "delete") {
		echo "<table align='center' class='tbl-border' cellspacing='1'>\n
			<tr>\n
				<td class='tbl1'>";
					if (dbcount("(*)", DB_SITE_LINKS, "link_cat='".$id."'") == 0) {
						$data = dbarray(dbquery("SELECT * FROM ".DB_SITE_LINKS." WHERE link_id='".$id."'"));
						$result = dbquery("UPDATE ".DB_SITE_LINKS." SET link_order=link_order-1 WHERE link_order>'".$data['link_order']."'");
						$result = dbquery("DELETE FROM ".DB_SITE_LINKS." WHERE link_id='".$id."'");
						redirect(FUSION_SELF.$aidlink."&amp;action=refresh&amp;pagenum=1&amp;status=del");
					} else {
						echo "<center>".$locale['415']."</center>\n";
						echo "<center>
							<form method='post'  action='".FUSION_SELF.$aidlink."&amp;action=all_delete&amp;pagenum=1'>
								<input type='submit' name='del_del' value='".$locale['461']."' class='button' />
								<input  type='hidden' name='link_id' value='".$id."' />
							</form>
						</center>\n";
						echo "<center><br />".$locale['462']."</center>\n";
					}
				echo "</td>\n
			</tr>\n
		</table>\n";
	}

	//  ###################### Set up menu ##########################################
	// Get top row in the table
	echo "<br />
	<table cellspacing='1' cellpadding='0' class='tbl-border' align='center'>\n
		<tr>\n
			<td class='tbl2'><strong>".$locale['440']."</strong></td>\n
			<td class='tbl2' width='260px'><strong>".$locale['447']."</strong></td>\n
			<td class='tbl2'><strong>".$locale['441']."</strong></td>\n
			<td class='tbl2'><strong>".$locale['448']."</strong></td>\n
			<td class='tbl2'><strong>".$locale['442']."</strong></td>\n
			<td class='tbl2' width='80px'><strong>".$locale['443']."</strong></td>\n
		</tr>\n";

		//Link Level 0
		$msql = dbquery("SELECT * FROM ".DB_SITE_LINKS." WHERE link_cat='0' ORDER BY link_order");
		$numrows = dbrows($msql);
		$max_link_order = dbarray(dbquery("SELECT link_order FROM ".DB_SITE_LINKS." ORDER BY link_order DESC LIMIT 0,1"));
		echo "<tr>
			<td class='tbl2'><strong>".$locale['431']."</strong></td>\n
			<td class='tbl2'></td>\n
			<td class='tbl2'></td>\n
			<td class='tbl2'></td>\n
			<td class='tbl2'></td>\n
			<td class='tbl2'><a href='".FUSION_SELF.$aidlink."&amp;action=add&amp;pagenum=1&amp;cat=0&amp;newid=".($max_link_order['link_order'] + 1)."'><img src='".IMAGES."navigation/add_m.png' alt='".$locale['480']."' title='".$locale['480']."'></a></td>\n
		</tr>\n";
		if ($numrows != 0) {
			$i = 1;
			while ($mdata = dbarray($msql)) {
				if ($mdata['link_position'] == 1) {
					$side_img = "<img src='".IMAGES."navigation/side1.gif' alt='".$locale['425']."' title='".$locale['425']."'>";
				} elseif ($mdata['link_position'] == 3) {
					$side_img = "<img src='".IMAGES."navigation/side3.gif' alt='".$locale['427']."' title='".$locale['427']."'>";
				} else {
					$side_img = "<img src='".IMAGES."navigation/side2.gif' alt='".$locale['426']."' title='".$locale['426']."'>";
				}
				echo "<tr>
					<td class='tbl1'><font color='#00D413'>".parseubb($mdata['link_name'], "b|i|u|color|img")."</font></td>\n
					<td class='tbl1'>".$mdata['link_url']."</td>
					<td class='tbl1'>".getgroupname($mdata['link_visibility'])."</td>\n
					<td class='tbl1'>".$side_img."</td>\n
					<td class='tbl1'>
						".$mdata['link_order']." ";
						$up = $mdata['link_order'] - 1;
						$down = $mdata['link_order'] + 1;
						if ($i == 1) {
							if ($numrows > 1) {
								echo "<a href='".FUSION_SELF.$aidlink."&amp;action=movedown&amp;pagenum=1&amp;order=$down&amp;id=".$mdata['link_id']."&amp;cat=".$mdata['link_cat']."'><img src='".IMAGES."navigation/down_g.gif' style='border:0px;'></a>\n";
							}
						} else if ($i > 1) {
							if ($numrows > $i) {
								echo "<a href='".FUSION_SELF.$aidlink."&amp;action=moveup&amp;pagenum=1&amp;order=$up&amp;id=".$mdata['link_id']."&amp;cat=".$mdata['link_cat']."'><img src='".IMAGES."navigation/up_g.gif' style='border:0px;'></a><a href='".FUSION_SELF.$aidlink."&amp;action=movedown&amp;pagenum=1&amp;order=$down&amp;id=".$mdata['link_id']."&amp;cat=".$mdata['link_cat']."'><img src='".IMAGES."navigation/down_g.gif' style='border:0px;'></a>";
							} else if ($numrows = $i) {
								echo "<a href='".FUSION_SELF.$aidlink."&amp;action=moveup&amp;pagenum=1&amp;order=$up&amp;id=".$mdata['link_id']."&amp;cat=".$mdata['link_cat']."'><img src='".IMAGES."navigation/up_g.gif' style='border:0px;'></a>";
							}
						}
					echo "</td>\n<td class='tbl1'>
						<a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;pagenum=1&amp;id=".$mdata['link_id']."'><img src='".IMAGES."navigation/edit.png' alt='".$locale['444']."' title='".$locale['444']."'></a> -
						<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;pagenum=1&amp;id=".$mdata['link_id']."'><img src='".IMAGES."navigation/delete.png' alt='".$locale['445']."' title='".$locale['445']."'></a> -
						<a href='".FUSION_SELF.$aidlink."&amp;action=add&amp;pagenum=1&amp;cat=".$mdata['link_id']."&amp;newid=".($max_link_order['link_order'] + 1)."'><img src='".IMAGES."navigation/add.png' alt='".$locale['481']."' title='".$locale['481']."'></a>
					</td>\n
				</tr>\n";

				// Link Level 1
				$msql2 = dbquery("SELECT * FROM ".DB_SITE_LINKS." WHERE link_cat='".$mdata['link_id']."' ORDER BY link_order");
				$numrows2 = dbrows($msql2);
				if ($numrows2 != 0) {
					$j = 1;
					while ($mdata2 = dbarray($msql2)) {
						if ($mdata2['link_position'] == 1) {
							$side_img2 = "<img src='".IMAGES."navigation/side1.gif' alt='".$locale['425']."' title='".$locale['425']."'>";
						} elseif ($mdata2['link_position'] == 3) {
							$side_img2 = "<img src='".IMAGES."navigation/side3.gif' alt='".$locale['427']."' title='".$locale['427']."'>";
						} else {
							$side_img2 = "<img src='".IMAGES."navigation/side2.gif' alt='".$locale['426']."' title='".$locale['426']."'>";
						}
						
						echo "<tr>\n
							<td class='tbl2'><font color='#2C93DD'><img src='".IMAGES."navigation/spacer.gif' width='3'> ".parseubb($mdata2['link_name'], "b|i|u|color|img")."</font></td>\n
							<td class='tbl2'>".$mdata2['link_url']."</td>\n<td class='tbl2'>".getgroupname($mdata2['link_visibility'])."</td>
							<td class='tbl2'>".$side_img2."</td>\n
							<td class='tbl2'>".$mdata2['link_order']." ";
								$up = $mdata2['link_order'] - 1;
								$down = $mdata2['link_order'] + 1;
								if ($j == 1) {
									if ($numrows2 > 1) {
										echo "<a href='".FUSION_SELF.$aidlink."&amp;action=movedown&amp;pagenum=1&amp;order=$down&amp;id=".$mdata2['link_id']."&amp;cat=".$mdata2['link_cat']."'><img src='".IMAGES."navigation/down_b.gif' style='border:0px;'></a>";
									}
								} else if ($j > 1) {
									if ($numrows2 > $j) {
										echo "<a href='".FUSION_SELF.$aidlink."&amp;action=moveup&amp;pagenum=1&amp;order=$up&amp;id=".$mdata2['link_id']."&amp;cat=".$mdata2['link_cat']."'><img src='".IMAGES."navigation/up_b.gif' style='border:0px;'></a><a href='".FUSION_SELF.$aidlink."&amp;action=movedown&amp;pagenum=1&amp;order=$down&amp;id=".$mdata2['link_id']."&amp;cat=".$mdata2['link_cat']."'><img src='".IMAGES."navigation/down_b.gif' style='border:0px;'></a>";
									} else if ($numrows2 = $j) {
										echo "<a href='".FUSION_SELF.$aidlink."&amp;action=moveup&amp;pagenum=1&amp;order=$up&amp;id=".$mdata2['link_id']."&amp;cat=".$mdata2['link_cat']."'><img src='".IMAGES."navigation/up_b.gif' style='border:0px;'></a>";
									}
								}
							echo "</td>\n<td class='tbl2'>
								<a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;pagenum=1&amp;id=".$mdata2['link_id']."'><img src='".IMAGES."navigation/edit.png' alt='".$locale['444']."' title='".$locale['444']."'></a> -
								<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;pagenum=1&amp;id=".$mdata2['link_id']."'><img src='".IMAGES."navigation/delete.png' alt='".$locale['445']."' title='".$locale['445']."'></a>\n";
	
								if ($mdata2['link_url'] =='') {	 
									echo " - <a href='".FUSION_SELF.$aidlink."&amp;action=add&amp;pagenum=1&amp;cat=".$mdata2['link_id']."&amp;level&amp;newid=".($max_link_order['link_order'] + 1)."'><img src='".IMAGES."navigation/add2.png' alt='".$locale['482']."' title='".$locale['482']."'></a>\n"; 
								}
							echo "</td>\n
						</tr>\n";

						// Link Level 2
						$msql3 = dbquery("SELECT * FROM ".DB_SITE_LINKS." WHERE link_cat='".$mdata2['link_id']."' ORDER BY link_order");
						$numrows3 = dbrows($msql3);
						if ($numrows3 != 0) {
							$k = 1;
							while ($mdata3 = dbarray($msql3)){
								if ($mdata3['link_position'] == 1) {
									$side_img3 = "<img src='".IMAGES."navigation/side1.gif' alt='".$locale['425']."' title='".$locale['425']."'>";
								} elseif ($mdata3['link_position'] == 3) {
									$side_img3 = "<img src='".IMAGES."navigation/side3.gif' alt='".$locale['427']."' title='".$locale['427']."'>";
								} else {
									$side_img3 = "<img src='".IMAGES."navigation/side2.gif' alt='".$locale['426']."' title='".$locale['426']."'>";
								}
								echo "<tr>\n
									<td class='tbl1'><font color='#F08300'><img src='".IMAGES."navigation/spacer.gif' width='3'> <img src='".IMAGES."navigation/spacer.gif' width='3'> ".parseubb($mdata3['link_name'], "b|i|u|color|img")."</font></td>\n
									<td class='tbl1'>".$mdata3['link_url']."</td><td class='tbl1'>".getgroupname($mdata3['link_visibility'])."</td>\n
									<td class='tbl1'>".$side_img3."</td>\n
									<td class='tbl1'>".$mdata3['link_order']." ";
										$up = $mdata3['link_order'] - 1;
										$down = $mdata3['link_order'] + 1;
										if ($k == 1) {
											if ($numrows3 > 1) {
												echo "<a href='".FUSION_SELF.$aidlink."&amp;action=movedown&amp;pagenum=1&amp;order=$down&amp;id=".$mdata3['link_id']."&amp;cat=".$mdata3['link_cat']."'><img src='".IMAGES."navigation/down_r.gif' style='border:0px;'></a>";
											}
										} else if ($k > 1) {
											if ($numrows3 > $k) {
												echo "<a href='".FUSION_SELF.$aidlink."&amp;action=moveup&amp;pagenum=1&amp;order=$up&amp;id=".$mdata3['link_id']."&amp;cat=".$mdata3['link_cat']."'><img src='".IMAGES."navigation/up_r.gif' style='border:0px;'></a><a href='".FUSION_SELF.$aidlink."&amp;action=movedown&amp;pagenum=1&amp;order=$down&amp;id=".$mdata3['link_id']."&amp;cat=".$mdata3['link_cat']."'><img src='".IMAGES."navigation/down_r.gif' style='border:0px;'></a>";
											} else if ($numrows3 = $k) {
												echo "<a href='".FUSION_SELF.$aidlink."&amp;action=moveup&amp;pagenum=1&amp;order=$up&amp;id=".$mdata3['link_id']."&amp;cat=".$mdata3['link_cat']."'><img src='".IMAGES."navigation/up_r.gif' style='border:0px;'></a>";
											}
										}
									echo "</td>\n<td class='tbl1'>
										<a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;pagenum=1&amp;id=".$mdata3['link_id']."'><img src='".IMAGES."navigation/edit.png' alt='".$locale['444']."' title='".$locale['444']."'></a> -
										<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;pagenum=1&amp;id=".$mdata3['link_id']."'><img src='".IMAGES."navigation/delete.png' alt='".$locale['445']."' title='".$locale['445']."'></a>
									</td>\n
								</tr>\n";
								$k++;
							}
						}
						$j++;
					}
				}
				$i++;
			}
		} else {
			echo "<center>".$locale['446']."<br /><br /></center>";
		}
		echo "</td>\n
	</tr>\n
</table>\n";
closetable();

require_once DESIGNS."templates/footer.php";
?>