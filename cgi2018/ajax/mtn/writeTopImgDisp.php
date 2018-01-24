<?php
/********************
TOPページのパラメータの出力 Version 1.0
PHP5
2016 Feb. 29 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbPageParam5C.php';
	require_once dirname(__FILE__) . '/../../sess/sess5C.php';

	$cond = sess5C::getSessCond();
	if($cond == sess5C::OWN_INTIME) {
		sess5C::updSessCond();

		$pageParam = new dbPageParam5C();
		setTopPageParam($pageParam ,'system'  ,'useTopSystemImg');
		setTopPageParam($pageParam ,'recruit' ,'useTopRecruitImg');
	}

	$ret['SESSCOND'] = $cond;
	print json_encode($ret);


	function setTopPageParam($pageParam ,$obj ,$useObj) {
		$branchNo = $_POST['branchNo'];

		//value2 表示/非表示
		if(isset($_POST[$useObj])) {
			$value2 = 'U';
		} else {
			$value2 = 'N';
		}

		//value3 表示する画像No
		$value3 = $_POST[$obj];

		$handle = $pageParam->getHandle();

		$pageParam->setVal(dbPageParam5C::FLD_VALUE1 ,'');
		$pageParam->setVal(dbPageParam5C::FLD_VALUE2 ,$value2);
		$pageParam->setVal(dbPageParam5C::FLD_VALUE3 ,$value3);
		$pageParam->setVal(dbPageParam5C::FLD_VALUE4 ,'');
		$pageParam->setVal(dbPageParam5C::FLD_VALUE5 ,'');

		if(strcmp($obj ,'system') == 0) {
			$dbKey = 'SYSTEM';
		}
		if(strcmp($obj ,'recruit') == 0) {
			$dbKey = 'RECRUIT';
		}

		$cond = dbPageParam5C::FLD_BRANCH_NO . '=' . $branchNo                . ' and ' .
				dbPageParam5C::FLD_PAGE_ID   . '=' . $handle->setQuote('TOP') . ' and ' .
				dbPageParam5C::FLD_OBJ       . '=' . $handle->setQuote($dbKey);
		$exist = $handle->existRec(dbPageParam5C::TABLE_NAME ,$cond);

		if($exist) {
			//更新
			$pageParam->upd($branchNo ,'TOP' ,$dbKey);
		} else {
			//レコード追加
			$pageParam->add($branchNo ,'TOP' ,$dbKey);
		}
	}
?>
