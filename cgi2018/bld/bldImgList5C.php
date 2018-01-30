<?php
/*************************
画像リスト Version 1.0
PHP5
2016 Feb. 20 ver 1.0
*************************/

	require_once dirname(__FILE__) . '/../db/dbImage5C.php';
	require_once dirname(__FILE__) . '/../dateTime5C.php';
	require_once dirname(__FILE__) . '/../strings5C.php';
	require_once dirname(__FILE__) . '/../logFile5C.php';

class bldImgList5C {

	const DEFAULT_IMG_WIDTH = 250;	// 画像表示の最大幅

	/********************
	画像リストタグ構築
	パラメータ：店No
	　　　　　　プロファイルデータ
	戻り値　　：タグ
	********************/
	function bldSeqList($branchNo ,$imgClass=null) {

		$img = new dbImage5C();

		$imgList = $img->readShowable($branchNo ,$imgClass);

		$imgVals = $imgList['imgInfo'];
		$listMax = $imgList['count'  ];

		$ret['data'   ] = '';
		$ret['extList'] = '';
		for($idx=0 ;$idx<$listMax ;$idx++) {
			$img1 = $imgVals[$idx];
			$imgTag = self::bldSeqList1($img1 ,$branchNo);

			if(strlen($imgTag) >= 1) {
				$ret['data'] = $ret['data'] . self::bldSeqList1($img1 ,$branchNo);

				$imgNo = $img1[dbImage5C::FLD_IMG_NO ];
				$ext   = $img1[dbImage5C::FLD_ORG_EXT];
				$ret['extList'] = $ret['extList'] . $imgNo . ':' . $ext . ',';
			}
		}
		$ret['extList'] = $ret['extList'] . '0:0';

		return $ret;
	}


	/********************
	1件分の画像リストタグ構築
	パラメータ：リスト
	戻り値　　：タグ

	<div id="sort-1014" class="prof1 ui-state-default">
		<div class="tnOut"><img src="../photo/tnNP.jpg" width="110" height="145"></div>
		<div class="profItem profName">ともよ</div>
		<div class="profItem profEdit"><input type="button" value="編集" class="toEdit" onclick="editProf(1014)"></div>
	</div>
	********************/
	function bldSeqList1($img1 ,$branchNo) {

		$imgNo = $img1[dbImage5C::FLD_IMG_NO ];
		$class = $img1[dbImage5C::FLD_CLASS  ];
		$ext   = $img1[dbImage5C::FLD_ORG_EXT];

		$width  = $img1[dbImage5C::FLD_WIDTH ];
		$height = $img1[dbImage5C::FLD_HEIGHT];

		if($width > self::DEFAULT_IMG_WIDTH) {
			$showWidth = self::DEFAULT_IMG_WIDTH;
		} else {
			$showWidth = $width;
		}

		$photoFile = '../img/' . $branchNo . '/' . $class . '/' . $imgNo . '.' . $ext;

		//ファイルの有無
		$filePath = dirname(__FILE__) . '/../' . $photoFile;
		if(is_file($filePath)) {
			$btnID   = 'seleImg' . $imgNo;
			$seleStr = '<input type="radio" name="seleImg" id="' . $btnID . '" value="' . $imgNo . '">';

			$imgStr   = '<img src="' . $photoFile . '" width="' . $showWidth . '">';		// height="145"
			$labelStr = '<label for="' . $btnID . '">' . $imgStr . '</label>';

			$ret = '<div id="img-' . $imgNo . '" class="img ui-state-default">' .
				'<div class="tnOut">' . $seleStr  . '</div>' .
				'<div class="tnOut">' . $labelStr . '</div>' .
				'</div>';
		} else {
			$ret = '';
		}

		return $ret;
	}
}
?>
