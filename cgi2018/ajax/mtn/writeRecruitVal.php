<?php
/********************
求人内容出力 Version 1.0
PHP5
2016 Feb. 23 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbPageParam5C.php';
	require_once dirname(__FILE__) . '/../../sess/sess5C.php';

	$branchNo = $_POST['branchNo'];	/* 店No */

	$cond = sess5C::getSessCond();
	if($cond == sess5C::OWN_INTIME) {
		/***** 求人内容の出力 *****/
		$pageParam = new dbPageParam5C();
		$handle = $pageParam->getHandle();

		//value3 表示する画像No
		$value3 = $_POST['img'];

		//str1 表示する文言
		$str1 = $_POST['str'];

		$pageParam->setVal(dbPageParam5C::FLD_VALUE1 ,'');
		$pageParam->setVal(dbPageParam5C::FLD_VALUE2 ,'');
		$pageParam->setVal(dbPageParam5C::FLD_VALUE3 ,$value3);
		$pageParam->setVal(dbPageParam5C::FLD_VALUE4 ,'');
		$pageParam->setVal(dbPageParam5C::FLD_VALUE5 ,'');
		$pageParam->setVal(dbPageParam5C::FLD_STR1   ,$str1);

		$pageID = 'RECRUIT';
		$dbKey  = 'CONTENT';

		$cond = dbPageParam5C::FLD_BRANCH_NO . '=' . $branchNo                  . ' and ' .
				dbPageParam5C::FLD_PAGE_ID   . '=' . $handle->setQuote($pageID) . ' and ' .
				dbPageParam5C::FLD_OBJ       . '=' . $handle->setQuote($dbKey);
		$exist = $handle->existRec(dbPageParam5C::TABLE_NAME ,$cond);

		if($exist) {
			//更新
			$pageParam->upd($branchNo ,$pageID ,$dbKey);
		} else {
			//レコード追加
			$pageParam->add($branchNo ,$pageID ,$dbKey);
		}
	}

	$ret['SESSCOND'] = $cond;
	print json_encode($ret);
?>
