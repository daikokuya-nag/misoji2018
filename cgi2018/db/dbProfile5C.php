<?php
/*************************
プロファイルデータ Version 1.2
PHP5
*************************/

	require_once dirname(__FILE__) . '/dbWorks5C.php';
	require_once dirname(__FILE__) . '/../sql5C.php';
	require_once dirname(__FILE__) . '/../dateTime5C.php';
	require_once dirname(__FILE__) . '/../common5C.php';
	require_once dirname(__FILE__) . '/../strings5C.php';
	require_once dirname(__FILE__) . '/../funcs5C.php';

class dbProfile5C {

	/*** テーブル名 ***/
	const TABLE_NAME = 'profile2018'; 

	/*** フィールド ***/
	const FLD_BRANCH_NO  = 'branchNo';	/* 店No */

	const FLD_DIR        = 'dir';		/* プロファイルID */
	const FLD_DISP_SEQ   = 'dispSeq';	/* 表示順 */
	const FLD_DISP       = 'disp';		/* 表示/非表示 */

	const FLD_NAME       = 'name';		/* 名前 */
	const FLD_AGE        = 'age';		/* 年齢 */

	const FLD_HEIGHT     = 'height';	/* 身長 */
	const FLD_SIZES      = 'sizes';		/* スリーサイズ */
	const FLD_ZODIAC     = 'zodiac';	/* 星座 */
	const FLD_BLOODTYPE  = 'bloodType';	/* 血液型 */

	const FLD_NEWFACE    = 'newFace';	/* 新人 */
	const FLD_WORK_DAY   = 'workDay';	/* 出勤日 */
	const FLD_REST_DAY   = 'restDay';	/* 公休日 */
	const FLD_WORK_TIME  = 'workTime';	/* 出勤時間 */
	const FLD_MASTERS_COMMENT = 'mastersComment';	/* 店長のコメント */
	const FLD_APPEAL_COMMENT  = 'appealComment';	/* アピールコメント */

	const FLD_PHOTOUSE_1 = 'photoUse_PC1';	/* 写真使用1 */
	const FLD_PHOTOUSE_2 = 'photoUse_PC2';	/* 写真使用2 */
	const FLD_PHOTOUSE_3 = 'photoUse_PC3';	/* 写真使用3 */
	const FLD_PHOTOUSE_4 = 'photoUse_PC4';	/* 写真使用4 */
	const FLD_PHOTOUSE_5 = 'photoUse_PC5';	/* 写真使用5 */

	const FLD_PHOTOUSE_S = 'photoUse_TN';	/* 写真使用サムネイル */
	const FLD_PHOTOUSE_M = 'photoUse_ML';	/*  */

	const FLD_PHOTOEXT_1 = 'photoExt_PC1';	/* 写真ファイル拡張子1 */
	const FLD_PHOTOEXT_2 = 'photoExt_PC2';	/* 写真ファイル拡張子2 */
	const FLD_PHOTOEXT_3 = 'photoExt_PC3';	/* 写真ファイル拡張子3 */
	const FLD_PHOTOEXT_4 = 'photoExt_PC4';	/* 写真ファイル拡張子4 */
	const FLD_PHOTOEXT_5 = 'photoExt_PC5';	/* 写真ファイル拡張子5 */

	const FLD_PHOTOEXT_S = 'photoExt_TN';	/* 写真ファイル拡張子サムネイル */
	const FLD_PHOTOEXT_M = 'photoExt_ML';	/*  */

	const FLD_PHOTO_SHOW = 'photoShow';	/* 写真使用(表示可 or NG or 準備中) */

	const FLD_PCD = 'pcd';	/* ログインパスコード */

		/*** Bデータ ***/
	const FLD_B1  = 'B1';
	const FLD_B2  = 'B2';
	const FLD_B3  = 'B3';
	const FLD_B4  = 'B4';
	const FLD_B5  = 'B5';
	const FLD_B6  = 'B6';
	const FLD_B7  = 'B7';
	const FLD_B8  = 'B8';
	const FLD_B9  = 'B9';
	const FLD_B10 = 'B10';
	const FLD_B11 = 'B11';
	const FLD_B12 = 'B12';
	const FLD_B13 = 'B13';


		/*** QA ***/
	const FLD_QA1  = 'QA1';
	const FLD_QA2  = 'QA2';
	const FLD_QA3  = 'QA3';
	const FLD_QA4  = 'QA4';
	const FLD_QA5  = 'QA5';
	const FLD_QA6  = 'QA6';
	const FLD_QA7  = 'QA7';
	const FLD_QA8  = 'QA8';
	const FLD_QA9  = 'QA9';
	const FLD_QA10 = 'QA10';
	const FLD_QA11 = 'QA11';
	const FLD_QA12 = 'QA12';
	const FLD_QA13 = 'QA13';
	const FLD_QA14 = 'QA14';


	const FLD_ADD_DT = 'addDT';		/* レコード登録日時 */
	const FLD_UPD_DT = 'updDT';		/* レコード更新日時 */


	/***** 定数 *****/
	const DISP_ON   = 'U';	/* 表示 */
	const DISP_OFF  = 'N';	/* 非表示 */
	const DISP_DEL  = 'D';	/* 削除 */

	const WORK_ON   = 'W';	/* 出勤 */
	const REST_ON   = 'R';	/* 休 */

	const PHOTO_USE = 'U';	/* 写真使用 */
	const PHOTO_NOT = 'N';	/* 写真非使用 */

	const PHOTO_SHOW_OK  = 'SHOW';		/* 表示可 */
	const PHOTO_SHOW_NG  = 'NG';		/* 写真NG */
	const PHOTO_SHOW_NP  = 'NP';		/* 写真準備中 */
	const PHOTO_SHOW_NOT = 'NO';		/* 写真なし */


	const BDATA_NUMS = 13;

	/*** 新人 ***/
	const NEW_FACE = 'N';


	var $handle;

	var $branchNo;

	var $dispSeq;

	var $vals;

	/********************
	コンストラクタ(DB接続)
	パラメータ：-
	戻り値　　：-
	********************/
	function dbProfile5C($branchNo ,$handle=null) {

		if(is_null($handle)) {
			$this->handle = new sql5C();
		} else {
			$this->handle = $handle;
		}

		$this->branchNo = $branchNo;

		$this->vals = array();
	}

	function setVal($fld ,$val) {

		$this->vals[$fld] = $val;
	}

	/********************
	プロファイルデータの読み込み(削除以外)
	パラメータ：-
	戻り値　　：プロファイルリスト
	********************/
	function readAll() {

		$db = $this->handle;

		$fldArr = array(
			self::FLD_DIR     ,self::FLD_DISP_SEQ  ,self::FLD_DISP ,
			self::FLD_NAME    ,self::FLD_AGE   ,
			self::FLD_HEIGHT  ,self::FLD_SIZES , self::FLD_ZODIAC ,self::FLD_BLOODTYPE ,
			self::FLD_NEWFACE ,self::FLD_WORK_TIME ,self::FLD_WORK_DAY ,self::FLD_REST_DAY ,self::FLD_MASTERS_COMMENT ,self::FLD_APPEAL_COMMENT ,
			self::FLD_PHOTO_SHOW ,

			self::FLD_PHOTOUSE_1 ,self::FLD_PHOTOUSE_2 ,self::FLD_PHOTOUSE_3 ,self::FLD_PHOTOUSE_4 ,self::FLD_PHOTOUSE_5 ,
			self::FLD_PHOTOUSE_S ,self::FLD_PHOTOUSE_M ,

			self::FLD_PHOTOEXT_1 ,self::FLD_PHOTOEXT_2 ,self::FLD_PHOTOEXT_3 ,self::FLD_PHOTOEXT_4 ,self::FLD_PHOTOEXT_5 ,
			self::FLD_PHOTOEXT_S ,self::FLD_PHOTOEXT_M ,

			self::FLD_B1 ,self::FLD_B2 ,self::FLD_B3 ,self::FLD_B4 ,self::FLD_B5 ,
			self::FLD_B6 ,self::FLD_B7 ,self::FLD_B8 ,self::FLD_B9 ,self::FLD_B10 ,
			self::FLD_B11 ,self::FLD_B12 ,self::FLD_B13 ,

			self::FLD_QA1 ,self::FLD_QA2 ,self::FLD_QA3 ,self::FLD_QA4 ,self::FLD_QA5 ,
			self::FLD_QA6 ,self::FLD_QA7 ,self::FLD_QA8 ,self::FLD_QA9 ,self::FLD_QA10 ,
			self::FLD_QA11 ,self::FLD_QA12 ,self::FLD_QA13 ,self::FLD_QA14 ,

			self::FLD_ADD_DT ,self::FLD_UPD_DT
		);
		$where =
			self::FLD_BRANCH_NO . '='  . $this->branchNo . ' and ' .
			self::FLD_DISP      . '!=' . $db->setQuote(self::DISP_DEL);	/* 削除でないレコード */

		$ret = $this->readMain($fldArr ,$where);

		return $ret;
	}

	/********************
	プロファイルデータの読み込み(削除以外)
	パラメータ：-
	戻り値　　：プロファイルリスト
	********************/
	function readAllName() {

		$db = $this->handle;

		$fldArr = array(
			self::FLD_DIR ,self::FLD_DISP_SEQ ,self::FLD_DISP ,self::FLD_NAME ,self::FLD_UPD_DT);
		$where =
			self::FLD_BRANCH_NO . '='  . $this->branchNo . ' and ' .
			self::FLD_DISP      . '!=' . $db->setQuote(self::DISP_DEL);	/* 削除でないレコード */

		$ret = $this->readMain($fldArr ,$where);

		return $ret;
	}

	/********************
	表示可のプロファイルデータの読み込み
	パラメータ：-
	戻り値　　：プロファイルリスト
	********************/
	function readShowableProf() {

		$db = $this->handle;

		$fldArr = array(
			self::FLD_DIR     ,self::FLD_DISP_SEQ ,self::FLD_DISP ,
			self::FLD_NAME    ,self::FLD_AGE   ,
			self::FLD_HEIGHT  ,self::FLD_SIZES , self::FLD_ZODIAC , self::FLD_BLOODTYPE ,
			self::FLD_NEWFACE ,self::FLD_WORK_TIME ,self::FLD_WORK_DAY ,self::FLD_REST_DAY ,self::FLD_MASTERS_COMMENT ,self::FLD_APPEAL_COMMENT ,
			self::FLD_PHOTO_SHOW ,

			self::FLD_PHOTOUSE_1 ,self::FLD_PHOTOUSE_2 ,self::FLD_PHOTOUSE_3 ,self::FLD_PHOTOUSE_4 ,self::FLD_PHOTOUSE_5 ,
			self::FLD_PHOTOUSE_S ,self::FLD_PHOTOUSE_M ,

			self::FLD_PHOTOEXT_1 ,self::FLD_PHOTOEXT_2 ,self::FLD_PHOTOEXT_3 ,self::FLD_PHOTOEXT_4 ,self::FLD_PHOTOEXT_5 ,
			self::FLD_PHOTOEXT_S ,self::FLD_PHOTOEXT_M ,

			self::FLD_B1 ,self::FLD_B2 ,self::FLD_B3 ,self::FLD_B4 ,self::FLD_B5 ,
			self::FLD_B6 ,self::FLD_B7 ,self::FLD_B8 ,self::FLD_B9 ,self::FLD_B10 ,
			self::FLD_B11 ,self::FLD_B12 ,self::FLD_B13 ,

			self::FLD_QA1 ,self::FLD_QA2 ,self::FLD_QA3 ,self::FLD_QA4 ,self::FLD_QA5 ,
			self::FLD_QA6 ,self::FLD_QA7 ,self::FLD_QA8 ,self::FLD_QA9 ,self::FLD_QA10 ,
			self::FLD_QA11 ,self::FLD_QA12 ,self::FLD_QA13 ,self::FLD_QA14 ,

			self::FLD_ADD_DT ,self::FLD_UPD_DT
		);
		$where =
			self::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .
			self::FLD_DISP      . '=' . $db->setQuote(self::DISP_ON);	/* 表示可のレコード */

		$ret = $this->readMain($fldArr ,$where);

		return $ret;
	}

	/********************
	検索画面で指定されたBデータの読み込み
	パラメータ：Bデータリスト
	戻り値　　：プロファイルリスト
	********************/
	function readSearch($bData) {

		$db = $this->handle;

		$bFldList = array(
			'' ,
			self::FLD_B1 ,self::FLD_B2 ,self::FLD_B3 ,self::FLD_B4 ,self::FLD_B5 ,
			self::FLD_B6 ,self::FLD_B7 ,self::FLD_B8 ,self::FLD_B9 ,self::FLD_B10 ,
			self::FLD_B11 ,self::FLD_B12 ,self::FLD_B13
		);

		$fldArr = array(
			self::FLD_DIR,
			self::FLD_B1 ,self::FLD_B2 ,self::FLD_B3 ,self::FLD_B4 ,self::FLD_B5 ,
			self::FLD_B6 ,self::FLD_B7 ,self::FLD_B8 ,self::FLD_B9 ,self::FLD_B10 ,
			self::FLD_B11 ,self::FLD_B12 ,self::FLD_B13
		);

		$where =
			self::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .
			self::FLD_DISP      . '=' . $db->setQuote(self::DISP_ON);	/* 表示可のレコード */

		$where2 = '';
		for($idx=1 ;$idx<=self::BDATA_NUMS ;$idx++) {
			if(!is_null($bData[$idx])) {
				if(strlen($bData[$idx]) >= 1) {
					if(strlen($where2) >= 1) {
						$where2 = $where2 . ' or ';
					}
					$where2 = $where2 . $bFldList[$idx]  . '=' . $db->setQuote($bData[$idx]);
				}
			}
		}

		if(strlen($where2) >= 1) {
			$where = $where . ' and (' . $where2 . ')';
		}

		$fldList = funcs5C::setFldArrToList($fldArr);

		$order = 'rand()';
		$profileList = $db->selectRec(self::TABLE_NAME ,$fldList ,$where ,$order);

		$ret['profInfo'] = null;
		$ret['count'   ] = 0;

		if($profileList['rows'] >= 1) {
			/*** 1件以上あったとき ***/
			$recList = $profileList['fetch'];

			$recIdxMax = $profileList['rows'];
			for($recIdx=0 ;$recIdx<$recIdxMax ;$recIdx++) {
				$rec1 = $recList[$recIdx];

				/*** ディレクトリ名 ***/
				$profile[$recIdx][self::FLD_DIR] = $rec1[0];

				/*** 一致数 ***/
				$hitNum = 0;
				for($fldIdx=1 ;$fldIdx<=self::BDATA_NUMS ;$fldIdx++) {

					$fldName = $fldArr[$fldIdx];
					$profile[$recIdx][$fldName] = $rec1[$fldIdx];

					if(strlen($rec1[$fldIdx]) >= 1) {
						$hitNum++;
					}
				}

				$profile[$recIdx]['hitNum'] = $hitNum;
			}

			$ret['profInfo'] = $profile;
			$ret['count'   ] = count($profile);
		}

		return $ret;
	}

	/********************
	topページの人妻リスト表示用の読み込み
	パラメータ：-
	戻り値　　：プロファイルリスト
	********************/
	function readTopListProf() {

		$db = $this->handle;

		$fldArr = array(
			self::FLD_DIR ,self::FLD_NAME ,self::FLD_NEWFACE ,self::FLD_PHOTO_SHOW ,
			self::FLD_PHOTOUSE_S ,self::FLD_PHOTOEXT_S
		);
		$where =
			self::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .
			self::FLD_DISP      . '=' . $db->setQuote(self::DISP_ON);	/* 表示可のレコード */

		$ret = $this->readRandom($fldArr ,$where);

		return $ret;
	}


	/********************
	プロファイルリストの読み込み(本体)
	パラメータ：フィールドリスト
	　　　　　　条件
	戻り値　　：プロファイルリスト
	********************/
	private function readRandom($fldArr ,$where) {

		$fldList = funcs5C::setFldArrToList($fldArr);
		$order   = 'rand() limit 0, 21';

		$db = $this->handle;
		$profileList = $db->selectRec(self::TABLE_NAME ,$fldList ,$where ,$order);

		$fldListMax = count($fldArr);
		if($profileList['rows'] <= 0) {
			/*** 0件のとき ***/
			for($fldIdx=0 ;$fldIdx<$fldListMax ;$fldIdx++) {
				$fldName = $fldArr[$fldIdx];
				$profile[0][$fldName] = '';
			}
		} else {
			/*** 1件以上あったとき ***/
			$recList = $profileList['fetch'];
			$recIdxMax = $profileList['rows'];
			for($recIdx=0 ;$recIdx<$recIdxMax ;$recIdx++) {
				$rec1 = $recList[$recIdx];
				for($fldIdx=0 ;$fldIdx<$fldListMax ;$fldIdx++) {
					$fldName = $fldArr[$fldIdx];
					$profile[$recIdx][$fldName] = $rec1[$fldIdx];
				}
			}
		}

		$ret['profInfo'] = $profile;
		$ret['count'   ] = count($profile);

		return $ret;
	}


	/********************
	プロファイルリストの読み込み(本体)
	パラメータ：フィールドリスト
	　　　　　　条件
	戻り値　　：プロファイルリスト
	********************/
	private function readMain($fldArr ,$where) {

		$fldList = funcs5C::setFldArrToList($fldArr);
		$order   = self::FLD_DISP_SEQ;

		$db = $this->handle;
		$profileList = $db->selectRec(self::TABLE_NAME ,$fldList ,$where ,$order);

		$fldListMax = count($fldArr);
		if($profileList['rows'] <= 0) {
			/*** 0件のとき ***/
			for($fldIdx=0 ;$fldIdx<$fldListMax ;$fldIdx++) {
				$fldName = $fldArr[$fldIdx];
				$profile[0][$fldName] = '';
			}
		} else {
			/*** 1件以上あったとき ***/
			$recList = $profileList['fetch'];
			$recIdxMax = $profileList['rows'];
			for($recIdx=0 ;$recIdx<$recIdxMax ;$recIdx++) {
				$rec1 = $recList[$recIdx];
				for($fldIdx=0 ;$fldIdx<$fldListMax ;$fldIdx++) {
					$fldName = $fldArr[$fldIdx];
					$profile[$recIdx][$fldName] = $rec1[$fldIdx];
				}
			}
		}

		$ret['profInfo'] = $profile;
		$ret['count'   ] = count($profile);

		return $ret;
	}


	/********************
	プロファイルの取得
	パラメータ：ディレクトリ
	戻り値　　：
	********************/
	function get($dir) {

		$db = $this->handle;

		$fldArr = array(
			self::FLD_DISP_SEQ   ,self::FLD_DISP  ,
			self::FLD_NAME       ,self::FLD_AGE   ,
			self::FLD_HEIGHT     ,self::FLD_SIZES , self::FLD_ZODIAC , self::FLD_BLOODTYPE ,
			self::FLD_NEWFACE    ,self::FLD_WORK_TIME ,self::FLD_WORK_DAY ,self::FLD_REST_DAY ,self::FLD_MASTERS_COMMENT ,self::FLD_APPEAL_COMMENT ,
			self::FLD_PHOTO_SHOW ,self::FLD_PCD ,

			self::FLD_PHOTOUSE_1 ,self::FLD_PHOTOUSE_2 ,self::FLD_PHOTOUSE_3 ,self::FLD_PHOTOUSE_4 ,self::FLD_PHOTOUSE_5 ,
			self::FLD_PHOTOUSE_S ,self::FLD_PHOTOUSE_M ,

			self::FLD_PHOTOEXT_1 ,self::FLD_PHOTOEXT_2 ,self::FLD_PHOTOEXT_3 ,self::FLD_PHOTOEXT_4 ,self::FLD_PHOTOEXT_5 ,
			self::FLD_PHOTOEXT_S ,self::FLD_PHOTOEXT_M ,

			self::FLD_B1 ,self::FLD_B2 ,self::FLD_B3 ,self::FLD_B4 ,self::FLD_B5 ,
			self::FLD_B6 ,self::FLD_B7 ,self::FLD_B8 ,self::FLD_B9 ,self::FLD_B10 ,
			self::FLD_B11 ,self::FLD_B12 ,self::FLD_B13 ,

			self::FLD_QA1 ,self::FLD_QA2 ,self::FLD_QA3 ,self::FLD_QA4 ,self::FLD_QA5 ,
			self::FLD_QA6 ,self::FLD_QA7 ,self::FLD_QA8 ,self::FLD_QA9 ,self::FLD_QA10 ,
			self::FLD_QA11 ,self::FLD_QA12 ,self::FLD_QA13 ,self::FLD_QA14 ,

			self::FLD_ADD_DT ,self::FLD_UPD_DT
		);
		$where =
			self::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .
			self::FLD_DIR       . '=' . $db->setQuote($dir);

		$ret = $this->readMainONE($fldArr ,$where);

		return $ret;
	}


	/********************
	パスコードの取得
	パラメータ：ディレクトリ
	戻り値　　：
	********************/
	function getPP($dir) {

		$db = $this->handle;

		$fldArr = array(self::FLD_PCD);
		$where =
			self::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .
			self::FLD_DIR       . '=' . $db->setQuote($dir);

		$pcd = $this->readMainONE($fldArr ,$where);
		$ret = $pcd[self::FLD_PCD];
		if(strlen($ret) <= 0) {
			$ret = $dir;
		}

		return $ret;
	}


	/********************
	サムネイル表示の可否
	パラメータ：ディレクトリ
	戻り値　　：写真表示情報
	********************/
	function getTNPhotoShow($dir) {

		$db = $this->handle;

		$fldArr = array(self::FLD_PHOTOUSE_S  ,self::FLD_PHOTOEXT_S  ,self::FLD_PHOTO_SHOW);

		$where =
			self::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .
			self::FLD_DIR       . '=' . $db->setQuote($dir);

		$showData = $this->readMainONE($fldArr ,$where);

		$ret['SHOW'] = $showData[self::FLD_PHOTO_SHOW];
		$ret['EXT' ] = $showData[self::FLD_PHOTOEXT_S];

		/***** 表示可なら写真ファイルの有無の確認 *****/
		if(strcmp($showData[self::FLD_PHOTO_SHOW] ,self::PHOTO_SHOW_OK) == 0) {
			$thFilePath = dirname(__FILE__) . '/../../photos/' . $dir . '/' . $dir . '-s.' . $showData[self::FLD_PHOTOEXT_S];
			/*** 写真ファイルがなければ「写真なし」 ***/
			if(!is_file($thFilePath)) {
				$ret['SHOW'] = self::PHOTO_SHOW_NOT;
			}
		}

		return $ret;
	}


	/********************
	プロファイルリストの読み込み(本体)
	パラメータ：フィールドリスト
	　　　　　　条件
	戻り値　　：プロファイルリスト
	********************/
	private function readMainONE($fldArr ,$where) {

		$fldList = funcs5C::setFldArrToList($fldArr);

		$db = $this->handle;
		$profileList = $db->selectRec(self::TABLE_NAME ,$fldList ,$where);

		$fldListMax = count($fldArr);
		if($profileList['rows'] <= 0) {
			/*** 0件のとき ***/
			for($fldIdx=0 ;$fldIdx<$fldListMax ;$fldIdx++) {
				$fldName = $fldArr[$fldIdx];
				$profile[$fldName] = '';
			}
		} else {
			/*** 1件以上あったとき ***/
			$rec1 = $profileList['fetch'][0];
			for($fldIdx=0 ;$fldIdx<$fldListMax ;$fldIdx++) {
				$fldName = $fldArr[$fldIdx];
				$profile[$fldName] = $rec1[$fldIdx];
			}
		}

		$ret = $profile;

		return $ret;
	}


	/********************
	カラのプロファイルの取得
	パラメータ：-
	戻り値　　：
	********************/
	function getEmpty() {

		$ret = array(
			self::FLD_NAME => '' ,				/* 名前 */
			self::FLD_AGE  => '' ,				/* 年齢 */

			self::FLD_HEIGHT  => '' ,			/* 身長 */
			self::FLD_SIZES   => '' ,			/* スリーサイズ */
			self::FLD_ZODIAC  => '' ,			/* 星座 */
			self::FLD_BLOODTYPE => '' ,			/* 血液型 */

			self::FLD_WORK_DAY => '' ,			/* 出勤日 */
			self::FLD_REST_DAY => '' ,			/* 公休日 */
			self::FLD_MASTERS_COMMENT => '' ,	/* コメント */
			self::FLD_APPEAL_COMMENT  => '' ,

			self::FLD_NEWFACE => '' ,			/* 新人 */

			self::FLD_ADD_DT  => '' ,			/* レコード登録日時 */
			self::FLD_UPD_DT  => '' ,			/* レコード更新日時 */


			self::FLD_PHOTOUSE_1 => '' ,		/* 写真1 */
			self::FLD_PHOTOUSE_2 => '' ,		/* 写真2 */
			self::FLD_PHOTOUSE_3 => '' ,		/* 写真3 */
			self::FLD_PHOTOUSE_4 => '' ,		/* 写真4 */
			self::FLD_PHOTOUSE_5 => '' ,		/* 写真5 */

			self::FLD_PHOTOUSE_S => '' ,		/* 写真サムネイル */
			self::FLD_PHOTOUSE_M => '' ,		/* 写真中 */


			self::FLD_PHOTOEXT_1 => '' ,		/* 写真ファイル拡張子1 */
			self::FLD_PHOTOEXT_2 => '' ,		/* 写真ファイル拡張子2 */
			self::FLD_PHOTOEXT_3 => '' ,		/* 写真ファイル拡張子3 */
			self::FLD_PHOTOEXT_4 => '' ,		/* 写真ファイル拡張子4 */
			self::FLD_PHOTOEXT_5 => '' ,		/* 写真ファイル拡張子5 */

			self::FLD_PHOTOEXT_S => '' ,		/* 写真ファイル拡張子サムネイル */
			self::FLD_PHOTOEXT_M => '' ,		/* 写真ファイル拡張子中 */


			self::FLD_B1 => '' ,
			self::FLD_B2 => '' ,
			self::FLD_B3 => '' ,
			self::FLD_B4 => '' ,
			self::FLD_B5 => '' ,

			self::FLD_B6 => '' ,
			self::FLD_B7 => '' ,
			self::FLD_B8 => '' ,
			self::FLD_B9 => '' ,
			self::FLD_B10 => '' ,

			self::FLD_B11 => '' ,
			self::FLD_B12 => '' ,
			self::FLD_B13 => '' ,


			self::FLD_QA1 => '' ,
			self::FLD_QA2 => '' ,
			self::FLD_QA3 => '' ,
			self::FLD_QA4 => '' ,
			self::FLD_QA5 => '' ,

			self::FLD_QA6 => '' ,
			self::FLD_QA7 => '' ,
			self::FLD_QA8 => '' ,
			self::FLD_QA9 => '' ,
			self::FLD_QA10 => '' ,

			self::FLD_QA11 => '' ,
			self::FLD_QA12 => '' ,
			self::FLD_QA13 => '' ,
			self::FLD_QA14 => '' ,


			self::FLD_PHOTO_SHOW =>  self::PHOTO_SHOW_NP	/* 写真使用 */
		);

		return $ret;
	}


	/********************
	プロファイルの更新
	パラメータ：ディレクトリ
	戻り値　　：
	********************/
	function upd($dir) {

		$updDT = dateTime5C::getCurrDT();

		$db = $this->handle;

		$vals = $this->vals;

		/*** 写真ファイル拡張子 ***/
		$photoExt = '';
		if(isset($vals[self::FLD_PHOTOEXT_1])) {
			$photoExt = $photoExt . self::FLD_PHOTOEXT_1 . '=' . $db->setQuote($vals[self::FLD_PHOTOEXT_1]) . ',';
		}
		if(isset($vals[self::FLD_PHOTOEXT_2])) {
			$photoExt = $photoExt . self::FLD_PHOTOEXT_2 . '=' . $db->setQuote($vals[self::FLD_PHOTOEXT_2]) . ',';
		}
		if(isset($vals[self::FLD_PHOTOEXT_3])) {
			$photoExt = $photoExt . self::FLD_PHOTOEXT_3 . '=' . $db->setQuote($vals[self::FLD_PHOTOEXT_3]) . ',';
		}
		if(isset($vals[self::FLD_PHOTOEXT_4])) {
			$photoExt = $photoExt . self::FLD_PHOTOEXT_4 . '=' . $db->setQuote($vals[self::FLD_PHOTOEXT_4]) . ',';
		}
		if(isset($vals[self::FLD_PHOTOEXT_5])) {
			$photoExt = $photoExt . self::FLD_PHOTOEXT_5 . '=' . $db->setQuote($vals[self::FLD_PHOTOEXT_5]) . ',';
		}

		if(isset($vals[self::FLD_PHOTOEXT_M])) {
			$photoExt = $photoExt . self::FLD_PHOTOEXT_M . '=' . $db->setQuote($vals[self::FLD_PHOTOEXT_M]) . ',';
		}
		if(isset($vals[self::FLD_PHOTOEXT_S])) {
			$photoExt = $photoExt . self::FLD_PHOTOEXT_S . '=' . $db->setQuote($vals[self::FLD_PHOTOEXT_S]) . ',';
		}


		$bData = '';
		if(isset($vals[self::FLD_B1])) {
			$bData = $bData . self::FLD_B1 . '=' . $db->setQuote($vals[self::FLD_B1]) . ',';
		}
		if(isset($vals[self::FLD_B2])) {
			$bData = $bData . self::FLD_B2 . '=' . $db->setQuote($vals[self::FLD_B2]) . ',';
		}
		if(isset($vals[self::FLD_B3])) {
			$bData = $bData . self::FLD_B3 . '=' . $db->setQuote($vals[self::FLD_B3]) . ',';
		}
		if(isset($vals[self::FLD_B4])) {
			$bData = $bData . self::FLD_B4 . '=' . $db->setQuote($vals[self::FLD_B4]) . ',';
		}
		if(isset($vals[self::FLD_B5])) {
			$bData = $bData . self::FLD_B5 . '=' . $db->setQuote($vals[self::FLD_B5]) . ',';
		}

		if(isset($vals[self::FLD_B6])) {
			$bData = $bData . self::FLD_B6 . '=' . $db->setQuote($vals[self::FLD_B6]) . ',';
		}
		if(isset($vals[self::FLD_B7])) {
			$bData = $bData . self::FLD_B7 . '=' . $db->setQuote($vals[self::FLD_B7]) . ',';
		}
		if(isset($vals[self::FLD_B8])) {
			$bData = $bData . self::FLD_B8 . '=' . $db->setQuote($vals[self::FLD_B8]) . ',';
		}
		if(isset($vals[self::FLD_B9])) {
			$bData = $bData . self::FLD_B9 . '=' . $db->setQuote($vals[self::FLD_B9]) . ',';
		}
		if(isset($vals[self::FLD_B10])) {
			$bData = $bData . self::FLD_B10 . '=' . $db->setQuote($vals[self::FLD_B10]) . ',';
		}

		if(isset($vals[self::FLD_B11])) {
			$bData = $bData . self::FLD_B11 . '=' . $db->setQuote($vals[self::FLD_B11]) . ',';
		}
		if(isset($vals[self::FLD_B12])) {
			$bData = $bData . self::FLD_B12 . '=' . $db->setQuote($vals[self::FLD_B12]) . ',';
		}
		if(isset($vals[self::FLD_B13])) {
			$bData = $bData . self::FLD_B13 . '=' . $db->setQuote($vals[self::FLD_B13]) . ',';
		}


		$qaData = '';
		if(isset($vals[self::FLD_QA1])) {
			$qaData = $qaData . self::FLD_QA1 . '=' . $db->setQuote($vals[self::FLD_QA1]) . ',';
		}
		if(isset($vals[self::FLD_QA2])) {
			$qaData = $qaData . self::FLD_QA2 . '=' . $db->setQuote($vals[self::FLD_QA2]) . ',';
		}
		if(isset($vals[self::FLD_QA3])) {
			$qaData = $qaData . self::FLD_QA3 . '=' . $db->setQuote($vals[self::FLD_QA3]) . ',';
		}
		if(isset($vals[self::FLD_QA4])) {
			$qaData = $qaData . self::FLD_QA4 . '=' . $db->setQuote($vals[self::FLD_QA4]) . ',';
		}
		if(isset($vals[self::FLD_QA5])) {
			$qaData = $qaData . self::FLD_QA5 . '=' . $db->setQuote($vals[self::FLD_QA5]) . ',';
		}

		if(isset($vals[self::FLD_QA6])) {
			$qaData = $qaData . self::FLD_QA6 . '=' . $db->setQuote($vals[self::FLD_QA6]) . ',';
		}
		if(isset($vals[self::FLD_QA7])) {
			$qaData = $qaData . self::FLD_QA7 . '=' . $db->setQuote($vals[self::FLD_QA7]) . ',';
		}
		if(isset($vals[self::FLD_QA8])) {
			$qaData = $qaData . self::FLD_QA8 . '=' . $db->setQuote($vals[self::FLD_QA8]) . ',';
		}
		if(isset($vals[self::FLD_QA9])) {
			$qaData = $qaData . self::FLD_QA9 . '=' . $db->setQuote($vals[self::FLD_QA9]) . ',';
		}
		if(isset($vals[self::FLD_QA10])) {
			$qaData = $qaData . self::FLD_QA10 . '=' . $db->setQuote($vals[self::FLD_QA10]) . ',';
		}

		if(isset($vals[self::FLD_QA11])) {
			$qaData = $qaData . self::FLD_QA11 . '=' . $db->setQuote($vals[self::FLD_QA11]) . ',';
		}
		if(isset($vals[self::FLD_QA12])) {
			$qaData = $qaData . self::FLD_QA12 . '=' . $db->setQuote($vals[self::FLD_QA12]) . ',';
		}
		if(isset($vals[self::FLD_QA13])) {
			$qaData = $qaData . self::FLD_QA13 . '=' . $db->setQuote($vals[self::FLD_QA13]) . ',';
		}
		if(isset($vals[self::FLD_QA14])) {
			$qaData = $qaData . self::FLD_QA14 . '=' . $db->setQuote($vals[self::FLD_QA14]) . ',';
		}


		$fldList =
			self::FLD_NEWFACE . '=' . $db->setQuote($vals[self::FLD_NEWFACE]) . ',' .
			self::FLD_NAME    . '=' . $db->setQuote($vals[self::FLD_NAME   ]) . ',' .
			self::FLD_AGE     . '=' . $db->setQuote($vals[self::FLD_AGE    ]) . ',' .
			self::FLD_HEIGHT  . '=' . $db->setQuote($vals[self::FLD_HEIGHT ]) . ',' .
			self::FLD_SIZES   . '=' . $db->setQuote($vals[self::FLD_SIZES  ]) . ',' .
			self::FLD_ZODIAC  . '=' . $db->setQuote($vals[self::FLD_ZODIAC ]) . ',' .
			self::FLD_BLOODTYPE . '=' . $db->setQuote($vals[self::FLD_BLOODTYPE]) . ',' .

			self::FLD_MASTERS_COMMENT . '=' . $db->setQuote($vals[self::FLD_MASTERS_COMMENT]) . ',' .
			self::FLD_APPEAL_COMMENT  . '=' . $db->setQuote($vals[self::FLD_APPEAL_COMMENT])  . ',' .
			self::FLD_PCD     . '=' . $db->setQuote($vals[self::FLD_PCD    ]) . ',' .

			$photoExt .

			self::FLD_PHOTOUSE_1 . '=' . $db->setQuote($vals[self::FLD_PHOTOUSE_1]) . ',' .
			self::FLD_PHOTOUSE_2 . '=' . $db->setQuote($vals[self::FLD_PHOTOUSE_2]) . ',' .
			self::FLD_PHOTOUSE_3 . '=' . $db->setQuote($vals[self::FLD_PHOTOUSE_3]) . ',' .
			self::FLD_PHOTOUSE_4 . '=' . $db->setQuote($vals[self::FLD_PHOTOUSE_4]) . ',' .
			self::FLD_PHOTOUSE_5 . '=' . $db->setQuote($vals[self::FLD_PHOTOUSE_5]) . ',' .

			self::FLD_PHOTOUSE_S . '=' . $db->setQuote($vals[self::FLD_PHOTOUSE_S]) . ',' .
			self::FLD_PHOTOUSE_M . '=' . $db->setQuote($vals[self::FLD_PHOTOUSE_M]) . ',' .

			self::FLD_PHOTO_SHOW . '=' . $db->setQuote($vals[self::FLD_PHOTO_SHOW]) . ',' .

			$bData .
			$qaData .

			self::FLD_UPD_DT . '=' . $db->setQuote($updDT);


		$where =
			self::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .
			self::FLD_DIR       . '=' . $db->setQuote($dir);

		$db->updateRec(self::TABLE_NAME ,$fldList ,$where);
	}


	/********************
	表示順/表示可否の更新
	パラメータ：ディレクトリ
	　　　　　　表示順
	　　　　　　表示可否
	戻り値　　：
	********************/
	function updDispSeq($dir ,$seq ,$disp) {

			/*$updDT = dateTime5C::getCurrDT();*/

		$db = $this->handle;

		$fldList =
			self::FLD_DISP_SEQ . '=' . $seq . ',' .
			self::FLD_DISP     . '=' . $db->setQuote($disp);

		$where =
			self::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .
			self::FLD_DIR       . '=' . $db->setQuote($dir);

		$db->updateRec(self::TABLE_NAME ,$fldList ,$where);
	}


	/********************
	表示可否の更新
	パラメータ：ディレクトリ
	　　　　　　表示可否
	戻り値　　：
	********************/
	function updDisp($dir ,$disp) {

			/*$updDT = dateTime5C::getCurrDT();*/

		$db = $this->handle;

		$fldList = self::FLD_DISP . '=' . $db->setQuote($disp);

		$where =
			self::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .
			self::FLD_DIR       . '=' . $db->setQuote($dir);

		$db->updateRec(self::TABLE_NAME ,$fldList ,$where);
	}


	/********************
	プロファイルの追加
	パラメータ：ディレクトリ
	戻り値　　：
	********************/
	function add($dir) {

		$db = $this->handle;

		/***** 既存レコードの表示順を1ずらす *****/
		$fldArr = array(self::FLD_DIR ,self::FLD_DISP_SEQ);
		$where =
			self::FLD_BRANCH_NO . '='  . $this->branchNo . ' and ' .
			self::FLD_DISP      . '!=' . $db->setQuote(self::DISP_DEL);	/* 削除でないレコード */

		$currList = $this->readMain($fldArr ,$where);

		$recList = $currList['profInfo'];
		$idxMax = $currList['count'];
		for($idx=0 ;$idx<$idxMax ;$idx++) {
			$curr1 = $recList[$idx];
			$key = $curr1[self::FLD_DIR];
			$seq = $curr1[self::FLD_DISP_SEQ];
			$seq++;

			$fldList = self::FLD_DISP_SEQ . '=' . $seq;

			$where =
				self::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .
				self::FLD_DIR       . '=' . $db->setQuote($key);

			$db->updateRec(self::TABLE_NAME ,$fldList ,$where);
		}


		$addDT = dateTime5C::getCurrDT();
		$vals = $this->vals;

		$fldArr = array(
			self::FLD_BRANCH_NO ,
			self::FLD_DIR       ,
			self::FLD_DISP      ,
			self::FLD_DISP_SEQ  ,

			self::FLD_NEWFACE ,
			self::FLD_NAME    ,
			self::FLD_AGE     ,
			self::FLD_HEIGHT  ,
			self::FLD_SIZES   ,
			self::FLD_ZODIAC  ,
			self::FLD_BLOODTYPE ,

			self::FLD_MASTERS_COMMENT ,
			self::FLD_APPEAL_COMMENT  ,

			self::FLD_PCD ,

			self::FLD_PHOTOUSE_1 ,
			self::FLD_PHOTOUSE_2 ,
			self::FLD_PHOTOUSE_3 ,
			self::FLD_PHOTOUSE_4 ,
			self::FLD_PHOTOUSE_5 ,

			self::FLD_PHOTOUSE_S ,
			self::FLD_PHOTOUSE_M ,

			self::FLD_PHOTO_SHOW ,

			self::FLD_ADD_DT
		);

		$valList =
			$this->branchNo     . ',' .
			$db->setQuote($dir) . ',' .
			$db->setQuote(self::DISP_OFF) . ',' .
			'1'                 . ',' .

			$db->setQuote($vals[self::FLD_NEWFACE]) . ',' .
			$db->setQuote($vals[self::FLD_NAME   ]) . ',' .
			$db->setQuote($vals[self::FLD_AGE    ]) . ',' .
			$db->setQuote($vals[self::FLD_HEIGHT ]) . ',' .
			$db->setQuote($vals[self::FLD_SIZES  ]) . ',' .
			$db->setQuote($vals[self::FLD_ZODIAC ]) . ',' .
			$db->setQuote($vals[self::FLD_BLOODTYPE]) . ',' .

			$db->setQuote($vals[self::FLD_MASTERS_COMMENT]) . ',' .
			$db->setQuote($vals[self::FLD_APPEAL_COMMENT])  . ',' .

			$db->setQuote($vals[self::FLD_PCD]) . ',' .

			$db->setQuote($vals[self::FLD_PHOTOUSE_1]) . ',' .
			$db->setQuote($vals[self::FLD_PHOTOUSE_2]) . ',' .
			$db->setQuote($vals[self::FLD_PHOTOUSE_3]) . ',' .
			$db->setQuote($vals[self::FLD_PHOTOUSE_4]) . ',' .
			$db->setQuote($vals[self::FLD_PHOTOUSE_5]) . ',' .

			$db->setQuote($vals[self::FLD_PHOTOUSE_S]) . ',' .
			$db->setQuote($vals[self::FLD_PHOTOUSE_M]) . ',' .

			$db->setQuote($vals[self::FLD_PHOTO_SHOW]) . ',' .

			$db->setQuote($addDT);


		/*** 写真ファイル拡張子 ***/
		if(isset($vals[self::FLD_PHOTOEXT_1])) {
			$fldArr[] = self::FLD_PHOTOEXT_1;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_PHOTOEXT_1]);
		}

		if(isset($vals[self::FLD_PHOTOEXT_2])) {
			$fldArr[] = self::FLD_PHOTOEXT_2;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_PHOTOEXT_2]);
		}

		if(isset($vals[self::FLD_PHOTOEXT_3])) {
			$fldArr[] = self::FLD_PHOTOEXT_3;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_PHOTOEXT_3]);
		}

		if(isset($vals[self::FLD_PHOTOEXT_4])) {
			$fldArr[] = self::FLD_PHOTOEXT_4;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_PHOTOEXT_4]);
		}

		if(isset($vals[self::FLD_PHOTOEXT_5])) {
			$fldArr[] = self::FLD_PHOTOEXT_5;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_PHOTOEXT_5]);
		}

		if(isset($vals[self::FLD_PHOTOEXT_M])) {
			$fldArr[] = self::FLD_PHOTOEXT_M;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_PHOTOEXT_M]);
		}

		if(isset($vals[self::FLD_PHOTOEXT_S])) {
			$fldArr[] = self::FLD_PHOTOEXT_S;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_PHOTOEXT_S]);
		}


		if(isset($vals[self::FLD_B1])) {
			$fldArr[] = self::FLD_B1;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_B1]);
		}

		if(isset($vals[self::FLD_B2])) {
			$fldArr[] = self::FLD_B2;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_B2]);
		}

		if(isset($vals[self::FLD_B3])) {
			$fldArr[] = self::FLD_B3;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_B3]);
		}

		if(isset($vals[self::FLD_B4])) {
			$fldArr[] = self::FLD_B4;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_B4]);
		}

		if(isset($vals[self::FLD_B5])) {
			$fldArr[] = self::FLD_B5;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_B5]);
		}

		if(isset($vals[self::FLD_B6])) {
			$fldArr[] = self::FLD_B6;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_B6]);
		}

		if(isset($vals[self::FLD_B7])) {
			$fldArr[] = self::FLD_B7;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_B7]);
		}

		if(isset($vals[self::FLD_B8])) {
			$fldArr[] = self::FLD_B8;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_B8]);
		}

		if(isset($vals[self::FLD_B9])) {
			$fldArr[] = self::FLD_B9;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_B9]);
		}

		if(isset($vals[self::FLD_B10])) {
			$fldArr[] = self::FLD_B10;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_B10]);
		}

		if(isset($vals[self::FLD_B11])) {
			$fldArr[] = self::FLD_B11;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_B11]);
		}

		if(isset($vals[self::FLD_B12])) {
			$fldArr[] = self::FLD_B12;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_B12]);
		}

		if(isset($vals[self::FLD_B13])) {
			$fldArr[] = self::FLD_B13;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_B13]);
		}


		if(isset($vals[self::FLD_QA1])) {
			$fldArr[] = self::FLD_QA1;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_QA1]);
		}

		if(isset($vals[self::FLD_QA2])) {
			$fldArr[] = self::FLD_QA2;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_QA2]);
		}

		if(isset($vals[self::FLD_QA3])) {
			$fldArr[] = self::FLD_QA3;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_QA3]);
		}

		if(isset($vals[self::FLD_QA4])) {
			$fldArr[] = self::FLD_QA4;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_QA4]);
		}

		if(isset($vals[self::FLD_QA5])) {
			$fldArr[] = self::FLD_QA5;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_QA5]);
		}

		if(isset($vals[self::FLD_QA6])) {
			$fldArr[] = self::FLD_QA6;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_QA6]);
		}

		if(isset($vals[self::FLD_QA7])) {
			$fldArr[] = self::FLD_QA7;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_QA7]);
		}

		if(isset($vals[self::FLD_QA8])) {
			$fldArr[] = self::FLD_QA8;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_QA8]);
		}

		if(isset($vals[self::FLD_QA9])) {
			$fldArr[] = self::FLD_QA9;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_QA9]);
		}

		if(isset($vals[self::FLD_QA10])) {
			$fldArr[] = self::FLD_QA10;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_QA10]);
		}

		if(isset($vals[self::FLD_QA11])) {
			$fldArr[] = self::FLD_QA11;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_QA11]);
		}

		if(isset($vals[self::FLD_QA12])) {
			$fldArr[] = self::FLD_QA12;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_QA12]);
		}

		if(isset($vals[self::FLD_QA13])) {
			$fldArr[] = self::FLD_QA13;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_QA13]);
		}

		if(isset($vals[self::FLD_QA14])) {
			$fldArr[] = self::FLD_QA14;
			$valList = $valList  . ',' . $db->setQuote($vals[self::FLD_QA14]);
		}


		$fldList = funcs5C::setFldArrToList($fldArr);
		$db->insertRec(self::TABLE_NAME ,$fldList ,$valList);
	}


	/********************
	プロファイルの有無
	パラメータ：プロファイル
	戻り値　　：'ALREADY' 有り
	　　　　　　'NOTYET'  ナシ
	********************/
	function existProfileDir($dir) {

		$db = $this->handle;

		$where =
			self::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .
			self::FLD_DIR       . '=' . $db->setQuote($dir);

		$exist = $db->existRec(self::TABLE_NAME ,$where);
		if($exist) {
			$ret = 'ALREADY';
		} else {
			$ret = 'NOTYET';
		}

		return $ret;
	}


	/********************
	写真表示の読み込み
	パラメータ：ディレクトリ
	　　　　　　プロファイルリスト
	戻り値　　：写真表示情報
	********************/
	function getShowPhoto($profData) {

		$ret['SHOWMODE']['L'] = $profData[self::FLD_PHOTO_SHOW];
		$ret['SHOWMODE']['M'] = $profData[self::FLD_PHOTO_SHOW];
		$ret['SHOWMODE']['S'] = $profData[self::FLD_PHOTO_SHOW];

				/* print 'name ' . $profData[PROF_NAME_S] . ' mode ' . $profData[PROF_FLD_PHOTO_SHOW] . $ret['SHOWMODE']['S']; */
		/***** 表示可なら写真ファイルの有無の確認 *****/
		if(strcmp($profData[self::FLD_PHOTO_SHOW] ,self::PHOTO_SHOW_OK) == 0) {
			$basePath = files5C::getFileName('PHOTO_BASEDIR'  ,$this->branchNo);
			$baseDir = $basePath['PC'] . '/' . $profData[self::FLD_DIR] . '/';

			$ret['1'] = $this->setUsePhoto($baseDir ,$profData[self::FLD_DIR] ,'1' ,$profData[self::FLD_PHOTOUSE_1] ,$profData[self::FLD_PHOTOEXT_1]);
			$ret['2'] = $this->setUsePhoto($baseDir ,$profData[self::FLD_DIR] ,'2' ,$profData[self::FLD_PHOTOUSE_2] ,$profData[self::FLD_PHOTOEXT_2]);
			$ret['3'] = $this->setUsePhoto($baseDir ,$profData[self::FLD_DIR] ,'3' ,$profData[self::FLD_PHOTOUSE_3] ,$profData[self::FLD_PHOTOEXT_3]);
			$ret['4'] = $this->setUsePhoto($baseDir ,$profData[self::FLD_DIR] ,'4' ,$profData[self::FLD_PHOTOUSE_4] ,$profData[self::FLD_PHOTOEXT_4]);
			$ret['5'] = $this->setUsePhoto($baseDir ,$profData[self::FLD_DIR] ,'5' ,$profData[self::FLD_PHOTOUSE_5] ,$profData[self::FLD_PHOTOEXT_5]);

			$ret['S'] = $this->setUsePhoto($baseDir ,$profData[self::FLD_DIR] ,'-s' ,$profData[self::FLD_PHOTOUSE_S] ,$profData[self::FLD_PHOTOEXT_S]);
			$ret['M'] = $this->setUsePhoto($baseDir ,$profData[self::FLD_DIR] ,'-m' ,$profData[self::FLD_PHOTOUSE_M] ,$profData[self::FLD_PHOTOEXT_M]);


			$ret['SHOW'] = array();

			$showNum = 0;
			if(strcmp($ret['1'] ,self::PHOTO_USE) == 0) {
				$showNum++;
				$ret['SHOW'][$showNum] = '1';
			}
			if(strcmp($ret['2'] ,self::PHOTO_USE) == 0) {
				$showNum++;
				$ret['SHOW'][$showNum] = '2';
			}
			if(strcmp($ret['3'] ,self::PHOTO_USE) == 0) {
				$showNum++;
				$ret['SHOW'][$showNum] = '3';
			}
			if(strcmp($ret['4'] ,self::PHOTO_USE) == 0) {
				$showNum++;
				$ret['SHOW'][$showNum] = '4';
			}
			if(strcmp($ret['5'] ,self::PHOTO_USE) == 0) {
				$showNum++;
				$ret['SHOW'][$showNum] = '5';
			}

			$ret['NUM'] = $showNum;

			/***** 表示する写真がなければ「写真ナシ」状態 *****/
			if($showNum <= 0) {
				$ret['SHOWMODE']['L'] = self::PHOTO_SHOW_NOT;
			}

			if(strcmp($ret['M'] ,self::PHOTO_NOT) == 0) {
				$ret['SHOWMODE']['M'] = self::PHOTO_SHOW_NOT;
			}

			if(strcmp($ret['S'] ,self::PHOTO_NOT) == 0) {
				$ret['SHOWMODE']['S'] = self::PHOTO_SHOW_NOT;
			}
		}

		return $ret;
	}


	function setUsePhoto($baseDir ,$profDir ,$photoID ,$use ,$ext) {

		$ret = self::PHOTO_NOT;

		if(strcmp($use ,self::PHOTO_USE) == 0) {
			if(strlen($ext) >= 1) {
				$fileFullName = $baseDir . $profDir . $photoID . '.' . $ext;
				if(is_file($fileFullName)) {
					$ret = self::PHOTO_USE;
				}
			}
		}

		return $ret;
	}


	/********************
	プロファイル削除
	パラメータ：プロファイル
	戻り値　　：ナシ
	********************/
	function del($dir) {

		$db = $this->handle;

		$where =
			self::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .
			self::FLD_DIR       . '=' . $db->setQuote($dir);

		$db->delRec(self::TABLE_NAME ,$where);


		/***** 残ったレコードの表示順を採番し直す *****/
		$fldArr = array(self::FLD_DIR ,self::FLD_DISP_SEQ);
		$where =
			self::FLD_BRANCH_NO . '='  . $this->branchNo . ' and ' .
			self::FLD_DISP      . '!=' . $db->setQuote(self::DISP_DEL);	/* 削除でないレコード */

		$currList = $this->readMain($fldArr ,$where);

		$recList = $currList['profInfo'];
		$idxMax  = $currList['count'];
		for($idx=0 ;$idx<$idxMax ;$idx++) {
			$curr1 = $recList[$idx];

			$seq = $idx + 1;
			$fldList = self::FLD_DISP_SEQ . '=' . $seq;

			$where =
				self::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .
				self::FLD_DIR       . '=' . $db->setQuote($curr1[self::FLD_DIR]);

			$db->updateRec(self::TABLE_NAME ,$fldList ,$where);
		}
	}


/*****************************************************************************************************************************/
	/********************
	プロファイルデータの読み込みの本体
	パラメータ：プロファイルデータ(JSON形式)
	戻り値　　：プロファイルデータ(配列)
	********************/
	function readProfData($profLine) {

		$json = new Services_JSON();
		$jsonData = $json->decode($profLine);

		$ret[PROF_NAME     ] = '';	/* 名前 */
		$ret[PROF_AGE      ] = '';	/* 年齢 */

		$ret[PROF_BIRTHDATE_S] = '';	/* 誕生日 */
		$ret[PROF_ZODIAC   ] = '';	/* 星座 */

		$ret[PROF_HEIGHT   ] = '';	/* 身長 */
		$ret[PROF_SIZES    ] = '';	/* スリーサイズ */

		$ret[PROF_BLOODTYPE_S] = '';	/* 血液型 */

		$ret[PROF_WORK_TIME_S] = '';	/* 出勤時間 */
		$ret[PROF_WORK_DAY ] = '';	/* 出勤日 */
		$ret[PROF_REST_DAY ] = '';	/* 公休日 */
		$ret[PROF_COMMENT  ] = '';	/* コメント */

		$ret[PROF_NEWFACE  ] = '';	/* 新人 */


		$ret[PROF_ADD_DT   ] = '';	/* レコード登録日時 */
		$ret[PROF_UPD_DT   ] = '';	/* レコード更新日時 */


		$ret[PROF_PHOTOUSE_1_S] = '';	/* 写真1 */
		$ret[PROF_PHOTOUSE_2_S] = '';	/* 写真2 */
		$ret[PROF_PHOTOUSE_3_S] = '';	/* 写真3 */
		$ret[PROF_PHOTOUSE_4_S] = '';	/* 写真4 */
		$ret[PROF_PHOTOUSE_5_S] = '';	/* 写真5 */

		$ret[PROF_PHOTOUSE_S_S] = '';	/* 写真サムネイル */
		$ret[PROF_PHOTOUSE_M_S] = '';	/* 写真中 */


		$ret[PROF_PHOTOEXT_1_S] = '';	/* 写真ファイル拡張子1 */
		$ret[PROF_PHOTOEXT_2_S] = '';	/* 写真ファイル拡張子2 */
		$ret[PROF_PHOTOEXT_3_S] = '';	/* 写真ファイル拡張子3 */
		$ret[PROF_PHOTOEXT_4_S] = '';	/* 写真ファイル拡張子4 */
		$ret[PROF_PHOTOEXT_5_S] = '';	/* 写真ファイル拡張子5 */

		$ret[PROF_PHOTOEXT_S_S] = '';	/* 写真ファイル拡張子サムネイル */
		$ret[PROF_PHOTOEXT_M_S] = '';	/* 写真ファイル拡張子中 */

		$ret[PROF_FLD_PHOTO_SHOW] = PROF_PHOTO_SHOW_NP;	/* 写真使用 */

		$tempValue = (array)$jsonData;

		if(isset($tempValue[PROF_NAME_S])) {
			$ret[PROF_NAME_S] = $tempValue[PROF_NAME_S];
		}

		if(isset($tempValue[PROF_AGE_S])) {
			$ret[PROF_AGE_S] = $tempValue[PROF_AGE_S];
		}


		if(isset($tempValue[PROF_BIRTHDATE_S])) {
			$ret[PROF_BIRTHDATE_S] = $tempValue[PROF_BIRTHDATE_S];
		}

		if(isset($tempValue[PROF_ZODIAC_S])) {
			$ret[PROF_ZODIAC_S] = $tempValue[PROF_ZODIAC_S];
		}


		if(isset($tempValue[PROF_HEIGHT_S])) {
			$ret[PROF_HEIGHT_S] = $tempValue[PROF_HEIGHT_S];
		}

		if(isset($tempValue[PROF_SIZES_S])) {
			$ret[PROF_SIZES_S] = $tempValue[PROF_SIZES_S];
		}

		if(isset($tempValue[PROF_BLOODTYPE_S])) {
			$ret[PROF_BLOODTYPE_S] = $tempValue[PROF_BLOODTYPE_S];
		}

		if(isset($tempValue[PROF_WORK_TIME_S])) {
			$ret[PROF_WORK_TIME_S] = $tempValue[PROF_WORK_TIME_S];
		}

		if(isset($tempValue[PROF_WORK_DAY_S])) {
			$ret[PROF_WORK_DAY_S] = $tempValue[PROF_WORK_DAY_S];
		}

		if(isset($tempValue[PROF_REST_DAY_S])) {
			$ret[PROF_REST_DAY_S] = $tempValue[PROF_REST_DAY_S];
		}

		if(isset($tempValue[PROF_COMMENT_S])) {
			$ret[PROF_COMMENT_S] = $tempValue[PROF_COMMENT_S];
		}

		if(isset($tempValue[PROF_NEWFACE_S])) {
			$ret[PROF_NEWFACE_S] = $tempValue[PROF_NEWFACE_S];
		}


		if(isset($tempValue[PROF_UPD_DT_S])) {
			$ret[PROF_UPD_DT_S] = $tempValue[PROF_UPD_DT_S];
		}


		if(isset($tempValue[PROF_PHOTOUSE_1_S])) {
			$ret[PROF_PHOTOUSE_1_S] = $tempValue[PROF_PHOTOUSE_1_S];
		}

		if(isset($tempValue[PROF_PHOTOUSE_2_S])) {
			$ret[PROF_PHOTOUSE_2_S] = $tempValue[PROF_PHOTOUSE_2_S];
		}

		if(isset($tempValue[PROF_PHOTOUSE_3_S])) {
			$ret[PROF_PHOTOUSE_3_S] = $tempValue[PROF_PHOTOUSE_3_S];
		}

		if(isset($tempValue[PROF_PHOTOUSE_4_S])) {
			$ret[PROF_PHOTOUSE_4_S] = $tempValue[PROF_PHOTOUSE_4_S];
		}

		if(isset($tempValue[PROF_PHOTOUSE_5_S])) {
			$ret[PROF_PHOTOUSE_5_S] = $tempValue[PROF_PHOTOUSE_5_S];
		}

		if(isset($tempValue[PROF_PHOTOUSE_S_S])) {
			$ret[PROF_PHOTOUSE_S_S] = $tempValue[PROF_PHOTOUSE_S_S];
		}

		if(isset($tempValue[PROF_PHOTOUSE_M_S])) {
			$ret[PROF_PHOTOUSE_M_S] = $tempValue[PROF_PHOTOUSE_M_S];
		}


		if(isset($tempValue[PROF_PHOTOEXT_1_S])) {
			$ret[PROF_PHOTOEXT_1_S] = $tempValue[PROF_PHOTOEXT_1_S];
		}

		if(isset($tempValue[PROF_PHOTOEXT_2_S])) {
			$ret[PROF_PHOTOEXT_2_S] = $tempValue[PROF_PHOTOEXT_2_S];
		}

		if(isset($tempValue[PROF_PHOTOEXT_3_S])) {
			$ret[PROF_PHOTOEXT_3_S] = $tempValue[PROF_PHOTOEXT_3_S];
		}

		if(isset($tempValue[PROF_PHOTOEXT_4_S])) {
			$ret[PROF_PHOTOEXT_4_S] = $tempValue[PROF_PHOTOEXT_4_S];
		}

		if(isset($tempValue[PROF_PHOTOEXT_5_S])) {
			$ret[PROF_PHOTOEXT_5_S] = $tempValue[PROF_PHOTOEXT_5_S];
		}

		if(isset($tempValue[PROF_PHOTOEXT_S_S])) {
			$ret[PROF_PHOTOEXT_S_S] = $tempValue[PROF_PHOTOEXT_S_S];
		}

		if(isset($tempValue[PROF_PHOTOEXT_M_S])) {
			$ret[PROF_PHOTOEXT_M_S] = $tempValue[PROF_PHOTOEXT_M_S];
		}


		if(isset($tempValue[PROF_FLD_PHOTO_SHOW])) {
			$ret[PROF_FLD_PHOTO_SHOW] = $tempValue[PROF_FLD_PHOTO_SHOW];
		}



		if(isset($tempValue[PROF_WORK_DEF_SUN_F_S])) {
			$ret[PROF_WORK_DEF_SUN_F_S] = $tempValue[PROF_WORK_DEF_SUN_F_S];
		}
		if(isset($tempValue[PROF_WORK_DEF_MON_F_S])) {
			$ret[PROF_WORK_DEF_MON_F_S] = $tempValue[PROF_WORK_DEF_MON_F_S];
		}
		if(isset($tempValue[PROF_WORK_DEF_TUE_F_S])) {
			$ret[PROF_WORK_DEF_TUE_F_S] = $tempValue[PROF_WORK_DEF_TUE_F_S];
		}
		if(isset($tempValue[PROF_WORK_DEF_WED_F_S])) {
			$ret[PROF_WORK_DEF_WED_F_S] = $tempValue[PROF_WORK_DEF_WED_F_S];
		}
		if(isset($tempValue[PROF_WORK_DEF_THU_F_S])) {
			$ret[PROF_WORK_DEF_THU_F_S] = $tempValue[PROF_WORK_DEF_THU_F_S];
		}
		if(isset($tempValue[PROF_WORK_DEF_FRI_F_S])) {
			$ret[PROF_WORK_DEF_FRI_F_S] = $tempValue[PROF_WORK_DEF_FRI_F_S];
		}
		if(isset($tempValue[PROF_WORK_DEF_SAT_F_S])) {
			$ret[PROF_WORK_DEF_SAT_F_S] = $tempValue[PROF_WORK_DEF_SAT_F_S];
		}


		if(isset($tempValue[PROF_WORK_DEF_SUN_T_S])) {
			$ret[PROF_WORK_DEF_SUN_T_S] = $tempValue[PROF_WORK_DEF_SUN_T_S];
		}
		if(isset($tempValue[PROF_WORK_DEF_MON_T_S])) {
			$ret[PROF_WORK_DEF_MON_T_S] = $tempValue[PROF_WORK_DEF_MON_T_S];
		}
		if(isset($tempValue[PROF_WORK_DEF_TUE_T_S])) {
			$ret[PROF_WORK_DEF_TUE_T_S] = $tempValue[PROF_WORK_DEF_TUE_T_S];
		}
		if(isset($tempValue[PROF_WORK_DEF_WED_T_S])) {
			$ret[PROF_WORK_DEF_WED_T_S] = $tempValue[PROF_WORK_DEF_WED_T_S];
		}
		if(isset($tempValue[PROF_WORK_DEF_THU_T_S])) {
			$ret[PROF_WORK_DEF_THU_T_S] = $tempValue[PROF_WORK_DEF_THU_T_S];
		}
		if(isset($tempValue[PROF_WORK_DEF_FRI_T_S])) {
			$ret[PROF_WORK_DEF_FRI_T_S] = $tempValue[PROF_WORK_DEF_FRI_T_S];
		}
		if(isset($tempValue[PROF_WORK_DEF_SAT_T_S])) {
			$ret[PROF_WORK_DEF_SAT_T_S] = $tempValue[PROF_WORK_DEF_SAT_T_S];
		}


		if(isset($tempValue[PROF_WORK_DEF_SUN_MO_S])) {
			$ret[PROF_WORK_DEF_SUN_MO_S] = $tempValue[PROF_WORK_DEF_SUN_MO_S];
		}
		if(isset($tempValue[PROF_WORK_DEF_MON_MO_S])) {
			$ret[PROF_WORK_DEF_MON_MO_S] = $tempValue[PROF_WORK_DEF_MON_MO_S];
		}
		if(isset($tempValue[PROF_WORK_DEF_TUE_MO_S])) {
			$ret[PROF_WORK_DEF_TUE_MO_S] = $tempValue[PROF_WORK_DEF_TUE_MO_S];
		}
		if(isset($tempValue[PROF_WORK_DEF_WED_MO_S])) {
			$ret[PROF_WORK_DEF_WED_MO_S] = $tempValue[PROF_WORK_DEF_WED_MO_S];
		}
		if(isset($tempValue[PROF_WORK_DEF_THU_MO_S])) {
			$ret[PROF_WORK_DEF_THU_MO_S] = $tempValue[PROF_WORK_DEF_THU_MO_S];
		}
		if(isset($tempValue[PROF_WORK_DEF_FRI_MO_S])) {
			$ret[PROF_WORK_DEF_FRI_MO_S] = $tempValue[PROF_WORK_DEF_FRI_MO_S];
		}
		if(isset($tempValue[PROF_WORK_DEF_SAT_MO_S])) {
			$ret[PROF_WORK_DEF_SAT_MO_S] = $tempValue[PROF_WORK_DEF_SAT_MO_S];
		}

		return $ret;
	}
}
?>
