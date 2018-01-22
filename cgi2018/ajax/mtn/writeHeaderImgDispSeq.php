<?php
/********************
ヘッダパラメータの出力 Version 1.0
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

		setTopPageParam($pageParam);
		setOtherPageParam($pageParam);
	}

	$ret['SESSCOND'] = $cond;
	print json_encode($ret);


	function setTopPageParam($pageParam) {

		$branchNo = $_POST['branchNo'];

		//value1 表示順
		$seq = $_POST['headerTopImg'];

		$value1 = $seq[0] . ':' . $seq[1] . ':' . $seq[2] . ':' . $seq[3];

		//value2 表示/非表示
		if(isset($_POST['useHeaderTopImgA'])) {
			$useA = 'U';
		} else {
			$useA = 'N';
		}
		if(isset($_POST['useHeaderTopImgB'])) {
			$useB = 'U';
		} else {
			$useB = 'N';
		}
		if(isset($_POST['useHeaderTopImgC'])) {
			$useC = 'U';
		} else {
			$useC = 'N';
		}
		if(isset($_POST['useHeaderTopImgD'])) {
			$useD = 'U';
		} else {
			$useD = 'N';
		}

		$value2 = $useA . ':' . $useB . ':' . $useC . ':' . $useD;

		//value3 表示する画像No
		$imgNoA = $_POST['imgNoA'];
		$imgNoB = $_POST['imgNoB'];
		$imgNoC = $_POST['imgNoC'];
		$imgNoD = $_POST['imgNoD'];

		$value3 = $imgNoA . ':' . $imgNoB . ':' . $imgNoC . ':' . $imgNoD;

		$handle = $pageParam->getHandle();

		$pageParam->setVal(dbPageParam5C::FLD_VALUE1 ,$value1);
		$pageParam->setVal(dbPageParam5C::FLD_VALUE2 ,$value2);
		$pageParam->setVal(dbPageParam5C::FLD_VALUE3 ,$value3);
		$pageParam->setVal(dbPageParam5C::FLD_VALUE4 ,'');
		$pageParam->setVal(dbPageParam5C::FLD_VALUE5 ,'');

		$cond = dbPageParam5C::FLD_BRANCH_NO . '=' . $branchNo                . ' and ' .
				dbPageParam5C::FLD_PAGE_ID   . '=' . $handle->setQuote('TOP') . ' and ' .
				dbPageParam5C::FLD_OBJ       . '=' . $handle->setQuote('HEADER');

		$exist = $handle->existRec(dbPageParam5C::TABLE_NAME ,$cond);

		if($exist) {
			//更新
			$pageParam->upd($branchNo ,'TOP' ,'HEADER');
		} else {
			//レコード追加
			$pageParam->add($branchNo ,'TOP' ,'HEADER');
		}
	}


	function setOtherPageParam($pageParam) {
		$branchNo = $_POST['branchNo'];

		//value3 表示する画像No
		$value3 = $_POST['imgOther'];

		$handle = $pageParam->getHandle();

		$pageParam->setVal(dbPageParam5C::FLD_VALUE1 ,'');
		$pageParam->setVal(dbPageParam5C::FLD_VALUE2 ,'');
		$pageParam->setVal(dbPageParam5C::FLD_VALUE3 ,$value3);
		$pageParam->setVal(dbPageParam5C::FLD_VALUE4 ,'');
		$pageParam->setVal(dbPageParam5C::FLD_VALUE5 ,'');

		$cond = dbPageParam5C::FLD_BRANCH_NO . '=' . $branchNo                  . ' and ' .
				dbPageParam5C::FLD_PAGE_ID   . '=' . $handle->setQuote('OTHER') . ' and ' .
				dbPageParam5C::FLD_OBJ       . '=' . $handle->setQuote('HEADER');

		$exist = $handle->existRec(dbPageParam5C::TABLE_NAME ,$cond);

		if($exist) {
			//更新
			$pageParam->upd($branchNo ,'OTHER' ,'HEADER');
		} else {
			//レコード追加
			$pageParam->add($branchNo ,'OTHER' ,'HEADER');
		}
	}
?>
