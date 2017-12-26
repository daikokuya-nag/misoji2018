<?php
/*************************
店長ブログ Version 1.0
PHP5
2016 June 03 ver 1.0
*************************/

	require_once dirname(__FILE__) . '/../sql5C.php';
	require_once dirname(__FILE__) . '/../dateTime5C.php';
	require_once dirname(__FILE__) . '/../common5C.php';
	require_once dirname(__FILE__) . '/../strings5C.php';
	require_once dirname(__FILE__) . '/../funcs5C.php';
	require_once dirname(__FILE__) . '/../logFile5C.php';

class dbMBlog5C {

	/*** テーブル名 ***/
	const TABLE_NAME = 'mastersBlog';


	/*** フィールド ***/
	const FLD_GROUP_NO  = 'groupNo';	/* グループNo */
	const FLD_BRANCH_NO = 'branchNo';	/* 店No */

	const FLD_TITLE     = 'title';		/* タイトル */
	const FLD_CATE      = 'category';	/* 種類 */
	const FLD_DIGEST    = 'digest';		/* 概要 */
	const FLD_CONTENT   = 'content';	/* 本文 */
	const FLD_DATE      = 'blogDate';	/* 記事日付 */
	const FLD_TERM      = 'term';		/* 記事期間 */
	const FLD_DISP      = 'disp';		/* 表示/非表示 */
	const FLD_DISP_BEG  = 'dispBegDT';	/* 表示開始日時 */
	const FLD_SHOWN     = 'shown';		/* 既に表示 */

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


	/********************
	コンストラクタ(DB接続)
	パラメータ：-
	戻り値　　：-
	********************/
	function dbMBlog5C($handle=null) {
		$this->__construct($handle);
	}

	function __construct($handle=null) {

		if(is_null($handle)) {
			$this->handle = new sql5C();
		} else {
			$this->handle = $handle;
		}
	}

	/********************
	ブログリストの読み込み(削除以外)
	パラメータ：グループNo
	　　　　　　店No
	戻り値　　：ブログリスト
	********************/
	function readAll($groupNo ,$branchNo) {

		$db = $this->handle;

		$fldArr = array(
			self::FLD_TITLE    ,
			self::FLD_CATE     ,
			self::FLD_DIGEST   ,
			self::FLD_CONTENT  ,
			self::FLD_DATE     ,
			self::FLD_TERM     ,
			self::FLD_DISP     ,
			self::FLD_DISP_BEG ,
			self::FLD_SHOWN    ,

			self::FLD_BEG_DATE ,
			self::FLD_END_DATE ,

			self::FLD_ADD_DT   ,
			self::FLD_UPD_DT
		);

		$where =
			self::FLD_GROUP_NO  . '='  . $groupNo  . ' and ' .
			self::FLD_BRANCH_NO . '='  . $branchNo . ' and ' .
			self::FLD_DISP      . '!=' . $db->setQuote(self::DISP_DEL);	/* 削除でないレコード */

		$ret = $this->readMain($groupNo ,$branchNo ,$fldArr ,$where);

		return $ret;
	}

	/********************
	ブログリストの読み込み
	パラメータ：グループNo
	　　　　　　店No
	戻り値　　：ブログリスト
	********************/
	function readShowable($groupNo ,$branchNo ,$dt=null) {

		$db = $this->handle;

		$fldArr = array(
			self::FLD_TITLE    ,
			self::FLD_CATE     ,
			self::FLD_DIGEST   ,
			self::FLD_CONTENT  ,
			self::FLD_DATE     ,
			self::FLD_TERM     ,
			self::FLD_DISP     ,
			self::FLD_DISP_BEG ,
			self::FLD_SHOWN    ,

			self::FLD_BEG_DATE ,
			self::FLD_END_DATE ,

			self::FLD_ADD_DT   ,
			self::FLD_UPD_DT
		);

		$where =
			self::FLD_GROUP_NO  . '=' . $groupNo  . ' and ' .
			self::FLD_BRANCH_NO . '=' . $branchNo . ' and ' .
			self::FLD_DISP      . '=' . $db->setQuote(self::DISP_ON);	/* 削除でないレコード */

		$ret = $this->readMain($groupNo ,$branchNo ,$fldArr ,$where);


		$seq = 1;
		$idxMax = $ret['count'];
		for($idx=0 ;$idx<$idxMax ;$idx++) {
			$ret['blogInfo'][$idx][self::FLD_NO] = $seq;
			$seq++;
		}

		return $ret;
	}



	/********************
	ブログの取得
	パラメータ：グループNo
	　　　　　　店No
	　　　　　　ブログNo
	戻り値　　：
	********************/
	function get($groupNo ,$branchNo ,$newsDT) {

		$db = $this->handle;

		$fldArr = array(
			self::FLD_TITLE    ,
			self::FLD_CATE     ,
			self::FLD_DIGEST   ,
			self::FLD_CONTENT  ,
			self::FLD_DATE     ,
			self::FLD_TERM     ,
			self::FLD_DISP     ,
			self::FLD_DISP_BEG ,
			self::FLD_SHOWN    ,

			self::FLD_BEG_DATE ,
			self::FLD_END_DATE ,

			self::FLD_ADD_DT   ,
			self::FLD_UPD_DT
		);

		$where =
			self::FLD_GROUP_NO  . '=' . $groupNo  . ' and ' .
			self::FLD_BRANCH_NO . '=' . $branchNo . ' and ' .
			self::FLD_ADD_DT    . '=' . $db->setQuote($newsDT);	/* 記事日付のレコード */

		$ret = $this->readMainONE($groupNo ,$branchNo ,$fldArr ,$where);

		return $ret;
	}

	/********************
	ブログリストの読み込み(本体)
	パラメータ：グループNo
	　　　　　　店No
	　　　　　　フィールドリスト
	　　　　　　条件
	戻り値　　：ブログリスト
	********************/
	private function readMain($groupNo ,$branchNo ,$fldArr ,$where) {

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

		$ret['blogInfo'] = $news;
		$ret['count'   ] = count($news);

		return $ret;
	}


	/********************
	ブログ1件の読み込み(本体)
	パラメータ：グループNo
	　　　　　　店No
	　　　　　　フィールドリスト
	　　　　　　条件
	戻り値　　：ブログリスト
	********************/
	private function readMainONE($groupNo ,$branchNo ,$fldArr ,$where) {

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
	ブログの更新
	パラメータ：グループNo
	　　　　　　店No
	　　　　　　ブログNo
	　　　　　　タイトル
	　　　　　　種類
	　　　　　　概要
	　　　　　　本文
	　　　　　　記事日付
	　　　　　　記事期間
	　　　　　　表示開始日時
	　　　　　　記事開始日
	　　　　　　記事終了日
	戻り値　　：
	********************/
	function upd($groupNo ,$branchNo ,$newsNo ,$title ,$category ,$content ,$newsDate ,$dispBeg) {

				logFile5C::debug('既存ブログ更新 No.' . $newsNo);
		$updDT = dateTime5C::getCurrDT();

		$db = $this->handle;

		if(strlen($dispBeg) >= 1) {
			$dispBegStr = self::FLD_DISP_BEG . '=' . $db->setQuote($dispBeg) . ',';
		} else {
			$dispBegStr = '';
		}

		$digest  = '';
		$termStr = '';
		$dispBDateStr = self::FLD_BEG_DATE . '=null' . ',';
		$dispEDateStr = self::FLD_END_DATE . '=null' . ',';


		$fldList =
			self::FLD_TITLE    . '=' . $db->setQuote($title   ) . ',' .
			self::FLD_CATE     . '=' . $db->setQuote($category) . ',' .
			self::FLD_DIGEST   . '=' . $db->setQuote($digest  ) . ',' .
			self::FLD_CONTENT  . '=' . $db->setQuote($content ) . ',' .
			self::FLD_DATE     . '=' . $db->setQuote($newsDate) . ',' .
			$termStr    .
			$dispBegStr .

			$dispBDateStr .
			$dispEDateStr .

			self::FLD_UPD_DT   . '=' . $db->setQuote($updDT);
			/* self::FLD_SHOWNはそのまま */

		$where =
			self::FLD_GROUP_NO  . '=' . $groupNo  . ' and ' .
			self::FLD_BRANCH_NO . '=' . $branchNo . ' and ' .
			self::FLD_ADD_DT    . '=' . $db->setQuote($newsNo);	/* 記事日付のレコード */

		$newsList = $db->updateRec(self::TABLE_NAME ,$fldList ,$where);
	}


	/********************
	ブログの追加
	パラメータ：グループNo
	　　　　　　店No
	　　　　　　タイトル
	　　　　　　種類
	　　　　　　概要
	　　　　　　本文
	　　　　　　記事日付
	　　　　　　表示開始日時
	戻り値　　：
	********************/
	function add($groupNo ,$branchNo ,$title ,$category ,$content ,$newsDate ,$disp ,$dispBeg) {

				logFile5C::debug('新規ブログ　　' . ' **CONTENT:' . $content . ' **DATE:' . $newsDate . ' **DISPBEG:' . $dispBeg);

		$addDT = dateTime5C::getCurrDT();

		$db = $this->handle;

		$fldArr = array(
			self::FLD_GROUP_NO  ,
			self::FLD_BRANCH_NO ,
			self::FLD_TITLE     ,
			self::FLD_CATE      ,
			self::FLD_CONTENT   ,
			self::FLD_DATE      ,
			self::FLD_DISP      ,

			self::FLD_ADD_DT
		);

		$valueList =
			$groupNo  . ',' .
			$branchNo . ',' .
			$db->setQuote($title)    . ',' .
			$db->setQuote($category) . ',' .
			$db->setQuote($content)  . ',' .
			$db->setQuote($newsDate) . ',' .
			$db->setQuote($disp)     . ',' .

			$db->setQuote($addDT);

		/*
		if(strlen($term) >= 1) {
			$fldArr[] = self::FLD_TERM;
			$valueList = $valueList  . ',' . $db->setQuote($term);
		}
		*/

		if(strlen($dispBeg) >= 1) {
			$fldArr[] = self::FLD_DISP_BEG;
			$valueList = $valueList  . ',' . $db->setQuote($dispBeg);
		}

		/*
		if(strlen($begDate) >= 1) {
			$fldArr[] = self::FLD_BEG_DATE;
			$valueList = $valueList  . ',' . $db->setQuote($begDate);
		}

		if(strlen($endDate) >= 1) {
			$fldArr[] = self::FLD_END_DATE;
			$valueList = $valueList  . ',' . $db->setQuote($endDate);
		}
		*/

		$fldList = funcs5C::setFldArrToList($fldArr);
		$db->insertRec(self::TABLE_NAME ,$fldList ,$valueList);

		return $addDT;
	}


	/********************
	ブログの削除
	パラメータ：グループNo
	　　　　　　店No
	　　　　　　ブログNo
	戻り値　　：
	********************/
	function setDelMark($groupNo ,$branchNo ,$newsNo) {

				logFile5C::debug('既存ブログ削除 No.' . $newsNo);
		$updDT = dateTime5C::getCurrDT();

		$db = $this->handle;
		$fldList =
			self::FLD_DISP    . '=' . $db->setQuote(self::DISP_DEL) . ',' .
			self::FLD_UPD_DT  . '=' . $db->setQuote($updDT);

		$where =
			self::FLD_GROUP_NO  . '=' . $groupNo  . ' and ' .
			self::FLD_BRANCH_NO . '=' . $branchNo . ' and ' .
			self::FLD_ADD_DT    . '=' . $db->setQuote($newsNo);	/* 記事日付のレコード */

		$newsList = $db->updateRec(self::TABLE_NAME ,$fldList ,$where);
	}


	/********************
	出力日時経過
	パラメータ：グループNo
	　　　　　　店No
	　　　　　　ブログNo
	戻り値　　：
	********************/
	function setDispMark($groupNo ,$branchNo ,$newsNo) {

				logFile5C::debug('出力日時経過 No.' . $newsNo);
		$updDT = dateTime5C::getCurrDT();

		$db = $this->handle;
		$fldList =
			self::FLD_SHOWN   . '=' . $db->setQuote(self::SHOWN_SHOWN) . ',' .
			self::FLD_UPD_DT  . '=' . $db->setQuote($updDT);

		$where =
			self::FLD_GROUP_NO  . '=' . $groupNo  . ' and ' .
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

		$groupNo  = $dataList['groupNo'];
		$branchNo = $dataList['branchNo'];

		$updDT = dateTime5C::getCurrDT();

		$db = $this->handle;

		$fldArr = array(
			self::FLD_DISP   ,
			self::FLD_ADD_DT
		);

		$where =
			self::FLD_GROUP_NO  . '='  . $groupNo  . ' and ' .
			self::FLD_BRANCH_NO . '='  . $branchNo;

		$ret = $this->readMain($groupNo ,$branchNo ,$fldArr ,$where);

		$dispCnt = 0;
		$idxMax = $ret['count'];

		$prevNews = $ret['blogInfo'];

		for($idx=0 ;$idx<$idxMax ;$idx++) {
			$news1 = $prevNews[$idx];
			$newsDT = $news1[self::FLD_ADD_DT];

			$newsDTExp = explode(' ' ,$newsDT);
			$dateExp = explode('-' ,$newsDTExp[0]);
			$timeExp = explode(':' ,$newsDTExp[1]);

			$dataID = '' . $dateExp[0] . $dateExp[1] . $dateExp[2] . '_' . $timeExp[0] . $timeExp[1] . $timeExp[2];

			if(isset($dataList[$dataID])) {
				$disp = $dataList[$dataID];

				$fldList =
					self::FLD_DISP    . '=' . $db->setQuote($disp) . ',' .
					self::FLD_UPD_DT  . '=' . $db->setQuote($updDT);

				$where =
					self::FLD_GROUP_NO  . '=' . $groupNo  . ' and ' .
					self::FLD_BRANCH_NO . '=' . $branchNo . ' and ' .
					self::FLD_ADD_DT    . '=' . $db->setQuote($newsDT);	/* 記事日付のレコード */

				$result = $db->updateRec(self::TABLE_NAME ,$fldList ,$where);
				$dispCnt++;
			}
		}

		return $dispCnt;
	}


/*****************************************************************************************************************************/



	/********************
	ブログリストの読み込み(表示用)
	パラメータ：グループNo
	　　　　　　店No
	戻り値　　：ブログリスト
	********************/
	function readForShow($groupNo ,$branchNo) {

		/***** リストファイルの読み込み *****/
		$fileName = $this->getFileName($groupNo ,$branchNo);
		$newsLine = funcs5C::readFile($fileName['path'] . '.' . $fileName['ext']);

		$json = new Services_JSON();
		$jsonData = $json->decode($newsLine);

		$idx = 1;
		foreach($jsonData as $read1) {
			$tempValue = (array)$read1;
			$newsIdx[$idx] = $tempValue;		/* $ret['blogInfo'][$idx] = (array)$value; */
			$idx++;
		}

		/***** データ本体の読み込み *****/
		$news[0][self::FLD_TITLE   ] = '';
		$news[0][self::FLD_CATE    ] = '';
		$news[0][self::FLD_DIGEST  ] = '';
		$news[0][self::FLD_CONTENT ] = '';
		$news[0][self::FLD_DATE    ] = '';
		$news[0][self::FLD_TERM    ] = '';
		$news[0][self::FLD_DISP    ] = '';
		$news[0][self::FLD_DISP_BEG] = '';
		$news[0][self::FLD_SHOWN   ] = '';

		$news[0][self::FLD_BEG_DATE] = '';
		$news[0][self::FLD_END_DATE] = '';

		$news[0][self::FLD_ADD_DT  ] = '';
		$news[0][self::FLD_UPD_DT  ] = '';

		$listIdx = 1;
		$idxMax = count($newsIdx);
		for($idx=1 ;$idx<=$idxMax ;$idx++) {

			if(strcmp($newsIdx[$idx][self::FLD_DISP] ,self::DISP_DEL) != 0) {	/* 「削除」でなければ追加 */
				$newsLine = funcs5C::readFile($fileName['path'] . '-' . $newsIdx[$idx][self::FLD_NO] . '.' . $fileName['ext']);
				$dataLine = $this->readData($newsLine);
				$news[$listIdx] = $dataLine;
				$news[$listIdx][self::FLD_DISP] = $newsIdx[$idx][self::FLD_DISP];
				$listIdx++;
			}

		}

		$ret['blogInfo'] = $news;
		$ret['count'   ] = count($news);

		return $ret;
	}


	/********************
	ブログリストの読み込み
	パラメータ：グループNo
	　　　　　　店No
	戻り値　　：ブログリスト
	********************/
	function read($groupNo ,$branchNo) {

		/***** リストファイルの読み込み *****/
		$fileName = $this->getFileName($groupNo ,$branchNo);
		$newsLine = funcs5C::readFile($fileName['path'] . '.' . $fileName['ext']);

		$json = new Services_JSON();
		$jsonData = $json->decode($newsLine);

		$idx = 1;
		foreach($jsonData as $read1) {
			$tempValue = (array)$read1;
			$newsIdx[$idx] = $tempValue;		/* $ret['blogInfo'][$idx] = (array)$value; */
			$idx++;
		}

		/***** データ本体の読み込み *****/
		$news[0][self::FLD_TITLE   ] = '';
		$news[0][self::FLD_CATE    ] = '';
		$news[0][self::FLD_DIGEST  ] = '';
		$news[0][self::FLD_CONTENT ] = '';
		$news[0][self::FLD_DATE    ] = '';
		$news[0][self::FLD_TERM    ] = '';
		$news[0][self::FLD_DISP    ] = '';
		$news[0][self::FLD_DISP_BEG] = '';
		$news[0][self::FLD_SHOWN   ] = '';

		$news[0][self::FLD_BEG_DATE] = '';
		$news[0][self::FLD_END_DATE] = '';

		$news[0][self::FLD_ADD_DT  ] = '';
		$news[0][self::FLD_UPD_DT  ] = '';

		$listIdx = 1;
		$idxMax = count($newsIdx);
		for($idx=1 ;$idx<=$idxMax ;$idx++) {
			if(strcmp($newsIdx[$idx][self::FLD_DISP] ,self::DISP_DEL) != 0) {	/* 「削除」でなければ追加 */
				$newsLine = funcs5C::readFile($fileName['path'] . '-' . $newsIdx[$idx][self::FLD_NO] . '.' . $fileName['ext']);
				$dataLine = $this->readData($newsLine);
				$news[$listIdx] = $dataLine;
				$news[$listIdx][self::FLD_DISP] = $newsIdx[$idx][self::FLD_DISP];
				$listIdx++;
			}
		}

		$ret['blogInfo'] = $news;
		$ret['count'   ] = count($news);

		return $ret;
	}


	/********************
	ブログリストの読み込み
	パラメータ：グループNo
	　　　　　　店No
	戻り値　　：ブログリスト
	********************/
	function readShowableOLD($groupNo ,$branchNo ,$dt) {

		/***** リストファイルの読み込み *****/
		$fileName = $this->getFileName($groupNo ,$branchNo);
		$newsLine = funcs5C::readFile($fileName['path'] . '.' . $fileName['ext']);

		$json = new Services_JSON();
		$jsonData = $json->decode($newsLine);

		$idx = 1;
		foreach($jsonData as $read1) {
			$tempValue = (array)$read1;
			$newsIdx[$idx] = $tempValue;		/* $ret['blogInfo'][$idx] = (array)$value; */
			$idx++;
		}

		/***** データ本体の読み込み *****/
		$news[0][self::FLD_TITLE   ] = '';
		$news[0][self::FLD_CATE    ] = '';
		$news[0][self::FLD_DIGEST  ] = '';
		$news[0][self::FLD_CONTENT ] = '';
		$news[0][self::FLD_DATE    ] = '';
		$news[0][self::FLD_TERM    ] = '';
		$news[0][self::FLD_DISP    ] = '';
		$news[0][self::FLD_DISP_BEG] = '';
		$news[0][self::FLD_SHOWN   ] = '';

		$news[0][self::FLD_BEG_DATE] = '';
		$news[0][self::FLD_END_DATE] = '';

		$news[0][self::FLD_ADD_DT  ] = '';
		$news[0][self::FLD_UPD_DT  ] = '';

		$idxMax = count($newsIdx);
		$validIdx = 1;
		$updated = '';

		/***
			1.「表示」がONで、
			2.表示開始日時の指定があり、
			3.表示時刻が過ぎている
			もののみ抽出する
		***/
		for($idx=1 ;$idx<=$idxMax ;$idx++) {
			if(strcmp($newsIdx[$idx][self::FLD_DISP] ,self::DISP_ON) == 0) {	/* 「表示」がONのもの */

				$newsLine = funcs5C::readFile($fileName['path'] . '-' . $newsIdx[$idx][self::FLD_NO] . '.' . $fileName['ext']);
				$dataLine = $this->readData($newsLine);

				/*****
					表示開始日時の指定があるもの…表示時刻経過後であれば表示
					表示開始日時の指定がないもの…無条件に表示
				*****/
				$disp = false;
				if(strlen($dataLine[self::FLD_DISP_BEG]) >= 1) {
					$timeDiff = dateTime5C::compareDT($dt ,$dataLine[self::FLD_DISP_BEG] . ':00');
					if($timeDiff <= 0) {
						$updated = 'upd';
						$disp = true;
					}
				} else {
					$disp = true;
				}

				if($disp) {
					$news[$validIdx] = $dataLine;		/* $news['blogInfo'][$idx] = (array)$value; */
					$validIdx++;
				}
			}
		}

		$ret['blogInfo'] = $news;
		$ret['count'   ] = count($news);
		$ret['upd'     ] = $updated;

		return $ret;
	}


	/********************
	表示ONと表示時刻経過後のブログリストの読み込み
	パラメータ：グループNo
	　　　　　　店No
	　　　　　　日時
	戻り値　　：ブログリスト
	********************/
	function readDispBeg($groupNo ,$branchNo ,$dt) {

		$updated = '';

		/***** リストファイルの読み込み *****/
		$fileName = $this->getFileName($groupNo ,$branchNo);
		$newsLine = funcs5C::readFile($fileName['path'] . '.' . $fileName['ext']);

		$json = new Services_JSON();
		$jsonData = $json->decode($newsLine);

		$idx = 1;
		foreach($jsonData as $read1) {
			$tempValue = (array)$read1;
			$newsIdx[$idx] = $tempValue;		/* $ret['blogInfo'][$idx] = (array)$value; */
			$idx++;
		}

		/***** データ本体の読み込み *****/
		$news[0][self::FLD_TITLE   ] = '';
		$news[0][self::FLD_CATE    ] = '';
		$news[0][self::FLD_DIGEST  ] = '';
		$news[0][self::FLD_CONTENT ] = '';
		$news[0][self::FLD_DATE    ] = '';
		$news[0][self::FLD_TERM    ] = '';
		$news[0][self::FLD_DISP    ] = '';
		$news[0][self::FLD_DISP_BEG] = '';
		$news[0][self::FLD_SHOWN   ] = '';

		$news[0][self::FLD_BEG_DATE] = '';
		$news[0][self::FLD_END_DATE] = '';

		$news[0][self::FLD_ADD_DT  ] = '';
		$news[0][self::FLD_UPD_DT  ] = '';

		$idxMax = count($newsIdx);
		$validIdx = 1;
		for($idx=1 ;$idx<=$idxMax ;$idx++) {
			if(strcmp($newsIdx[$idx][self::FLD_DISP] ,self::DISP_ON) == 0) {	/* 「表示」のもの */
				$newsLine = funcs5C::readFile($fileName['path'] . '-' . $newsIdx[$idx][self::FLD_NO] . '.' . $fileName['ext']);
				$dataLine = $this->readData($newsLine);


				/* 表示時刻経過後であれば表示 */
				if(strlen($dataLine[self::FLD_DISP_BEG]) >= 1) {	/* 表示開始日時があるも */


					$timeDiff = dateTime5C::compareDT($dt ,$dataLine[self::FLD_DISP_BEG]);
					if($timeDiff >= 0) {
						$updated = 'upd';
						$news[$validIdx] = $dataLine;		/* $news['blogInfo'][$idx] = (array)$value; */
						$validIdx++;
					}
				}


			}
		}

		$ret['blogInfo'] = $news;
		$ret['count'   ] = count($news);
		$ret['upd'     ] = $updated;

		return $ret;
	}


	function readData($newsLine) {

		$json = new Services_JSON();
		$jsonData = $json->decode($newsLine);

		$ret[self::FLD_TITLE   ] = '';
		$ret[self::FLD_CATE    ] = '';
		$ret[self::FLD_DIGEST  ] = '';
		$ret[self::FLD_CONTENT ] = '';
		$ret[self::FLD_DATE    ] = '';
		$ret[self::FLD_TERM    ] = '';
		$ret[self::FLD_DISP_BEG] = '';
		$ret[self::FLD_SHOWN   ] = '';

		$ret[self::FLD_BEG_DATE] = '';
		$ret[self::FLD_END_DATE] = '';

		$ret[self::FLD_ADD_DT  ] = '';
		$ret[self::FLD_UPD_DT  ] = '';

		$tempValue = (array)$jsonData;


		if(isset($tempValue[self::FLD_TITLE])) {
			$ret[self::FLD_TITLE] = $tempValue[self::FLD_TITLE];
		}

		if(isset($tempValue[self::FLD_CATE])) {
			$ret[self::FLD_CATE] = $tempValue[self::FLD_CATE];
		}

		if(isset($tempValue[self::FLD_DIGEST])) {
			$ret[self::FLD_DIGEST] = mb_ereg_replace('\\\"' ,'"' ,$tempValue[self::FLD_DIGEST]);
		}

		if(isset($tempValue[self::FLD_CONTENT])) {
			$ret[self::FLD_CONTENT] = mb_ereg_replace('\\\"' ,'"' ,$tempValue[self::FLD_CONTENT]);
		}

		if(isset($tempValue[self::FLD_DATE])) {
			$ret[self::FLD_DATE] = $tempValue[self::FLD_DATE];
		}

		if(isset($tempValue[self::FLD_TERM])) {
			$ret[self::FLD_TERM] = $tempValue[self::FLD_TERM];
		}

		if(isset($tempValue[self::FLD_DISP_BEG])) {
			$ret[self::FLD_DISP_BEG] = $tempValue[self::FLD_DISP_BEG];
		}

		if(isset($tempValue[self::FLD_SHOWN])) {
			$ret[self::FLD_SHOWN] = $tempValue[self::FLD_SHOWN];
		}


		if(isset($tempValue[self::FLD_BEG_DATE])) {
			$ret[self::FLD_BEG_DATE] = $tempValue[self::FLD_BEG_DATE];
		}

		if(isset($tempValue[self::FLD_END_DATE])) {
			$ret[self::FLD_END_DATE] = $tempValue[self::FLD_END_DATE];
		}


		if(isset($tempValue[self::FLD_ADD_DT])) {
			$ret[self::FLD_ADD_DT] = $tempValue[self::FLD_ADD_DT];
		}

		if(isset($tempValue[self::FLD_UPD_DT])) {
			$ret[self::FLD_UPD_DT] = $tempValue[self::FLD_UPD_DT];
		}

		return $ret;
	}






	/********************
	ブログ表示の更新
	パラメータ：グループNo
	　　　　　　店No
	　　　　　　ブログNo
	　　　　　　表示
	戻り値　　：
	********************/
	function updShow($groupNo ,$branchNo ,$newsNo ,$shown) {

		$updDT = dateTime5C::getCurrDT();

		/*** 既存ブログの読み込み ***/
		$fileName = $this->getFileName($groupNo ,$branchNo);

		$fileFullPath = $fileName['path'] . '-' . $newsNo . '.' . $fileName['ext'];
		$currNews = funcs5C::readFile($fileFullPath);

		$json = new Services_JSON();
		$read1 = $json->decode($currNews);
		$jsonData = (array)$read1;


		/*** 既存ブログ更新 ***/
		$jsonData[self::FLD_SHOWN ] = $shown;
		$jsonData[self::FLD_UPD_DT] = $updDT;

		$this->write($groupNo ,$branchNo ,$newsNo ,$jsonData);
	}


	/********************
	ブログの削除
	パラメータ：グループNo
	　　　　　　店No
	　　　　　　ブログNo
	戻り値　　：
	********************/
	function del($groupNo ,$branchNo ,$newsNo) {

		/****** リストファイルの更新 ******/
		$fileName = $this->getFileName($groupNo ,$branchNo);
		$newsLine = funcs5C::readFile($fileName['path'] . '.' . $fileName['ext']);

		$json = new Services_JSON();
		$jsonData = $json->decode($newsLine);

		$idx = 1;
		foreach($jsonData as $read1) {
			$tempValue = (array)$read1;
			$newsData[$idx] = $tempValue;		/* $ret['blogInfo'][$idx] = (array)$value; */
			$idx++;
		}

		$idxMax = count($newsData);
		for($idx=1 ;$idx<=$idxMax ;$idx++) {
			if($newsData[$idx][self::FLD_NO] == $newsNo) {
				$newsData[$idx][self::FLD_DISP] = self::DISP_DEL;
			}
		}

		/*** リストの再出力 ***/
		$jsonStr = $json->encode($newsData);
		$fp = fopen($fileName['path'] . '.' . $fileName['ext'] ,'w');
		fwrite($fp ,$jsonStr);
		fclose($fp);
	}


	function write($groupNo ,$branchNo ,$newsNo ,$newsData) {

		$json = new Services_JSON();
		$jsonStr = $json->encode($newsData);

		/*** ブログの再出力 ***/
		$fileName = $this->getFileName($groupNo ,$branchNo);

		$fileFullPath = $fileName['path'] . '-' . $newsNo . '.' . $fileName['ext'];

		$fp = fopen($fileFullPath ,'w');
		fwrite($fp ,$jsonStr);
		fclose($fp);

		chmod($fileFullPath ,0744);
	}


	/********************
	表示可否の更新
	パラメータ：データリスト
	戻り値　　：
	********************/
	function updDispOLD($dataList) {

		$groupNo  = $dataList['groupNo'];
		$branchNo = $dataList['branchNo'];

						/*** 既存ブログの読み込み ***/
			/*
					$newsList = $this->readAll($groupNo ,$branchNo);
					$max = $newsList['count'];
			*/


		/***** リストファイルの読み込み *****/
		$fileName = $this->getFileName($groupNo ,$branchNo);
		$newsLine = funcs5C::readFile($fileName['path'] . '.' . $fileName['ext']);

		$json = new Services_JSON();
		$jsonData = $json->decode($newsLine);

		$idx = 1;
		foreach($jsonData as $read1) {
			$tempValue = (array)$read1;
			$currNews[$idx] = $tempValue;		/* $ret['blogInfo'][$idx] = (array)$value; */
			$idx++;
		}

					/*		$currNews = $newsList['blogInfo']; */
		$max = count($currNews);
		/*** ブログごとの表示可否の更新 ***/
				logFile5C::debug('ブログの表示可否');
		for($i=1 ;$i<=$max ;$i++) {
/*			$dataID = 'dispAll' . $currNews[$i][self::FLD_NO];*/
			$dataID = 'disp' . $currNews[$i][self::FLD_NO];

			$show[$i] = $currNews[$i];
			if(strcmp($show[$i][self::FLD_DISP] ,self::DISP_DEL) != 0) {		/* 「削除」でなければ更新 */
//				if(isset($dataList[$dataID])) {
//					/*** 表示データがあれば表示 ***/
//						logFile5C::debug('NewsID:' . $dataID . '  表示');
//					$disp = self::DISP_ON;
//					$dispCnt++;
//				} else {
//					/*** 表示データがなければ非表示 ***/
//						logFile5C::debug('NewsID:' . $dataID . '  非表示');
//					$disp = self::DISP_OFF;
//				}
//				$show[$i][self::FLD_NO  ] = $currNews[$i][self::FLD_NO];
//				$show[$i][self::FLD_DISP] = $disp;

				if(isset($dataList[$dataID])) {
					$disp = $dataList[$dataID];
					$show[$i][self::FLD_NO  ] = $currNews[$i][self::FLD_NO];
					$show[$i][self::FLD_DISP] = $disp;
				}
			}
		}

		$dispCnt = 0;
		for($i=1 ;$i<=$max ;$i++) {
			$disp = $show[$i][self::FLD_DISP];

			if(strcmp($disp ,'U') == 0) {
				$dispCnt++;
			}
		}

			logFile5C::debug('ブログ出力:' . $dispCnt . '件');
		if($dispCnt >= 1) {
			/***** ブログの再出力 *****/
				logFile5C::debug('ブログ再出力:' . $dispCnt . '件');
			$json = new Services_JSON();
			$jsonStr = $json->encode($show);
			$fileName = $this->getFileName($groupNo ,$branchNo);
			$fp = fopen($fileName['path'] . '.' . $fileName['ext'] ,'w');
			fwrite($fp ,$jsonStr);
			fclose($fp);
		}

		return $dispCnt;
	}






	/********************
	ファイル名構築
	パラメータ：グループNo
	　　　　　　店No
	戻り値　　：
	********************/
	function getFileName($groupNo ,$branchNo) {

		$noS = $this->setFormat($groupNo ,$branchNo);
		$ret['path'] = realpath(dirname(__FILE__) . '/../../dataFiles') . '/news' . $noS['groupNo'] . $noS['branchNo'];
		$ret['ext' ] = 'json';

		return $ret;
	}

	/********************
	Noパディング
	パラメータ：グループNo
	　　　　　　店No
	戻り値　　：
	********************/
	function setFormat($groupNo ,$branchNo) {

		$ret['groupNo' ] = sprintf('%03d' ,$groupNo);
		$ret['branchNo'] = sprintf('%03d' ,$branchNo);

		return $ret;
	}
}
?>
