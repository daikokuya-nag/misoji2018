<?php
/********************
年齢認証ページの画像の読み込み Version 1.0
PHP5
2016 Feb. 20 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbPageParam5C.php';
	require_once dirname(__FILE__) . '/../../db/dbLinkExchange5C.php';

	require_once dirname(__FILE__) . '/../../bld/bldImgList5C.php';

	$branchNo = $_REQUEST['branchNo'];	/* 店No */

	$extList  = bldImgList5C::bldSeqList($branchNo);

	$extS1 = explode(',' ,$extList['extList']);
	$idxMax = count($extS1);
	for($idx=0 ;$idx<$idxMax ;$idx++) {
		$ext1 = $extS1[$idx];
		$extS2 = explode(':' ,$ext1);
		$extS3[$extS2[0]] = $extS2[1];
	}

	$imgVals  = readImg($branchNo ,$extS3);		// 画像
	$linkVals = readLinkExchange($branchNo ,$extS3);	// 相互リンク

	$ret['extList'] = $extList['extList'];
	$ret['img'    ] = $imgVals['img'];
	$ret['str'    ] = $imgVals['str'];
	$ret['link'   ] = $linkVals;

	print json_encode($ret);


	function readImg($branchNo ,$extList) {

		$pageParam = new dbPageParam5C();
		$pageVals  = $pageParam->readAll($branchNo ,'AGE_AUTH' ,'TOP');

		// 文言
		$strStr1 = $pageVals['pageVal'][0][dbPageParam5C::FLD_STR1];
		if(strlen($strStr1) >= 1) {
			$ret['str'] = $strStr1;
		} else {
			$ret['str'] = $pageVals['pageVal'][0][dbPageParam5C::FLD_VALUE4];
		}

		// 画像
		$ret['img']['imgVal'] = $pageVals['pageVal'][0];

		//画像ファイルの有無
		$imgNo = $pageVals['pageVal'][0]['value3'];
		$fileRoot = dirname(__FILE__) . '/../../../img/1/AGE_AUTH/';
		if(isset($extList[$imgNo])) {
			$extName = $extList[$imgNo];
		} else {
			$extName = '';
		}

		$filePath = $fileRoot . $imgNo . '.' . $extName;
		if(is_file($filePath)) {
			$fileExist = '1';
		} else {
			$fileExist = '0';
		}
		$ret['img']['fileExist'] = $fileExist;

		return $ret;
	}

	function readLinkExchange($branchNo ,$extList) {

		$pageParam = new dbLinkExchange5C();
		$linkVals  = $pageParam->readAll($branchNo ,'TOP');

		$fileRoot    = dirname(__FILE__) . '/../../../img/1/AGE_AUTH/';
		$fileURLRoot = '../img/1/AGE_AUTH/';

		$ret = array();
		$vals = $linkVals['val'];
		$idxMax = $linkVals['count'];
		for($idx=0 ;$idx<$idxMax ;$idx++) {
			$link1 = $vals[$idx];

			$img1 = $link1[dbLinkExchange5C::FLD_IMG_FILE];
			if(strncasecmp($img1 ,'http' ,4) == 0) {
				$imgURL = $img1;		// リンク先の画像をそのまま使用するとき
			} else {
				$imgURL = '';
				if(isset($extList[$img1])) {
					$extName = $extList[$img1];
					$filePath = $fileRoot . $img1 . '.' . $extName;

					if(is_file($filePath)) {
						$imgURL = $fileURLRoot . $img1 . '.' . $extName;
					}
				}
			}
			$link1['imgURL'] = $imgURL;
			$ret[] = $link1;
		}

		return $ret;
	}
?>
