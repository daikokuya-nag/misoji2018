<?php
/********************
ニュースの読み込み Version 1.0
PHP5
2016 Feb. 20 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../sql5C.php';
	require_once dirname(__FILE__) . '/../../db/dbNews5C.php';
	require_once dirname(__FILE__) . '/../../bld/bldNews5C.php';

	$branchNo = $_REQUEST['branchNo'];	/* 店No */

	$handle = new sql5C();

	$newsDB   = new dbNews5C($handle);
	$newsList = new bldNews5C();

	$newsData = $newsDB->readAll($branchNo);
	$newsTag  = $newsList->bld($branchNo ,$newsData);

	print json_encode($newsTag);
?>
