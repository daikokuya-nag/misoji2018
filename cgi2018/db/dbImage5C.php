<?php
/*************************
画像データ Version 1.2
PHP5
*************************/

	require_once dirname(__FILE__) . '/../sql5C.php';
	require_once dirname(__FILE__) . '/../dateTime5C.php';
	require_once dirname(__FILE__) . '/../common5C.php';
	require_once dirname(__FILE__) . '/../strings5C.php';
	require_once dirname(__FILE__) . '/../funcs5C.php';

class dbImage5C {

	/*** テーブル名 ***/
	const TABLE_NAME = 'image';

	/*** フィールド ***/
	const FLD_IMG_NO     = 'imgNo';		/* 画像No */
	const FLD_BRANCH_NO  = 'branchNo';	/* 店No */

	const FLD_WIDTH      = 'width';		/* 幅画素数 */
	const FLD_HEIGHT     = 'height';	/* 高さ画素数 */
	const FLD_TITLE      = 'title';		/* 表題 */
	const FLD_CLASS      = 'class';		/* 画像の分類 */

	const FLD_ORG_WIDTH    = 'orgWidth';	/* 原稿の幅画素数 */
	const FLD_ORG_HEIGHT   = 'orgHeight';	/* 原稿の高さ画素数 */
	const FLD_ORG_FILENAME = 'orgFileName';	/* 原稿のファイル名 */
	const FLD_ORG_EXT      = 'orgExt';		/* 原稿の拡張子 */

	const FLD_USE_MARK     = 'useMark';		/* 使用/非使用/削除 */

	const FLD_ADD_DT = 'addDT';		/* レコード登録日時 */
	const FLD_UPD_DT = 'updDT';		/* レコード更新日時 */


	/***** 定数 *****/
	const CLASS_TOP_HEADER   = 'TOP_HEADER';	/* ヘッダ画像 */

	const DISP_ON   = 'U';	/* 使用 */
	const DISP_OFF  = 'N';	/* 非表示 */
	const DISP_DEL  = 'D';	/* 論理削除 */


	var $handle;
	var $branchNo;
	var $dispSeq;
	var $vals;

	/********************
	コンストラクタ(DB接続)
	パラメータ：-
	戻り値　　：-
	********************/
	function dbImage5C($branchNo ,$handle=null) {

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
	画像データの読み込み(削除以外)
	パラメータ：-
	戻り値　　：画像リスト
	********************/
	function readAll() {

		$db = $this->handle;

		$fldArr = array(
			self::FLD_IMG_NO ,
			self::FLD_WIDTH ,self::FLD_HEIGHT ,self::FLD_TITLE ,self::FLD_CLASS ,
			self::FLD_ORG_WIDTH ,self::FLD_ORG_HEIGHT ,self::FLD_ORG_FILENAME ,self::FLD_ORG_EXT ,
			self::FLD_ADD_DT ,self::FLD_UPD_DT
		);
		$where =
			self::FLD_BRANCH_NO . '='  . $this->branchNo . ' and ' .
			self::FLD_USE_MARK  . '!=' . $db->setQuote(self::DISP_DEL);	/* 削除でないレコード */

		$ret = $this->readMain($fldArr ,$where);

		return $ret;
	}

	/********************
	表示可の画像データの読み込み
	パラメータ：-
	戻り値　　：画像リスト
	********************/
	function readShowable() {

		$db = $this->handle;

		$fldArr = array(
			self::FLD_IMG_NO ,
			self::FLD_WIDTH ,self::FLD_HEIGHT ,self::FLD_TITLE ,self::FLD_CLASS ,
			self::FLD_ORG_WIDTH ,self::FLD_ORG_HEIGHT ,self::FLD_ORG_FILENAME ,self::FLD_ORG_EXT ,
			self::FLD_ADD_DT ,self::FLD_UPD_DT
		);
		$where =
			self::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .
			self::FLD_USE_MARK  . '=' . $db->setQuote(self::DISP_ON);	/* 表示可のレコード */

		$ret = $this->readMain($fldArr ,$where);

		return $ret;
	}

	/********************
	画像リストの読み込み(本体)
	パラメータ：フィールドリスト
	　　　　　　条件
	戻り値　　：画像リスト
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

		$ret['imgInfo'] = $profile;
		$ret['count'  ] = count($profile);

		return $ret;
	}


	/********************
	画像の取得
	パラメータ：画像No.
	戻り値　　：
	********************/
	function get($imgNo) {

		$db = $this->handle;

		$fldArr = array(
			self::FLD_WIDTH ,self::FLD_HEIGHT ,self::FLD_TITLE ,self::FLD_CLASS ,
			self::FLD_ORG_WIDTH ,self::FLD_ORG_HEIGHT ,self::FLD_ORG_FILENAME ,self::FLD_ORG_EXT ,
			self::FLD_ADD_DT ,self::FLD_UPD_DT
		);
		$where = self::FLD_IMG_NO . '='  . $imgNo;


		$ret = $this->readMainONE($fldArr ,$where);

		return $ret;
	}

	/********************
	画像リストの読み込み(本体)
	パラメータ：フィールドリスト
	　　　　　　条件
	戻り値　　：画像情報
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
	画像情報の更新
	パラメータ：画像No
	戻り値　　：
	********************/
	function upd($imgNo) {

		$updDT = dateTime5C::getCurrDT();

		$db = $this->handle;

		$vals = $this->vals;


		$fldList =
			self::FLD_WIDTH  . '=' . $vals[self::FLD_WIDTH]  . ',' .
			self::FLD_HEIGHT . '=' . $vals[self::FLD_HEIGHT] . ',' .
			self::FLD_TITLE  . '=' . $db->setQuote($vals[self::FLD_TITLE]) . ',' .
			self::FLD_CLASS  . '=' . $db->setQuote($vals[self::FLD_CLASS]) . ',' .

			self::FLD_ORG_WIDTH    . '=' . $vals[self::FLD_ORG_WIDTH]  . ',' .
			self::FLD_ORG_HEIGHT   . '=' . $vals[self::FLD_ORG_HEIGHT] . ',' .
			self::FLD_ORG_FILENAME . '=' . $db->setQuote($vals[self::FLD_ORG_FILENAME]) . ',' .
			self::FLD_ORG_EXT      . '=' . $db->setQuote($vals[self::FLD_ORG_EXT]) . ',' .

			self::FLD_UPD_DT . '=' . $db->setQuote($updDT);

		$where = self::FLD_IMG_NO . '=' . $imgNo;

		$db->updateRec(self::TABLE_NAME ,$fldList ,$where);
	}

	/********************
	画像の追加
	パラメータ：-
	戻り値　　：
	********************/
	function add() {

		$db = $this->handle;

		$addDT = dateTime5C::getCurrDT();
		$vals = $this->vals;

		$fldArr = array(
			self::FLD_BRANCH_NO ,

			self::FLD_WIDTH  ,
			self::FLD_HEIGHT ,
			self::FLD_TITLE  ,
			self::FLD_CLASS  ,

			self::FLD_ORG_WIDTH    ,
			self::FLD_ORG_HEIGHT   ,
			self::FLD_ORG_FILENAME ,
			self::FLD_ORG_EXT      ,

			self::FLD_ADD_DT
		);

		$valList =
			$this->branchNo     . ',' .

			$vals[self::FLD_WIDTH]  . ',' .
			$vals[self::FLD_HEIGHT] . ',' .
			$db->setQuote($vals[self::FLD_TITLE]) . ',' .
			$db->setQuote($vals[self::FLD_CLASS]) . ',' .

			$vals[self::FLD_ORG_WIDTH]  . ',' .
			$vals[self::FLD_ORG_HEIGHT] . ',' .
			$db->setQuote($vals[self::FLD_ORG_FILENAME]) . ',' .
			$db->setQuote($vals[self::FLD_ORG_EXT]) . ',' .

			$db->setQuote($addDT);

		$fldList = funcs5C::setFldArrToList($fldArr);
		$db->insertRec(self::TABLE_NAME ,$fldList ,$valList);
	}



	/********************
	画像削除
	パラメータ：プロファイル
	戻り値　　：ナシ
	********************/
	function del($imgNo) {

		$db = $this->handle;

		$fldList =
			self::FLD_USE_MARK . '=' . self::DISP_DEL . ',' . 
			self::FLD_UPD_DT   . '=' . $db->setQuote($updDT);

		$where = self::FLD_IMG_NO . '=' . $imgNo;

		$db->updateRec(self::TABLE_NAME ,$fldList ,$where);
	}
}
?>
