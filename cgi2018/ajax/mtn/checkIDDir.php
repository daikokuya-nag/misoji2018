<?php
/********************
識別子の使用チェック Version 1.0
PHP4
2014 Sep. 13 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbProfile5C.php';

	$branchNo = $_REQUEST['branchNo'];
	$dir      = $_REQUEST['profDir'];

	$prof = new dbProfile5C($branchNo);
	$ret = $prof->existProfileDir($dir);

	print $ret;
?>
