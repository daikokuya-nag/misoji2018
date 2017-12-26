<?php
/*************************
ニュースリスト Version 1.0
PHP5
2016 Feb. 20 ver 1.0
*************************/

	require_once dirname(__FILE__) . '/../db/dbNews5C.php';
	require_once dirname(__FILE__) . '/../proviTag5C.php';

class bldNews5C {

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

	const KWD_1ST_NEWS = '__1ST_NEWS__';	/* そのページの最初のニュース */

	const KWD_CATE = '__NEWS_CATE__';	/* ニュース種類 */

	const KWD_LAST_UPD = '<!-- NEWS_KWD_LAST_UPD -->';	/* 最終更新日 */


	/********************
	リストタグ構築
	パラメータ：店No
	　　　　　　ニュースデータ
	戻り値　　：タグ
	********************/
	function bld($branchNo ,$list) {

		$ret['title'] = '<tr id="newsListHTR">'      .
			'<th class="newsDisp">表示</th>'         .
			'<th class="newsDispBeg">表示開始</th>'  .
			'<th class="newsTitle">タイトル</th>'    .
			'<th class="newsCategory">種類</th>'     .
			'<th class="newsDate">記事日付</th>'     .
			'<th class="newsTerm">期間</th>'         .
			'<th class="newsDigest">概要</th>'       .
			'<th class="newsEdit">編集</th>'         .
			'</tr>';

		$ret['data' ] = '';
		$ret['newsNoList'] = array();
		$newsList = $list['newsInfo'];
		$listMax = $list['count'];

		for($i=0 ;$i<$listMax ;$i++) {
			$news1 = $newsList[$i];

			$ret['data'] = $ret['data'] . $this->bld1($news1 ,true);

			$addDT = $news1[dbNews5C::FLD_ADD_DT];
			if(strlen($addDT) >= 1) {
				$addDTExp = explode(' ' , $addDT);
				$dateExp = explode('-' ,$addDTExp[0]);
				$timeExp = explode(':' ,$addDTExp[1]);

				$newsNo = $dateExp[0] . $dateExp[1] . $dateExp[2] . '_' . $timeExp[0] . $timeExp[1] . $timeExp[2];		/*$news1[dbNews5C::FLD_ADD_DT];*/
			} else {
				$newsNo = '';
			}

			$ret['newsNoList'][$i] = $newsNo;
		}

		return $ret;
	}

	/********************
	1行分のリストタグ構築
	パラメータ：リスト
	　　　　　　trの有無
	戻り値　　：タグ
	********************/
	function bld1($news1 ,$showTR ,$showDiv='') {

		$addDT = $news1[dbNews5C::FLD_ADD_DT];
		if(strlen($addDT) >= 1) {
			$newsNo    = '\'' . $news1[dbNews5C::FLD_ADD_DT] . '\'';

			$newsNoExp = explode(' ' ,$news1[dbNews5C::FLD_ADD_DT]);
			$dateExp = explode('-' ,$newsNoExp[0]);
			$timeExp = explode(':' ,$newsNoExp[1]);

			$idNewsNo = 'news' . $dateExp[0] . $dateExp[1] . $dateExp[2] . '_' . $timeExp[0] . $timeExp[1] . $timeExp[2];
			$newsTRID = ' id="' . $dateExp[0] . $dateExp[1] . $dateExp[2] . '_' . $timeExp[0] . $timeExp[1] . $timeExp[2] . '"';
			$newsTDClass = 'td' . $dateExp[0] . $dateExp[1] . $dateExp[2] . '_' . $timeExp[0] . $timeExp[1] . $timeExp[2];

		} else {
			$newsNo   = '';
			$idNewsNo = 'news000000$000000';
			$newsTRID = '';
			$newsTDClass = '';
		}

		if(strcmp($news1[dbNews5C::FLD_DISP] ,dbNews5C::DISP_ON) == 0) {
			$disp = ' checked';
		} else {
			$disp = '';
		}
		$dispCB   = '<input type="checkbox" id="' . $idNewsNo . '" name="' . $idNewsNo . '" value="U"' . $disp . ' onchange="enableWriteNewsDisp();" class="dispNewsSW" />';

		if(strlen($news1[dbNews5C::FLD_DISP_BEG]) >= 1) {
			$dispBeg = $news1[dbNews5C::FLD_DISP_BEG];
		} else {
			$dispBeg = '即時';
		}

		$category = $news1[dbNews5C::FLD_CATE];
		$cateStr  = 'その他';
		if(strcmp($category ,dbNews5C::CATE_EVENT) == 0) {
			$cateStr = 'イベント';
		}
		if(strcmp($category ,dbNews5C::CATE_GIRL) == 0) {
			$cateStr = '女性';
		}
		if(strcmp($category ,dbNews5C::CATE_MEMBER) == 0) {
			$cateStr = '会員';
		}


		$termStr = '-';
		if(strlen($news1[dbNews5C::FLD_BEG_DATE]) >= 1) {
			$termStr = $news1[dbNews5C::FLD_BEG_DATE];

			if(strlen($news1[dbNews5C::FLD_END_DATE]) >= 1) {
				$termStr = $termStr . '～' . $news1[dbNews5C::FLD_END_DATE];
			}
		}

		if($showTR) {
			$trBeg = '<tr' . $newsTRID . '>';
			$trEnd = '</tr>';
		} else {
			$trBeg = '';
			$trEnd = '';
		}

		$proviTag = new proviTag5C();
		$newsDigest = $proviTag->delNewsTag($news1[dbNews5C::FLD_DIGEST]);
		$editBtn = '<input type="button" value="　" style="font-size:0.9em;" onclick="editNews(' . $newsNo . ')" />';

		if(strlen($showDiv) <= 0) {
			$divTagBeg = '<div class="' . $newsTDClass . '">';
		} else {
			$divTagBeg = '<div ' . $showDiv . '>';
		}
		$divTagEnd = '</div>';

		$ret = $trBeg .
			'<td class="newsDisp">'     . $divTagBeg . $dispCB                     . $divTagEnd . '</td>' .
			'<td class="newsDispBeg">'  . $divTagBeg . $dispBeg                    . $divTagEnd . '</td>' .
			'<td class="newsTitle">'    . $divTagBeg . $news1[dbNews5C::FLD_TITLE] . $divTagEnd . '</td>' .
			'<td class="newsCategory">' . $divTagBeg . $cateStr                    . $divTagEnd . '</td>' .
			'<td class="newsDate">'     . $divTagBeg . $news1[dbNews5C::FLD_DATE]  . $divTagEnd . '</td>' .
			'<td class="newsTerm">'     . $divTagBeg . $termStr                    . $divTagEnd . '</td>' .
			'<td class="newsDigest">'   . $divTagBeg . $newsDigest                 . $divTagEnd . '</td>' .
			'<td class="newsEdit">'     . $divTagBeg . $editBtn                    . $divTagEnd . '</td>' .
			$trEnd;

		return $ret;
	}



	/********************
	リストタグ構築
	パラメータ：店No
	　　　　　　ニュースデータ
	戻り値　　：タグ
	********************/
	function bldForShow($branchNo ,$list) {

		$ret['title'] = '<tr id="newsListHTRShow">'  .
			'<th class="newsDisp">表示</th>'         .
			'<th class="newsDispBeg">表示開始</th>'  .
			'<th class="newsTitle">タイトル</th>'    .
			'<th class="newsCategory">種類</th>'     .
			'<th class="newsDate">記事日付</th>'     .
			'<th class="newsTerm">期間</th>'         .
			'<th class="newsDigest">概要</th>'       .
			'<th class="newsEdit">表示</th>'         .
			'</tr>';

		$ret['data' ] = '';
		$ret['newsNoList'] = array();
		$newsList = $list['newsInfo'];
		$listMax = $list['count'];

		$listIdx = 0;
		for($i=1 ;$i<=$listMax ;$i++) {
			$ret['data'] = $ret['data'] . $this->bld1ForShow($newsList[$i] ,true);

			$ret['newsNoList'][$listIdx] = $newsList[$i][dbNews5C::NO];
			$listIdx++;
		}

		return $ret;
	}

	/********************
	1行分のリストタグ構築
	パラメータ：リスト
	　　　　　　trの有無
	戻り値　　：タグ
	********************/
	function bld1ForShow($news1 ,$showTR) {

		$newsNo = $news1[dbNews5C::NO];

		if(strcmp($news1[dbNews5C::DISP] ,dbNews5C::DISP_ON) == 0) {
			$disp = ' checked';
		} else {
			$disp = '';
		}
		$dispCB   = '<input type="checkbox" id="dispAll' . $newsNo . '" name="dispAll' . $newsNo . '" value="U"' . $disp . ' onchange="updNewsDisp30(' . $newsNo . ');enableWriteNewsDisp();" class="dispAllNewsSW" />';	/*  disabled="disabled" */

		if(strlen($news1[dbNews5C::DISP_BEG]) >= 1) {
			$dispBeg = $news1[dbNews5C::DISP_BEG];
		} else {
			$dispBeg = '即時';
		}

		$cateStr  = 'その他';
		if(strcmp($news1[dbNews5C::CATE] ,dbNews5C::CATE_EVENT) == 0) {
			$cateStr = 'イベント';
		}
		if(strcmp($news1[dbNews5C::CATE] ,dbNews5C::CATE_GIRL) == 0) {
			$cateStr = '女性';
		}
		if(strcmp($news1[dbNews5C::CATE] ,dbNews5C::CATE_MEMBER) == 0) {
			$cateStr = '会員';
		}

		if($showTR) {
			$trBeg = '<tr>';
			$trEnd = '</tr>';
		} else {
			$trBeg = '';
			$trEnd = '';
		}

		$proviTag = new proviTag5C();
		$newsDigest = $proviTag->delNewsTag($news1[dbNews5C::DIGEST]);
		$editBtn = '<input type="button" value="　" style="font-size:0.9em;" onclick="showNews(' . $newsNo . ')" />';

		$ret = $trBeg .
			'<td class="newsDisp">'     . $dispCB              . '</td>' .
			'<td class="newsDispBeg">'  . $dispBeg             . '</td>' .
			'<td class="newsTitle">'    . $news1[dbNews5C::TITLE] . '</td>' .
			'<td class="newsCategory">' . $cateStr             . '</td>' .
			'<td class="newsDate">'     . $news1[dbNews5C::DATE]  . '</td>' .
			'<td class="newsTerm">'     . $news1[dbNews5C::TERM]  . '</td>' .
			'<td class="newsDigest">'   . $newsDigest          . '</td>' .
			'<td class="newsEdit">'     . $editBtn             . '</td>' .
			$trEnd;

		return $ret;
	}
}
?>
