<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2003 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/

if ($_GET['submit']) {
	$save_pref = true;
}

$_include_path = '../include/';
require($_include_path.'vitals.inc.php');
$_section[0][0] = _AT('tools');
$_section[0][1] = 'tools/';
$_section[1][0] = _AT('course_default_prefs');


require($_include_path.'header.inc.php');

if (!$_SESSION['is_admin']) {
	$errors[]=AT_ERROR_PREFS_NO_ACCESS;
	print_errors($errors);
	exit;
}

echo '<h2>';
if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 2) {
	echo '<a href="tools/" class="hide"><img src="images/icons/default/square-large-tools.gif"  class="menuimageh2" width="42" height="40" border="0" vspace="2" alt="" /></a> ';
}
if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 1) {
	echo '<a href="tools/" class="hide">'._AT('tools').'</a>';
}
echo '</h2>';

echo '<h3>';
if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 2) {
	echo '&nbsp;<img src="images/icons/default/edit-preferences-large.gif"  class="menuimageh3" width="42" height="38" alt="" /> ';
}
if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 1) {
	echo _AT('course_default_prefs');
}
echo '</h3>';

?>
<p><?php echo _AT('save_default_prefs_how'); ?></p>
<p><a href="tools/preferences.php?save=4"><?php echo _AT('save_default_prefs'); ?></a>.</p>
<?php
	require($_include_path.'footer.inc.php');
?>
