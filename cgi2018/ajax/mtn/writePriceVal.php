<?php
/********************
料金表出力 Version 1.0
PHP5
2016 Feb. 23 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbGeneral5C.php';
	require_once dirname(__FILE__) . '/../../sess/sess5C.php';

	$branchNo = $_POST['branchNo'];	/* 店No */
	$str      = $_POST['str'     ];	/* 料金内容 */

	$cond = sess5C::getSessCond();
	if($cond == sess5C::OWN_INTIME) {
		/***** 料金の出力 *****/
		$gene = new dbGeneral5C();

		$exist = $gene->isExist($branchNo ,dbGeneral5C::CATE_SYSTEM);
		if($exist) {
			$ret = $gene->upd($branchNo ,dbGeneral5C::CATE_SYSTEM ,$str);
		} else {
			$ret = $gene->add($branchNo ,dbGeneral5C::CATE_SYSTEM ,$str);
		}
	}

	$ret['SESSCOND'] = $cond;
	print json_encode($ret);
?>
