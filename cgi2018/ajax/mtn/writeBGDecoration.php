<?php
/********************
装飾用データの出力 Version 1.0
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

		//value1 背景指定
		$value1 = $_POST['useBG'];

		//value2 背景色
		$value2 = $_POST['color'];

		//value3 背景画像No
		$value3 = $_POST['imgNo'];

		//value4 日時
		$value4 = dateTime5C::getCurrDTR();

		$handle = $pageParam->getHandle();

		$pageParam->setVal(dbPageParam5C::FLD_VALUE1 ,$value1);
		$pageParam->setVal(dbPageParam5C::FLD_VALUE2 ,$value2);
		$pageParam->setVal(dbPageParam5C::FLD_VALUE3 ,$value3);
		$pageParam->setVal(dbPageParam5C::FLD_VALUE4 ,$value4);
		$pageParam->setVal(dbPageParam5C::FLD_VALUE5 ,'');

		$cond = dbPageParam5C::FLD_BRANCH_NO . '=' . $branchNo                . ' and ' .
				dbPageParam5C::FLD_PAGE_ID   . '=' . $handle->setQuote('ALL') . ' and ' .
				dbPageParam5C::FLD_OBJ       . '=' . $handle->setQuote('DECO');

		$exist = $handle->existRec(dbPageParam5C::TABLE_NAME ,$cond);

		if($exist) {
			//更新
			$pageParam->upd($branchNo ,'ALL' ,'DECO');
		} else {
			//レコード追加
			$pageParam->add($branchNo ,'ALL' ,'DECO');
		}
	}
?>
