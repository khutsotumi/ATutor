<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2004 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id: announcements.inc.php,v 1.12 2004/02/18 16:00:04 joel Exp $

if (!defined('AT_INCLUDE_PATH')) { exit; }

	echo '<small class="spacer">'.AT_date(_AT('announcement_date_format')).'</small>';

	echo '<h1><img src="images/icons/default/square-large-home.gif" class="menuimageh1" border="0" alt="" />'.$_SESSION['course_title'];
	if (!$_SESSION['is_admin'] && !$_SESSION['enroll']) {
		echo '<small> - ';
		echo '<a href="enroll.php?course='.$_SESSION[course_id].'">'._AT('enroll').'</a></small>';
	}
	echo '</h1>';
	/* help for content pages */
	if (($_SESSION['is_admin']) && ($_SESSION['prefs'][PREF_EDIT] == 1)) {
		if ($_SESSION['prefs'][PREF_MENU]==1){
			$help[] = AT_HELP_ADD_ANNOUNCEMENT2;
		} else {
			$help[] = AT_HELP_ADD_ANNOUNCEMENT;
		}
		$help[] = AT_HELP_ADD_TOP_PAGE;


	}
	if ($_SESSION['prefs'][PREF_EDIT] == 1) {
		print_help($help);
	}
	if (($_SESSION['is_admin'] == 1) && ($_SESSION['prefs']['PREF_EDIT'] == 1)) {
		print_editorlg( _AT('add_announcement'), 'editor/add_news.php' , _AT('add_top_page') ,'editor/edit_content.php');
	}

	$sql	= "SELECT COUNT(*) AS cnt FROM ".TABLE_PREFIX."news WHERE course_id=$_SESSION[course_id]";
	$result = mysql_query($sql, $db);

	if ($row = mysql_fetch_assoc($result)) {		
		$num_results = $row['cnt'];
		$results_per_page = NUM_ANNOUNCEMENTS;
		$num_pages = ceil($num_results / $results_per_page);
		$page = intval($_GET['p']);
		if (!$page) {
			$page = 1;
		}	
		$count = (($page-1) * $results_per_page) + 1;

		$offset = ($page-1)*$results_per_page;

		$sql = "SELECT N.* FROM ".TABLE_PREFIX."news N WHERE N.course_id=$_SESSION[course_id] ORDER BY date DESC LIMIT $offset, $results_per_page";
	}

	$result = mysql_query($sql, $db);
	if (mysql_num_rows($result) == 0) {
		echo '<i>'._AT('no_announcements').'</i>';
	} else {
		$news = array();
		while ($row = mysql_fetch_assoc($result)) {
			/* this can't be cached because it called _AT */

			$news[$row['news_id']] = array(
							'date'		=> AT_date(	_AT('announcement_date_format'), 
													$row['date'], 
													AT_DATE_MYSQL_DATETIME),
 							'title'		=> AT_print($row['title'], 'news.title'),
							'body'		=> AT_print($row['body'], 'news.body', $row['formatting']));
					

		}

		echo '<table border="0" cellspacing="1" cellpadding="0" width="98%" summary="">';
		
		foreach ($news as $news_id => $news_item) {
			echo '<tr>';
			echo '<td>';
			echo '<br /><h4>'.$news_item['title'];
			print_editor( _AT('edit'), 'editor/edit_news.php?aid='.$news_id,
							_AT('delete'), 'editor/delete_news.php?aid='.$news_id);
			echo '</h4>';

			echo $news_item['body'];

			echo '<br /><small class="date">'._AT('posted').' '.$news_item['date'].'</small>';
			echo '</td>';
			echo '</tr>';
			echo '<tr><td class="row3" height="1"><img src="images/clr.gif" height="1" width="1" alt="" /></td></tr>';
		}
		echo '</table><br />';

		echo _AT('page').': | ';
		for ($i=1; $i<=$num_pages; $i++) {
			if ($i == $page) {
				echo '<strong>'.$i.'</strong>';
			} else {
				echo '<a href="'.$_SERVER['PHP_SELF'].'?p='.$i.'">'.$i.'</a>';
			}
			echo ' | ';
		}
	}


?>