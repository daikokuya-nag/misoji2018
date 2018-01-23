<?php
/********************
装飾用データの読み込み Version 1.0
PHP5
2016 Feb. 20 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbPageParam5C.php';
	require_once dirname(__FILE__) . '/../../bld/bldImgList5C.php';

	$branchNo = $_REQUEST['branchNo'];	/* 店No */

	$pageParam = new dbPageParam5C();

	$extList = bldImgList5C::bldSeqList($branchNo);
	$imgVals = readList($pageParam ,$branchNo ,$extList['extList']);

	$ret['extList'] = $extList['extList'];
	$ret['vals'   ] = $imgVals;

	print json_encode($ret);


	function readList($pageParam ,$branchNo ,$extList) {
		$pageVals = $pageParam->readAll($branchNo ,'ALL' ,'DECO');

		$ret['pageVal'] = $pageVals['pageVal'][0];

		$decoVal = $pageVals['pageVal'][0];

		//背景画像ファイル
		$imgNo = $decoVal['value3'];

		$extS1 = explode(',' ,$extList);
		$idxMax = count($extS1);
		for($idx=0 ;$idx<$idxMax ;$idx++) {
			$ext1 = $extS1[$idx];
			$extS2 = explode(':' ,$ext1);
			$extS3[$extS2[0]] = $extS2[1];
		}

		$fileExist = '';
		$fileRoot = dirname(__FILE__) . '/../../../img/1/DECO/';

		if(isset($extS3[$imgNo])) {
			$extName = $extS3[$imgNo];
		} else {
			$extName = '';
		}

		$filePath = $fileRoot . $imgNo . '.' . $extName;

		if(is_file($filePath)) {
			$fileExist = $fileExist . '1';
		} else {
			$fileExist = $fileExist . '0';
		}
		$ret['fileExist'] = $fileExist;

		return $ret;
	}
?>
