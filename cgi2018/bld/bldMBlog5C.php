<?php
/*************************
ブログリスト Version 1.0
PHP5
2016 June 03 ver 1.0
*************************/

	require_once dirname(__FILE__) . '/../db/dbMBlog5C.php';
	require_once dirname(__FILE__) . '/../proviTag5C.php';

class bldMBlog5C {

	/***** テンプレートキーワード *****/
	/*** 本文の範囲 ***/
	const MAIN_BEG_LINE = '<!-- BLOG_MAIN_LIST_BEG -->';	/* ブログ出力開始位置 */
	const MAIN_END_LINE = '<!-- BLOG_MAIN_LIST_END -->';	/* ブログ出力終了位置 */


	const KWD_BLOG_NO = '<!-- BLOG_NO -->';			/* ブログNo */
	const KWD_DATE    = '<!-- BLOG_DATE -->';		/* 投稿日 */
	const KWD_TIME    = '<!-- BLOG_TIME -->';		/* 投稿時刻 */
	const KWD_TERM    = '<!-- BLOG_TERM -->';		/* 表示期間 */
	const KWD_TITLE   = '<!-- BLOG_TITLE -->';		/* ブログタイトル */
	const KWD_CONTENT = '<!-- BLOG_CONTENT -->';	/* ブログ本文 */

	const KWD_1ST_BLOG = '__1ST_BLOG__';	/* そのページの最初のブログ */


	const KWD_CATE = '__BLOG_CATE__';	/* ブログ種類 */

	const KWD_LAST_UPD = '<!-- BLOG_KWD_LAST_UPD -->';	/* 最終更新日 */


	/********************
	リストタグ構築
	パラメータ：グループNo
	　　　　　　店No
	　　　　　　ブログデータ
	戻り値　　：タグ
	********************/
	function bld($groupNo ,$branchNo ,$list) {

		$ret['title'] = '<tr id="blogListHTR">'      .
			'<th class="blogDisp">表示</th>'         .
			/* '<th class="blogDispBeg">表示開始</th>'  . */
			'<th class="blogTitle">タイトル</th>'    .
			'<th class="blogCategory">種類</th>'     .
			'<th class="blogDate">記事日付</th>'     .
			/* '<th class="blogTerm">期間</th>'         . */
			'<th class="blogDigest">概要</th>'       .
			'<th class="blogEdit">編集</th>'         .
			'</tr>';

		$ret['data' ] = '';
		$ret['blogNoList'] = array();
		$blogList = $list['blogInfo'];
		$listMax = $list['count'];

		for($i=0 ;$i<$listMax ;$i++) {
			$blog1 = $blogList[$i];

			$ret['data'] = $ret['data'] . $this->bld1($blog1 ,true);

			$addDT = $blog1[dbMBlog5C::FLD_ADD_DT];
			if(strlen($addDT) >= 1) {
				$addDTExp = explode(' ' , $addDT);
				$dateExp = explode('-' ,$addDTExp[0]);
				$timeExp = explode(':' ,$addDTExp[1]);

				$blogNo = $dateExp[0] . $dateExp[1] . $dateExp[2] . '_' . $timeExp[0] . $timeExp[1] . $timeExp[2];		/*$blog1[dbMBlog5C::FLD_ADD_DT];*/
			} else {
				$blogNo = '';
			}

			$ret['blogNoList'][$i] = $blogNo;
		}

		return $ret;
	}

	/********************
	1行分のリストタグ構築
	パラメータ：リスト
	　　　　　　trの有無
	戻り値　　：タグ
	********************/
	function bld1($blog1 ,$showTR ,$showDiv='') {

		$addDT = $blog1[dbMBlog5C::FLD_ADD_DT];
		if(strlen($addDT) >= 1) {
			$blogNo    = '\'' . $blog1[dbMBlog5C::FLD_ADD_DT] . '\'';

			$blogNoExp = explode(' ' ,$blog1[dbMBlog5C::FLD_ADD_DT]);
			$dateExp = explode('-' ,$blogNoExp[0]);
			$timeExp = explode(':' ,$blogNoExp[1]);

			$idBlogNo    = 'blog'  . $dateExp[0] . $dateExp[1] . $dateExp[2] . '_' . $timeExp[0] . $timeExp[1] . $timeExp[2];
			$blogTRID    = ' id="' . $dateExp[0] . $dateExp[1] . $dateExp[2] . '_' . $timeExp[0] . $timeExp[1] . $timeExp[2] . '"';
			$blogTDClass = 'td'    . $dateExp[0] . $dateExp[1] . $dateExp[2] . '_' . $timeExp[0] . $timeExp[1] . $timeExp[2];
		} else {
			$blogNo   = '';
			$idBlogNo = 'blog000000$000000';
			$blogTRID = '';
			$blogTDClass = '';
		}


		if(strcmp($blog1[dbMBlog5C::FLD_DISP] ,dbMBlog5C::DISP_ON) == 0) {
			$disp = ' checked';
		} else {
			$disp = '';
		}
/*		$dispCB   = '<input type="checkbox" id="disp' . $idBlogNo . '" name="disp' . $idBlogNo . '" value="U"' . $disp . ' onclick="enableWriteBlogDisp();" class="dispSW dispBlogSW" />';*/
		$dispCB   = '<input type="checkbox" id="' . $idBlogNo . '" name="' . $idBlogNo . '" value="U"' . $disp . ' onchange="updBlogDispALL(' . $blogNo . ');enableWriteBlogDisp();" class="dispBlogSW" />';	/*  disabled="disabled" */

		if(strlen($blog1[dbMBlog5C::FLD_DISP_BEG]) >= 1) {
			$dispBeg = $blog1[dbMBlog5C::FLD_DISP_BEG];
		} else {
			$dispBeg = '即時';
		}

		$category = $blog1[dbMBlog5C::FLD_CATE];
		$cateStr  = 'その他';
		if(strcmp($category ,dbMBlog5C::CATE_EVENT) == 0) {
			$cateStr = 'イベント';
		}
		if(strcmp($category ,dbMBlog5C::CATE_GIRL) == 0) {
			$cateStr = '女性';
		}
		if(strcmp($category ,dbMBlog5C::CATE_MEMBER) == 0) {
			$cateStr = '会員';
		}


		$termStr = '-';
		if(strlen($blog1[dbMBlog5C::FLD_BEG_DATE]) >= 1) {
			$termStr = $blog1[dbMBlog5C::FLD_BEG_DATE];

			if(strlen($blog1[dbMBlog5C::FLD_END_DATE]) >= 1) {
				$termStr = $termStr . '～' . $blog1[dbMBlog5C::FLD_END_DATE];
			}
		}

		if($showTR) {
			$trBeg = '<tr' . $blogTRID . '>';
			$trEnd = '</tr>';
		} else {
			$trBeg = '';
			$trEnd = '';
		}

		$proviTag = new proviTag5C();
		$blogDigest = $proviTag->delBlogTag($blog1[dbMBlog5C::FLD_CONTENT]);
		$editBtn = '<input type="button" value="　" style="font-size:0.9em;" onclick="editBlog(' . $blogNo . ')" />';

		if(strlen($showDiv) <= 0) {
			$divTagBeg = '<div class="' . $blogTDClass . '">';
		} else {
			$divTagBeg = '<div ' . $showDiv . '>';
		}
		$divTagEnd = '</div>';

		$ret = $trBeg .
			'<td class="blogDisp">'     . $divTagBeg . $dispCB                      . $divTagEnd . '</td>' .
			/* '<td class="blogDispBeg">'  . $divTagBeg . $dispBeg                     . $divTagEnd . '</td>' . */
			'<td class="blogTitle">'    . $divTagBeg . $blog1[dbMBlog5C::FLD_TITLE] . $divTagEnd . '</td>' .
			'<td class="blogCategory">' . $divTagBeg . $cateStr                     . $divTagEnd . '</td>' .
			'<td class="blogDate">'     . $divTagBeg . $blog1[dbMBlog5C::FLD_DATE]  . $divTagEnd . '</td>' .
			/* '<td class="blogTerm">'     . $divTagBeg . $termStr                     . $divTagEnd . '</td>' . */
			'<td class="blogDigest">'   . $divTagBeg . $blogDigest                  . $divTagEnd . '</td>' .
			'<td class="blogEdit">'     . $divTagBeg . $editBtn                     . $divTagEnd . '</td>' .
			$trEnd;

		return $ret;
	}



	/********************
	リストタグ構築
	パラメータ：グループNo
	　　　　　　店No
	　　　　　　ブログデータ
	戻り値　　：タグ
	********************/
	function bldForShow($groupNo ,$branchNo ,$list) {

		$ret['title'] = '<tr id="blogListHTRShow">'  .
			'<th class="blogDisp">表示</th>'         .
			'<th class="blogDispBeg">表示開始</th>'  .
			'<th class="blogTitle">タイトル</th>'    .
			'<th class="blogCategory">種類</th>'     .
			'<th class="blogDate">記事日付</th>'     .
			'<th class="blogTerm">期間</th>'         .
			'<th class="blogDigest">概要</th>'       .
			'<th class="blogEdit">表示</th>'         .
			'</tr>';

		$ret['data' ] = '';
		$ret['blogNoList'] = array();
		$blogList = $list['blogInfo'];
		$listMax = $list['count'];

		$listIdx = 0;
		for($i=1 ;$i<=$listMax ;$i++) {
			$ret['data'] = $ret['data'] . $this->bld1ForShow($blogList[$i] ,true);

			$ret['blogNoList'][$listIdx] = $blogList[$i][dbMBlog5C::NO];
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
	function bld1ForShow($blog1 ,$showTR) {

		$blogNo = $blog1[dbMBlog5C::NO];

		if(strcmp($blog1[dbMBlog5C::DISP] ,dbMBlog5C::DISP_ON) == 0) {
			$disp = ' checked';
		} else {
			$disp = '';
		}
		$dispCB   = '<input type="checkbox" id="dispAll' . $blogNo . '" name="dispAll' . $blogNo . '" value="U"' . $disp . ' onchange="updBlogDisp30(' . $blogNo . ');enableWriteBlogDisp();" class="dispAllBlogSW" />';	/*  disabled="disabled" */

		if(strlen($blog1[dbMBlog5C::DISP_BEG]) >= 1) {
			$dispBeg = $blog1[dbMBlog5C::DISP_BEG];
		} else {
			$dispBeg = '即時';
		}

		$cateStr  = 'その他';
		if(strcmp($blog1[dbMBlog5C::CATE] ,dbMBlog5C::CATE_EVENT) == 0) {
			$cateStr = 'イベント';
		}
		if(strcmp($blog1[dbMBlog5C::CATE] ,dbMBlog5C::CATE_GIRL) == 0) {
			$cateStr = '女性';
		}
		if(strcmp($blog1[dbMBlog5C::CATE] ,dbMBlog5C::CATE_MEMBER) == 0) {
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
		$blogDigest = $proviTag->delBlogTag($blog1[dbMBlog5C::DIGEST]);
		$editBtn = '<input type="button" value="　" style="font-size:0.9em;" onclick="showBlog(' . $blogNo . ')" />';

		$ret = $trBeg .
			'<td class="blogDisp">'     . $dispCB                  . '</td>' .
			'<td class="blogDispBeg">'  . $dispBeg                 . '</td>' .
			'<td class="blogTitle">'    . $blog1[dbMBlog5C::TITLE] . '</td>' .
			'<td class="blogCategory">' . $cateStr                 . '</td>' .
			'<td class="blogDate">'     . $blog1[dbMBlog5C::DATE]  . '</td>' .
			'<td class="blogTerm">'     . $blog1[dbMBlog5C::TERM]  . '</td>' .
			'<td class="blogDigest">'   . $blogDigest              . '</td>' .
			'<td class="blogEdit">'     . $editBtn                 . '</td>' .
			$trEnd;

		return $ret;
	}
}
?>
