<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: !autolink_bbcode_include.php
| Author: Wooya
| Edited: slawekneo
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

	if (!function_exists('autolink_run')) {
		function bbcode_off($text, $part) {
			if ($part == 1) {
				$text = str_replace("[", " &#91;", $text);
				$text = str_replace("]", "&#93; ", $text);
			} elseif ($part == 2) {
				$text = preg_replace('^<a href="(.*?)" target="_blank" title="autolink">(.*?)</a>^si', '\1', $text);
				$text = str_replace(" &#91;", "&#91;", $text);
				$text = str_replace("&#93; ", "&#93;", $text);
			}
			return $text;
		}

		function autolink_callbackPreCode($matches) {
			return '[code]'.bbcode_off($matches[1], 1).'[/code]';
		}

		function autolink_callbackPostCode($matches) {
			return '[code]'.bbcode_off($matches[1], 2).'[/code]';
		}

		function autolink_callbackPreGeshi($matches) {
			return '[geshi='.$matches[1].']'.bbcode_off($matches[2], '1').'[/geshi]';
		}

		function autolink_callbackPostGeshi($matches) {
			return '[geshi='.$matches[1].']'.bbcode_off($matches[2], 2).'[/geshi]';
		}

		function autolink_callbackPrePHP($matches) {
			return '[php]'.bbcode_off($matches[1], 1).'[/php]';
		}

		function autolink_callbackPostPHP($matches) {
			return '[php]'.bbcode_off($matches[1], 2).'[/php]';
		}

		function autolink_callbackURLWithProtocol($matches) {
			$len = strlen($matches[2]);
			return $matches[1].'<a href="'.$matches[2].'" target="_blank" title="autolink">'
			.trimlink($matches[2], 20)
			.($len > 30	? substr($matches[2], $len - 10, $len) : '').'</a>';
		}

		function autolink_callbackURLWithoutProtocol($matches) {
			$len = strlen($matches[2]);
			return $matches[1].'<a href="http://'.$matches[2].'" target="_blank" title="autolink">'
			.trimlink($matches[2], 20)
			.(strlen($matches[1]) > 30 ? substr($matches[2], $len - 10, $len) : '').'</a>';
		}

		function autolink_callbackMail($matches) {
			return hide_email($matches[0]);
		}

		function autolink_run($text) {
			$containsCode = strpos($text, '[code]') !== FALSE;
			$containsGeshi = strpos($text, '[geshi]') !== FALSE;
			$containsPHP = strpos($text, '[php]') !== FALSE;
			if ($containsCode) {
				$text = preg_replace_callback('#\[code\](.*?)\[/code\]#si', 'autolink_callbackPreCode', $text);
			}
			if ($containsGeshi) {
				$text = preg_replace_callback('#\[geshi=(.*?)\](.*?)\[/geshi\]#si', 'autolink_callbackPreGeshi', $text);
			}
			if ($containsPHP) {
				$text = preg_replace_callback('#\[php\](.*?)\[/php\]#si', 'autolink_callbackPrePHP', $text);
			}
			$text = str_replace(array("]", "&gt;", "[", "&lt;"), array("]&nbsp;", "&gt; ", " &nbsp;[", " &lt;"), $text);
			$text = preg_replace_callback('#(^|[\n ])((http|https|ftp|ftps)://[\w\#$%&~/.\-;:=,?@\[\]\(\)+]*)#si', 'autolink_callbackURLWithProtocol', $text);
			$text = preg_replace_callback('#(^|\s)((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]\(\)+]*)#si', 'autolink_callbackURLWithoutProtocol', $text);
			$text = preg_replace_callback('#[a-z0-9_.-]+?@[\w\-]+\.([\w\-\.]+\.)*[\w]+#si', 'autolink_callbackMail', $text);
			if ($containsCode) {
				$text = preg_replace_callback('#\[code\](.*?)\[/code\]#si', 'autolink_callbackPostCode', $text);
			}
			if ($containsGeshi) {
				$text = preg_replace_callback('#\[geshi=(.*?)\](.*?)\[/geshi\]#si', 'autolink_callbackPostGeshi', $text);
			}
			if ($containsPHP) {
				$text = preg_replace_callback('#\[php\](.*?)\[/php\]#si', 'autolink_callbackPostPHP', $text);
			}
			return str_replace(array("]&nbsp;", "&gt; ", " &nbsp;[", " &lt;"), array("]", "&gt;", "[", "&lt;"), $text);
		}
	}

	$text = autolink_run($text);
?>