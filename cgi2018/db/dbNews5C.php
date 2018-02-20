<?php
/*************************
ニュースデータ Version 1.0
PHP5
2016 Feb. 20 ver 1.0
*************************/

	require_once dirname(__FILE__) . '/../sql5C.php';
	require_once dirname(__FILE__) . '/../dateTime5C.php';
	require_once dirname(__FILE__) . '/../common5C.php';
	require_once dirname(__FILE__) . '/../strings5C.php';
	require_once dirname(__FILE__) . '/../funcs5C.php';
	require_once dirname(__FILE__) . '/../logFile5C.php';

class dbNews5C {

	/*** テーブル名 ***/
	const TABLE_NAME = 'news';


	/*** フィールド ***/
	const FLD_BRANCH_NO = 'branchNo';	/* 店No */

	const FLD_TITLE     = 'title';		/* タイトル */
	const FLD_CATE      = 'category';	/* 種類 */
	const FLD_CONTENT   = 'content';	/* 本文 */
	const FLD_DATE      = 'newsDate';	/* 記事日付 */
	const FLD_TERM      = 'term';		/* 記事期間 */
	const FLD_DISP      = 'disp';		/* 表示/非表示 */
	const FLD_DISP_BEG  = 'dispBegDT';	/* 表示開始日時 */
	const FLD_SHOWN     = 'shown';		/* 既に表示 */
	const FLD_BG_COLOR  = 'BGColor';	/* 背景色 */

	const FLD_BEG_DATE  = 'begDate';	/* 記事開始日 */
	const FLD_END_DATE  = 'endDate';	/* 記事終了日 */

	const FLD_ADD_DT    = 'addDT';		/* レコード登録日時 */
	const FLD_UPD_DT    = 'updDT';		/* レコード更新日時 */


	/*** HTMLファイル出力識別 ***/
	const FLD_TMP_DISP_MODE = 'dispMode';		/* 表示識別 */


	/* HTMLファイル出力用 */
	const FLD_NO        = 'seqNo';		/* 連番 */

	/***** 配信区分 *****/
	const CATE_EVENT  = 'E';	/* イベント */
	const CATE_GIRL   = 'W';	/* 女性 */
	const CATE_MEMBER = 'M';	/* 会員 */
	const CATE_OTHER  = 'O';	/* その他 */

	const DISP_ON  = 'U';	/* 表示 */
	const DISP_OFF = 'N';	/* 非表示 */
	const DISP_DEL = 'D';	/* 削除 */


	const SHOWN_SHOWN = 'S';	/* 表示済み */


	/*** HTMLファイル出力識別 ***/
	const DISP_MODE_ALWAYS = 'A';		/* 常時出力 */
	const DISP_MODE_DISP   = 'B';		/* 過去に表示済み */
	const DISP_MODE_READY  = 'C';		/* 出力日時経過 */
	const DISP_MODE_YET    = 'D';		/* 出力日時未経過 */


	/*** 新規 ***/
	const NEW_REC = 0;

	var $handle;
	var $vals;

	/********************
	コンストラクタ(DB接続)
	パラメータ：-
	戻り値　　：-
	********************/
	function dbNews5C($handle=null) {

		if(is_null($handle)) {
			$this->handle = new sql5C();
		} else {
			$this->handle = $handle;
		}

		$this->vals = array();
	}

	public function getHandle() {
		return $this->handle;
	}

	function resetVal() {

		$this->vals = array();
	}

	function setVal($fld ,$val) {

		$this->vals[$fld] = $val;
	}

	/********************
	ニュースリストの読み込み(削除以外)
	パラメータ：店No
	戻り値　　：ニュースリスト
	********************/
	function readAll($branchNo) {

		$db = $this->handle;

		$fldArr = array(
			self::FLD_TITLE    ,
			self::FLD_CATE     ,
			self::FLD_CONTENT  ,
			self::FLD_DATE     ,
			self::FLD_TERM     ,
			self::FLD_DISP     ,
			self::FLD_DISP_BEG ,
			self::FLD_SHOWN    ,

			self::FLD_BEG_DATE ,
			self::FLD_END_DATE ,
			self::FLD_BG_COLOR ,

			self::FLD_ADD_DT   ,
			self::FLD_UPD_DT
		);

		$where =
			self::FLD_BRANCH_NO . '='  . $branchNo . ' and ' .
			self::FLD_DISP      . '!=' . $db->setQuote(self::DISP_DEL);	/* 削除でないレコード */

		$ret = $this->readMain($branchNo ,$fldArr ,$where);

		return $ret;
	}

	/********************
	ニュースリストの読み込み
	パラメータ：店No
	戻り値　　：ニュースリスト
	********************/
	function readShowable($branchNo ,$dt) {

		$db = $this->handle;

		$fldArr = array(
			self::FLD_TITLE    ,
			self::FLD_CATE     ,
			self::FLD_CONTENT  ,
			self::FLD_DATE     ,
			self::FLD_TERM     ,
			self::FLD_DISP     ,
			self::FLD_DISP_BEG ,
			self::FLD_SHOWN    ,

			self::FLD_BEG_DATE ,
			self::FLD_END_DATE ,
			self::FLD_BG_COLOR ,

			self::FLD_ADD_DT   ,
			self::FLD_UPD_DT
		);

		$where =
			self::FLD_BRANCH_NO . '=' . $branchNo . ' and ' .
			self::FLD_DISP      . '=' . $db->setQuote(self::DISP_ON);	/* 削除でないレコード */

		$ret = $this->readMain($branchNo ,$fldArr ,$where);

		$seq = 1;
		$idxMax = $ret['count'];
		for($idx=0 ;$idx<$idxMax ;$idx++) {
			$ret['newsInfo'][$idx][self::FLD_NO] = $seq;
			$seq++;
		}

		return $ret;
	}



	/********************
	ニュースの取得
	パラメータ：店No
	　　　　　　ニュースNo
	戻り値　　：
	********************/
	function get($branchNo ,$newsDT) {

		$db = $this->handle;

		$fldArr = array(
			self::FLD_TITLE    ,
			self::FLD_CATE     ,
			self::FLD_CONTENT  ,
			self::FLD_DATE     ,
			self::FLD_TERM     ,
			self::FLD_DISP     ,
			self::FLD_DISP_BEG ,
			self::FLD_SHOWN    ,

			self::FLD_BEG_DATE ,
			self::FLD_END_DATE ,
			self::FLD_BG_COLOR ,

			self::FLD_ADD_DT   ,
			self::FLD_UPD_DT
		);

		$where =
			self::FLD_BRANCH_NO . '=' . $branchNo . ' and ' .
			self::FLD_ADD_DT    . '=' . $db->setQuote($newsDT);	/* 記事日付のレコード */

		$ret = $this->readMainONE($branchNo ,$fldArr ,$where);

		return $ret;
	}

	/********************
	ニュースリストの読み込み(本体)
	パラメータ：店No
	　　　　　　フィールドリスト
	　　　　　　条件
	戻り値　　：ニュースリスト
	********************/
	private function readMain($branchNo ,$fldArr ,$where) {

		$fldList = funcs5C::setFldArrToList($fldArr);
		$order   = self::FLD_ADD_DT . ' desc';

		$db = $this->handle;
		$newsList = $db->selectRec(self::TABLE_NAME ,$fldList ,$where ,$order);

		$fldListMax = count($fldArr);
		if($newsList['rows'] <= 0) {
			/*** 0件のとき ***/
			for($fldIdx=0 ;$fldIdx<$fldListMax ;$fldIdx++) {
				$fldName = $fldArr[$fldIdx];
				$news[0][$fldName] = '';
			}
		} else {
			/*** 1件以上あったとき ***/
			$recList = $newsList['fetch'];
			$recIdxMax = $newsList['rows'];
			for($recIdx=0 ;$recIdx<$recIdxMax ;$recIdx++) {
				$rec1 = $recList[$recIdx];
				for($fldIdx=0 ;$fldIdx<$fldListMax ;$fldIdx++) {
					$fldName = $fldArr[$fldIdx];
					$news[$recIdx][$fldName] = $rec1[$fldIdx];
				}
			}
		}

		$ret['newsInfo'] = $news;
		$ret['count'   ] = count($news);

		return $ret;
	}


	/********************
	ニュース1件の読み込み(本体)
	パラメータ：店No
	　　　　　　フィールドリスト
	　　　　　　条件
	戻り値　　：ニュースリスト
	********************/
	private function readMainONE($branchNo ,$fldArr ,$where) {

		$fldList = funcs5C::setFldArrToList($fldArr);

		$db = $this->handle;
		$newsList = $db->selectRec(self::TABLE_NAME ,$fldList ,$where);

		$fldListMax = count($fldArr);
		if($newsList['rows'] <= 0) {
			/*** 0件のとき ***/
			for($fldIdx=0 ;$fldIdx<$fldListMax ;$fldIdx++) {
				$fldName = $fldArr[$fldIdx];
				$news[$fldName] = '';
			}
		} else {
			/*** 1件以上あったとき ***/
			$rec1 = $newsList['fetch'][0];
			for($fldIdx=0 ;$fldIdx<$fldListMax ;$fldIdx++) {
				$fldName = $fldArr[$fldIdx];
				$news[$fldName] = $rec1[$fldIdx];
			}
		}

		$ret = $news;

		return $ret;
	}


	/********************
	ニュースの更新
	パラメータ：店No
	　　　　　　ニュースNo
	戻り値　　：
	********************/
	function upd($branchNo ,$newsNo) {

				logFile5C::debug('既存ニュース更新 No.' . $newsNo);
		$updDT = dateTime5C::getCurrDT();

		$db = $this->handle;
		$vals = $this->vals;

		if(strlen($vals[self::FLD_TERM]) >= 1) {
			$termStr = self::FLD_TERM . '=' . $db->setQuote($vals[self::FLD_TERM]) . ',';
		} else {
			$termStr = '';
		}

		if(strlen($vals[self::FLD_DISP_BEG]) >= 1) {
			$dispBegStr = self::FLD_DISP_BEG . '=' . $db->setQuote($vals[self::FLD_DISP_BEG]) . ',';
		} else {
			$dispBegStr = '';
		}


		if(strlen($vals[self::FLD_BEG_DATE]) >= 1) {
			$dispBDateStr = self::FLD_BEG_DATE . '=' . $db->setQuote($vals[self::FLD_BEG_DATE]) . ',';
		} else {
			$dispBDateStr = self::FLD_BEG_DATE . '=null' . ',';
		}

		if(strlen($vals[self::FLD_END_DATE]) >= 1) {
			$dispEDateStr = self::FLD_END_DATE . '=' . $db->setQuote($vals[self::FLD_END_DATE]) . ',';
		} else {
			$dispEDateStr = self::FLD_END_DATE . '=null' . ',';
		}

		if(strlen($vals[self::FLD_BG_COLOR]) >= 1) {
			$dispEDateStr = self::FLD_BG_COLOR . '=' . $db->setQuote($vals[self::FLD_BG_COLOR]) . ',';
		} else {
			$dispEDateStr = self::FLD_BG_COLOR . '=null' . ',';
		}

		$fldList =
			self::FLD_TITLE    . '=' . $db->setQuote($vals[self::FLD_TITLE  ]) . ',' .
			self::FLD_CATE     . '=' . $db->setQuote($vals[self::FLD_CATE   ]) . ',' .
			self::FLD_CONTENT  . '=' . $db->setQuote($vals[self::FLD_CONTENT]) . ',' .
			self::FLD_DATE     . '=' . $db->setQuote($vals[self::FLD_DATE   ]) . ',' .
			$termStr    .
			$dispBegStr .

			$dispBDateStr .
			$dispEDateStr .

			self::FLD_UPD_DT   . '=' . $db->setQuote($updDT);
			/* self::FLD_SHOWNはそのまま */

		$where =
			self::FLD_BRANCH_NO . '=' . $branchNo . ' and ' .
			self::FLD_ADD_DT    . '=' . $db->setQuote($newsNo);	/* 記事日付のレコード */

		$newsList = $db->updateRec(self::TABLE_NAME ,$fldList ,$where);
	}


	/********************
	ニュースの追加
	パラメータ：店No
	戻り値　　：
	********************/
	function add($branchNo) {

		$db = $this->handle;
		$vals = $this->vals;

				logFile5C::debug('新規ニュース　　' .
						'CONTENT:'    . $vals[self::FLD_CONTENT] .
						' **DATE:'    . $vals[self::FLD_DATE   ] .
						' **TERM:'    . $vals[self::FLD_TERM   ] .
						' **DISPBEG:' . $vals[self::FLD_DISP_BEG]);

		$addDT = dateTime5C::getCurrDT();


		$fldArr = array(
			self::FLD_BRANCH_NO ,
			self::FLD_TITLE     ,
			self::FLD_CATE      ,
			self::FLD_CONTENT   ,
			self::FLD_DATE      ,
			self::FLD_DISP      ,

			self::FLD_ADD_DT
		);

		$valueList =
			$branchNo . ',' .
			$db->setQuote($vals[self::FLD_TITLE  ]) . ',' .
			$db->setQuote($vals[self::FLD_CATE   ]) . ',' .
			$db->setQuote($vals[self::FLD_CONTENT]) . ',' .
			$db->setQuote($vals[self::FLD_DATE   ]) . ',' .
			$db->setQuote(self::DISP_OFF) . ',' .

			$db->setQuote($addDT);

		/*
		if(strlen($term) >= 1) {
			$fldArr[] = self::FLD_TERM;
			$valueList = $valueList  . ',' . $db->setQuote($vals[self::FLD_TERM]);
		}
		*/

		if(strlen($vals[self::FLD_DISP_BEG]) >= 1) {
			$fldArr[] = self::FLD_DISP_BEG;
			$valueList = $valueList  . ',' . $db->setQuote($vals[self::FLD_DISP_BEG]);
		}

		if(strlen($vals[self::FLD_BEG_DATE]) >= 1) {
			$fldArr[] = self::FLD_BEG_DATE;
			$valueList = $valueList  . ',' . $db->setQuote($vals[self::FLD_BEG_DATE]);
		}

		if(strlen($vals[self::FLD_END_DATE]) >= 1) {
			$fldArr[] = self::FLD_END_DATE;
			$valueList = $valueList  . ',' . $db->setQuote($vals[self::FLD_END_DATE]);
		}

		if(strlen($vals[self::FLD_BG_COLOR]) >= 1) {
			$fldArr[] = self::FLD_BG_COLOR;
			$valueList = $valueList  . ',' . $db->setQuote($vals[self::FLD_BG_COLOR]);
		}

		$fldList = funcs5C::setFldArrToList($fldArr);
		$db->insertRec(self::TABLE_NAME ,$fldList ,$valueList);

		return $addDT;
	}


	/********************
	ニュースの削除
	パラメータ：店No
	　　　　　　ニュースNo
	戻り値　　：
	********************/
	function setDelMark($branchNo ,$newsNo) {

				logFile5C::debug('既存ニュース削除 No.' . $newsNo);
		$updDT = dateTime5C::getCurrDT();

		$db = $this->handle;
		$fldList =
			self::FLD_DISP   . '=' . $db->setQuote(self::DISP_DEL) . ',' .
			self::FLD_UPD_DT . '=' . $db->setQuote($updDT);

		$where =
			self::FLD_BRANCH_NO . '=' . $branchNo . ' and ' .
			self::FLD_ADD_DT    . '=' . $db->setQuote($newsNo);	/* 記事日付のレコード */

		$newsList = $db->updateRec(self::TABLE_NAME ,$fldList ,$where);
	}


	/********************
	出力日時経過
	パラメータ：店No
	　　　　　　ニュースNo
	戻り値　　：
	********************/
	function setDispMark($branchNo ,$newsNo) {

				logFile5C::debug('出力日時経過 No.' . $newsNo);
		$updDT = dateTime5C::getCurrDT();

		$db = $this->handle;
		$fldList =
			self::FLD_SHOWN  . '=' . $db->setQuote(self::SHOWN_SHOWN) . ',' .
			self::FLD_UPD_DT . '=' . $db->setQuote($updDT);

		$where =
			self::FLD_BRANCH_NO . '=' . $branchNo . ' and ' .
			self::FLD_ADD_DT    . '=' . $db->setQuote($newsNo);	/* 記事日付のレコード */

		$newsList = $db->updateRec(self::TABLE_NAME ,$fldList ,$where);
	}


	/********************
	表示可否の更新
	パラメータ：更新データリスト
	戻り値　　：
	********************/
	function updDisp($dataList) {

		$branchNo = $dataList['branchNo'];

		$updDT = dateTime5C::getCurrDT();

		$db = $this->handle;

		$fldArr = array(
			self::FLD_DISP   ,
			self::FLD_ADD_DT
		);

		$where =
			self::FLD_BRANCH_NO . '='  . $branchNo;

		$ret = $this->readMain($branchNo ,$fldArr ,$where);

		$dispCnt = 0;
		$idxMax = $ret['count'];

		$prevNews = $ret['newsInfo'];

		for($idx=0 ;$idx<$idxMax ;$idx++) {
			$news1 = $prevNews[$idx];
			$newsDT = $news1[self::FLD_ADD_DT];

			$newsDTExp = explode(' ' ,$newsDT);
			$dateExp = explode('-' ,$newsDTExp[0]);
			$timeExp = explode(':' ,$newsDTExp[1]);

			$dataID = 'news' . $dateExp[0] . $dateExp[1] . $dateExp[2] . '_' . $timeExp[0] . $timeExp[1] . $timeExp[2];

			if(isset($dataList[$dataID])) {
				$disp = $dataList[$dataID];

				$fldList =
					self::FLD_DISP    . '=' . $db->setQuote($disp) . ',' .
					self::FLD_UPD_DT  . '=' . $db->setQuote($updDT);

				$where =
					self::FLD_BRANCH_NO . '=' . $branchNo . ' and ' .
					self::FLD_ADD_DT    . '=' . $db->setQuote($newsDT);	/* 記事日付のレコード */

				$result = $db->updateRec(self::TABLE_NAME ,$fldList ,$where);
				$dispCnt++;
			}
		}

		return $dispCnt;
	}
}
?>
