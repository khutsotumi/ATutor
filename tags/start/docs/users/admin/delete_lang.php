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

$section = 'users';
$_include_path = '../../include/';
require($_include_path.'vitals.inc.php');
if (!$_SESSION['s_is_super_admin']) {
	exit;
}
if ($_POST['cancel']) {
	Header('Location: language.php?f='.urlencode_feedback(AT_FEEDBACK_CANCELLED));
	exit;
}

if ($_POST['submit']) {
	$sql = "DELETE FROM ".TABLE_PREFIX."lang2 WHERE lang='$_POST[delete_lang]'";
	$result = mysql_query($sql, $db);

	$sql	= "UPDATE ".TABLE_PREFIX."members SET language='".DEFAULT_LANGUAGE."' WHERE language='$_POST[delete_lang]'";
	mysql_query($sql, $db);

	cache_purge('system_langs', 'system_langs');
	Header('Location: language.php?f='.urlencode_feedback(AT_FEEDBACK_LANG_DELETED));
	exit;
}

if($_GET['delete_lang']){
		require($_include_path.'admin_html/header.inc.php');
?>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="delete_lang" value="<?php echo $_GET['delete_lang'] ?>">
<?php

		echo '<h2>'._AT('lang_manager').'</h2>';
		echo '<h3>'._AT('delete_language').'</h3>';
		$warnings[]=array(AT_WARNING_DELETE_LANG, $_GET['delete_lang']);
		print_warnings($warnings);

	?>
	<input type="submit" name="submit" value="<?php echo _AT('delete'); ?>" class="button"> - <input type="submit" name="cancel" class="button" value=" <?php echo _AT('cancel'); ?> " />
	</form>
<?php
		require($_include_path.'footer.inc.php');
}
?>
