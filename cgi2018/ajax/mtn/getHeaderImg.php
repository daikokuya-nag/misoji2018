<?php
/********************
ヘッダのパラメータの読み込み Version 1.0
PHP5
2016 Feb. 20 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbPageParam5C.php';
	require_once dirname(__FILE__) . '/../../bld/bldImgList5C.php';

	$branchNo = $_REQUEST['branchNo'];	/* 店No */

	$pageParam = new dbPageParam5C();

	$extList   = bldImgList5C::bldSeqList($branchNo);
	$topVals   = readTop($pageParam ,$branchNo ,$extList['extList']);
	$otherVals = readOther($pageParam ,$branchNo ,$extList['extList']);

	$ret['extList'] = $extList['extList'];
	$ret['top'    ] = $topVals;
	$ret['other'  ] = $otherVals;

	print json_encode($ret);


	function readTop($pageParam ,$branchNo ,$extList) {
		$pageVals = $pageParam->readAll($branchNo ,'TOP' ,'HEADER');

		$ret['pageVal'] = $pageVals['pageVal'][0];

		//画像ファイルの有無
		$imgNoList = $pageVals['pageVal'][0]['value3'];
		$imgNo = array('' ,'' ,'' ,'');
		if(strlen($imgNoList) >= 1) {
			$imgNo = explode(':' ,$imgNoList);
		}

		$extS1 = explode(',' ,$extList);
		$idxMax = count($extS1);
		for($idx=0 ;$idx<$idxMax ;$idx++) {
			$ext1 = $extS1[$idx];
			$extS2 = explode(':' ,$ext1);
			$extS3[$extS2[0]] = $extS2[1];
		}

		$fileExist = '';
		$fileRoot = dirname(__FILE__) . '/../../../img/1/HEADER/';
		$imgNoMax = count($imgNo);
		for($idx=0 ;$idx<$imgNoMax ;$idx++) {
			$img1 = $imgNo[$idx];

			if(isset($extS3[$img1])) {
				$extName = $extS3[$img1];
			} else {
				$extName = '';
			}

			$filePath = $fileRoot . $img1 . '.' . $extName;
			if(is_file($filePath)) {
				$fileExist = $fileExist . '1:';
			} else {
				$fileExist = $fileExist . '0:';
			}
		}
		$ret['fileExist'] = $fileExist;

		return $ret;
	}


	function readOther($pageParam ,$branchNo ,$extList) {

		$pageVals = $pageParam->readAll($branchNo ,'OTHER' ,'HEADER');

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
		$fileRoot = dirname(__FILE__) . '/../../../img/1/HEADER/';

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
