<?php
/********************
TOPページの画像の読み込み Version 1.0
PHP5
2016 Feb. 20 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbPageParam5C.php';
	require_once dirname(__FILE__) . '/../../bld/bldImgList5C.php';

	$branchNo = $_REQUEST['branchNo'];	/* 店No */

	$pageParam = new dbPageParam5C();

	$extList     = bldImgList5C::bldSeqList($branchNo);
	$recruitVals = readTopImg($pageParam ,$branchNo ,$extList['extList'] ,'RECRUIT');
	$systemVals  = readTopImg($pageParam ,$branchNo ,$extList['extList'] ,'SYSTEM');
	$areaVals    = readTopVal($pageParam ,$branchNo ,'AREA');

	$ret['extList'] = $extList['extList'];
	$ret['recruit'] = $recruitVals;
	$ret['system' ] = $systemVals;
	$ret['area'   ] = $areaVals;

	print json_encode($ret);


	function readTopImg($pageParam ,$branchNo ,$extList ,$obj) {

		$pageVals = $pageParam->readAll($branchNo ,'TOP' ,$obj);

		$ret['pageVal'] = $pageVals['pageVal'][0];

		//画像ファイルの有無
		$imgNo = $pageVals['pageVal'][0]['value3'];

		$extS1 = explode(',' ,$extList);
		$idxMax = count($extS1);
		for($idx=0 ;$idx<$idxMax ;$idx++) {
			$ext1 = $extS1[$idx];
			$extS2 = explode(':' ,$ext1);
			$extS3[$extS2[0]] = $extS2[1];
		}

		$fileExist = '';
		$fileRoot = dirname(__FILE__) . '/../../../img/1/TOP/';

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

	function readTopVal($pageParam ,$branchNo ,$obj) {

		$pageVals = $pageParam->readAll($branchNo ,'TOP' ,$obj);
		$ret['pageVal'] = $pageVals['pageVal'][0];
		$ret['areaBGColor' ] = $pageVals['pageVal'][0]['value1'];
		$ret['titleBGColor'] = $pageVals['pageVal'][0]['value2'];
		$ret['titleColor'  ] = $pageVals['pageVal'][0]['value3'];

		return $ret;
	}

?>
