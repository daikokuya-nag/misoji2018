<?php
/********************
ログアウト Version 1.0
PHP4
2014 Sep. 13 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../sess/sess5C.php';

	$sessID = session_id();
	sess5C::delSessCond($sessID);

	unset($_SESSION['BRANCHNO']);
	unset($_SESSION['MTN']);
?>
