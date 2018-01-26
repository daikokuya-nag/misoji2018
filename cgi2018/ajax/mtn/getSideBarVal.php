<?php
/********************
サイドバーの読み込み Version 1.0
PHP5
2016 Feb. 20 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbPageParam5C.php';
	require_once dirname(__FILE__) . '/../../bld/bldImgList5C.php';

	$branchNo = $_REQUEST['branchNo'];	/* 店No */

	$pageParam = new dbPageParam5C();

	// サイドバーの位置の指定があればその値、なければ右
	if(isset($_REQUEST['pos'])) {
		$pos = $_REQUEST['pos'];
	} else {
		$pos = 'R';
	}

	$extList = bldImgList5C::bldSeqList($branchNo);
	$img1    = readSideBarImg($pageParam ,$branchNo ,$extList['extList'] ,$pos ,'IMG1');
	$img2    = readSideBarImg($pageParam ,$branchNo ,$extList['extList'] ,$pos ,'IMG2');

	$ret['extList'] = $extList['extList'];
	$ret['img1'] = $img1;
	$ret['img2'] = $img2;

	print json_encode($ret);


	function readSideBarImg($pageParam ,$branchNo ,$extList ,$pos ,$obj) {

		$pageVals = $pageParam->readAll($branchNo ,'SIDEBAR_' . $pos ,$obj);

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
		$fileRoot = dirname(__FILE__) . '/../../../img/1/SIDEBAR/';

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
