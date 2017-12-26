<?php
/*************************
ニュースページ再生成 Version 1.0
PHP5
2016 Mar. 3 ver 1.0
*************************/

	require_once dirname(__FILE__) . '/../db/dbNews5C.php';

	require_once dirname(__FILE__) . '/../proviTag5C.php';
	require_once dirname(__FILE__) . '/../files5C.php';
	require_once dirname(__FILE__) . '/../strings5C.php';
	require_once dirname(__FILE__) . '/../dateTime5C.php';

	require_once dirname(__FILE__) . '/../logFile5C.php';
	require_once dirname(__FILE__) . '/../common5C.php';

	require_once dirname(__FILE__) . '/htmlTemplate5C.php';

class htmlNews5C {

	const MAX_FILE_SIZE_MO = 20000;		/* 20000 */	/* 4000 */


	/***** テンプレートキーワード *****/
	/*** ダイジェストの範囲 ***/
	const DIGEST_BEG_LINE = '<!-- NEWS_DIGEST_LIST_BEG -->';	/* ニュース出力開始位置 */
	const DIGEST_END_LINE = '<!-- NEWS_DIGEST_LIST_END -->';	/* ニュース出力終了位置 */

	/*** 本文の範囲 ***/
	const MAIN_BEG_LINE = '<!-- NEWS_MAIN_LIST_BEG -->';	/* ニュース出力開始位置 */
	const MAIN_END_LINE = '<!-- NEWS_MAIN_LIST_END -->';	/* ニュース出力終了位置 */


	const KWD_NEWS_NO = '<!-- NEWS_NO -->';			/* ニュースNo */
	const KWD_DATE    = '<!-- NEWS_DATE -->';		/* ニュース日付 */
	const KWD_TERM    = '<!-- NEWS_TERM -->';		/* ニュース期間 */
	const KWD_TITLE   = '<!-- NEWS_TITLE -->';		/* ニュースタイトル */
	const KWD_DIGEST  = '<!-- NEWS_DIGEST -->';		/* ニュース概要 */
	const KWD_CONTENT = '<!-- NEWS_CONTENT -->';	/* ニュース本文 */
	const KWD_CATE    = '__NEWS_CATE__';			/* ニュース種類 */

	const KWD_DATE_YMD     = '<!-- NEWS_DATE_YMD -->';
	const KWD_BEG_DATE_YMD = '<!-- NEWS_BEG_YMD -->';
	const KWD_END_DATE_YMD = '<!-- NEWS_END_YMD -->';

	const KWD_NEWS_IN_TERM = '<!-- NEWS_IN_TERM_TAG -->';
	const KWD_NEWEST_DAY   = '<!-- NEWEST_DAY_TAG -->';

	const KWD_1ST_NEWS = '__1ST_NEWS__';				/* そのページの最初のニュース */
	const KWD_LAST_UPD = '<!-- NEWS_KWD_LAST_UPD -->';	/* 最終更新日 */

	const CURL_REPL  = 'URL';
	const CURL_ERASE = 'DEL';


	var $handle;

	var $groupNo;
	var $branchNo;


	/********************
	コンストラクタ(DB接続)
	パラメータ：-
	戻り値　　：-
	********************/
	function htmlNews5C($groupNo ,$branchNo ,$handle=null) {
		$this->__construct($groupNo ,$branchNo ,$handle);
	}

	function __construct($groupNo ,$branchNo ,$handle=null) {

		if(is_null($handle)) {
			$this->handle = new sql5C();
		} else {
			$this->handle = $handle;
		}

		$this->groupNo  = $groupNo;
		$this->branchNo = $branchNo;

		$this->vals = array();
	}

	/********************
	ダイジェストページ再出力
	パラメータ：グループNo
	　　　　　　店No
	戻り値　　：-
	********************/
	function updPre() {

		$groupNo  = $this->groupNo;
		$branchNo = $this->branchNo;

		$dt = dateTime5C::getCurrDT();
		/*** 表示ONのニュースの読み込み ***/
		$news = new dbNews5C();
		$targetNews = $news->readShowable($groupNo ,$branchNo ,$dt);

		$targetNews = $this->setDispMode($targetNews ,$dt ,$news);		/* 表示識別の設定 */


		/***** ニュースのダイジェストと本文が別ページにあるとき *****/
		$this->updM($groupNo ,$branchNo ,$targetNews ,'PC');
		$ver     = dateTime5C::getCurrDTN();
		$modDate = dateTime5C::getDate();

		$this->updD($groupNo ,$branchNo ,$targetNews ,'PC' ,0 ,$ver);

/*		$newsNum = $this->updM($groupNo ,$branchNo ,$targetNews ,'MO1'); */
		$this->updD($groupNo ,$branchNo ,$targetNews ,'MO1' ,0 ,$ver);

/**		$this->updD($groupNo ,$branchNo ,$targetNews ,'MO1' ,$newsNum ,'');

		$this->modDate($groupNo ,$branchNo ,'MO1' ,$modDate); **/

		/***** ニュースのダイジェストと本文が同一ページにあるとき *****/
		/* $this->updDM($groupNo ,$branchNo ,$targetNews ,$modDate ,'SP'); */
	}

	/********************
	表示時刻指定のニュース出力
	パラメータ：グループNo
	　　　　　　店No
	戻り値　　：-
	********************/
	function reshowNews() {

		$groupNo  = $this->groupNo;
		$branchNo = $this->branchNo;

		$dt = dateTime5C::getCurrDT();
		/*** 表示ONのニュースの読み込み ***/
		$news = new dbNews5C();
		$targetNews = $news->readShowable($groupNo ,$branchNo ,$dt);

		$targetNews = $this->setDispMode($targetNews ,$dt ,$news);		/* 表示識別の設定 */

		if($targetNews['newNews'] >= 1) {
			/***** ニュースのダイジェストと本文が別ページにあるとき *****/
			$this->updM($groupNo ,$branchNo ,$targetNews ,'PC');
			$ver     = dateTime5C::getCurrDTN();
			$modDate = dateTime5C::getDate();

			$this->updD($groupNo ,$branchNo ,$targetNews ,'PC' ,0 ,$ver);

/*			$newsNum = $this->updM($groupNo ,$branchNo ,$targetNews ,'MO1'); */

			$this->updD($groupNo ,$branchNo ,$targetNews ,'MO1' ,0 ,$ver);

	/**		$this->updD($groupNo ,$branchNo ,$targetNews ,'MO1' ,$newsNum ,'');

			$this->modDate($groupNo ,$branchNo ,'MO1' ,$modDate); **/

			/***** ニュースのダイジェストと本文が同一ページにあるとき *****/
			/* $this->updDM($groupNo ,$branchNo ,$targetNews ,$modDate ,'SP'); */
		}

		return $targetNews['newNews'];
	}


	function setDispMode($newsInfo ,$dt ,$news) {

		$groupNo  = $this->groupNo;
		$branchNo = $this->branchNo;

		$retList = array();
		$outIdx = 0;
		$newNews = 0;

		$newsList = $newsInfo['newsInfo'];
		$idxMax   = $newsInfo['count'];

		for($newsIdx=0 ;$newsIdx<$idxMax ;$newsIdx++) {
			$news1 = $newsList[$newsIdx];

			$shown  = $news1[dbNews5C::FLD_SHOWN];
			$dispDT = str_replace('-' ,dateTime5C::DATE_SEP ,$news1[dbNews5C::FLD_DISP_BEG]);

			$disp = true;
			if(strlen($dispDT) >= 1) {
				/* 出力日時があるとき */
				if(strcmp($shown ,dbNews5C::SHOWN_SHOWN) == 0) {
					/* 過去に表示済み */
					$news1[dbNews5C::FLD_TMP_DISP_MODE] = dbNews5C::DISP_MODE_DISP;
				} else {
					$diffT = dateTime5C::compareDT($dispDT ,$dt);
					if($diffT >= 0) {
						/* 出力日時経過 */
						$news1[dbNews5C::FLD_TMP_DISP_MODE] = dbNews5C::DISP_MODE_READY;
						$news->setDispMark($groupNo ,$branchNo ,$news1[dbNews5C::FLD_ADD_DT]);
						$newNews++;
					} else {
						/* 出力日時未経過 */
						$news1[dbNews5C::FLD_TMP_DISP_MODE] = dbNews5C::DISP_MODE_YET;
						$disp = false;
					}
				}
			} else {
				/* 出力日時がなければ常に出力 */
				$news1[dbNews5C::FLD_TMP_DISP_MODE] = dbNews5C::DISP_MODE_ALWAYS;
			}

			if($disp) {
				$retList[$outIdx] = $news1;
				$outIdx++;
			}
		}

		$ret['newsInfo'] = $retList;
		$ret['count']    = $outIdx;
		$ret['newNews']  = $newNews;

		return $ret;
	}



	function updFromProf($groupNo ,$branchNo ,$ver) {

		$dt = dateTime5C::getCurrDT();
		/*** 表示ONのニュースの読み込み ***/
		$news = new dbNews5C();
		$targetNews = $news->readShowable($groupNo ,$branchNo ,$dt);

		$this->updD($groupNo ,$branchNo ,$targetNews ,'PC' ,0 ,$ver);
		$this->updD($groupNo ,$branchNo ,$targetNews ,'MO1' ,0 ,$ver);
	}


	function updFromDispBeg($groupNo ,$branchNo) {

		/*** 表示ONと表示時刻経過後のニュースの読み込み ***/
		$dt = dateTime5C::getCurrDT();
		$news = new dbNews5C();
		$targetNews = $news->readShowable($groupNo ,$branchNo ,$dt);
		$ret = '';


				logFile5C::debug('出力ニュース数:' . $targetNews['count']);


		if(strcmp($targetNews['upd'] ,'upd') == 0) {
			$newsList = $targetNews['newsInfo'];
			$newsMax  = $targetNews['count'];

					/* print 'newsMax:' . $newsMax . "\n"; */
					/* print 'templataLines:' . $formatMax . "\n"; */

			for($newsCnt=0 ;$newsCnt<$newsMax ;$newsCnt++) {
				$news1 = $newsList[$newsCnt];

							/* logFile5C::debug('出力ニュースNo:' . $news1[dbNews5C::FLD_NO] . ' 表示開始日時:' . $news1[dbNews5C::FLD_DISP_BEG] . ' 表示完了:' . $news1[dbNews5C::FLD_SHOWN]); */

				/*
				if(strlen($news1[dbNews5C::FLD_DISP_BEG]) >= 1) {
					if(strcmp($news1[dbNews5C::FLD_SHOWN] ,'S') != 'S') {
						$news->updShow($groupNo ,$branchNo ,$news1[dbNews5C::FLD_NO] ,'S');
						$ret = 'upd';
					}
				}
				*/
			}

			if(strcmp($ret ,'upd') == 0) {
						logFile5C::debug('表示開始時のニュース出力');
				/***** ニュースのダイジェストと本文が別ページにあるとき *****/
				$this->updM($groupNo ,$branchNo ,$targetNews ,'PC');
				$ver     = dateTime5C::getCurrDTN();
				$modDate = dateTime5C::getDate();

				$this->updD($groupNo ,$branchNo ,$targetNews ,'PC' ,0 ,$ver);

				$this->updD($groupNo ,$branchNo ,$targetNews ,'MO1' ,0 ,$ver);

/*				$newsNum = $this->updM($groupNo ,$branchNo ,$targetNews ,'MO1');*/

				/*
				$this->updD($groupNo ,$branchNo ,$targetNews ,'MO1' ,$newsNum ,'');
				*/

				/* $this->modDate($groupNo ,$branchNo ,'MO1' ,$modDate); */


				/***** ニュースのダイジェストと本文が同一ページにあるとき *****/
				/* $this->updDM($groupNo ,$branchNo ,$targetNews ,$modDate ,'SP'); */
			}
		}

			logFile5C::debug('表示開始ニュースの有無:' . $ret);

		return $ret;
	}




	/********************
	ダイジェストページ再出力
	パラメータ：グループNo
	　　　　　　店No
	　　　　　　ニュースリスト
	　　　　　　出力デバイス
	　　　　　　出力ニュース数
	戻り値　　：-
	********************/
	function updD($groupNo ,$branchNo ,$targetNews ,$device ,$writeNews ,$ver) {

		$templateFileName = files5C::getFileName('DIGEST_TEMPLATE' ,$groupNo ,$branchNo);	/* ニュースダイジェストテンプレートファイル */
		$outFileName      = files5C::getFileName('DIGEST_OUTPUT'   ,$groupNo ,$branchNo);	/* ニュースダイジェスト出力先ファイル */

		$newsPageFileName = files5C::getFileName('NEWS_DIGEST_TO_MAIN'      ,$groupNo ,$branchNo);	/* ダイジェストから見たニュース本文ファイル */
		$digestProfDir    = files5C::getFileName('NEWS_DIGEST_TO_PROF_MAIN' ,$groupNo ,$branchNo);	/* ダイジェストから見たプロファイルページ */

		$digestPageCD = $this->getPageCharCD($device);		/* 文字コード */
		$decoMode     = $this->getDecoMode($device);		/* 装飾 */


		$templateLine = $this->read1($templateFileName[$device] ,self::DIGEST_BEG_LINE ,self::DIGEST_END_LINE);
			/* print $newsPageFileName[$device]; */
		$this->writeD($targetNews ,$templateLine ,$outFileName[$device] ,$decoMode[$device] ,$newsPageFileName[$device] ,$digestProfDir[$device] ,$digestPageCD[$device] ,$writeNews ,$ver);
	}


	/********************
	ニュースページ再出力
	パラメータ：グループNo
	　　　　　　店No
	　　　　　　ニュースリスト
	　　　　　　出力デバイス
	戻り値　　：出力ニュース件数
	********************/
	function updM($groupNo ,$branchNo ,$targetNews ,$device) {

		$templateFileName  = files5C::getFileName('NEWS_TEMPLATE'  ,$groupNo ,$branchNo);	/* ニューステンプレートファイル */
		$outFileName       = files5C::getFileName('NEWS_OUTPUT'    ,$groupNo ,$branchNo);	/* ニュース出力先ファイル */
		$temporaryFileName = files5C::getFileName('NEWS_TEMPORARY' ,$groupNo ,$branchNo);	/* ニュース一時ファイル */

		$newsProfDir       = files5C::getFileName('NEWS_MAIN_TO_PROF_MAIN' ,$groupNo ,$branchNo);	/* ニュース本文から見たプロファイルページ */

		$newsPageCD = $this->getPageCharCD($device);	/* 文字コード */
		$decoMode   = $this->getDecoMode($device);		/* 装飾 */


		$templateLine = $this->read1($templateFileName[$device] ,self::MAIN_BEG_LINE ,self::MAIN_END_LINE);

		if(strcmp($device ,'MO1') == 0) {
			$writeNews = $this->writeMTest($targetNews ,$templateLine ,$temporaryFileName[$device] ,$decoMode[$device] ,$newsProfDir[$device] ,$newsPageCD[$device] ,self::MAX_FILE_SIZE_MO);
		} else {
			$writeNews = 0;
		}

		$this->writeM($targetNews ,$templateLine ,$outFileName[$device] ,$decoMode[$device] ,$newsProfDir[$device] ,$newsPageCD[$device] ,$writeNews);

		return $writeNews;
	}


	/********************
	ダイジェスト-ニュースページ再出力
	パラメータ：グループNo
	　　　　　　店No
	　　　　　　ニュースリスト
	　　　　　　出力デバイス
	戻り値　　：-
	********************/
	function updDM($groupNo ,$branchNo ,$targetNews ,$modDate ,$device) {

		$templateFileName = files5C::getFileName('NEWS_TEMPLATE' ,$groupNo ,$branchNo);	/* ニューステンプレートファイル */
		$outFileName      = files5C::getFileName('NEWS_OUTPUT'   ,$groupNo ,$branchNo);	/* ニュース出力先ファイル */

		$newsProfDir      = files5C::getFileName('NEWS_DIGEST_TO_PROF_MAIN' ,$groupNo ,$branchNo);	/* ニュース本文から見たプロファイルページ */
		$newsPageFileName = files5C::getFileName('NEWS_DIGEST_TO_MAIN'      ,$groupNo ,$branchNo);	/* ダイジェストから見たニュース本文ファイル */

		$newsPageCD = $this->getPageCharCD($device);	/* 文字コード */
		$decoMode   = $this->getDecoMode($device);		/* 装飾 */


		$templateLine = $this->readDM($templateFileName[$device] ,self::DIGEST_BEG_LINE ,self::DIGEST_END_LINE ,self::MAIN_BEG_LINE ,self::MAIN_END_LINE);

		$this->writeDM($targetNews ,$templateLine ,$outFileName[$device] ,$decoMode[$device] ,$newsPageCD[$device] ,$newsProfDir[$device] ,$modDate);
	}


	function getPageCharCD($device) {
		$pageCD['PC' ] = 'UTF-8';		/* 'UTF-8'; */
		$pageCD['SP' ] = 'UTF-8';
		$pageCD['MO1'] = 'SJIS';

		return $pageCD;
	}


	function getDecoMode($device) {
		$decoMode['PC' ] = common5C::DECO_CSS_CLASS;
		$decoMode['SP' ] = common5C::DECO_CSS_CLASS;
		$decoMode['MO1'] = common5C::DECO_FONT;

		return $decoMode;
	}


	/********************
	テンプレートファイルの読み込み
	パラメータ：テンプレートファイル名
	　　　　　　開始位置キーワード
	　　　　　　終了位置キーワード
	戻り値　　：タグ
	********************/
	function read1($templateFile ,$begKWD ,$endKWD) {

					/* print '**********' . $templateFile . '**********'; */

		$out[0][1] = '';
		$out[1][1] = '';
		$out[2][1] = '';

		/***** テンプレートファイル読み込み *****/
		$templateList = htmlTemplate5C::read($templateFile);
		$lineMax  = $templateList['count'];
		$lineData = $templateList['line'];


		/*** 開始位置まではそのまま ***/
		$idx = 1;
		for($lineIdx=0 ;$lineIdx<$lineMax ;$lineIdx++) {
			$out[0][$idx] = $lineData[$lineIdx];
			if(strcmp($out[0][$idx] ,$begKWD) == 0) {
				$lineIdx++;
				break;
			}
			$idx++;
		}


		/*** 終了位置の直前まで ***/
		$idx = 1;
		for(;$lineIdx<$lineMax ;$lineIdx++) {
			$out[1][$idx] = $lineData[$lineIdx];
			if(strcmp($out[1][$idx] ,$endKWD) == 0) {
				$lineIdx++;
				break;
			}
			$idx++;
		}


		/*** 終了位置より後ろはそのまま ***/
		$idx = 1;
		for(;$lineIdx<$lineMax ;$lineIdx++) {
			$out[2][$idx] = $lineData[$lineIdx];
			$idx++;
		}

		return $out;
	}


	/********************
	テンプレートファイルの読み込み
	パラメータ：テンプレートファイル名
	　　　　　　ダイジェスト開始位置キーワード
	　　　　　　ダイジェスト終了位置キーワード
	　　　　　　本体開始位置キーワード
	　　　　　　本体終了位置キーワード
	戻り値　　：タグ
	********************/
	function readDM($templateFile ,$begDKWD ,$endDKWD ,$begMKWD ,$endMKWD) {

		$out[0][1] = '';
		$out[1][1] = '';
		$out[2][1] = '';
		$out[3][1] = '';
		$out[4][1] = '';

		/***** テンプレートファイル読み込み *****/
		$templateList = htmlTemplate5C::read($templateFile);
		$lineMax  = $templateList['count'];
		$lineData = $templateList['line'];

		/***** ダイジェスト *****/
		/*** ダイジェストの開始位置まではそのまま ***/
		$idx = 1;
		for($lineIdx=0 ;$lineIdx<$lineMax ;$lineIdx++) {
			$out[0][$idx] = $lineData[$lineIdx];
			if(strcmp($out[0][$idx] ,$begDKWD) == 0) {
				$lineIdx++;
				break;
			}
			$idx++;
		}

		/*** ダイジェストの終了位置の直前まで ***/
		$idx = 1;
		for(;$lineIdx<$lineMax ;$lineIdx++) {
			$out[1][$idx] = $lineData[$lineIdx];
			if(strcmp($out[1][$idx] ,$endDKWD) == 0) {
				$lineIdx++;
				break;
			}
			$idx++;
		}


		/***** ニュース本体 *****/
		/*** 本体の開始位置まではそのまま ***/
		$idx = 1;
		for(;$lineIdx<$lineMax ;$lineIdx++) {
			$out[2][$idx] = $lineData[$lineIdx];
			if(strcmp($out[2][$idx] ,$begMKWD) == 0) {
				$lineIdx++;
				break;
			}
			$idx++;
		}

		/*** 本体の終了位置の直前まで ***/
		$idx = 1;
		for(;$lineIdx<$lineMax ;$lineIdx++) {
			$out[3][$idx] = $lineData[$lineIdx];
			if(strcmp($out[3][$idx] ,$endMKWD) == 0) {
				$lineIdx++;
				break;
			}
			$idx++;
		}


		/*** 本体の終了位置より後ろはそのまま出力 ***/
		$idx = 1;
		for(;$lineIdx<$lineMax ;$lineIdx++) {
			$out[4][$idx] = $lineData[$lineIdx];
			$idx++;
		}

		return $out;
	}


	/********************
	ダイジェストの出力
	パラメータ：ニュースリスト
	　　　　　　テンプレート行リスト
	　　　　　　出力ファイル名
	　　　　　　装飾区分
	　　　　　　ニュースページのファイル名
	　　　　　　プロファイルページへのパス
	　　　　　　文字コード
	戻り値　　：-
	********************/
	function writeD($targetNews ,$templateLine ,$outFile ,$decoMode ,$newsPageFile ,$profilePagePath ,$charCD ,$writeNum ,$ver) {

		$fpO = fopen($outFile ,'w');
				/* print '出力ファイル:' . $outFile; */


		/***** 開始位置までの出力 *****/
		$lineMax = count($templateLine[0]);

		if(strlen($ver) <= 0) {
			for($lineIdx=1 ;$lineIdx<=$lineMax ;$lineIdx++) {
				fwrite($fpO ,$templateLine[0][$lineIdx] . common5C::CSRC_NL_CODE);
			}
		} else {
			for($lineIdx=1 ;$lineIdx<=$lineMax ;$lineIdx++) {
				if(strings5C::mb_existStr($templateLine[0][$lineIdx] ,PROF_RECOMM_JS_VER) >= 0) {
					$str = mb_ereg_replace(PROF_RECOMM_JS_VER ,$ver ,$templateLine[0][$lineIdx]);
					fwrite($fpO ,$str . common5C::CSRC_NL_CODE);
				} else {
					fwrite($fpO ,$templateLine[0][$lineIdx] . common5C::CSRC_NL_CODE);
				}
			}
		}


		/***** ダイジェストの出力 *****/
		$formCnt = 1;
		$format[$formCnt] = '';

		$lineMax = count($templateLine[1]);
		if(strcmp($charCD ,'SJIS') == 0) {
			for($lineIdx=1 ;$lineIdx<=$lineMax ;$lineIdx++) {
				$format[$formCnt] = mb_convert_encoding($templateLine[1][$lineIdx] ,'UTF-8' ,'SJIS');
				$formCnt++;
			}
		} else {
			$format = $templateLine[1];
		}
		$newsLine = $this->bldNewsLineD($targetNews ,$format ,$newsPageFile ,$decoMode ,$charCD ,$profilePagePath ,$writeNum);
		fwrite($fpO ,$newsLine);


		/***** 終了位置より後ろの出力 *****/
		$lineMax = count($templateLine[2]);
		for($lineIdx=1 ;$lineIdx<=$lineMax ;$lineIdx++) {
			fwrite($fpO ,$templateLine[2][$lineIdx] . common5C::CSRC_NL_CODE);
		}

		fclose($fpO);


				$stdFileSize = filesize($outFile);
				logFile5C::debug($outFile . ' 出力後のファイルサイズ:' . $stdFileSize);
	}

	/********************
	ニュース本体の出力テスト
	パラメータ：ニュースリスト
	　　　　　　テンプレート行リスト
	　　　　　　出力ファイル名
	　　　　　　装飾区分
	　　　　　　プロファイルページへのパス
	　　　　　　文字コード
	　　　　　　ファイルサイズの上限
	戻り値　　：出力可能件数
	********************/
	function writeMTest($targetNews ,$templateLine ,$outFile ,$decoMode ,$profilePagePath ,$charCD ,$maxFileSize) {

		$fpO = fopen($outFile ,'w');
				/* print '出力ファイル:' . $outFile; */

		/***** ニュース記事以外の大きさ *****/
		/*** 開始位置までの出力 ***/
		$lineMax = count($templateLine[0]);
		for($lineIdx=1 ;$lineIdx<=$lineMax ;$lineIdx++) {
			fwrite($fpO ,$templateLine[0][$lineIdx] . common5C::CSRC_NL_CODE);
		}

		/*** 終了位置より後ろの出力 ***/
		$lineMax = count($templateLine[2]);
		for($lineIdx=1 ;$lineIdx<=$lineMax ;$lineIdx++) {
			fwrite($fpO ,$templateLine[2][$lineIdx] . common5C::CSRC_NL_CODE);
		}

		fclose($fpO);
		$currFileSize = filesize($outFile);
				/* print 'file size:' . $currFileSize . "\n"; */

		/***** ニュースの出力 *****/
		$formCnt = 1;
		$format[$formCnt] = '';

		/*** テンプレートの変換 ***/
		$lineMax = count($templateLine[1]);
		if(strcmp($charCD ,'SJIS') == 0) {
			for($lineIdx=1 ;$lineIdx<=$lineMax ;$lineIdx++) {
				$format[$formCnt] = mb_convert_encoding($templateLine[1][$lineIdx] ,'UTF-8' ,'SJIS');
				$formCnt++;
			}
		} else {
			$format = $templateLine[1];
		}


		$newsList = $targetNews['newsInfo'];
		$newsMax = $targetNews['count'];

		$news1['count'] = 1;
		$outNews = 0;
		for($newsCnt=0 ;$newsCnt<$newsMax ;$newsCnt++) {
			$news1['newsInfo'][0] = $newsList[$newsCnt];

			$newsLine = $this->bldNewsLineM($news1 ,$format ,$decoMode ,$charCD ,$profilePagePath ,0);

			$currFileSize += strlen($newsLine);
					/* print 'file size:' . $currFileSize . "\n"; */
			if($currFileSize >= $maxFileSize) {
				break;
			}
			$outNews++;
		}

				/* print 'out News:' . $outNews; */
			$currFileSize = filesize($outFile);

		return $outNews;
	}


	/********************
	ニュース本体の出力
	パラメータ：ニュースリスト
	　　　　　　テンプレート行リスト
	　　　　　　出力ファイル名
	　　　　　　装飾区分
	　　　　　　プロファイルページへのパス
	　　　　　　文字コード
	　　　　　　出力件数
	戻り値　　：-
	********************/
	function writeM($targetNews ,$templateLine ,$outFile ,$decoMode ,$profilePagePath ,$charCD ,$writeNum) {

		$fpO = fopen($outFile ,'w');
				/* print '出力ファイル:' . $outFile; */


		/***** 開始位置までの出力 *****/
		$lineMax = count($templateLine[0]);
		for($lineIdx=1 ;$lineIdx<=$lineMax ;$lineIdx++) {
			fwrite($fpO ,$templateLine[0][$lineIdx] . common5C::CSRC_NL_CODE);
		}


		/***** ニュースの出力 *****/
		$formCnt = 1;
		$format[$formCnt] = '';

		$lineMax = count($templateLine[1]);
		if(strcmp($charCD ,'SJIS') == 0) {
			for($lineIdx=1 ;$lineIdx<=$lineMax ;$lineIdx++) {
				$format[$formCnt] = mb_convert_encoding($templateLine[1][$lineIdx] ,'UTF-8' ,'SJIS');
				$formCnt++;
			}
		} else {
			$format = $templateLine[1];
		}

					/*print_r($targetNews);*/

		$newsLine = $this->bldNewsLineM($targetNews ,$format ,$decoMode ,$charCD ,$profilePagePath ,$writeNum);
		fwrite($fpO ,$newsLine);


		/***** 終了位置より後ろの出力 *****/
		$lineMax = count($templateLine[2]);
		for($lineIdx=1 ;$lineIdx<=$lineMax ;$lineIdx++) {
			fwrite($fpO ,$templateLine[2][$lineIdx] . common5C::CSRC_NL_CODE);
		}

		fclose($fpO);
	}


	/********************
	ダイジェスト-ニュースの出力
	パラメータ：ニュースリスト
	　　　　　　テンプレート行リスト
	　　　　　　出力ファイル名
	　　　　　　装飾区分
	　　　　　　ニュースページから見たプロファイルページへのパス
	　　　　　　文字コード
	戻り値　　：タグ
	********************/
	function writeDM($targetNews ,$templateLine ,$outFile ,$decoMode ,$charCD ,$profilePagePath ,$modDate) {

		/***** 出力ファイル *****/
		$fpO = fopen($outFile ,'w');


		/***** 開始位置までの出力 *****/
		$lineMax = count($templateLine[0]);
		for($lineIdx=1 ;$lineIdx<=$lineMax ;$lineIdx++) {
			$line1 = $templateLine[0][$lineIdx];
			if(strings5C::mb_existStr($line1 ,self::KWD_LAST_UPD) >= 0) {
				$line1 = mb_ereg_replace(self::KWD_LAST_UPD ,$modDate ,$line1);
			}
			fwrite($fpO ,$line1 . common5C::CSRC_NL_CODE);
		}


		/***** ダイジェストの出力 *****/
		$formCnt = 1;
		$format[$formCnt] = '';

		$lineMax = count($templateLine[1]);
		if(strcmp($charCD ,'SJIS') == 0) {
			for($lineIdx=1 ;$lineIdx<=$lineMax ;$lineIdx++) {
				$format[$formCnt] = mb_convert_encoding($templateLine[1][$lineIdx] ,'UTF-8' ,'SJIS');
				$formCnt++;
			}
		} else {
			$format = $templateLine[1];
		}
		$newsLine = $this->bldNewsLineD($targetNews ,$format ,$outFile ,$decoMode ,$charCD ,$profilePagePath ,0);
		fwrite($fpO ,$newsLine);


		/***** ダイジェスト-ニュース間の出力 *****/
		$lineMax = count($templateLine[2]);
		for($lineIdx=1 ;$lineIdx<=$lineMax ;$lineIdx++) {
			fwrite($fpO ,$templateLine[2][$lineIdx] . common5C::CSRC_NL_CODE);
		}


		/***** ニュースの出力 *****/
		$formCnt = 1;
		$format[$formCnt] = '';

		$lineMax = count($templateLine[1]);
		if(strcmp($charCD ,'SJIS') == 0) {
			for($lineIdx=1 ;$lineIdx<=$lineMax ;$lineIdx++) {
				$format[$formCnt] = mb_convert_encoding($templateLine[3][$lineIdx] ,'UTF-8' ,'SJIS');
				$formCnt++;
			}
		} else {
			$format = $templateLine[3];
		}
		$newsLine = $this->bldNewsLineM($targetNews ,$format ,$decoMode ,$charCD ,$profilePagePath ,0);
		fwrite($fpO ,$newsLine);


		/***** ニュースより後ろの出力 *****/
		$lineMax = count($templateLine[4]);
		for($lineIdx=1 ;$lineIdx<=$lineMax ;$lineIdx++) {
			fwrite($fpO ,$templateLine[4][$lineIdx] . common5C::CSRC_NL_CODE);
		}

		fclose($fpO);
	}


	/********************
	ダイジェストの出力の本体
	パラメータ：ニュースリスト
	　　　　　　テンプレート行
	　　　　　　ニュースページのファイル名
	　　　　　　装飾区分
	　　　　　　文字コード
	　　　　　　プロファイルページへのパス
	　　　　　　出力件数
	戻り値　　：出力タグ
	********************/
	function bldNewsLineD($targetNews ,$format ,$newsPageFile ,$decoMode ,$charCD ,$profilePagePath ,$writeNum) {

		$ret = '';
		$noList = '0';

		$newsList = $targetNews['newsInfo'];

		$todayStr = dateTime5C::getDate('');

		/*** 出力するニュース数 ***/
		if($writeNum <= 0) {
			$newsMax = $targetNews['count'];
		} else {
			$newsMax = $writeNum;
		}
		if($newsMax > 5) {
			$newsMax = 5;
			$readMore = true;
		} else {
			$readMore = false;
		}


		$formatMax = count($format);		/*** テンプレートの行数 ***/

				/* print 'newsMax:' . $newsMax . "\n"; */
				/* print 'templataLines:' . $formatMax . "\n"; */

		for($newsCnt=0 ;$newsCnt<$newsMax ;$newsCnt++) {
			$news1 = $newsList[$newsCnt];

			/*** ダイジェスト内の、記事本体へのリンクの変換 ***/
			$digestStr = proviTag5C::convToMainContent($news1[dbNews5C::FLD_DIGEST] ,self::CURL_REPL ,$news1[dbNews5C::FLD_NO] ,$newsPageFile);
			$digestStr = strings5C::cnvCRLFToBR($digestStr);

			$newsContent = strings5C::cnvCRLFToBR($news1[dbNews5C::FLD_CONTENT]);

			/*** 記事日付 ***/
			$dateExp = explode('-' ,$news1[dbNews5C::FLD_DATE]);
			$dateYMD = $dateExp[0] . $dateExp[1] . $dateExp[2];

			/*** 開始日 ***/
			if(strlen($news1[dbNews5C::FLD_BEG_DATE]) >= 1) {
				$dateExp = explode('-' ,$news1[dbNews5C::FLD_BEG_DATE]);
				$begDateYMD = $dateExp[0] . $dateExp[1] . $dateExp[2];
			} else {
				$begDateYMD = '';
			}

			/*** 終了日 ***/
			if(strlen($news1[dbNews5C::FLD_END_DATE]) >= 1) {
				$dateExp = explode('-' ,$news1[dbNews5C::FLD_END_DATE]);
				$endDateYMD = $dateExp[0] . $dateExp[1] . $dateExp[2];
			} else {
				$endDateYMD = '';
			}

			if(strlen($begDateYMD) >= 1) {
				$noList = $noList . ',' . $news1[dbNews5C::FLD_NO];
			}

			for($formatCnt=1 ;$formatCnt<=$formatMax ;$formatCnt++) {
				$line1 = $format[$formatCnt];

				/*** 記事日 ***/
				$line1 = $this->cnvDateYMD(self::KWD_DATE_YMD ,$dateYMD ,$line1);
				$line1 = $this->cnvNewestDay(self::KWD_NEWEST_DAY ,$dateYMD ,$todayStr ,$line1);	/*** 「NEW」の印 ***/

				/*** イベント ***/
				$line1 = $this->cnvDateYMD(self::KWD_BEG_DATE_YMD ,$begDateYMD ,$line1);	/*** 開始日 ***/
				$line1 = $this->cnvDateYMD(self::KWD_END_DATE_YMD ,$endDateYMD ,$line1);	/*** 終了日 ***/
				$line1 = $this->cnvINTerm(self::KWD_NEWS_IN_TERM ,$begDateYMD ,$endDateYMD ,$todayStr ,$line1);	/*** 「開催中」の印 ***/


				$line1 = mb_ereg_replace(self::KWD_NEWS_NO ,$news1[dbNews5C::FLD_NO   ] ,$line1);
				$line1 = mb_ereg_replace(self::KWD_DATE    ,$news1[dbNews5C::FLD_DATE ] ,$line1);
				$line1 = mb_ereg_replace(self::KWD_TERM    ,'<span class="newsTerm">期間：' . $news1[dbNews5C::FLD_TERM ] . '</span><br />' ,$line1);
				$line1 = mb_ereg_replace(self::KWD_TITLE   ,$news1[dbNews5C::FLD_TITLE] ,$line1);
				$line1 = mb_ereg_replace(self::KWD_CATE    ,$news1[dbNews5C::FLD_CATE ] ,$line1);
				$line1 = mb_ereg_replace(self::KWD_DIGEST  ,$digestStr                  ,$line1);
				$line1 = mb_ereg_replace(self::KWD_CONTENT ,$newsContent                ,$line1);

						/* print 'replaced Line1:' . $line1 . "\n"; */

				$ret = $ret . $line1 . common5C::CSRC_NL_CODE;
			}
		}

		/***** 装飾の変換 *****/
		if(strcmp($decoMode ,common5C::DECO_CSS_CLASS) == 0) {
			$ret = proviTag5C::convKWDC($ret);
		} else {
			if(strcmp($decoMode ,common5C::DECO_CSS_STYLE) == 0) {
				$ret = proviTag5C::convKWDS($ret);
			} else {
				$ret = proviTag5C::convKWDF($ret);
			}
		}

		$ret = proviTag5C::goNewsTop($ret);

				/* print 'before convURL:' . $ret . "\n"; */
		$ret = proviTag5C::convURLMB($ret ,self::CURL_REPL);		/* リンク指定の変換 */
		$ret = proviTag5C::convWorkInfoMB($ret ,self::CURL_REPL);	/* 出勤情報リンク指定の変換 */
		$ret = proviTag5C::convTelNoMB($ret ,self::CURL_REPL);	/* 電話番号の変換 */
		$ret = proviTag5C::convProfile($ret ,$profilePagePath ,self::CURL_REPL);	/* プロファイルリンク指定の変換 */

		/*** 文字コード変換 ***/
		if(strcmp($charCD ,'SJIS') == 0) {
			$ret = mb_convert_encoding($ret ,'SJIS' ,'UTF-8');
		}

		$ret = $ret . '        <input type="hidden" id="termIDList" value="' . $noList . '">';
		$ret = $ret . '        <input type="hidden" id="newsMax" value="' . $newsMax . '">';

		if($readMore) {
			$ret = $ret . '                <div class="readMore"><a href="news.html">もっと読む</a></div>';
		}

		return $ret;
	}


	function cnvDateYMD($kwd ,$dateYMD ,$line1) {

		$ret = '';
		if(strings5C::mb_existStr($line1 ,$kwd) >= 0) {
			/***** キーワードがあって… *****/
			if(strlen($dateYMD) >= 1) {
				/***** 日付があれば出力 *****/
				$ret = mb_ereg_replace($kwd ,$dateYMD ,$line1);
			}
		} else {
			/***** キーワードがなければそのまま出力 *****/
			$ret = $line1;
		}

		return $ret;
	}




	/********************
	「開催中」の印
	パラメータ：キーワード
	　　　　　　開始日
	　　　　　　終了日
	　　　　　　今日の日付
	　　　　　　行文字列
	戻り値　　：出力文字列
	********************/
	function cnvINTerm($kwd ,$begDate ,$endDate ,$todayDate ,$line1) {

		$ret = '';
		if(strings5C::mb_existStr($line1 ,$kwd) >= 0) {
			/***** キーワードがあり… *****/
			if(strlen($begDate) >= 1
			&& strlen($endDate) >= 1) {
				/***** 開始日と終了日があり… *****/
				if($begDate <= $todayDate
				&& $endDate >= $todayDate) {
					/***** 今日が開始日と終了日の間なら出力 *****/
					$ret = mb_ereg_replace($kwd ,'' ,$line1);
				}
			}
		} else {
			/***** キーワードがなければそのまま出力 *****/
			$ret = $line1;
		}

		return $ret;
	}

	/********************
	「NEW」の印
	パラメータ：キーワード
	　　　　　　記事日付
	　　　　　　今日の日付
	　　　　　　行文字列
	戻り値　　：出力文字列
	********************/
	function cnvNewestDay($kwd ,$newsDate ,$todayDate ,$line1) {

		$ret = '';
		if(strings5C::mb_existStr($line1 ,$kwd) >= 0) {
			/***** キーワードがあり… *****/
			if(strlen($newsDate) >= 1) {
				/***** 記事日付があり… *****/
				if($newsDate == $todayDate) {
					/***** 今日が記事日付なら出力 *****/
					$ret = mb_ereg_replace($kwd ,'' ,$line1);
				}
			}
		} else {
			/***** キーワードがなければそのまま出力 *****/
			$ret = $line1;
		}

		return $ret;
	}


	/********************
	ニュース出力の本体
	パラメータ：ニュースリスト
	　　　　　　テンプレート行
	　　　　　　装飾区分
	　　　　　　文字コード
	　　　　　　プロファイルページへのパス
	　　　　　　出力件数
	戻り値　　：出力タグ
	********************/
	function bldNewsLineM($targetNews ,$format ,$decoMode ,$charCD ,$profilePagePath ,$writeNum) {

		$ret = '';

		$newsList = $targetNews['newsInfo'];

		/*** 出力するニュース数 ***/
		if($writeNum <= 0) {
			$newsMax = $targetNews['count'];
		} else {
			$newsMax = $writeNum;
		}

		$formatMax = count($format);		/*** テンプレートの行数 ***/

				/*
				print 'newsMax:' . $newsMax . "\n";
				print 'templataLines:' . $formatMax . "\n";
				*/
					/*
					print_r($newsList);
					print_r($format);
					*/

		for($newsCnt=0 ;$newsCnt<$newsMax ;$newsCnt++) {
			$news1 = $newsList[$newsCnt];
			$newsContent = strings5C::cnvCRLFToBR($news1[dbNews5C::FLD_CONTENT]);

			for($formatCnt=1 ;$formatCnt<=$formatMax ;$formatCnt++) {
				$line1 = $format[$formatCnt];

				$line1 = mb_ereg_replace(self::KWD_NEWS_NO ,$news1[dbNews5C::FLD_NO     ] ,$line1);
				$line1 = mb_ereg_replace(self::KWD_DATE    ,$news1[dbNews5C::FLD_DATE   ] ,$line1);
				$line1 = mb_ereg_replace(self::KWD_TERM    ,'<span class="newsTerm">期間：' . $news1[dbNews5C::FLD_TERM ] . '</span><br />' ,$line1);
				$line1 = mb_ereg_replace(self::KWD_TITLE   ,$news1[dbNews5C::FLD_TITLE  ] ,$line1);
				$line1 = mb_ereg_replace(self::KWD_CATE    ,$news1[dbNews5C::FLD_CATE   ] ,$line1);
				$line1 = mb_ereg_replace(self::KWD_CONTENT ,$newsContent           ,$line1);
						/* print 'replaced Line1:' . $line1 . "\n"; */
				$ret = $ret . $line1 . common5C::CSRC_NL_CODE;
			}
		}

		/***** 装飾の変換 *****/
		if(strcmp($decoMode ,common5C::DECO_CSS_CLASS) == 0) {
			$ret = proviTag5C::convKWDC($ret);
		} else {
			if(strcmp($decoMode ,common5C::DECO_CSS_STYLE) == 0) {
				$ret = proviTag5C::convKWDS($ret);
			} else {
				$ret = proviTag5C::convKWDF($ret);
			}
		}

				/* print 'before convURL:' . $ret . "\n"; */
		$ret = proviTag5C::convURLMB($ret ,self::CURL_REPL);		/* リンク指定の変換 */
				/* print $ret; */
		$ret = proviTag5C::convWorkInfoMB($ret ,self::CURL_REPL);	/* 出勤情報リンク指定の変換 */
		$ret = proviTag5C::convTelNoMB($ret ,self::CURL_REPL);	/* 電話番号の変換 */
		$ret = proviTag5C::convProfile($ret ,$profilePagePath ,self::CURL_REPL);	/* プロファイルリンク指定の変換 */

		/* 文字コード変換 */
		if(strcmp($charCD ,'SJIS') == 0) {
			$ret = mb_convert_encoding($ret ,'SJIS' ,'UTF-8');
		}

		return $ret;
	}



	/********************
	ダイジェストページ再出力
	パラメータ：グループNo
	　　　　　　店No
	　　　　　　出力デバイス
	　　　　　　最終更新日
	戻り値　　：-
	********************/
	function modDate($groupNo ,$branchNo ,$device ,$modDate) {

		$templateFileName = files5C::getFileName('TOP_PAGE_TEMPLATE' ,$groupNo ,$branchNo);
		$outFileName      = files5C::getFileName('TOP_PAGE_OUTPUT'   ,$groupNo ,$branchNo);

		/* 文字コード */
		$pageCD['MO1'] = 'SJIS';
		$pageCD['MO2'] = 'SJIS';

		$templateLine = $this->modDateRead1($templateFileName[$device]);
			/* print $newsPageFileName[$device]; */
		$this->modDateWrite($templateLine ,$outFileName[$device] ,$pageCD[$device] ,$modDate);
	}


	/********************
	テンプレートファイルの読み込み
	パラメータ：テンプレートファイル名
	戻り値　　：タグ
	********************/
	function modDateRead1($templateFile) {

		/***** テンプレートファイル読み込み *****/
				print '***テンプレートファイル:' . $templateFile;
		$templateList = htmlTemplate5C::read($templateFile);
		$lineData = $templateList['line'];

		return $lineData;
	}


	/********************
	ダイジェストの出力
	パラメータ：ニュースリスト
	　　　　　　テンプレート行リスト
	　　　　　　出力ファイル名
	　　　　　　装飾区分
	　　　　　　ニュースページのファイル名
	　　　　　　プロファイルページへのパス
	　　　　　　文字コード
	戻り値　　：-
	********************/
	function modDateWrite($templateLine ,$outFile ,$charCD ,$modDate) {

		$fpO = fopen($outFile ,'w');
				print '***出力ファイル:' . $outFile;

		$lineMax = count($templateLine);
		for($lineIdx=0 ;$lineIdx<$lineMax ;$lineIdx++) {
			if(strings5C::mb_existStr($templateLine[$lineIdx] ,self::KWD_LAST_UPD) >= 0) {
				$str = mb_ereg_replace(self::KWD_LAST_UPD ,$modDate ,$templateLine[$lineIdx]);
				fwrite($fpO ,$str . common5C::CSRC_NL_CODE);
			} else {
				fwrite($fpO ,$templateLine[$lineIdx] . common5C::CSRC_NL_CODE);
			}
		}

		fclose($fpO);
	}

}
?>
