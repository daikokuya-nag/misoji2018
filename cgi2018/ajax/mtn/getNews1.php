<?php
/********************
ニュース取出し Version 1.0
PHP5
2016 Feb. 22 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbNews5C.php';

	$branchNo = $_REQUEST['branchNo'];	/* 店No */
	$newsNo   = $_REQUEST['newsNo'];

	/***** ニュースの取出し *****/
	$news = new dbNews5C();
	$ret = $news->get($branchNo ,$newsNo);

	print json_encode($ret);
?>
