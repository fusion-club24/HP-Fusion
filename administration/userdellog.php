<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: userdellog.php
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
require_once "../maincore.php";

if (!checkrights("UDL") || !defined("iAUTH") || !isset($_GET['aid']) || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

require_once DESIGNS."templates/admin_header.php";
include LOCALE.LOCALESET."admin/userdellog.php";

opentable($locale['100']);

$limit = 10;

$rows = dbcount("(userdellog_id)", DB_USER_DELLOG);
if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
if ((isset($_GET['disable']) && isnum($_GET['disable']))) {
			$result = dbquery("DELETE FROM ".DB_USER_DELLOG." WHERE userdellog_user_id='".$_GET['disable']."'");
	redirect(FUSION_SELF.$aidlink);	
}	
$logsql = dbquery("SELECT * FROM ".DB_USER_DELLOG." ORDER BY userdellog_id DESC LIMIT ".$_GET['rowstart'].",".$limit);
	if (dbrows($logsql)) {
    echo "<table class='tbl center' cellpadding='1' cellspacing='1' width='100%' align='center'>";
        echo "<tr>";
                echo"<td class='tbl2' style='text-align:center;white-space:nowrap;width:10%;'><b>".$locale['102']."</b></td>";
                echo"<td class='tbl2' style='text-align:center;white-space:nowrap;width:10%;'><b>".$locale['103']."</b></td>";
                echo"<td class='tbl2' style='text-align:center;white-space:nowrap;width:15%;'><b>".$locale['104']."</b></td>";
                echo"<td class='tbl2' style='text-align:center;white-space:nowrap;width:25%;'><b>".$locale['105']."</b></td>";
                echo"<td class='tbl2' style='text-align:center;white-space:nowrap;width:20%;'><b>".$locale['106']."</b></td>";
				echo"<td class='tbl2' style='text-align:center;white-space:nowrap;width:5%;'><b>".$locale['101']."</b></td>";
          echo"</tr>";
    while($lodata = dbarray($logsql)) {
        echo "<tr>";
               echo"<td class='tbl1' style='text-align:center;'>".$lodata['userdellog_user_id']."</td>";
               echo"<td class='tbl1' style='text-align:center;'>".$lodata['userdellog_user_name']."</td>";
			   echo"<td class='tbl1' style='text-align:center;'>".$lodata['userdellog_user_email']."</td>";
			   echo"<td class='tbl1' style='text-align:center;'>".$lodata['userdellog_user_ip']."</td>";
               echo"<td class='tbl1' style='text-align:center;'>".showdate("longdate", $lodata['userdellog_timestamp'])."</td>";
			   echo"<td class='tbl1' style='text-align:center;'><a onclick = \"return confirm('".$locale['313']."');\" href='".FUSION_SELF.$aidlink."&amp;disable=".$lodata['userdellog_user_id']."'><img src='".IMAGES."no.png' alt='".$locale['312']."' title='".$locale['312']."' style='border:0px;' /></a>\n</td>";
              echo"</tr>";
    }
    echo "</table>";
} else {
	echo "<table class='tbl center' cellpadding='1' cellspacing='1' width='100%' align='center'>";
    echo $locale['107'];
	echo "</table>";
}
if ($rows > $limit) { echo "<div align='center' style='margin-top:5px;'>".makepagenav($_GET['rowstart'], $limit, $rows, 3, FUSION_SELF."?aid=".$_GET['aid']."&amp;")."</div>"; }

closetable();

require_once DESIGNS."templates/footer.php";
?>