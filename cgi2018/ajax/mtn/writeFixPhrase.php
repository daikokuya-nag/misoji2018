<?php
/********************
定型文出力 Version 1.0
PHP5
2016 Feb. 23 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbGeneral5C.php';

	$branchNo = $_POST['branchNo' ];	/* 店No */
	$str      = $_POST['phraseStr'];	/* 文言 */

	/***** 定型文の出力 *****/
	$gene = new dbGeneral5C();

	$exist = $gene->isExist($branchNo ,dbGeneral5C::CATE_FIX_PHRASE);
	if($exist) {
		$ret = $gene->upd($branchNo ,dbGeneral5C::CATE_FIX_PHRASE ,$str);
	} else {
		$ret = $gene->add($branchNo ,dbGeneral5C::CATE_FIX_PHRASE ,$str);
	}
?>
