<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: blocked.php
| Author: firemike
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
require_once "../../maincore.php";
require_once DESIGNS."templates/header.php";

if (!defined("IN_FUSION")) { die("Access Denied"); }

include LOCALE.LOCALESET."users_blocks.php";
	
if (isset($_GET['adm'])){
	opentable($locale['usbs_007']);
		echo $locale['usbs_008'];
	closetable();
} else {

	opentable($locale['usbs_007']);
		echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border center'>\n"
			echo "<tr>\n";
				echo "<td align='center'><img src='".INCLUDES."users_blocks/images/stop.png'></td>";
			echo "</tr><tr>";
				echo "<td align='center'>".$locale['usbs_004']."</td>";
			echo "</tr><tr>";
				echo "<td align='center'>".$locale['usbs_005']."<a href='".INCLUDES."users_blocks/block.php?blockid=".$_GET['id']."'>".$locale['usbs_006']."</a></td>";
			echo "</tr>";
		echo "</table>";

	closetable();
}

require_once DESIGNS."templates/footer.php";
?>