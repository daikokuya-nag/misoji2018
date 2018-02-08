<?php
/*************************
相互リンク Version 1.2
PHP5
*************************/

	require_once dirname(__FILE__) . '/../sql5C.php';
	require_once dirname(__FILE__) . '/../dateTime5C.php';
	require_once dirname(__FILE__) . '/../common5C.php';
	require_once dirname(__FILE__) . '/../strings5C.php';
	require_once dirname(__FILE__) . '/../funcs5C.php';

class dbLinkExchange5C {

	/*** テーブル名 ***/
	const TABLE_NAME = 'linkExchange';

	/*** フィールド ***/
	const FLD_NO        = 'No';			/* 連番 */
	const FLD_BRANCH_NO = 'branchNo';	/* 店No */
	const FLD_PAGE_ID   = 'pageID';		/* ページ識別 */
	const FLD_SEQ       = 'seq';		/* 表示順 */

	const FLD_SITE_NAME = 'siteName';	/* サイト名 */
	const FLD_URL       = 'url';		/* URL */
	const FLD_IMG_FILE  = 'imgFile';	/* 画像ファイル */

	const FLD_ADD_DT = 'addDT';		/* レコード登録日時 */
	const FLD_UPD_DT = 'updDT';		/* レコード更新日時 */


	var $handle;
	var $vals;

	/********************
	コンストラクタ(DB接続)
	パラメータ：-
	戻り値　　：-
	********************/
	function dbLinkExchange5C($handle=null) {

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
	値リストの読み込み
	パラメータ：ページID
	戻り値　　：リスト
	********************/
	function readAll($branchNo ,$pageID) {

		$db = $this->handle;

		$fldArr = array(
			self::FLD_NO ,self::FLD_SITE_NAME ,self::FLD_URL ,self::FLD_IMG_FILE ,self::FLD_ADD_DT ,self::FLD_UPD_DT
		);
		$where = self::FLD_BRANCH_NO . '=' . $branchNo . ' and ' . self::FLD_PAGE_ID . '=' . $db->setQuote($pageID);

		$ret = $this->readMain($fldArr ,$where);

		return $ret;
	}

	/********************
	値リストの読み込み(本体)
	パラメータ：フィールドリスト
	　　　　　　条件
	戻り値　　：リスト
	********************/
	private function readMain($fldArr ,$where) {

		$fldList = funcs5C::setFldArrToList($fldArr);
		$order   = self::FLD_SEQ;

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

		$ret['val'  ] = $profile;
		$ret['count'] = count($profile);

		return $ret;
	}


	/********************
	値の更新
	パラメータ：ページID
	　　　　　　項目
	戻り値　　：
	********************/
	function upd($branchNo ,$pageID ,$seq) {

		$updDT = dateTime5C::getCurrDT();
		$db = $this->handle;
		$vals = $this->vals;

		$fldList =
			self::FLD_SITE_NAME . '=' . $db->setQuote($vals[self::FLD_SITE_NAME]) . ',' .
			self::FLD_URL       . '=' . $db->setQuote($vals[self::FLD_URL])       . ',' .
			self::FLD_IMG_FILE  . '=' . $db->setQuote($vals[self::FLD_IMG_FILE])  . ',' .
			self::FLD_UPD_DT    . '=' . $db->setQuote($updDT);

		$where =
			self::FLD_BRANCH_NO . '=' . $branchNo              . ' and ' .
			self::FLD_PAGE_ID   . '=' . $db->setQuote($pageID) . ' and ' .
			self::FLD_SEQ       . '=' . $seq;

		$db->updateRec(self::TABLE_NAME ,$fldList ,$where);
	}

	/********************
	値の追加
	パラメータ：-
	戻り値　　：
	********************/
	function add($branchNo ,$pageID ,$seq) {

		$db = $this->handle;

		$addDT = dateTime5C::getCurrDT();
		$vals = $this->vals;

		$fldArr = array(
			self::FLD_BRANCH_NO ,
			self::FLD_PAGE_ID   ,
			self::FLD_SEQ       ,

			self::FLD_SITE_NAME ,
			self::FLD_URL ,
			self::FLD_IMG_FILE ,
			self::FLD_ADD_DT
		);

		$valList =
			$branchNo              . ',' .
			$db->setQuote($pageID) . ',' .
			$seq                   . ',' .

			$db->setQuote($vals[self::FLD_SITE_NAME]) . ',' .
			$db->setQuote($vals[self::FLD_URL])       . ',' .
			$db->setQuote($vals[self::FLD_IMG_FILE])  . ',' .
			$db->setQuote($addDT);

		$fldList = funcs5C::setFldArrToList($fldArr);
		$db->insertRec(self::TABLE_NAME ,$fldList ,$valList);
	}

	/********************
	削除
	パラメータ：店No
	　　　　　　リンクNo
	戻り値　　：
	********************/
	function del($branchNo ,$pageID ,$linkNo) {

				logFile5C::debug('相互リンク削除 No.' . $linkNo);

		$db = $this->handle;
		$where =
			self::FLD_BRANCH_NO . '=' . $branchNo              . ' and ' .
			self::FLD_PAGE_ID   . '=' . $db->setQuote($pageID) . ' and ' .
			self::FLD_SEQ       . '=' . $linkNo;

		$newsList = $db->delRec(self::TABLE_NAME ,$where);
	}
}
?>
