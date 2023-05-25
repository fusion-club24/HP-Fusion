<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: bbcode_rights_b.php
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
if (!defined("IN_FUSION")) { die("Access Denied"); }

function bbcodes($b){
  $fbb = dbquery("SELECT * FROM ".DB_BBCODES ."");
  while ($data = dbarray($fbb)) {
    if (bbcodep($b) == 1 ){
         return 1;   
    }
  }
  
  return 0;
}

function bbcodep($bb){
  global $userdata;
  if(iGUEST){
    	$bb0 = dbquery("SELECT * FROM ".DB_BBCODES_RIGHTSGROUPS." WHERE gid = '100' AND bbcode = '".$bb."'");
   	if (dbrows($bb0)) {
      $data = dbarray($bb0);
        if ($data['perm'] == 1){
          return 1;
        }
     }
    return 0;
  }else{
   $result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$userdata['user_id']."'");
   $user_data = dbarray($result);
	$bb2 = dbquery("SELECT * FROM ".DB_BBCODES_RIGHTSGROUPS." WHERE gid = '".$user_data['user_level']."' AND bbcode = '".$bb."'");
   	if (dbrows($bb2)) {
      $data = dbarray($bb2);
        if ($data['perm'] == 1){
          return 1;
        }
     }
     
  $bb3 = dbquery("SELECT * FROM ".DB_BBCODES_RIGHTSUSERS." WHERE uid = '".$user_data['user_id']."' AND bbcode = '".$bb."'");
   	if (dbrows($bb3)) {
      $data = dbarray($bb3);
        if ($data['perm'] == 1){
          return 1;
        }
     }
     
  
  
	$user_groups = (strpos($user_data['user_groups'], ".") == 0 ? explode(".", substr($user_data['user_groups'], 1)) : explode(".", $user_data['user_groups']));
		for ($i = 0; $i < count($user_groups); $i++) {
				$bb1 = dbquery("SELECT * FROM ".DB_BBCODES_RIGHTSGROUPS." WHERE gid = '".$user_groups[$i]."' AND bbcode = '".$bb."'");
   	if (dbrows($bb1)) {
      $data = dbarray($bb1);
        if ($data['perm'] == 1){
          return 1;
        }
     }
		}
     
     return 0;
  }
}
		
?>

