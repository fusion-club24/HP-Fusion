<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: bbcode_rights_a.php
| Author: Luben Kirov (Sharky)
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
require_once DESIGNS."templates/admin_header.php";

if (!checkrights("BBR") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

include LOCALE.LOCALESET."admin/bbcode_rights.php";

opentable($locale['bbp_admin1']);

echo "<div align='center'><strong>".$locale['bbp_title']."</strong></div>";

if(!isset($_POST['edit_group']) AND !isset($_POST['edit_user'])){
    echo"<div align ='center'>";
 if (!isset($_POST['search_users']) || !isset($_POST['search_criteria'])) {
		echo "<form name='searchform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
		echo "<table cellpadding='0' cellspacing='0' width='450' class='center'>\n";
		echo "<tr>\n<td align='center' class='tbl'>".$locale['bbp_411']."<br /><br />\n";
		echo "<input type='text' name='search_criteria' class='textbox' style='width:300px' />\n</td>\n";
		echo "</tr>\n<tr>\n<td align='center' class='tbl'>\n";
		echo "<label><input type='radio' name='search_type' value='user_name' checked='checked' />".$locale['bbp_413']."</label>\n";
		echo "<label><input type='radio' name='search_type' value='user_id' />".$locale['bbp_412']."</label></td>\n";
		echo "</tr>\n<tr>\n<td align='center' class='tbl'><input type='submit' name='search_users' value='".$locale['bbp_414']."' class='button' /></td>\n";
		echo "</tr>\n</table>\n</form>\n";
	} elseif (isset($_POST['search_users']) && isset($_POST['search_criteria'])) {
		$mysql_search = ""; 
		if ($_POST['search_type'] == "user_id" && isnum($_POST['search_criteria'])) {
			$mysql_search .= "user_id='".$_POST['search_criteria']."' ";
		} elseif ($_POST['search_type'] == "user_name" && preg_match("/^[-0-9A-Z_@\s]+$/i", $_POST['search_criteria'])) {
			$mysql_search .= "user_name LIKE '".$_POST['search_criteria']."%' ";
		}
		if ($mysql_search) {
			$result = dbquery("SELECT user_id, user_name FROM ".DB_USERS." WHERE ".$mysql_search." ORDER BY user_name");
		}
		if (isset($result) && dbrows($result)) {
			echo "<form name='add_users_p' method='post' action='".FUSION_SELF.$aidlink."'>\n";
			echo "<table cellpadding='0' cellspacing='1' width='450' class='tbl-border center'>\n";
			$i = 0; $users = "";
			while ($data = dbarray($result)) {
				$row_color = ($i % 2 == 0 ? "tbl1" : "tbl2"); $i++;
				$users .= "<tr>\n<td class='$row_color'><label><input type='radio' name='user_id' value='".$data['user_id']."' /> ".$data['user_name']."</label></td>\n</tr>";
			}
			if ($i > 0) {
						echo "<tr>\n<td class='tbl2'><strong>".$locale['bbp_413']."</strong></td>\n</tr>\n";
				echo $users."<tr>\n<td align='center' class='tbl'>\n";
				echo "<br />\n<input type='submit' name='edit_user' value='".$locale['bbp_417']."' class='button' />\n";
				echo "</td>\n</tr>\n";
			} else {
				echo "<tr>\n<td align='center' class='tbl'>".$locale['bbp_418']."<br /><br />\n";
				echo "<a href='".FUSION_SELF.$aidlink."'>".$locale['bbp_419']."</a>\n</td>\n</tr>\n";
			}
			echo "</table>\n</form>\n";
		} else {
			echo "<table cellpadding='0' cellspacing='1' width='450' class='tbl-border center'>\n";
			echo "<tr>\n<td align='center' class='tbl'>".$locale['bbp_418']."<br /><br />\n";
			echo "<a href='".FUSION_SELF.$aidlink."'>".$locale['bbp_419']."</a>\n</td>\n</tr>\n</table>\n";
		}
	}
	echo"</div>";
closetable();
opentable($locale['bbp_admin2']);
    echo "<form name='editgroup' method='post' action='".FUSION_SELF.$aidlink."'>\n";
    echo"<div align ='center'>".$locale['bbp_gr']." <select name='grp'>
    <option value='100'>".$locale['bbp_gu']."</option>
    <option value='101'>".$locale['bbp_me']."</option>
    <option value='102'>".$locale['bbp_ad']."</option>
    <option value='103'>".$locale['bbp_sa']."</option>";
    $grps = dbquery("SELECT * FROM ".DB_USER_GROUPS." ORDER BY group_name");
    while ($data = dbarray($grps)) {
    echo"<option value='".$data['group_id']."'>".$data['group_name']."</option>";
    }    
    echo"</select><br>
    <input type='submit' name='edit_group' value='".$locale['bbp_417']."' class='button' />
    </div></form>";
closetable();
}

if(isset($_POST['edit_group'])){
  $grps = dbquery("SELECT * FROM ".DB_BBCODES."");
  $i = 0;
  echo"<div align ='center'>";
  echo "<form name='savegroup' method='post' action='".FUSION_SELF.$aidlink."'>\n";
  echo"<table width='400px'><tr><td>".$locale['bbp_420']."</td><td>".$locale['bbp_421']."</td><td>".$locale['bbp_422']."</td></tr>";
    while ($data = dbarray($grps)) {
      $result = dbquery("SELECT * FROM ".DB_BBCODES_RIGHTSGROUPS." WHERE bbcode='".$data['bbcode_name']."' AND gid='".$_POST['grp']."'");
      echo"<tr><td><input type='hidden' name='n".$i."' value='".$data['bbcode_name']."'>".$data['bbcode_name']."</td>";
        If(dbrows($result) == 0){
          echo"<td><input type='hidden' name='a".$i."' value='0'><input type='radio' name='c".$i."' value='1' /></td><td><input type='radio' name='c".$i."' value='0' checked /></td></tr>";
        }else{
          $data1 = dbarray($result);
          If($data1['perm'] == 1){
            echo"<td><input type='hidden' name='a".$i."' value='1'><input type='radio' name='c".$i."' value='1' checked /></td><td><input type='radio' name='c".$i."' value='0' /></td></tr>";
          }else{
            echo"<td><input type='hidden' name='a".$i."' value='1'><input type='radio' name='c".$i."' value='1' /></td><td><input type='radio' name='c".$i."' value='0' checked /></td></tr>";
          }
          
        }
      $i++;    
    }
  echo"</table><input type='hidden' name='id' value='".$_POST['grp']."'><input type='hidden' name='nbb' value='".$i."'><input type='submit' name='save_group' value='".$locale['bbp_423']."' class='button' /></form></div>";
closetable();
}

if(isset($_POST['edit_user'])){
  $grps = dbquery("SELECT * FROM ".DB_BBCODES."");
  $i = 0;
  echo"<div align ='center'>";
  echo "<form name='savegroup' method='post' action='".FUSION_SELF.$aidlink."'>\n";
  echo"<table width='400px'><tr><td>".$locale['bbp_420']."</td><td>".$locale['bbp_421']."</td><td>".$locale['bbp_422']."</td></tr>";
    while ($data = dbarray($grps)) {
      $result = dbquery("SELECT * FROM ".DB_BBCODES_RIGHTSUSERS." WHERE bbcode='".$data['bbcode_name']."' AND uid='".$_POST['user_id']."'");
      echo"<tr><td><input type='hidden' name='n".$i."' value='".$data['bbcode_name']."'>".$data['bbcode_name']."</td>";
        If(dbrows($result) == 0){
          echo"<td><input type='hidden' name='a".$i."' value='0'><input type='radio' name='c".$i."' value='1' /></td><td><input type='radio' name='c".$i."' value='0' checked /></td></tr>";
        }else{
          $data1 = dbarray($result);
          If($data1['perm'] == 1){
            echo"<td><input type='hidden' name='a".$i."' value='1'><input type='radio' name='c".$i."' value='1' checked /></td><td><input type='radio' name='c".$i."' value='0' /></td></tr>";
          }else{
            echo"<td><input type='hidden' name='a".$i."' value='1'><input type='radio' name='c".$i."' value='1' /></td><td><input type='radio' name='c".$i."' value='0' checked /></td></tr>";
          }
          
        }
      $i++;    
    }
  echo"</table><input type='hidden' name='id' value='".$_POST['user_id']."'><input type='hidden' name='nbb' value='".$i."'><input type='submit' name='save_user' value='".$locale['bbp_423']."' class='button' /></form></div>";
closetable();
}

if(isset($_POST['save_user'])){
  $i=0;
  while ($i<$_POST['nbb']) {
    if($_POST['a'.$i] == 1){
    $result = dbquery("UPDATE ".DB_BBCODES_RIGHTSUSERS." SET perm='".$_POST['c'.$i]."' WHERE uid = '".$_POST['id']."' AND bbcode = '".$_POST['n'.$i]."'");
    }else{
    $result = dbquery("INSERT INTO ".DB_BBCODES_RIGHTSUSERS." (uid, perm, bbcode) VALUES ('".$_POST['id']."', '".$_POST['c'.$i]."', '".$_POST['n'.$i]."')");
    }
    $i++;
  }
}

if(isset($_POST['save_group'])){
  $i=0;
  while ($i<$_POST['nbb']) {
    if($_POST['a'.$i] == 1){
    $result = dbquery("UPDATE ".DB_BBCODES_RIGHTSGROUPS." SET perm='".$_POST['c'.$i]."' WHERE gid = '".$_POST['id']."' AND bbcode = '".$_POST['n'.$i]."'");
    }else{
    $result = dbquery("INSERT INTO ".DB_BBCODES_RIGHTSGROUPS." (gid, perm, bbcode) VALUES ('".$_POST['id']."', '".$_POST['c'.$i]."', '".$_POST['n'.$i]."')");
    }
    $i++;
  }
}

require_once DESIGNS."templates/footer.php";
?>

