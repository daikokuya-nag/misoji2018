<?php
/********************
セッション状態取得 Version 1.0
PHP5
2016 Feb. 20 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../sess/sess5C.php';

	$sessID = session_id();
	$cond = sess5C::getSessCond($sessID);

	print $cond;
?>
