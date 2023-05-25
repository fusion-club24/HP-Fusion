<?php
$locale['uf_score'] = "ScoreSystem";
$locale['uf_score_desc'] = "Display the score of Users";
if (isset($locale['pfss_units'])) {
	$locale['uf_score_1'] = "Current ".$locale['pfss_units']."";
} else {
	$locale['uf_score_1'] = "Current Score";
}
?>