<?php
/********************
プロファイルの読み込み Version 1.0
PHP5
2016 Feb. 20 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../sql5C.php';
	require_once dirname(__FILE__) . '/../../db/dbProfile5C.php';
	require_once dirname(__FILE__) . '/../../bld/bldProfile5C.php';

	$branchNo = $_REQUEST['branchNo'];	/* 店No */

	$handle = new sql5C();

	$profile  = new dbProfile5C($branchNo ,$handle);
	$profList = new bldProfile5C();

	/*** 表示順 ***/
	$profDataA = $profile->readAll();
	$ret['SEQ'] = $profList->bldSeqList($branchNo ,$profDataA);

	/*** ニュース記事へ埋め込み ***/
	$profDataS = $profile->readShowableProf();
	$ret['FORNEWS'] = $profList->bldNewsList($branchNo ,$profDataS);

	print json_encode($ret);
?>
