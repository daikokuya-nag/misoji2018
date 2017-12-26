<?php
/********************
求人内容取出し Version 1.0
PHP5
2016 Feb. 23 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbGeneral5C.php';

	$branchNo = $_REQUEST['branchNo'];	/* 店No */

	/***** 求人内容の取出し *****/
	$gene = new dbGeneral5C();
	$ret = $gene->read($branchNo ,dbGeneral5C::CATE_RECRUIT);

	print $ret['vals'][0][dbGeneral5C::FLD_STR];
?>
