<?php
/*************************
ページパラメータ Version 1.2
PHP5
*************************/

	require_once dirname(__FILE__) . '/../sql5C.php';
	require_once dirname(__FILE__) . '/../dateTime5C.php';
	require_once dirname(__FILE__) . '/../common5C.php';
	require_once dirname(__FILE__) . '/../strings5C.php';
	require_once dirname(__FILE__) . '/../funcs5C.php';

class dbPageParam5C {

	/*** テーブル名 ***/
	const TABLE_NAME = 'pageParam';

	/*** フィールド ***/
	const FLD_BRANCH_NO = 'branchNo';	/* 店No */
	const FLD_PAGE_ID   = 'pageID';		/* ページ識別 */
	const FLD_OBJ       = 'obj';		/* ページ内の項目識別 */

	const FLD_VALUE1 = 'value1';		/* 値1 */
	const FLD_VALUE2 = 'value2';		/* 値2 */
	const FLD_VALUE3 = 'value3';		/* 値3 */
	const FLD_VALUE4 = 'value4';		/* 値4 */
	const FLD_VALUE5 = 'value5';		/* 値5 */

	const FLD_ADD_DT = 'addDT';		/* レコード登録日時 */
	const FLD_UPD_DT = 'updDT';		/* レコード更新日時 */


	/***** ページ識別 *****/
	const PAGE_TOP = 'TOP';	/* ヘッダ */


	/***** 項目識別 *****/
	const OBJ_HEADER = 'HEADER';


	var $handle;
	var $branchNo;
	var $vals;

	/********************
	コンストラクタ(DB接続)
	パラメータ：-
	戻り値　　：-
	********************/
	function dbPageParam5C($branchNo ,$handle=null) {

		if(is_null($handle)) {
			$this->handle = new sql5C();
		} else {
			$this->handle = $handle;
		}

		$this->branchNo = $branchNo;

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
	function readAll($pageID ,$obj=null) {

		$db = $this->handle;

		$fldArr = array(
			self::FLD_VALUE1 ,self::FLD_VALUE2 ,self::FLD_VALUE3 ,self::FLD_VALUE4 ,self::FLD_VALUE5 ,
			self::FLD_ADD_DT ,self::FLD_UPD_DT
		);
		$where =
			self::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .
			self::FLD_PAGE_ID   . '=' . $db->setQuote($pageID);

		if(is_null($obj)) {
			//項目識別の指定がなければ抽出する項目に追加
			$fldArr[] = self::FLD_OBJ;
		} else {
			//項目識別の指定があれば検索条件に追加
			$where = $where . ' and ' .
				self::FLD_OBJ . '=' . $db->setQuote($obj);
		}

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
		$order   = self::FLD_ADD_DT;

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

		$ret['pageVal'] = $profile;
		$ret['count'  ] = count($profile);

		return $ret;
	}



	/********************
	値の更新
	パラメータ：ページID
	　　　　　　項目
	戻り値　　：
	********************/
	function upd($pageID ,$obj) {

		$updDT = dateTime5C::getCurrDT();
		$db = $this->handle;
		$vals = $this->vals;

		$fldList =
			self::FLD_VALUE1 . '=' . $db->setQuote($vals[self::FLD_VALUE1]) . ',' .
			self::FLD_VALUE2 . '=' . $db->setQuote($vals[self::FLD_VALUE2]) . ',' .
			self::FLD_VALUE3 . '=' . $db->setQuote($vals[self::FLD_VALUE3]) . ',' .
			self::FLD_VALUE4 . '=' . $db->setQuote($vals[self::FLD_VALUE4]) . ',' .
			self::FLD_VALUE5 . '=' . $db->setQuote($vals[self::FLD_VALUE5]) . ',' .
			self::FLD_UPD_DT . '=' . $db->setQuote($updDT);

		$where =
			self::FLD_BRANCH_NO . '=' . $this->branchNo        . ' and ' .
			self::FLD_PAGE_ID   . '=' . $db->setQuote($pageID) . ' and ' .
			self::FLD_OBJ       . '=' . $db->setQuote($obj);

		$db->updateRec(self::TABLE_NAME ,$fldList ,$where);
	}

	/********************
	値の追加
	パラメータ：-
	戻り値　　：
	********************/
	function add($pageID ,$obj) {

		$db = $this->handle;

		$addDT = dateTime5C::getCurrDT();
		$vals = $this->vals;

		$fldArr = array(
			self::FLD_BRANCH_NO ,
			self::FLD_PAGE_ID   ,
			self::FLD_OBJ       ,

			self::FLD_VALUE1 ,
			self::FLD_VALUE2 ,
			self::FLD_VALUE3 ,
			self::FLD_VALUE4 ,
			self::FLD_VALUE5 ,
			self::FLD_ADD_DT
		);

		$valList =
			$this->branchNo        . ',' .
			$db->setQuote($pageID) . ',' .
			$db->setQuote($obj)    . ',' .

			$db->setQuote($vals[self::FLD_VALUE1]) . ',' .
			$db->setQuote($vals[self::FLD_VALUE2]) . ',' .
			$db->setQuote($vals[self::FLD_VALUE3]) . ',' .
			$db->setQuote($vals[self::FLD_VALUE4]) . ',' .
			$db->setQuote($vals[self::FLD_VALUE5]) . ',' .
			$db->setQuote($addDT);

		$fldList = funcs5C::setFldArrToList($fldArr);
		$db->insertRec(self::TABLE_NAME ,$fldList ,$valList);
	}
}
?>
