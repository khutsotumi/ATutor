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

define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
session_write_close();
//authenticate(USER_CLIENT, USER_ADMIN);
require('include/functions.inc.php');

	$myPrefs = getPrefs($_SESSION['login']);

	cleanUp();

	howManyMessages(&$topMsgNum, &$bottomMsgNum);

	if ($_REQUEST['set']) {
		if (isset($_GET['set'])) {
			if ($_GET['set'] == $_POST['message']) {
				$message = $_POST['tempField'];
			} else {
				$message = $_POST['message'];
			}
		} else {
			$message = $_POST['message'];
		}
		//$message = $_POST['message'];
		postMessage($_SESSION['login'], $message, $topMsgNum, $bottomMsgNum);
	} else if ($_REQUEST['firstLoginFlag'] > 0) {
        postMessage(_AC('chat_system'), _AC('chat_user_logged_in', $_SESSION['login']), $topMsgNum, $bottomMsgNum);
    }

require(AT_INCLUDE_PATH.'pub/header.inc.php');
	if ($myPrefs['refresh'] != 'manual') {
?>
	<script language="javascript" type="text/javascript">
	<!--
		setTimeout("reDisplay()", <?php echo $myPrefs['refresh'] * 1000; ?>);
		function reDisplay() {
			window.location.href = "<?php echo $_SERVER[PHP_SELF]; ?>";
		}
	//-->
	</script>
<?php
	} /* end if */
?>
<a name="messages"></a>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
<tr>
	<th align="left" class="box"><?php echo _AC('chat_messages') ?></th>
</tr>
</table>

<?php
	$min = 1;
	if ($topMsgNum - 10 > 1) {
		$min = $topMsgNum - 10;
	}
	if ($myPrefs['onlyNewFlag'] > 0) {
		$min = $myPrefs['lastRead'] +1;
	}
	if ($min <= $topMsgNum) {
	   echo '<table border="0" cellpadding="2" cellspacing="0" width="95%" class="box2">';
	} else {
	   echo '<p>'._AC('chat_no_new_messages').'</p>';
	}

	if ($myPrefs['newestFirstFlag'] > 0) {
        for ($i = $topMsgNum; $i >= $min; $i--) {
            showMessage($i, $myPrefs);
        }
    } else {
        for ($i = $min; $i <= $topMsgNum ; $i++) {
            showMessage($i, $myPrefs);
        }
    }

    if ($min <= $topMsgNum) {
		echo '</table>';
	}

	echo '<table width="100%" border="0" cellpadding="5" cellspacing="0">';
	echo '<tr>';
	echo '<td align="right">';
    if ($myPrefs['navigationAidFlag'] > 0) {	
		echo '<label accesskey="m" for="messageQ"><a href="display.php#messages" id="messageQ" onfocus="this.className=\'highlight\'" onblur="this.className=\'\'" title="'._AC('chat_jump_to_message').' Alt-m">'._AC('chat_jump_to_message').'</a></label> | ';
	}

	echo '<label accesskey="r" for="refreshQ"><a id="refreshQ" href="display.php" target="display" onfocus="this.className=\'highlight\'" onblur="this.className=\'\'" title="'._AC('chat_refresh_message').' Alt-r">'._AC('chat_refresh_message').'</a></label>';
	echo '</td></tr>';
	echo '</table>';
    
    echo '<br /><br />';
    if ($myPrefs['refresh'] == 'manual') {
		echo '<table width="100%" border="0" cellpadding="5" cellspacing="0">
           <tr><th align="left" class="box">'._AC('chat_compose_message').'</th></tr></table>';
		echo '<p class="light">';

		echo '<form action="display.php" target="display" name="f1" method="post" onSubmit="return checkForm();">
			   <input type="hidden" name="set" value="1" />
			   <label accesskey="c" for="message"><input type="text" maxlength="200" size="50" id="message" name="message" value="" class="input" title="Alt-c" onfocus="this.className=\'input highlight\'" onblur="this.className=\'input\'" /></label>
			   <input type="submit" name="submit" value="'._AC('chat_send').'" class="submit" title="'._AC('chat_send').'" onfocus="this.className=\'submit highlight\'" onblur="this.className=\'submit\'" />';

		echo '</form></p>';
		echo '<script language="javascript"><!--
			   function checkForm() {
				   if (document.f1.message.value == "" || !document.f1.message.value) return false;
				   return true;
			   }';
		echo '//--></script>';
    } else {
        if ($myPrefs['bingFlag'] > 0 && $topMsgNum > $myPrefs['lastRead']) {
            echo '<embed src="bings/chime.wav" loop="false" autoplay="true" play="true" hidden="true" width="1" height="1" />';
        }
	} 

    $myPrefs['lastRead']	= $topMsgNum;
    $myPrefs['lastChecked']	= $topMsgNum;
    writePrefs($myPrefs, $_SESSION['login']);
	require(AT_INCLUDE_PATH.'pub/footer.inc.php');
?>