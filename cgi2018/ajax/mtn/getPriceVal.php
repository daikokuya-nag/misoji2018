<?php
/********************
料金表取出し Version 1.0
PHP5
2016 Feb. 23 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbPageParam5C.php';
	require_once dirname(__FILE__) . '/../../bld/bldImgList5C.php';

	$branchNo = $_REQUEST['branchNo'];	/* 店No */
	$pageParam = new dbPageParam5C();

	$extList = bldImgList5C::bldSeqList($branchNo);
	$ret['extList'] = $extList['extList'];

	$pageVals = $pageParam->readAll($branchNo ,'SYSTEM' ,'CONTENT');

	// 文言
	$strStr1 = $pageVals['pageVal'][0][dbPageParam5C::FLD_STR1];
	if(strlen($strStr1) >= 1) {
		$ret['str'] = $strStr1;
	} else {
		$ret['str'] = $pageVals['pageVal'][0][dbPageParam5C::FLD_VALUE4];
	}

	// 画像
	$ret['img']['pageVal'] = $pageVals['pageVal'][0];

	//画像ファイルの有無
	$imgNo = $pageVals['pageVal'][0][dbPageParam5C::FLD_VALUE3];

	$extS1 = explode(',' ,$extList['extList']);
	$idxMax = count($extS1);
	for($idx=0 ;$idx<$idxMax ;$idx++) {
		$ext1 = $extS1[$idx];
		$extS2 = explode(':' ,$ext1);
		$extS3[$extS2[0]] = $extS2[1];
	}

	$fileExist = '';
	$fileRoot = dirname(__FILE__) . '/../../../img/1/SYSTEM/';

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
	$ret['img']['fileExist'] = $fileExist;

	print json_encode($ret);
?>
