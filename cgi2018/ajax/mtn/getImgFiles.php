<?php
/********************
画像リストの読み込み Version 1.0
PHP5
2016 Feb. 20 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../sql5C.php';
	require_once dirname(__FILE__) . '/../../bld/bldImgList5C.php';

	$branchNo = $_REQUEST['branchNo'];	/* 店No */

	/*** 表示順 ***/
	$ret['SEQ'] = bldImgList5C::bldSeqList($branchNo);

	print json_encode($ret);
?>
