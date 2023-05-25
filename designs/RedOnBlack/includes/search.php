<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
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
	$locale['search'] = str_replace($locale['global_200'], "", $locale['global_202']);
		  echo"</div>\n
	
		 <div id='searchb'>
		  <form name='searchform' method='get' action='".BASEDIR."search.php?stype=all'>\n
          <input type='hidden' name='stype' value='all' />\n
		 <input type='text' class='searchbox' onblur='if (this.value == \"\") {this.value = \"".$locale['search']."....\";}' onfocus='if (this.value == \"".$locale['search']."....\") {this.value = \"\";}' id='stext' name='stext' value='".$locale['search']."....' />\n
		  
		  </form>
		  
		  </div>
		  
		  </div>\n"; 
		  ?>