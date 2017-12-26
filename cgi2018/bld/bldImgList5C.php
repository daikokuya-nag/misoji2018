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

	var $img;

	/********************
	画像リストタグ構築
	パラメータ：店No
	　　　　　　プロファイルデータ
	戻り値　　：タグ
	********************/
	function bldSeqList($branchNo) {

		$img = new dbImage5C($branchNo);

		$imgList = $img->readShowable();

		$imgVals = $imgList['imgInfo'];
		$listMax = $imgList['count'  ];

		$ret['data'   ] = '';
		$ret['extList'] = '';
		for($idx=0 ;$idx<$listMax ;$idx++) {
			$img1 = $imgVals[$idx];
			$ret['data'] = $ret['data'] . self::bldSeqList1($img1 ,$branchNo);

			$imgNo = $img1[dbImage5C::FLD_IMG_NO ];
			$ext   = $img1[dbImage5C::FLD_ORG_EXT];

			$ret['extList'] = $ret['extList'] . $imgNo . ':' . $ext . ',';
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

		$seleStr = '<input type="radio" name="seleImg" id="seleImg' . $imgNo . '" value="' . $imgNo . '">';

		$photoFile = '../img/' . $branchNo . '/' . $class . '/' . $imgNo . '.' . $ext;
		$imgStr = '<img src="' . $photoFile . '" width="110" height="145">';

		$ret = '<div id="img-' . $imgNo . '" class="img ui-state-default">' .
			'<div class="tnOut">' . $seleStr . '</div>' .
			'<div class="tnOut">' . $imgStr  . '</div>' .
			'</div>';


		return $ret;
	}
}
?>
