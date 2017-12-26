<?php
/*************************
汎用テーブル Version 1.0
PHP5
2016 Feb. 20 ver 1.0
*************************/

	require_once dirname(__FILE__) . '/../sql5C.php';
	require_once dirname(__FILE__) . '/../dateTime5C.php';
	require_once dirname(__FILE__) . '/../common5C.php';
	require_once dirname(__FILE__) . '/../strings5C.php';
	require_once dirname(__FILE__) . '/../funcs5C.php';
	require_once dirname(__FILE__) . '/../logFile5C.php';

class dbGeneral5C {

	/*** テーブル名 ***/
	const TABLE_NAME = 'general2018';

	/*** フィールド ***/
	const FLD_BRANCH_NO = 'branchNo';	/* 店No */

	const FLD_CATE      = 'catgory';	/* 分類 */
	const FLD_STR       = 'strVal';		/* 内容 */

	const FLD_ADD_DT    = 'addDT';		/* レコード登録日時 */
	const FLD_UPD_DT    = 'updDT';		/* レコード更新日時 */

	/***** 分類 *****/
	const CATE_SYSTEM     = 'SYSTEM';		/* システム */
	const CATE_RECRUIT    = 'RECRUIT';		/* 求人 */
	const CATE_FIX_PHRASE = 'FIX_PHRASE';	/* 定型文 */


	var $handle;


	/********************
	コンストラクタ(DB接続)
	パラメータ：-
	戻り値　　：-
	********************/
	function dbGeneral5C($handle=null) {
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
	内容読み込み
	パラメータ：店No
	　　　　　　分類
	戻り値　　：内容
	********************/
	function read($branchNo ,$cate) {

		$db = $this->handle;

		$fldArr = array(
			self::FLD_CATE ,
			self::FLD_STR  ,

			self::FLD_ADD_DT ,
			self::FLD_UPD_DT
		);

		$where =
			self::FLD_BRANCH_NO . '=' . $branchNo . ' and ' .
			self::FLD_CATE      . '=' . $db->setQuote($cate);

		$ret = $this->readMain($branchNo ,$fldArr ,$where);

		return $ret;
	}

	/********************
	内容読み込み(本体)
	パラメータ：店No
	　　　　　　フィールドリスト
	　　　　　　条件
	戻り値　　：内容
	********************/
	private function readMain($branchNo ,$fldArr ,$where) {

		$fldList = funcs5C::setFldArrToList($fldArr);

		$db = $this->handle;
		$newsList = $db->selectRec(self::TABLE_NAME ,$fldList ,$where);

		$fldListMax = count($fldArr);
		if($newsList['rows'] <= 0) {
			/*** 0件のとき ***/
			for($fldIdx=0 ;$fldIdx<$fldListMax ;$fldIdx++) {
				$fldName = $fldArr[$fldIdx];
				$vals[0][$fldName] = '';
			}
		} else {
			/*** 1件以上あったとき ***/
			$recList = $newsList['fetch'];
			$recIdxMax = $newsList['rows'];
			for($recIdx=0 ;$recIdx<$recIdxMax ;$recIdx++) {
				$rec1 = $recList[$recIdx];
				for($fldIdx=0 ;$fldIdx<$fldListMax ;$fldIdx++) {
					$fldName = $fldArr[$fldIdx];
					$vals[$recIdx][$fldName] = $rec1[$fldIdx];
				}
			}
		}

		$ret['vals' ] = $vals;
		$ret['count'] = count($vals);

		return $ret;
	}

	/********************
	更新
	パラメータ：店No
	　　　　　　分類
	　　　　　　内容
	戻り値　　：
	********************/
	function upd($branchNo ,$cate ,$strVal) {

		$updDT = dateTime5C::getCurrDT();

		$db = $this->handle;

		$fldList =
			self::FLD_STR    . '=' . $db->setQuote($strVal) . ',' .
			self::FLD_UPD_DT . '=' . $db->setQuote($updDT);

		$where =
			self::FLD_BRANCH_NO . '=' . $branchNo . ' and ' .
			self::FLD_CATE      . '=' . $db->setQuote($cate);

		$db->updateRec(self::TABLE_NAME ,$fldList ,$where);
	}


	/********************
	追加
	パラメータ：店No
	　　　　　　分類
	　　　　　　内容
	戻り値　　：
	********************/
	function add($branchNo ,$cate ,$strVal) {

		$addDT = dateTime5C::getCurrDT();

		$db = $this->handle;

		$fldArr = array(
			self::FLD_BRANCH_NO ,
			self::FLD_CATE      ,
			self::FLD_STR       ,

			self::FLD_ADD_DT
		);

		$valueList =
			$branchNo . ',' .
			$db->setQuote($cate)   . ',' .
			$db->setQuote($strVal) . ',' .

			$db->setQuote($addDT);

		$fldList = funcs5C::setFldArrToList($fldArr);
		$db->insertRec(self::TABLE_NAME ,$fldList ,$valueList);

		return $addDT;
	}


	/********************
	レコードの有無
	パラメータ：店No
	　　　　　　分類
	戻り値　　：
	********************/
	function isExist($branchNo ,$cate) {

		$db = $this->handle;

		$cond = 
			self::FLD_BRANCH_NO . '=' . $branchNo . ' and ' .
			self::FLD_CATE      . '=' . $db->setQuote($cate);

		$ret = $db->existRec(self::TABLE_NAME ,$cond);

		return $ret;
	}
}
?>
