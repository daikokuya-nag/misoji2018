<?php
/********************
ニュース出力 Version 1.0
PHP5
2016 Feb. 23 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbNews5C.php';
	require_once dirname(__FILE__) . '/../../bld/bldNews5C.php';

	$branchNo = $_POST['branchNo'];	/* 店No */
	$newsNo   = $_POST['newsNo'  ];	/* ニュースNo */

	$title    = $_POST['title'   ];	/* タイトル */
	$newsDate = $_POST['newsDate'];	/* 記事日付 */
	$newsTerm = $_POST['newsTerm'];	/* 期間 */

	$begDate  = $_POST['begDate' ];	/* 記事開始日 */
	$endDate  = $_POST['endDate' ];	/* 記事終了日 */

	$digest   = $_POST['digest'  ];	/* 概要 */
	$content  = $_POST['content' ];	/* 本文 */
	$cate     = $_POST['cate'    ];	/* 種類 */

	$dispBeg  = $_POST['dispBeg' ];	/* 表示開始日時 */

	$ret['tag'] = '';
	$ret['id' ] = '';

	$news = new dbNews5C();
	if(intval($newsNo) == dbNews5C::NEW_REC) {
		/*** 新規 ***/
		$targetNewsNo = $news->add($branchNo ,$title ,$cate ,$digest ,$content ,$newsDate ,dbNews5C::DISP_OFF ,$newsTerm ,$dispBeg ,$begDate ,$endDate);
	} else {
		/*** 既存 ***/
		$news->upd($branchNo ,$newsNo ,$title ,$cate ,$digest ,$content ,$newsDate ,$newsTerm ,$dispBeg ,$begDate ,$endDate);
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

	print json_encode($ret);
?>
