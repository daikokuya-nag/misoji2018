<?php
/********************
ニュース出力 Version 1.0
PHP5
2016 Feb. 23 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbNews5C.php';
	require_once dirname(__FILE__) . '/../../bld/bldNews5C.php';
	require_once dirname(__FILE__) . '/../../sess/sess5C.php';


	$cond = sess5C::getSessCond();
	if($cond == sess5C::OWN_INTIME) {
		sess5C::updSessCond();

		$branchNo = $_POST['branchNo'];	/* 店No */
		$newsNo   = $_POST['newsNo'  ];	/* ニュースNo */

		$ret['tag'] = '';
		$ret['id' ] = '';

		$news = new dbNews5C();

		$news->setVal(dbNews5C::FLD_TITLE    ,$_POST['title'   ]);		/* タイトル */
		$news->setVal(dbNews5C::FLD_CATE     ,$_POST['cate'    ]);		/* 種類 */
		$news->setVal(dbNews5C::FLD_CONTENT  ,$_POST['content' ]);		/* 本文 */
		$news->setVal(dbNews5C::FLD_DATE     ,$_POST['newsDate']);		/* 記事日付 */
		$news->setVal(dbNews5C::FLD_TERM     ,$_POST['newsTerm']);		/* 期間 */
		$news->setVal(dbNews5C::FLD_DISP_BEG ,$_POST['dispBeg' ]);		/* 表示開始日時 */


		$news->setVal(dbNews5C::FLD_BEG_DATE ,$_POST['begDate' ]);		/* 記事開始日 */
		$news->setVal(dbNews5C::FLD_END_DATE ,$_POST['endDate' ]);		/* 記事終了日 */

		$news->setVal(dbNews5C::FLD_BG_COLOR ,$_POST['bgColor' ]);		/* 背景色 */

		if(intval($newsNo) == dbNews5C::NEW_REC) {
			/*** 新規 ***/
			$targetNewsNo = $news->add($branchNo);
		} else {
			/*** 既存 ***/
			$news->upd($branchNo ,$newsNo);
			$targetNewsNo = $newsNo;
		}

		$news1 = $news->get($branchNo ,$targetNewsNo);
		$newsTag = new bldNews5C();
			//print 'news NO:' . $targetNewsNo;

		$newsNoExp = explode(' ' ,$targetNewsNo);
		$dateExp = explode('-' ,$newsNoExp[0]);
		if(count($dateExp) <= 1) {
			$dateExp = explode('/' ,$newsNoExp[0]);
		}

		$timeExp = explode(':' ,$newsNoExp[1]);
		$newsID = $dateExp[0] . $dateExp[1] . $dateExp[2] . '_' . $timeExp[0] . $timeExp[1] . $timeExp[2];
		$newsTDClass = 'td' . $newsID;

		$showDiv = 'class="' . $newsTDClass . '" style="display:none;"';
		$reshowTag = $newsTag->bld1($news1 ,false ,$showDiv);

		$ret['tag'] = $reshowTag;
		$ret['id' ] = $newsID;
	}
	$ret['SESSCOND'] = $cond;

	print json_encode($ret);
?>
