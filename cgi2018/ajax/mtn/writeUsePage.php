<?php
/********************
使用ページ情報の出力 Version 1.0
PHP5
2016 Feb. 23 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../sql5C.php';
	require_once dirname(__FILE__) . '/../../db/dbPageParam5C.php';
	require_once dirname(__FILE__) . '/../../sess/sess5C.php';

	$branchNo = $_POST['branchNo'];	/* 店No */
	$pageID   = $_POST['pageID'  ];	/* 対象ページ */
	$usePage  = $_POST['usePage' ];	/* 外部/内部の指定 */
	$otherURL = $_POST['otherURL'];	/* 外部使用時のURL */

	$cond = sess5C::getSessCond();
	if($cond == sess5C::OWN_INTIME) {
		$handle  = new sql5C();
		$paramDB = new dbPageParam5C($handle);

		$paramDB->setVal(dbPageParam5C::FLD_VALUE1 ,$usePage);
		$paramDB->setVal(dbPageParam5C::FLD_VALUE2 ,$otherURL);
		$paramDB->setVal(dbPageParam5C::FLD_VALUE3 ,'');
		$paramDB->setVal(dbPageParam5C::FLD_VALUE4 ,'');
		$paramDB->setVal(dbPageParam5C::FLD_VALUE5 ,'');

		$cond = dbPageParam5C::FLD_BRANCH_NO . '=' . $branchNo . ' and ' .
				dbPageParam5C::FLD_PAGE_ID   . '=' . $handle->setQuote($pageID);
		$exist = $handle->existRec(dbPageParam5C::TABLE_NAME ,$cond);

		/***** 使用ページの出力 *****/
		if($exist) {
			$ret = $paramDB->upd($branchNo ,$pageID ,dbPageParam5C::OBJ_USE_PAGE);
		} else {
			$ret = $paramDB->add($branchNo ,$pageID ,dbPageParam5C::OBJ_USE_PAGE);
		}
	}

				//print json_encode($ret);
?>
