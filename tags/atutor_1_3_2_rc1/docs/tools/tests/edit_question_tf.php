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

	define('AT_INCLUDE_PATH', '../../include/');
	require(AT_INCLUDE_PATH.'vitals.inc.php');


	$tt = $_POST['tt'];
	$tid = intval($_GET['tid']);
	if ($tid == 0){
		$tid = intval($_POST['tid']);
	}

	$qid = intval($_GET['qid']);
	if ($qid == 0){
		$qid = intval($_POST['qid']);
	}


	$_section[0][0] = _AT('tools');
	$_section[0][1] = 'tools/';
	$_section[1][0] = _AT('test_manager');
	$_section[1][1] = 'tools/tests/';
	$_section[2][0] = _AT('questions');
	$_section[2][1] = 'tools/tests/questions.php?tid='.$tid;
	$_section[3][0] = _AT('edit_question');

	if ($_POST['submit']) {
	
		if ($_POST['question'] == ''){
		$errors[]=AT_ERRORS_QUESTION_EMPTY;

		}
		if (!$errors) {
		$_POST['required'] = intval($_POST['required']);
		$_POST['feedback'] = trim($_POST['feedback']);
		$_POST['question'] = trim($_POST['question']);
		$_POST['tid']	   = intval($_POST['tid']);
		$_POST['qid']	   = intval($_POST['qid']);
		$_POST['weight']   = intval($_POST['weight']);
		$_POST['answer']   = intval($_POST['answer']);

		$sql	= "UPDATE ".TABLE_PREFIX."tests_questions SET	weight=$_POST[weight],
			required=$_POST[required],
			feedback='$_POST[feedback]',
			question='$_POST[question]',
			answer_0={$_POST[answer]}

			WHERE question_id=$_POST[qid] AND test_id=$_POST[tid] AND course_id=$_SESSION[course_id]";

		$result	= mysql_query($sql, $db);
		Header('Location: questions.php?tid='.$_POST['tid'].SEP.'tt='.$tt.SEP.'f='.urlencode_feedback(AT_FEEDBACK_QUESTION_UPDATED));
		exit;
		}
	}

	require(AT_INCLUDE_PATH.'header.inc.php');



echo '<h2>';
	if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 2) {
		echo '<a href="tools/" class="hide"><img src="images/icons/default/square-large-tools.gif"  class="menuimageh2" border="0" vspace="2" width="42" height="40" alt="" /></a>';
	}
	if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 1) {
		echo ' <a href="tools/" class="hide">'._AT('tools').'</a>';
	}
echo '</h2>';

echo '<h3>';
	if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 2) {
		echo '&nbsp;<img src="images/icons/default/test-manager-large.gif"  class="menuimageh3" width="42" height="38" alt="" /> ';
	}
	if ($_SESSION['prefs'][PREF_CONTENT_ICONS] != 1) {
		echo '<a href="tools/tests/">'._AT('test_manager').'</a>';
	}
echo '</h3>';

$_GET['tt'] = urldecode($_GET['tt']);
echo '<h3><img src="images/clr.gif" height="1" width="54" alt="" /><a href="tools/tests/questions.php?tid='.$_GET['tid'].SEP.'tt='.$_GET['tt'].'">'._AT('questions_for').' '.$_GET['tt'].'</a></h3>';

?>

<h4><img src="images/clr.gif" height="1" width="54" alt="" /><?php echo _AT('edit_tf_question'); ?> <?php echo $_GET['tt']; ?></h4>


<?php

	if (!$_POST['submit']) {
		$sql	= "SELECT * FROM ".TABLE_PREFIX."tests_questions WHERE question_id=$qid AND test_id=$tid AND course_id=$_SESSION[course_id] AND type=2";
		$result	= mysql_query($sql, $db);

		if (!($row = mysql_fetch_array($result))){
			$errors[]=AT_ERROR_QUESTION_NOT_FOUND;
			print_errors($errors);
			require (AT_INCLUDE_PATH.'footer.inc.php');
			exit;
		}

		$_POST	= $row;
	}

	if ($_POST['required'] == 1) {
		$req_yes = ' checked="checked"';
	} else {
		$req_no  = ' checked="checked"';
	}

	if ($_POST['answer'] == '') {
		if ($_POST['answer_0'] == 1) {
			$ans_yes = ' checked="checked"';
		} else {
			$ans_no  = ' checked="checked"';
		}
	} else {
		if ($_POST['answer'] == 1) {
			$ans_yes = ' checked="checked"';
		} else {
			$ans_no  = ' checked="checked"';
		}
	}

print_errors($errors);

?>
<form action="tools/tests/edit_question_tf.php" method="post" name="form">
<input type="hidden" name="tid" value="<?php echo $tid; ?>" />
<input type="hidden" name="qid" value="<?php echo $qid; ?>" />
<input type="hidden" name="tt" value="<?php echo $_GET['tt']; ?>" />
<input type="hidden" name="required" value="1" />
<table cellspacing="1" cellpadding="0" border="0" class="bodyline" summary="" align="center">
<tr>
	<th colspan="2" class="left"><?php echo _AT('edit_tf_question1'); ?></th>
</tr>

<!--tr>
	<td class="row1" align="right"><b>Required:</b></td>
	<td class="row1"><input type="radio" name="required" value="1" id="req1"<?php echo $req_yes; ?> /><label for="req1">yes</label>, <input type="radio" name="required" value="0" id="req2"<?php echo $req_no; ?> /><label for="req2">no</label></td>
</tr>
<tr><td height="1" class="row2" colspan="2"></td></tr-->
<tr>
	<td class="row1" align="right"><label for="weight"><b><?php echo _AT('weight'); ?>:</b></label></td>
	<td class="row1"><input type="text" name="weight" id="weight" class="formfieldR" size="2" maxlength="2" value="<?php echo $_POST['weight']; ?>" /></td>
</tr>
<tr><td height="1" class="row2" colspan="2"></td></tr>
<tr>
	<td class="row1" align="right" valign="top"><label for="feedback"><b><?php echo _AT('feedback'); ?>:</b></label></td>
	<td class="row1"><textarea id="feedback" cols="50" rows="3" name="feedback" class="formfield"><?php 
		echo htmlspecialchars(stripslashes($_POST['feedback'])); ?></textarea></td>
</tr>
<tr><td height="1" class="row2" colspan="2"></td></tr>
<tr>
	<td class="row1" align="right" valign="top"><label for="ques"><b><?php echo _AT('statement'); ?>:</b></label></td>
	<td class="row1"><textarea id="ques" cols="50" rows="6" name="question" class="formfield"><?php 
		echo htmlspecialchars(stripslashes($_POST['question'])); ?></textarea></td>
</tr>
<tr><td height="1" class="row2" colspan="2"></td></tr>
<tr>
	<td class="row1" align="right"><b><?php echo _AT('answer'); ?>:</b></td>
	<td class="row1"><input type="radio" name="answer" value="1" id="answer1"<?php echo $ans_yes; ?> /><label for="answer1"><?php echo _AT('true'); ?></label>, <input type="radio" name="answer" value="2" id="answer2"<?php echo $ans_no; ?> /><label for="answer2"><?php echo _AT('false'); ?></label></td>
</tr>
<tr><td height="1" class="row2" colspan="2"></td></tr>
<tr><td height="1" class="row2" colspan="2"></td></tr>
<tr>
	<td class="row1" colspan="2" align="center"><input type="submit" value="<?php echo _AT('save_test_question'); ?> Alt-s" class="button" name="submit" accesskey="s"/></td>
</tr>
</table>
<br />
<br />
</form>

<?php
	require (AT_INCLUDE_PATH.'footer.inc.php');
?>
