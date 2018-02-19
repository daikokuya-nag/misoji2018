<?php
/********************
ニュースの背景色 Version 1.0
PHP5
2016 Feb. 29 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbPageParam5C.php';
	require_once dirname(__FILE__) . '/../../sess/sess5C.php';
	require_once dirname(__FILE__) . '/../../dateTime5C.php';

	$cond = sess5C::getSessCond();
	if($cond == sess5C::OWN_INTIME) {
		sess5C::updSessCond();

		$pageParam = new dbPageParam5C();
		writeParam($pageParam);
	}

	$ret['SESSCOND'] = $cond;
	print json_encode($ret);


	function writeParam($pageParam) {
		$branchNo = $_POST['branchNo'];

		//value2 背景色
		$value2 = $_POST['color'];

		//value4 日時
		$value4 = dateTime5C::getCurrDTR();

		$handle = $pageParam->getHandle();

		$pageParam->setVal(dbPageParam5C::FLD_VALUE1 ,'');
		$pageParam->setVal(dbPageParam5C::FLD_VALUE2 ,$value2);
		$pageParam->setVal(dbPageParam5C::FLD_VALUE3 ,'');
		$pageParam->setVal(dbPageParam5C::FLD_VALUE4 ,$value4);
		$pageParam->setVal(dbPageParam5C::FLD_VALUE5 ,'');

		$cond = dbPageParam5C::FLD_BRANCH_NO . '=' . $branchNo                 . ' and ' .
				dbPageParam5C::FLD_PAGE_ID   . '=' . $handle->setQuote('NEWS') . ' and ' .
				dbPageParam5C::FLD_OBJ       . '=' . $handle->setQuote('BG_COLOR');

		$exist = $handle->existRec(dbPageParam5C::TABLE_NAME ,$cond);

		if($exist) {
			//更新
			$pageParam->upd($branchNo ,'NEWS' ,'BG_COLOR');
		} else {
			//レコード追加
			$pageParam->add($branchNo ,'NEWS' ,'BG_COLOR');
		}
	}
?>
