<?php
/*************************
出勤データ Version 1.1
PHP5
2016 Feb. 20 ver 1.0
2016 May  25 ver 1.1 出勤データの読み込み時readForWeek()にディレクトリを指定できるように改造
*************************/

	require_once dirname(__FILE__) . '/dbProfile5C.php';

	require_once dirname(__FILE__) . '/../sql5C.php';
	require_once dirname(__FILE__) . '/../dateTime5C.php';
	require_once dirname(__FILE__) . '/../common5C.php';
	require_once dirname(__FILE__) . '/../strings5C.php';
	require_once dirname(__FILE__) . '/../funcs5C.php';

class dbWorks5C {

	/*** テーブル名 ***/
	const TABLE_NAME = 'worksList';


	/*** フィールド ***/
	const FLD_BRANCH_NO = 'branchNo';	/* 店No */

	const FLD_DIR       = 'dir';		/* プロファイルID */
	const FLD_DISP      = 'disp';		/* 表示/非表示 */

	const FLD_SUN_F = 'sunF';	/* 日曜 */
	const FLD_SUN_T = 'sunT';
	const FLD_SUN_M = 'sunM';

	const FLD_MON_F = 'monF';	/* 月曜 */
	const FLD_MON_T = 'monT';
	const FLD_MON_M = 'monM';

	const FLD_TUE_F = 'tueF';	/* 火曜 */
	const FLD_TUE_T = 'tueT';
	const FLD_TUE_M = 'tueM';

	const FLD_WED_F = 'wedF';	/* 水曜 */
	const FLD_WED_T = 'wedT';
	const FLD_WED_M = 'wedM';

	const FLD_THU_F = 'thuF';	/* 木曜 */
	const FLD_THU_T = 'thuT';
	const FLD_THU_M = 'thuM';

	const FLD_FRI_F = 'FriF';	/* 金曜 */
	const FLD_FRI_T = 'FriT';
	const FLD_FRI_M = 'friM';

	const FLD_SAT_F = 'satF';	/* 土曜 */
	const FLD_SAT_T = 'satT';
	const FLD_SAT_M = 'satM';


	/*** 差分 ***/
	const FLD_DIFF1_D = 'diff1Date';
	const FLD_DIFF1_F = 'diff1F';
	const FLD_DIFF1_T = 'diff1T';
	const FLD_DIFF1_M = 'diff1M';

	const FLD_DIFF2_D = 'diff2Date';
	const FLD_DIFF2_F = 'diff2F';
	const FLD_DIFF2_T = 'diff2T';
	const FLD_DIFF2_M = 'diff2M';

	const FLD_DIFF3_D = 'diff3Date';
	const FLD_DIFF3_F = 'diff3F';
	const FLD_DIFF3_T = 'diff3T';
	const FLD_DIFF3_M = 'diff3M';

	const FLD_DIFF4_D = 'diff4Date';
	const FLD_DIFF4_F = 'diff4F';
	const FLD_DIFF4_T = 'diff4T';
	const FLD_DIFF4_M = 'diff4M';

	const FLD_DIFF5_D = 'diff5Date';
	const FLD_DIFF5_F = 'diff5F';
	const FLD_DIFF5_T = 'diff5T';
	const FLD_DIFF5_M = 'diff5M';

	const FLD_DIFF6_D = 'diff6Date';
	const FLD_DIFF6_F = 'diff6F';
	const FLD_DIFF6_T = 'diff6T';
	const FLD_DIFF6_M = 'diff6M';

	const FLD_DIFF7_D = 'diff7Date';
	const FLD_DIFF7_F = 'diff7F';
	const FLD_DIFF7_T = 'diff7T';
	const FLD_DIFF7_M = 'diff7M';

	const FLD_ADD_DT = 'addDT';		/* レコード登録日時 */
	const FLD_UPD_DT = 'updDT';		/* レコード更新日時 */


	/***** 定数 *****/
	const WORK_MODE_RECEPT = 'R';	/* 受付 */
	const WORK_MODE_TO     = 'T';	/* まで */


	const IDX_DOW = 'dow';
	const IDX_F   = 'from';
	const IDX_T   = 'to';
	const IDX_M   = 'mode';

	const IDX_DIFF   = 'diff';
	const IDX_DIFF_D = 'date';
	const IDX_DIFF_F = 'from';
	const IDX_DIFF_T = 'to';
	const IDX_DIFF_M = 'mode';

	var $handle;
	var $branchNo;
	var $vals;


	/********************
	コンストラクタ(DB接続)
	パラメータ：-
	戻り値　　：-
	********************/
	function dbWorks5C($branchNo ,$handle=null) {

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
	出勤データの読み込み(削除以外)
	パラメータ：-
	戻り値　　：出勤リスト
	********************/
	function readAll() {

		$db = $this->handle;

		$fldArr = array(
			self::FLD_DIR ,self::FLD_DISP_SEQ ,self::FLD_DISP ,

			self::FLD_SUN_F ,self::FLD_SUN_T ,self::FLD_SUN_M ,
			self::FLD_MON_F ,self::FLD_MON_T ,self::FLD_MON_M ,
			self::FLD_TUE_F ,self::FLD_TUE_T ,self::FLD_TUE_M ,
			self::FLD_WED_F ,self::FLD_WED_T ,self::FLD_WED_M ,
			self::FLD_THU_F ,self::FLD_THU_T ,self::FLD_THU_M ,
			self::FLD_FRI_F ,self::FLD_FRI_T ,self::FLD_FRI_M ,
			self::FLD_SAT_F ,self::FLD_SAT_T ,self::FLD_SAT_M ,

			self::FLD_DIFF1_D ,self::FLD_DIFF1_F ,self::FLD_DIFF1_T ,self::FLD_DIFF1_M ,
			self::FLD_DIFF2_D ,self::FLD_DIFF2_F ,self::FLD_DIFF2_T ,self::FLD_DIFF2_M ,
			self::FLD_DIFF3_D ,self::FLD_DIFF3_F ,self::FLD_DIFF3_T ,self::FLD_DIFF3_M ,
			self::FLD_DIFF4_D ,self::FLD_DIFF4_F ,self::FLD_DIFF4_T ,self::FLD_DIFF4_M ,
			self::FLD_DIFF5_D ,self::FLD_DIFF5_F ,self::FLD_DIFF5_T ,self::FLD_DIFF5_M ,
			self::FLD_DIFF6_D ,self::FLD_DIFF6_F ,self::FLD_DIFF6_T ,self::FLD_DIFF6_M ,
			self::FLD_DIFF7_D ,self::FLD_DIFF7_F ,self::FLD_DIFF7_T ,self::FLD_DIFF7_M ,

			self::FLD_ADD_DT ,self::FLD_UPD_DT
		);
		$where =
			self::FLD_BRANCH_NO . '='  . $this->$branchNo . ' and ' .
			self::FLD_DISP      . '!=' . $db->setQuote(self::DISP_DEL);	/* 削除でないレコード */

		$ret = $this->readMain($fldArr ,$where);

		return $ret;
	}

	/********************
	表示可の出勤データの読み込み
	パラメータ：-
	戻り値　　：出勤リスト
	********************/
	function readShowable() {

		$db = $this->handle;

		$fldArr = array(
			self::FLD_DIR ,self::FLD_DISP_SEQ ,self::FLD_DISP ,

			self::FLD_SUN_F ,self::FLD_SUN_T ,self::FLD_SUN_M ,
			self::FLD_MON_F ,self::FLD_MON_T ,self::FLD_MON_M ,
			self::FLD_TUE_F ,self::FLD_TUE_T ,self::FLD_TUE_M ,
			self::FLD_WED_F ,self::FLD_WED_T ,self::FLD_WED_M ,
			self::FLD_THU_F ,self::FLD_THU_T ,self::FLD_THU_M ,
			self::FLD_FRI_F ,self::FLD_FRI_T ,self::FLD_FRI_M ,
			self::FLD_SAT_F ,self::FLD_SAT_T ,self::FLD_SAT_M ,

			self::FLD_DIFF1_D ,self::FLD_DIFF1_F ,self::FLD_DIFF1_T ,self::FLD_DIFF1_M ,
			self::FLD_DIFF2_D ,self::FLD_DIFF2_F ,self::FLD_DIFF2_T ,self::FLD_DIFF2_M ,
			self::FLD_DIFF3_D ,self::FLD_DIFF3_F ,self::FLD_DIFF3_T ,self::FLD_DIFF3_M ,
			self::FLD_DIFF4_D ,self::FLD_DIFF4_F ,self::FLD_DIFF4_T ,self::FLD_DIFF4_M ,
			self::FLD_DIFF5_D ,self::FLD_DIFF5_F ,self::FLD_DIFF5_T ,self::FLD_DIFF5_M ,
			self::FLD_DIFF6_D ,self::FLD_DIFF6_F ,self::FLD_DIFF6_T ,self::FLD_DIFF6_M ,
			self::FLD_DIFF7_D ,self::FLD_DIFF7_F ,self::FLD_DIFF7_T ,self::FLD_DIFF7_M ,

			self::FLD_ADD_DT ,self::FLD_UPD_DT
		);
		$where =
			self::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .
			self::FLD_DISP      . '=' . $db->setQuote(self::DISP_ON);	/* 表示可のレコード */

		$ret = $this->readMain($fldArr ,$where);

		return $ret;
	}


	/********************
	出勤リストの読み込み(本体)
	パラメータ：フィールドリスト
	　　　　　　条件
	戻り値　　：出勤リスト
	********************/
	private function readMain($fldArr ,$where) {

		$fldList = funcs5C::setFldArrToList($fldArr);
		$order   = self::FLD_DISP_SEQ;

		$db = $this->handle;
		$workList = $db->selectRec(self::TABLE_NAME ,$fldList ,$where ,$order);

		$fldListMax = count($fldArr);
		if($workList['rows'] <= 0) {
			/*** 0件のとき ***/
			for($fldIdx=0 ;$fldIdx<$fldListMax ;$fldIdx++) {
				$fldName = $fldArr[$fldIdx];
				$work[0][$fldName] = '';
			}
		} else {
			/*** 1件以上あったとき ***/
			$recList = $workList['fetch'];
			$recIdxMax = $workList['rows'];
			for($recIdx=0 ;$recIdx<$recIdxMax ;$recIdx++) {
				$rec1 = $recList[$recIdx];
				for($fldIdx=0 ;$fldIdx<$fldListMax ;$fldIdx++) {
					$fldName = $fldArr[$fldIdx];
					$work[$recIdx][$fldName] = $rec1[$fldIdx];
				}
			}
		}

		$ret['workInfo'] = $work;
		$ret['count'   ] = count($work);

		return $ret;
	}


	/********************
	出勤情報の取得
	パラメータ：ディレクトリ
	戻り値　　：
	********************/
	function get($dir) {

		$db = $this->handle;

		$fldArr = array(
			self::FLD_DIR ,self::FLD_DISP ,

			self::FLD_SUN_F ,self::FLD_SUN_T ,self::FLD_SUN_M ,
			self::FLD_MON_F ,self::FLD_MON_T ,self::FLD_MON_M ,
			self::FLD_TUE_F ,self::FLD_TUE_T ,self::FLD_TUE_M ,
			self::FLD_WED_F ,self::FLD_WED_T ,self::FLD_WED_M ,
			self::FLD_THU_F ,self::FLD_THU_T ,self::FLD_THU_M ,
			self::FLD_FRI_F ,self::FLD_FRI_T ,self::FLD_FRI_M ,
			self::FLD_SAT_F ,self::FLD_SAT_T ,self::FLD_SAT_M ,

			self::FLD_DIFF1_D ,self::FLD_DIFF1_F ,self::FLD_DIFF1_T ,self::FLD_DIFF1_M ,
			self::FLD_DIFF2_D ,self::FLD_DIFF2_F ,self::FLD_DIFF2_T ,self::FLD_DIFF2_M ,
			self::FLD_DIFF3_D ,self::FLD_DIFF3_F ,self::FLD_DIFF3_T ,self::FLD_DIFF3_M ,
			self::FLD_DIFF4_D ,self::FLD_DIFF4_F ,self::FLD_DIFF4_T ,self::FLD_DIFF4_M ,
			self::FLD_DIFF5_D ,self::FLD_DIFF5_F ,self::FLD_DIFF5_T ,self::FLD_DIFF5_M ,
			self::FLD_DIFF6_D ,self::FLD_DIFF6_F ,self::FLD_DIFF6_T ,self::FLD_DIFF6_M ,
			self::FLD_DIFF7_D ,self::FLD_DIFF7_F ,self::FLD_DIFF7_T ,self::FLD_DIFF7_M ,

			self::FLD_ADD_DT ,self::FLD_UPD_DT
		);
		$where =
			self::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .
			self::FLD_DIR       . '=' . $db->setQuote($dir);

		$ret = $this->readMainONE($fldArr ,$where);

		return $ret;
	}

	/********************
	出勤情報リストの読み込み(本体)
	パラメータ：フィールドリスト
	　　　　　　条件
	戻り値　　：出勤情報リスト
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
	日付ごとにリスト整形
	パラメータ：出勤情報リスト
	　　　　　　日付リスト
	戻り値　　：日付順のリスト
	********************/
	function setByDate($workList ,$dateList) {

		$dowArr = array(
			array(self::IDX_F => $workList[self::FLD_SUN_F] ,self::IDX_T => $workList[self::FLD_SUN_T] ,self::IDX_M => $workList[self::FLD_SUN_M]) ,
			array(self::IDX_F => $workList[self::FLD_MON_F] ,self::IDX_T => $workList[self::FLD_MON_T] ,self::IDX_M => $workList[self::FLD_MON_M]) ,
			array(self::IDX_F => $workList[self::FLD_TUE_F] ,self::IDX_T => $workList[self::FLD_TUE_T] ,self::IDX_M => $workList[self::FLD_TUE_M]) ,
			array(self::IDX_F => $workList[self::FLD_WED_F] ,self::IDX_T => $workList[self::FLD_WED_T] ,self::IDX_M => $workList[self::FLD_WED_M]) ,
			array(self::IDX_F => $workList[self::FLD_THU_F] ,self::IDX_T => $workList[self::FLD_THU_T] ,self::IDX_M => $workList[self::FLD_THU_M]) ,
			array(self::IDX_F => $workList[self::FLD_FRI_F] ,self::IDX_T => $workList[self::FLD_FRI_T] ,self::IDX_M => $workList[self::FLD_FRI_M]) ,
			array(self::IDX_F => $workList[self::FLD_SAT_F] ,self::IDX_T => $workList[self::FLD_SAT_T] ,self::IDX_M => $workList[self::FLD_SAT_M])
		);

		$diffArr = array(
			$workList[self::FLD_DIFF1_D] =>
				array(self::IDX_DIFF_F => $workList[self::FLD_DIFF1_F] ,self::IDX_DIFF_T => $workList[self::FLD_DIFF1_T] ,self::IDX_DIFF_M => $workList[self::FLD_DIFF1_M]) ,
			$workList[self::FLD_DIFF2_D] =>
				array(self::IDX_DIFF_F => $workList[self::FLD_DIFF2_F] ,self::IDX_DIFF_T => $workList[self::FLD_DIFF2_T] ,self::IDX_DIFF_M => $workList[self::FLD_DIFF2_M]) ,
			$workList[self::FLD_DIFF3_D] =>
				array(self::IDX_DIFF_F => $workList[self::FLD_DIFF3_F] ,self::IDX_DIFF_T => $workList[self::FLD_DIFF3_T] ,self::IDX_DIFF_M => $workList[self::FLD_DIFF3_M]) ,
			$workList[self::FLD_DIFF4_D] =>
				array(self::IDX_DIFF_F => $workList[self::FLD_DIFF4_F] ,self::IDX_DIFF_T => $workList[self::FLD_DIFF4_T] ,self::IDX_DIFF_M => $workList[self::FLD_DIFF4_M]) ,
			$workList[self::FLD_DIFF5_D] =>
				array(self::IDX_DIFF_F => $workList[self::FLD_DIFF5_F] ,self::IDX_DIFF_T => $workList[self::FLD_DIFF5_T] ,self::IDX_DIFF_M => $workList[self::FLD_DIFF5_M]) ,
			$workList[self::FLD_DIFF6_D] =>
				array(self::IDX_DIFF_F => $workList[self::FLD_DIFF6_F] ,self::IDX_DIFF_T => $workList[self::FLD_DIFF6_T] ,self::IDX_DIFF_M => $workList[self::FLD_DIFF6_M]) ,
			$workList[self::FLD_DIFF7_D] =>
				array(self::IDX_DIFF_F => $workList[self::FLD_DIFF7_F] ,self::IDX_DIFF_T => $workList[self::FLD_DIFF7_T] ,self::IDX_DIFF_M => $workList[self::FLD_DIFF7_M])
		);


		$dateListMax = count($dateList);
		for($dateIdx=0 ;$dateIdx<$dateListMax ;$dateIdx++) {
			$dateStr = $dateList[$dateIdx];
			$dow = dateTime5C::getDOW($dateStr ,'-');

			/*** 曜日ごとの出勤情報 ***/
			$retWorks[$dateStr] = $dowArr[$dow];
			$retWorks[$dateStr][self::IDX_DOW] = $dow;

			/*** 差分 ***/
			if(isset($diffArr[$dateStr])) {
				$retWorks[$dateStr][self::IDX_DIFF] = $diffArr[$dateStr];
			}
		}

		return $retWorks;
	}


	/********************
	出勤情報の追加
	パラメータ：ディレクトリ
	　　　　　　表示
	戻り値　　：
	********************/
	function add($dir ,$dispVal) {

		$addDT = dateTime5C::getCurrDT();

		$db = $this->handle;

		$vals = $this->vals;

		$fldArr = array(
			self::FLD_BRANCH_NO ,self::FLD_DIR ,self::FLD_DISP ,

			self::FLD_SUN_F ,self::FLD_SUN_T ,self::FLD_SUN_M ,
			self::FLD_MON_F ,self::FLD_MON_T ,self::FLD_MON_M ,
			self::FLD_TUE_F ,self::FLD_TUE_T ,self::FLD_TUE_M ,
			self::FLD_WED_F ,self::FLD_WED_T ,self::FLD_WED_M ,
			self::FLD_THU_F ,self::FLD_THU_T ,self::FLD_THU_M ,
			self::FLD_FRI_F ,self::FLD_FRI_T ,self::FLD_FRI_M ,
			self::FLD_SAT_F ,self::FLD_SAT_T ,self::FLD_SAT_M ,

			self::FLD_DIFF1_D ,self::FLD_DIFF1_F ,self::FLD_DIFF1_T ,self::FLD_DIFF1_M ,
			self::FLD_DIFF2_D ,self::FLD_DIFF2_F ,self::FLD_DIFF2_T ,self::FLD_DIFF2_M ,
			self::FLD_DIFF3_D ,self::FLD_DIFF3_F ,self::FLD_DIFF3_T ,self::FLD_DIFF3_M ,
			self::FLD_DIFF4_D ,self::FLD_DIFF4_F ,self::FLD_DIFF4_T ,self::FLD_DIFF4_M ,
			self::FLD_DIFF5_D ,self::FLD_DIFF5_F ,self::FLD_DIFF5_T ,self::FLD_DIFF5_M ,
			self::FLD_DIFF6_D ,self::FLD_DIFF6_F ,self::FLD_DIFF6_T ,self::FLD_DIFF6_M ,
			self::FLD_DIFF7_D ,self::FLD_DIFF7_F ,self::FLD_DIFF7_T ,self::FLD_DIFF7_M ,

			self::FLD_ADD_DT
		);

		$valList =
			$this->branchNo . ',' . $db->setQuote($dir) . ',' . $db->setQuote($dispVal) . ',' .

			$db->setQuote($vals[self::FLD_SUN_F]) . ',' . $db->setQuote($vals[self::FLD_SUN_T]) . ',' . $db->setQuote($vals[self::FLD_SUN_M]) . ',' .
			$db->setQuote($vals[self::FLD_MON_F]) . ',' . $db->setQuote($vals[self::FLD_MON_T]) . ',' . $db->setQuote($vals[self::FLD_MON_M]) . ',' .
			$db->setQuote($vals[self::FLD_TUE_F]) . ',' . $db->setQuote($vals[self::FLD_TUE_T]) . ',' . $db->setQuote($vals[self::FLD_TUE_M]) . ',' .
			$db->setQuote($vals[self::FLD_WED_F]) . ',' . $db->setQuote($vals[self::FLD_WED_T]) . ',' . $db->setQuote($vals[self::FLD_WED_M]) . ',' .
			$db->setQuote($vals[self::FLD_THU_F]) . ',' . $db->setQuote($vals[self::FLD_THU_T]) . ',' . $db->setQuote($vals[self::FLD_THU_M]) . ',' .
			$db->setQuote($vals[self::FLD_FRI_F]) . ',' . $db->setQuote($vals[self::FLD_FRI_T]) . ',' . $db->setQuote($vals[self::FLD_FRI_M]) . ',' .
			$db->setQuote($vals[self::FLD_SAT_F]) . ',' . $db->setQuote($vals[self::FLD_SAT_T]) . ',' . $db->setQuote($vals[self::FLD_SAT_M]) . ',' .

			$db->setQuote($vals[self::FLD_DIFF1_D]) . ',' . $db->setQuote($vals[self::FLD_DIFF1_F]) . ',' . $db->setQuote($vals[self::FLD_DIFF1_T]) . ',' . $db->setQuote($vals[self::FLD_DIFF1_M]) . ',' .
			$db->setQuote($vals[self::FLD_DIFF2_D]) . ',' . $db->setQuote($vals[self::FLD_DIFF2_F]) . ',' . $db->setQuote($vals[self::FLD_DIFF2_T]) . ',' . $db->setQuote($vals[self::FLD_DIFF2_M]) . ',' .
			$db->setQuote($vals[self::FLD_DIFF3_D]) . ',' . $db->setQuote($vals[self::FLD_DIFF3_F]) . ',' . $db->setQuote($vals[self::FLD_DIFF3_T]) . ',' . $db->setQuote($vals[self::FLD_DIFF3_M]) . ',' .
			$db->setQuote($vals[self::FLD_DIFF4_D]) . ',' . $db->setQuote($vals[self::FLD_DIFF4_F]) . ',' . $db->setQuote($vals[self::FLD_DIFF4_T]) . ',' . $db->setQuote($vals[self::FLD_DIFF4_M]) . ',' .
			$db->setQuote($vals[self::FLD_DIFF5_D]) . ',' . $db->setQuote($vals[self::FLD_DIFF5_F]) . ',' . $db->setQuote($vals[self::FLD_DIFF5_T]) . ',' . $db->setQuote($vals[self::FLD_DIFF5_M]) . ',' .
			$db->setQuote($vals[self::FLD_DIFF6_D]) . ',' . $db->setQuote($vals[self::FLD_DIFF6_F]) . ',' . $db->setQuote($vals[self::FLD_DIFF6_T]) . ',' . $db->setQuote($vals[self::FLD_DIFF6_M]) . ',' .
			$db->setQuote($vals[self::FLD_DIFF7_D]) . ',' . $db->setQuote($vals[self::FLD_DIFF7_F]) . ',' . $db->setQuote($vals[self::FLD_DIFF7_T]) . ',' . $db->setQuote($vals[self::FLD_DIFF7_M]) . ',' .

			$db->setQuote($addDT
		);

		$fldList = funcs5C::setFldArrToList($fldArr);
		$db->insertRec(self::TABLE_NAME ,$fldList ,$valList);
	}


	/********************
	出勤情報の更新
	パラメータ：ディレクトリ
	戻り値　　：
	********************/
	function upd($dir) {

		$updDT = dateTime5C::getCurrDT();

		$db = $this->handle;

		$vals = $this->vals;

		$fldList =
			self::FLD_SUN_F . '=' . $db->setQuote($vals[self::FLD_SUN_F]) . ',' .
			self::FLD_SUN_T . '=' . $db->setQuote($vals[self::FLD_SUN_T]) . ',' .
			self::FLD_SUN_M . '=' . $db->setQuote($vals[self::FLD_SUN_M]) . ',' .

			self::FLD_MON_F . '=' . $db->setQuote($vals[self::FLD_MON_F]) . ',' .
			self::FLD_MON_T . '=' . $db->setQuote($vals[self::FLD_MON_T]) . ',' .
			self::FLD_MON_M . '=' . $db->setQuote($vals[self::FLD_MON_M]) . ',' .

			self::FLD_TUE_F . '=' . $db->setQuote($vals[self::FLD_TUE_F]) . ',' .
			self::FLD_TUE_T . '=' . $db->setQuote($vals[self::FLD_TUE_T]) . ',' .
			self::FLD_TUE_M . '=' . $db->setQuote($vals[self::FLD_TUE_M]) . ',' .

			self::FLD_WED_F . '=' . $db->setQuote($vals[self::FLD_WED_F]) . ',' .
			self::FLD_WED_T . '=' . $db->setQuote($vals[self::FLD_WED_T]) . ',' .
			self::FLD_WED_M . '=' . $db->setQuote($vals[self::FLD_WED_M]) . ',' .

			self::FLD_THU_F . '=' . $db->setQuote($vals[self::FLD_THU_F]) . ',' .
			self::FLD_THU_T . '=' . $db->setQuote($vals[self::FLD_THU_T]) . ',' .
			self::FLD_THU_M . '=' . $db->setQuote($vals[self::FLD_THU_M]) . ',' .

			self::FLD_FRI_F . '=' . $db->setQuote($vals[self::FLD_FRI_F]) . ',' .
			self::FLD_FRI_T . '=' . $db->setQuote($vals[self::FLD_FRI_T]) . ',' .
			self::FLD_FRI_M . '=' . $db->setQuote($vals[self::FLD_FRI_M]) . ',' .

			self::FLD_SAT_F . '=' . $db->setQuote($vals[self::FLD_SAT_F]) . ',' .
			self::FLD_SAT_T . '=' . $db->setQuote($vals[self::FLD_SAT_T]) . ',' .
			self::FLD_SAT_M . '=' . $db->setQuote($vals[self::FLD_SAT_M]) . ',' .


			self::FLD_DIFF1_D . '=' . $db->setQuote($vals[self::FLD_DIFF1_D]) . ',' .
			self::FLD_DIFF1_F . '=' . $db->setQuote($vals[self::FLD_DIFF1_F]) . ',' .
			self::FLD_DIFF1_T . '=' . $db->setQuote($vals[self::FLD_DIFF1_T]) . ',' .
			self::FLD_DIFF1_M . '=' . $db->setQuote($vals[self::FLD_DIFF1_M]) . ',' .

			self::FLD_DIFF2_D . '=' . $db->setQuote($vals[self::FLD_DIFF2_D]) . ',' .
			self::FLD_DIFF2_F . '=' . $db->setQuote($vals[self::FLD_DIFF2_F]) . ',' .
			self::FLD_DIFF2_T . '=' . $db->setQuote($vals[self::FLD_DIFF2_T]) . ',' .
			self::FLD_DIFF2_M . '=' . $db->setQuote($vals[self::FLD_DIFF2_M]) . ',' .

			self::FLD_DIFF3_D . '=' . $db->setQuote($vals[self::FLD_DIFF3_D]) . ',' .
			self::FLD_DIFF3_F . '=' . $db->setQuote($vals[self::FLD_DIFF3_F]) . ',' .
			self::FLD_DIFF3_T . '=' . $db->setQuote($vals[self::FLD_DIFF3_T]) . ',' .
			self::FLD_DIFF3_M . '=' . $db->setQuote($vals[self::FLD_DIFF3_M]) . ',' .

			self::FLD_DIFF4_D . '=' . $db->setQuote($vals[self::FLD_DIFF4_D]) . ',' .
			self::FLD_DIFF4_F . '=' . $db->setQuote($vals[self::FLD_DIFF4_F]) . ',' .
			self::FLD_DIFF4_T . '=' . $db->setQuote($vals[self::FLD_DIFF4_T]) . ',' .
			self::FLD_DIFF4_M . '=' . $db->setQuote($vals[self::FLD_DIFF4_M]) . ',' .

			self::FLD_DIFF5_D . '=' . $db->setQuote($vals[self::FLD_DIFF5_D]) . ',' .
			self::FLD_DIFF5_F . '=' . $db->setQuote($vals[self::FLD_DIFF5_F]) . ',' .
			self::FLD_DIFF5_T . '=' . $db->setQuote($vals[self::FLD_DIFF5_T]) . ',' .
			self::FLD_DIFF5_M . '=' . $db->setQuote($vals[self::FLD_DIFF5_M]) . ',' .

			self::FLD_DIFF6_D . '=' . $db->setQuote($vals[self::FLD_DIFF6_D]) . ',' .
			self::FLD_DIFF6_F . '=' . $db->setQuote($vals[self::FLD_DIFF6_F]) . ',' .
			self::FLD_DIFF6_T . '=' . $db->setQuote($vals[self::FLD_DIFF6_T]) . ',' .
			self::FLD_DIFF6_M . '=' . $db->setQuote($vals[self::FLD_DIFF6_M]) . ',' .

			self::FLD_DIFF7_D . '=' . $db->setQuote($vals[self::FLD_DIFF7_D]) . ',' .
			self::FLD_DIFF7_F . '=' . $db->setQuote($vals[self::FLD_DIFF7_F]) . ',' .
			self::FLD_DIFF7_T . '=' . $db->setQuote($vals[self::FLD_DIFF7_T]) . ',' .
			self::FLD_DIFF7_M . '=' . $db->setQuote($vals[self::FLD_DIFF7_M]) . ',' .

			self::FLD_UPD_DT . '=' . $db->setQuote($updDT);

		$where =
			self::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .
			self::FLD_DIR       . '=' . $db->setQuote($dir);

		$db->updateRec(self::TABLE_NAME ,$fldList ,$where);
	}


	/********************
	TOPページの表示用出勤データの読み込み
	パラメータ：-
	戻り値　　：出勤リスト
	********************/
	function readForTop() {

		$db = $this->handle;

		$dow = date('w');

		$fldArr = array(
			self::TABLE_NAME . '.' . self::FLD_DIR ,

			self::TABLE_NAME . '.' . self::FLD_DIFF1_D ,self::TABLE_NAME . '.' . self::FLD_DIFF1_F ,self::TABLE_NAME . '.' . self::FLD_DIFF1_T ,self::TABLE_NAME . '.' . self::FLD_DIFF1_M ,
			self::TABLE_NAME . '.' . self::FLD_DIFF2_D ,self::TABLE_NAME . '.' . self::FLD_DIFF2_F ,self::TABLE_NAME . '.' . self::FLD_DIFF2_T ,self::TABLE_NAME . '.' . self::FLD_DIFF2_M ,
			self::TABLE_NAME . '.' . self::FLD_DIFF3_D ,self::TABLE_NAME . '.' . self::FLD_DIFF3_F ,self::TABLE_NAME . '.' . self::FLD_DIFF3_T ,self::TABLE_NAME . '.' . self::FLD_DIFF3_M ,
			self::TABLE_NAME . '.' . self::FLD_DIFF4_D ,self::TABLE_NAME . '.' . self::FLD_DIFF4_F ,self::TABLE_NAME . '.' . self::FLD_DIFF4_T ,self::TABLE_NAME . '.' . self::FLD_DIFF4_M ,
			self::TABLE_NAME . '.' . self::FLD_DIFF5_D ,self::TABLE_NAME . '.' . self::FLD_DIFF5_F ,self::TABLE_NAME . '.' . self::FLD_DIFF5_T ,self::TABLE_NAME . '.' . self::FLD_DIFF5_M ,
			self::TABLE_NAME . '.' . self::FLD_DIFF6_D ,self::TABLE_NAME . '.' . self::FLD_DIFF6_F ,self::TABLE_NAME . '.' . self::FLD_DIFF6_T ,self::TABLE_NAME . '.' . self::FLD_DIFF6_M ,
			self::TABLE_NAME . '.' . self::FLD_DIFF7_D ,self::TABLE_NAME . '.' . self::FLD_DIFF7_F ,self::TABLE_NAME . '.' . self::FLD_DIFF7_T ,self::TABLE_NAME . '.' . self::FLD_DIFF7_M ,

			dbProfile5C::TABLE_NAME . '.' . dbProfile5C::FLD_NAME
		);

		if($dow == 0) {
			$fldArr[] = self::TABLE_NAME . '.' . self::FLD_SUN_F;
			$fldArr[] = self::TABLE_NAME . '.' . self::FLD_SUN_T;
			$fldArr[] = self::TABLE_NAME . '.' . self::FLD_SUN_M;
		}

		if($dow == 1) {
			$fldArr[] = self::TABLE_NAME . '.' . self::FLD_MON_F;
			$fldArr[] = self::TABLE_NAME . '.' . self::FLD_MON_T;
			$fldArr[] = self::TABLE_NAME . '.' . self::FLD_MON_M;
		}

		if($dow == 2) {
			$fldArr[] = self::TABLE_NAME . '.' . self::FLD_TUE_F;
			$fldArr[] = self::TABLE_NAME . '.' . self::FLD_TUE_T;
			$fldArr[] = self::TABLE_NAME . '.' . self::FLD_TUE_M;
		}

		if($dow == 3) {
			$fldArr[] = self::TABLE_NAME . '.' . self::FLD_WED_F;
			$fldArr[] = self::TABLE_NAME . '.' . self::FLD_WED_T;
			$fldArr[] = self::TABLE_NAME . '.' . self::FLD_WED_M;
		}

		if($dow == 4) {
			$fldArr[] = self::TABLE_NAME . '.' . self::FLD_THU_F;
			$fldArr[] = self::TABLE_NAME . '.' . self::FLD_THU_T;
			$fldArr[] = self::TABLE_NAME . '.' . self::FLD_THU_M;
		}

		if($dow == 5) {
			$fldArr[] = self::TABLE_NAME . '.' . self::FLD_FRI_F;
			$fldArr[] = self::TABLE_NAME . '.' . self::FLD_FRI_T;
			$fldArr[] = self::TABLE_NAME . '.' . self::FLD_FRI_M;
		}

		if($dow == 6) {
			$fldArr[] = self::TABLE_NAME . '.' . self::FLD_SAT_F;
			$fldArr[] = self::TABLE_NAME . '.' . self::FLD_SAT_T;
			$fldArr[] = self::TABLE_NAME . '.' . self::FLD_SAT_M;
		}
		$fldList = funcs5C::setFldArrToList($fldArr);

		$where =
			self::TABLE_NAME . '.' . self::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .

			dbProfile5C::TABLE_NAME . '.' . dbProfile5C::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .
			dbProfile5C::TABLE_NAME . '.' . dbProfile5C::FLD_DISP      . '=' . $db->setQuote(dbProfile5C::DISP_ON) . ' and ' .
			dbProfile5C::TABLE_NAME . '.' . dbProfile5C::FLD_DIR       . '=' . self::TABLE_NAME . '.' . self::FLD_DIR
		;

		$order = dbProfile5C::TABLE_NAME . '.' . dbProfile5C::FLD_DISP_SEQ;

		$db = $this->handle;
		$workList = $db->selectRec(self::TABLE_NAME . ',' . dbProfile5C::TABLE_NAME ,$fldList ,$where ,$order);

		$fldListMax = count($fldArr);
		if($workList['rows'] <= 0) {
			/*** 0件のとき ***/
			$work[0] = array(
				self::FLD_DIR   => '' ,

				self::FLD_DIFF1_D => '' ,self::FLD_DIFF1_F => '' ,self::FLD_DIFF1_T => '' ,self::FLD_DIFF1_M => '' ,
				self::FLD_DIFF2_D => '' ,self::FLD_DIFF2_F => '' ,self::FLD_DIFF2_T => '' ,self::FLD_DIFF2_M => '' ,
				self::FLD_DIFF3_D => '' ,self::FLD_DIFF3_F => '' ,self::FLD_DIFF3_T => '' ,self::FLD_DIFF3_M => '' ,
				self::FLD_DIFF4_D => '' ,self::FLD_DIFF4_F => '' ,self::FLD_DIFF4_T => '' ,self::FLD_DIFF4_M => '' ,
				self::FLD_DIFF5_D => '' ,self::FLD_DIFF5_F => '' ,self::FLD_DIFF5_T => '' ,self::FLD_DIFF5_M => '' ,
				self::FLD_DIFF6_D => '' ,self::FLD_DIFF6_F => '' ,self::FLD_DIFF6_T => '' ,self::FLD_DIFF6_M => '' ,
				self::FLD_DIFF7_D => '' ,self::FLD_DIFF7_F => '' ,self::FLD_DIFF7_T => '' ,self::FLD_DIFF7_M => '' ,

				dbProfile5C::FLD_NAME => '' ,

				'FROM' => '' ,'TO' => '' ,'MODE' => ''
			);
		} else {
			/*** 1件以上あったとき ***/
			$recList = $workList['fetch'];
			$recIdxMax = $workList['rows'];
			for($recIdx=0 ;$recIdx<$recIdxMax ;$recIdx++) {
				$rec1 = $recList[$recIdx];

				$work[$recIdx] = array(
					self::FLD_DIR   => $rec1[ 0] ,

					self::FLD_DIFF1_D => $rec1[ 1] ,
					self::FLD_DIFF1_F => $rec1[ 2] ,
					self::FLD_DIFF1_T => $rec1[ 3] ,
					self::FLD_DIFF1_M => $rec1[ 4] ,

					self::FLD_DIFF2_D => $rec1[ 5] ,
					self::FLD_DIFF2_F => $rec1[ 6] ,
					self::FLD_DIFF2_T => $rec1[ 7] ,
					self::FLD_DIFF2_M => $rec1[ 8] ,

					self::FLD_DIFF3_D => $rec1[ 9] ,
					self::FLD_DIFF3_F => $rec1[10] ,
					self::FLD_DIFF3_T => $rec1[11] ,
					self::FLD_DIFF3_M => $rec1[12] ,

					self::FLD_DIFF4_D => $rec1[13] ,
					self::FLD_DIFF4_F => $rec1[14] ,
					self::FLD_DIFF4_T => $rec1[15] ,
					self::FLD_DIFF4_M => $rec1[16] ,

					self::FLD_DIFF5_D => $rec1[17] ,
					self::FLD_DIFF5_F => $rec1[18] ,
					self::FLD_DIFF5_T => $rec1[19] ,
					self::FLD_DIFF5_M => $rec1[20] ,

					self::FLD_DIFF6_D => $rec1[21] ,
					self::FLD_DIFF6_F => $rec1[22] ,
					self::FLD_DIFF6_T => $rec1[23] ,
					self::FLD_DIFF6_M => $rec1[24] ,

					self::FLD_DIFF7_D => $rec1[25] ,
					self::FLD_DIFF7_F => $rec1[26] ,
					self::FLD_DIFF7_T => $rec1[27] ,
					self::FLD_DIFF7_M => $rec1[28] ,

					dbProfile5C::FLD_NAME => $rec1[29] ,

					'FROM' => $rec1[30] ,
					'TO'   => $rec1[31] ,
					'MODE' => $rec1[32]
				);
			}
		}

		$ret['workInfo'] = $work;
		$ret['count'   ] = count($work);

		return $ret;
	}


	/********************
	TOPページの表示用出勤データの読み込み
	パラメータ：-
	戻り値　　：出勤リスト
	********************/
	function readForTop2() {

		$db = $this->handle;

		$dow = date('w');

		$dowList = array(
			self::TABLE_NAME . '.' . self::FLD_SUN_F ,self::TABLE_NAME . '.' . self::FLD_SUN_T ,self::TABLE_NAME . '.' . self::FLD_SUN_M ,
			self::TABLE_NAME . '.' . self::FLD_MON_F ,self::TABLE_NAME . '.' . self::FLD_MON_T ,self::TABLE_NAME . '.' . self::FLD_MON_M ,
			self::TABLE_NAME . '.' . self::FLD_TUE_F ,self::TABLE_NAME . '.' . self::FLD_TUE_T ,self::TABLE_NAME . '.' . self::FLD_TUE_M ,
			self::TABLE_NAME . '.' . self::FLD_WED_F ,self::TABLE_NAME . '.' . self::FLD_WED_T ,self::TABLE_NAME . '.' . self::FLD_WED_M ,
			self::TABLE_NAME . '.' . self::FLD_THU_F ,self::TABLE_NAME . '.' . self::FLD_THU_T ,self::TABLE_NAME . '.' . self::FLD_THU_M ,
			self::TABLE_NAME . '.' . self::FLD_FRI_F ,self::TABLE_NAME . '.' . self::FLD_FRI_T ,self::TABLE_NAME . '.' . self::FLD_FRI_M ,
			self::TABLE_NAME . '.' . self::FLD_SAT_F ,self::TABLE_NAME . '.' . self::FLD_SAT_T ,self::TABLE_NAME . '.' . self::FLD_SAT_M ,

			self::TABLE_NAME . '.' . self::FLD_SUN_F ,self::TABLE_NAME . '.' . self::FLD_SUN_T ,self::TABLE_NAME . '.' . self::FLD_SUN_M ,
			self::TABLE_NAME . '.' . self::FLD_MON_F ,self::TABLE_NAME . '.' . self::FLD_MON_T ,self::TABLE_NAME . '.' . self::FLD_MON_M ,
			self::TABLE_NAME . '.' . self::FLD_TUE_F ,self::TABLE_NAME . '.' . self::FLD_TUE_T ,self::TABLE_NAME . '.' . self::FLD_TUE_M ,
			self::TABLE_NAME . '.' . self::FLD_WED_F ,self::TABLE_NAME . '.' . self::FLD_WED_T ,self::TABLE_NAME . '.' . self::FLD_WED_M ,
			self::TABLE_NAME . '.' . self::FLD_THU_F ,self::TABLE_NAME . '.' . self::FLD_THU_T ,self::TABLE_NAME . '.' . self::FLD_THU_M ,
			self::TABLE_NAME . '.' . self::FLD_FRI_F ,self::TABLE_NAME . '.' . self::FLD_FRI_T ,self::TABLE_NAME . '.' . self::FLD_FRI_M ,
			self::TABLE_NAME . '.' . self::FLD_SAT_F ,self::TABLE_NAME . '.' . self::FLD_SAT_T ,self::TABLE_NAME . '.' . self::FLD_SAT_M
		);

		$fldArr = array(
			self::TABLE_NAME . '.' . self::FLD_DIR ,

			self::TABLE_NAME . '.' . self::FLD_DIFF1_D ,self::TABLE_NAME . '.' . self::FLD_DIFF1_F ,self::TABLE_NAME . '.' . self::FLD_DIFF1_T ,self::TABLE_NAME . '.' . self::FLD_DIFF1_M ,
			self::TABLE_NAME . '.' . self::FLD_DIFF2_D ,self::TABLE_NAME . '.' . self::FLD_DIFF2_F ,self::TABLE_NAME . '.' . self::FLD_DIFF2_T ,self::TABLE_NAME . '.' . self::FLD_DIFF2_M ,
			self::TABLE_NAME . '.' . self::FLD_DIFF3_D ,self::TABLE_NAME . '.' . self::FLD_DIFF3_F ,self::TABLE_NAME . '.' . self::FLD_DIFF3_T ,self::TABLE_NAME . '.' . self::FLD_DIFF3_M ,
			self::TABLE_NAME . '.' . self::FLD_DIFF4_D ,self::TABLE_NAME . '.' . self::FLD_DIFF4_F ,self::TABLE_NAME . '.' . self::FLD_DIFF4_T ,self::TABLE_NAME . '.' . self::FLD_DIFF4_M ,
			self::TABLE_NAME . '.' . self::FLD_DIFF5_D ,self::TABLE_NAME . '.' . self::FLD_DIFF5_F ,self::TABLE_NAME . '.' . self::FLD_DIFF5_T ,self::TABLE_NAME . '.' . self::FLD_DIFF5_M ,
			self::TABLE_NAME . '.' . self::FLD_DIFF6_D ,self::TABLE_NAME . '.' . self::FLD_DIFF6_F ,self::TABLE_NAME . '.' . self::FLD_DIFF6_T ,self::TABLE_NAME . '.' . self::FLD_DIFF6_M ,
			self::TABLE_NAME . '.' . self::FLD_DIFF7_D ,self::TABLE_NAME . '.' . self::FLD_DIFF7_F ,self::TABLE_NAME . '.' . self::FLD_DIFF7_T ,self::TABLE_NAME . '.' . self::FLD_DIFF7_M ,

			dbProfile5C::TABLE_NAME . '.' . dbProfile5C::FLD_NAME
		);

		$dowDest = array();

		$dowMax = $dow + 7;
		for($dowIdx=$dow ;$dowIdx<$dowMax ;$dowIdx++) {
			$dowListOffset = $dowIdx * 3;
			$fldArr[] = $dowList[$dowListOffset    ];
			$fldArr[] = $dowList[$dowListOffset + 1];
			$fldArr[] = $dowList[$dowListOffset + 2];

			$dowDest[] = $dowList[$dowListOffset    ];
			$dowDest[] = $dowList[$dowListOffset + 1];
			$dowDest[] = $dowList[$dowListOffset + 2];
		}
		$fldList = funcs5C::setFldArrToList($fldArr);

		$where =
			self::TABLE_NAME . '.' . self::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .

			dbProfile5C::TABLE_NAME . '.' . dbProfile5C::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .
			dbProfile5C::TABLE_NAME . '.' . dbProfile5C::FLD_DISP      . '=' . $db->setQuote(dbProfile5C::DISP_ON) . ' and ' .
			dbProfile5C::TABLE_NAME . '.' . dbProfile5C::FLD_DIR       . '=' . self::TABLE_NAME . '.' . self::FLD_DIR
		;

		$order = dbProfile5C::TABLE_NAME . '.' . dbProfile5C::FLD_DISP_SEQ;

		$db = $this->handle;
		$workList = $db->selectRec(self::TABLE_NAME . ',' . dbProfile5C::TABLE_NAME ,$fldList ,$where ,$order);

		$fldListMax = count($fldArr);
		if($workList['rows'] <= 0) {
			/*** 0件のとき ***/
			$work[0] = array(
				self::FLD_DIR   => '' ,

				self::FLD_DIFF1_D => '' ,self::FLD_DIFF1_F => '' ,self::FLD_DIFF1_T => '' ,self::FLD_DIFF1_M => '' ,
				self::FLD_DIFF2_D => '' ,self::FLD_DIFF2_F => '' ,self::FLD_DIFF2_T => '' ,self::FLD_DIFF2_M => '' ,
				self::FLD_DIFF3_D => '' ,self::FLD_DIFF3_F => '' ,self::FLD_DIFF3_T => '' ,self::FLD_DIFF3_M => '' ,
				self::FLD_DIFF4_D => '' ,self::FLD_DIFF4_F => '' ,self::FLD_DIFF4_T => '' ,self::FLD_DIFF4_M => '' ,
				self::FLD_DIFF5_D => '' ,self::FLD_DIFF5_F => '' ,self::FLD_DIFF5_T => '' ,self::FLD_DIFF5_M => '' ,
				self::FLD_DIFF6_D => '' ,self::FLD_DIFF6_F => '' ,self::FLD_DIFF6_T => '' ,self::FLD_DIFF6_M => '' ,
				self::FLD_DIFF7_D => '' ,self::FLD_DIFF7_F => '' ,self::FLD_DIFF7_T => '' ,self::FLD_DIFF7_M => '' ,

				dbProfile5C::FLD_NAME => '' ,

				$dowDest[ 0] => '' ,$dowDest[ 1] => '' ,$dowDest[ 2] => '' ,
				$dowDest[ 3] => '' ,$dowDest[ 4] => '' ,$dowDest[ 5] => '' ,
				$dowDest[ 6] => '' ,$dowDest[ 7] => '' ,$dowDest[ 8] => '' ,
				$dowDest[ 9] => '' ,$dowDest[10] => '' ,$dowDest[11] => '' ,
				$dowDest[12] => '' ,$dowDest[13] => '' ,$dowDest[14] => '' ,
				$dowDest[15] => '' ,$dowDest[16] => '' ,$dowDest[17] => '' ,
				$dowDest[18] => '' ,$dowDest[19] => '' ,$dowDest[20] => '' ,
				$dowDest[21] => '' ,$dowDest[22] => '' ,$dowDest[23] => ''
			);
		} else {
			/*** 1件以上あったとき ***/
			$recList = $workList['fetch'];
			$recIdxMax = $workList['rows'];
			for($recIdx=0 ;$recIdx<$recIdxMax ;$recIdx++) {
				$rec1 = $recList[$recIdx];

				$work[$recIdx] = array(
					self::FLD_DIR   => $rec1[ 0] ,

					self::FLD_DIFF1_D => $rec1[ 1] ,
					self::FLD_DIFF1_F => $rec1[ 2] ,
					self::FLD_DIFF1_T => $rec1[ 3] ,
					self::FLD_DIFF1_M => $rec1[ 4] ,

					self::FLD_DIFF2_D => $rec1[ 5] ,
					self::FLD_DIFF2_F => $rec1[ 6] ,
					self::FLD_DIFF2_T => $rec1[ 7] ,
					self::FLD_DIFF2_M => $rec1[ 8] ,

					self::FLD_DIFF3_D => $rec1[ 9] ,
					self::FLD_DIFF3_F => $rec1[10] ,
					self::FLD_DIFF3_T => $rec1[11] ,
					self::FLD_DIFF3_M => $rec1[12] ,

					self::FLD_DIFF4_D => $rec1[13] ,
					self::FLD_DIFF4_F => $rec1[14] ,
					self::FLD_DIFF4_T => $rec1[15] ,
					self::FLD_DIFF4_M => $rec1[16] ,

					self::FLD_DIFF5_D => $rec1[17] ,
					self::FLD_DIFF5_F => $rec1[18] ,
					self::FLD_DIFF5_T => $rec1[19] ,
					self::FLD_DIFF5_M => $rec1[20] ,

					self::FLD_DIFF6_D => $rec1[21] ,
					self::FLD_DIFF6_F => $rec1[22] ,
					self::FLD_DIFF6_T => $rec1[23] ,
					self::FLD_DIFF6_M => $rec1[24] ,

					self::FLD_DIFF7_D => $rec1[25] ,
					self::FLD_DIFF7_F => $rec1[26] ,
					self::FLD_DIFF7_T => $rec1[27] ,
					self::FLD_DIFF7_M => $rec1[28] ,


					dbProfile5C::FLD_NAME => $rec1[29] ,


					$dowDest[ 0] => $rec1[30] ,
					$dowDest[ 1] => $rec1[31] ,
					$dowDest[ 2] => $rec1[32] ,

					$dowDest[ 3] => $rec1[33] ,
					$dowDest[ 4] => $rec1[34] ,
					$dowDest[ 5] => $rec1[35] ,

					$dowDest[ 6] => $rec1[36] ,
					$dowDest[ 7] => $rec1[37] ,
					$dowDest[ 8] => $rec1[38] ,

					$dowDest[ 9] => $rec1[39] ,
					$dowDest[10] => $rec1[40] ,
					$dowDest[11] => $rec1[41] ,

					$dowDest[12] => $rec1[42] ,
					$dowDest[13] => $rec1[43] ,
					$dowDest[14] => $rec1[44] ,

					$dowDest[15] => $rec1[45] ,
					$dowDest[16] => $rec1[46] ,
					$dowDest[17] => $rec1[47] ,

					$dowDest[18] => $rec1[48] ,
					$dowDest[19] => $rec1[49] ,
					$dowDest[20] => $rec1[50]
				);
			}
		}

		$ret['workInfo'] = $work;
		$ret['count'   ] = count($work);
		$ret['dowDest' ] = $dowDest;

		return $ret;
	}


	/********************
	出勤データの読み込み
	パラメータ：-
	戻り値　　：出勤リスト
	********************/
	function readForWeek($dir='') {

		$db = $this->handle;

		$fldArr = array(
			self::TABLE_NAME . '.' . self::FLD_DIR ,

			self::TABLE_NAME . '.' . self::FLD_SUN_F ,self::TABLE_NAME . '.' . self::FLD_SUN_T ,self::TABLE_NAME . '.' . self::FLD_SUN_M ,
			self::TABLE_NAME . '.' . self::FLD_MON_F ,self::TABLE_NAME . '.' . self::FLD_MON_T ,self::TABLE_NAME . '.' . self::FLD_MON_M ,
			self::TABLE_NAME . '.' . self::FLD_TUE_F ,self::TABLE_NAME . '.' . self::FLD_TUE_T ,self::TABLE_NAME . '.' . self::FLD_TUE_M ,
			self::TABLE_NAME . '.' . self::FLD_WED_F ,self::TABLE_NAME . '.' . self::FLD_WED_T ,self::TABLE_NAME . '.' . self::FLD_WED_M ,
			self::TABLE_NAME . '.' . self::FLD_THU_F ,self::TABLE_NAME . '.' . self::FLD_THU_T ,self::TABLE_NAME . '.' . self::FLD_THU_M ,
			self::TABLE_NAME . '.' . self::FLD_FRI_F ,self::TABLE_NAME . '.' . self::FLD_FRI_T ,self::TABLE_NAME . '.' . self::FLD_FRI_M ,
			self::TABLE_NAME . '.' . self::FLD_SAT_F ,self::TABLE_NAME . '.' . self::FLD_SAT_T ,self::TABLE_NAME . '.' . self::FLD_SAT_M ,

			self::TABLE_NAME . '.' . self::FLD_DIFF1_D ,self::TABLE_NAME . '.' . self::FLD_DIFF1_F ,self::TABLE_NAME . '.' . self::FLD_DIFF1_T ,self::TABLE_NAME . '.' . self::FLD_DIFF1_M ,
			self::TABLE_NAME . '.' . self::FLD_DIFF2_D ,self::TABLE_NAME . '.' . self::FLD_DIFF2_F ,self::TABLE_NAME . '.' . self::FLD_DIFF2_T ,self::TABLE_NAME . '.' . self::FLD_DIFF2_M ,
			self::TABLE_NAME . '.' . self::FLD_DIFF3_D ,self::TABLE_NAME . '.' . self::FLD_DIFF3_F ,self::TABLE_NAME . '.' . self::FLD_DIFF3_T ,self::TABLE_NAME . '.' . self::FLD_DIFF3_M ,
			self::TABLE_NAME . '.' . self::FLD_DIFF4_D ,self::TABLE_NAME . '.' . self::FLD_DIFF4_F ,self::TABLE_NAME . '.' . self::FLD_DIFF4_T ,self::TABLE_NAME . '.' . self::FLD_DIFF4_M ,
			self::TABLE_NAME . '.' . self::FLD_DIFF5_D ,self::TABLE_NAME . '.' . self::FLD_DIFF5_F ,self::TABLE_NAME . '.' . self::FLD_DIFF5_T ,self::TABLE_NAME . '.' . self::FLD_DIFF5_M ,
			self::TABLE_NAME . '.' . self::FLD_DIFF6_D ,self::TABLE_NAME . '.' . self::FLD_DIFF6_F ,self::TABLE_NAME . '.' . self::FLD_DIFF6_T ,self::TABLE_NAME . '.' . self::FLD_DIFF6_M ,
			self::TABLE_NAME . '.' . self::FLD_DIFF7_D ,self::TABLE_NAME . '.' . self::FLD_DIFF7_F ,self::TABLE_NAME . '.' . self::FLD_DIFF7_T ,self::TABLE_NAME . '.' . self::FLD_DIFF7_M ,

			dbProfile5C::TABLE_NAME . '.' . dbProfile5C::FLD_NAME
		);

		$fldList = funcs5C::setFldArrToList($fldArr);

		$where =
			self::TABLE_NAME . '.' . self::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .

			dbProfile5C::TABLE_NAME . '.' . dbProfile5C::FLD_BRANCH_NO . '=' . $this->branchNo . ' and ' .
			dbProfile5C::TABLE_NAME . '.' . dbProfile5C::FLD_DISP      . '=' . $db->setQuote(dbProfile5C::DISP_ON) . ' and ' .
			dbProfile5C::TABLE_NAME . '.' . dbProfile5C::FLD_DIR       . '=' . self::TABLE_NAME . '.' . self::FLD_DIR
		;

		if(strlen($dir) >= 1) {
			$where = $where . ' and ' . self::TABLE_NAME . '.' . self::FLD_DIR . '=' . $db->setQuote($dir);
		}


		$order = dbProfile5C::TABLE_NAME . '.' . dbProfile5C::FLD_DISP_SEQ;

		$db = $this->handle;
		$workList = $db->selectRec(self::TABLE_NAME . ',' . dbProfile5C::TABLE_NAME ,$fldList ,$where ,$order);

		$fldListMax = count($fldArr);
		if($workList['rows'] <= 0) {
			/*** 0件のとき ***/
			$work[0] = array(
				self::FLD_DIR => '' ,

				self::FLD_SUN_F => '' ,self::FLD_SUN_T => '' ,self::FLD_SUN_M => '' ,
				self::FLD_MON_F => '' ,self::FLD_MON_T => '' ,self::FLD_MON_M => '' ,
				self::FLD_TUE_F => '' ,self::FLD_TUE_T => '' ,self::FLD_TUE_M => '' ,
				self::FLD_WED_F => '' ,self::FLD_WED_T => '' ,self::FLD_WED_M => '' ,
				self::FLD_THU_F => '' ,self::FLD_THU_T => '' ,self::FLD_THU_M => '' ,
				self::FLD_FRI_F => '' ,self::FLD_FRI_T => '' ,self::FLD_FRI_M => '' ,
				self::FLD_SAT_F => '' ,self::FLD_SAT_T => '' ,self::FLD_SAT_M => '' ,

				self::FLD_DIFF1_D => '' ,self::FLD_DIFF1_F => '' ,
				self::FLD_DIFF1_T => '' ,self::FLD_DIFF1_M => '' ,

				self::FLD_DIFF2_D => '' ,self::FLD_DIFF2_F => '' ,
				self::FLD_DIFF2_T => '' ,self::FLD_DIFF2_M => '' ,

				self::FLD_DIFF3_D => '' ,self::FLD_DIFF3_F => '' ,
				self::FLD_DIFF3_T => '' ,self::FLD_DIFF3_M => '' ,

				self::FLD_DIFF4_D => '' ,self::FLD_DIFF4_F => '' ,
				self::FLD_DIFF4_T => '' ,self::FLD_DIFF4_M => '' ,

				self::FLD_DIFF5_D => '' ,self::FLD_DIFF5_F => '' ,
				self::FLD_DIFF5_T => '' ,self::FLD_DIFF5_M => '' ,

				self::FLD_DIFF6_D => '' ,self::FLD_DIFF6_F => '' ,
				self::FLD_DIFF6_T => '' ,self::FLD_DIFF6_M => '' ,

				self::FLD_DIFF7_D => '' ,self::FLD_DIFF7_F => '' ,
				self::FLD_DIFF7_T => '' ,self::FLD_DIFF7_M => '' ,

				dbProfile5C::FLD_NAME => ''
			);
		} else {
			/*** 1件以上あったとき ***/
			$recList = $workList['fetch'];
			$recIdxMax = $workList['rows'];
			for($recIdx=0 ;$recIdx<$recIdxMax ;$recIdx++) {
				$rec1 = $recList[$recIdx];

				$work[$recIdx] = array(
					self::FLD_DIR   => $rec1[ 0] ,

					self::FLD_SUN_F => $rec1[ 1] ,
					self::FLD_SUN_T => $rec1[ 2] ,
					self::FLD_SUN_M => $rec1[ 3] ,

					self::FLD_MON_F => $rec1[ 4] ,
					self::FLD_MON_T => $rec1[ 5] ,
					self::FLD_MON_M => $rec1[ 6] ,

					self::FLD_TUE_F => $rec1[ 7] ,
					self::FLD_TUE_T => $rec1[ 8] ,
					self::FLD_TUE_M => $rec1[ 9] ,

					self::FLD_WED_F => $rec1[10] ,
					self::FLD_WED_T => $rec1[11] ,
					self::FLD_WED_M => $rec1[12] ,

					self::FLD_THU_F => $rec1[13] ,
					self::FLD_THU_T => $rec1[14] ,
					self::FLD_THU_M => $rec1[15] ,

					self::FLD_FRI_F => $rec1[16] ,
					self::FLD_FRI_T => $rec1[17] ,
					self::FLD_FRI_M => $rec1[18] ,

					self::FLD_SAT_F => $rec1[19] ,
					self::FLD_SAT_T => $rec1[20] ,
					self::FLD_SAT_M => $rec1[21] ,


					self::FLD_DIFF1_D => $rec1[22] ,
					self::FLD_DIFF1_F => $rec1[23] ,
					self::FLD_DIFF1_T => $rec1[24] ,
					self::FLD_DIFF1_M => $rec1[25] ,

					self::FLD_DIFF2_D => $rec1[26] ,
					self::FLD_DIFF2_F => $rec1[27] ,
					self::FLD_DIFF2_T => $rec1[28] ,
					self::FLD_DIFF2_M => $rec1[29] ,

					self::FLD_DIFF3_D => $rec1[30] ,
					self::FLD_DIFF3_F => $rec1[31] ,
					self::FLD_DIFF3_T => $rec1[32] ,
					self::FLD_DIFF3_M => $rec1[33] ,

					self::FLD_DIFF4_D => $rec1[34] ,
					self::FLD_DIFF4_F => $rec1[35] ,
					self::FLD_DIFF4_T => $rec1[36] ,
					self::FLD_DIFF4_M => $rec1[37] ,

					self::FLD_DIFF5_D => $rec1[38] ,
					self::FLD_DIFF5_F => $rec1[39] ,
					self::FLD_DIFF5_T => $rec1[40] ,
					self::FLD_DIFF5_M => $rec1[41] ,

					self::FLD_DIFF6_D => $rec1[42] ,
					self::FLD_DIFF6_F => $rec1[43] ,
					self::FLD_DIFF6_T => $rec1[44] ,
					self::FLD_DIFF6_M => $rec1[45] ,

					self::FLD_DIFF7_D => $rec1[46] ,
					self::FLD_DIFF7_F => $rec1[47] ,
					self::FLD_DIFF7_T => $rec1[48] ,
					self::FLD_DIFF7_M => $rec1[49] ,

					dbProfile5C::FLD_NAME => $rec1[50]
				);
			}
		}

		$ret['workInfo'] = $work;
		$ret['count'   ] = count($work);

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
	}



/*********************************************************************************************************************************************/
	/********************
	カラのデータ
	パラメータ：
	戻り値　　：プロファイルデータ
	********************/
	function setEmpty() {

		$ret = array(
			self::FLD_DIR => '' ,

			self::FLD_SUN_F => '' ,
			self::FLD_MON_F => '' ,
			self::FLD_TUE_F => '' ,
			self::FLD_WED_F => '' ,
			self::FLD_THU_F => '' ,
			self::FLD_FRI_F => '' ,
			self::FLD_SAT_F => '' ,

			self::FLD_SUN_T => '' ,
			self::FLD_MON_T => '' ,
			self::FLD_TUE_T => '' ,
			self::FLD_WED_T => '' ,
			self::FLD_THU_T => '' ,
			self::FLD_FRI_T => '' ,
			self::FLD_SAT_T => '' ,

			self::FLD_SUN_MO => '' ,
			self::FLD_MON_MO => '' ,
			self::FLD_TUE_MO => '' ,
			self::FLD_WED_MO => '' ,
			self::FLD_THU_MO => '' ,
			self::FLD_FRI_MO => '' ,
			self::FLD_SAT_MO => '' ,

			self::FLD_ADD_DT => '' ,
			self::FLD_UPD_DT
		);

		return $ret;
	}


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


		$ret[PROF_WORK_DEF_SUN_F_S] = '';	/* 日曜 */
		$ret[PROF_WORK_DEF_MON_F_S] = '';	/* 月曜 */
		$ret[PROF_WORK_DEF_TUE_F_S] = '';	/* 火曜 */
		$ret[PROF_WORK_DEF_WED_F_S] = '';	/* 水曜 */
		$ret[PROF_WORK_DEF_THU_F_S] = '';	/* 木曜 */
		$ret[PROF_WORK_DEF_FRI_F_S] = '';	/* 金曜 */
		$ret[PROF_WORK_DEF_SAT_F_S] = '';	/* 土曜 */

		$ret[PROF_WORK_DEF_SUN_T_S] = '';	/* 日曜 */
		$ret[PROF_WORK_DEF_MON_T_S] = '';	/* 月曜 */
		$ret[PROF_WORK_DEF_TUE_T_S] = '';	/* 火曜 */
		$ret[PROF_WORK_DEF_WED_T_S] = '';	/* 水曜 */
		$ret[PROF_WORK_DEF_THU_T_S] = '';	/* 木曜 */
		$ret[PROF_WORK_DEF_FRI_T_S] = '';	/* 金曜 */
		$ret[PROF_WORK_DEF_SAT_T_S] = '';	/* 土曜 */

		$ret[PROF_WORK_DEF_SUN_MO_S] = PROF_WORK_MODE_TO;	/* 日曜 */
		$ret[PROF_WORK_DEF_MON_MO_S] = PROF_WORK_MODE_TO;	/* 月曜 */
		$ret[PROF_WORK_DEF_TUE_MO_S] = PROF_WORK_MODE_TO;	/* 火曜 */
		$ret[PROF_WORK_DEF_WED_MO_S] = PROF_WORK_MODE_TO;	/* 水曜 */
		$ret[PROF_WORK_DEF_THU_MO_S] = PROF_WORK_MODE_TO;	/* 木曜 */
		$ret[PROF_WORK_DEF_FRI_MO_S] = PROF_WORK_MODE_TO;	/* 金曜 */
		$ret[PROF_WORK_DEF_SAT_MO_S] = PROF_WORK_MODE_TO;	/* 土曜 */



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




	/********************
	プロファイルリストの読み込み
	パラメータ：店No
	戻り値　　：プロファイルインデックスリスト
	********************/
	function readList($branchNo) {

		/***** リストファイルの読み込み *****/
		$fileName = $this->getFileName($branchNo);
		$profLine = funcs5C::readFile($fileName['path'] . '.' . $fileName['ext']);

		$json = new Services_JSON();
		$jsonData = $json->decode($profLine);

		$prof = array();
		foreach($jsonData as $read1) {
			$tempValue = (array)$read1;
			$seqStr = $tempValue[PROF_DISP_SEQ_S];		/* 表示順 */
			$prof[$seqStr][PROF_DIR       ] = $tempValue[PROF_DIR       ];	/* プロファイルID */
			$prof[$seqStr][PROF_DISP_ONOFF_S] = $tempValue[PROF_DISP_ONOFF_S];	/* 表示/非表示 */

			$prof[$seqStr][PROF_ADD_DT    ] = $tempValue[PROF_ADD_DT_S];	/* レコード登録日時 */
			$prof[$seqStr][PROF_UPD_DT    ] = $tempValue[PROF_UPD_DT_S];	/* レコード更新日時 */
		}

		$ret['profList'] = $prof;
		$ret['count'   ] = count($prof);

		return $ret;
	}


	/********************
	プロファイルリストの更新(表示/非表示、表示順の更新)
	パラメータ：店No
	　　　　　　識別子
	　　　　　　表示順
	　　　　　　表示/非表示
	戻り値　　：
	********************/
	function updList($branchNo ,$updList) {

		$updDT = dateTime5C::getCurrDT();

		$currProf = $this->readList($branchNo);
		$currList = $currProf['profList'];
		$currMax  = $currProf['count'];
/*		$newList  = $currList;*/

		$updONOFF = false;
		$updIdxMax = count($updList);
		for($updIdx=1 ;$updIdx<=$updIdxMax ;$updIdx++) {
			for($currSeq=1 ;$currSeq<=$currMax ;$currSeq++) {
				if(strcmp($currList[$currSeq][PROF_DIR_S] ,$updList[$updIdx][PROF_DIR_S]) == 0) {
					/*** 表示/非表示が現状の値と異なっていれば更新 ***/
					if(strcmp($currList[$currSeq][PROF_DISP_ONOFF_S] ,$updList[$updIdx][PROF_DISP_ONOFF_S]) != 0) {
						$currList[$currSeq][PROF_DISP_ONOFF_S] = $updList[$updIdx][PROF_DISP_ONOFF_S];
						$currList[$currSeq][PROF_UPD_DT    ] = $updDT;
						$updONOFF = true;
					}
					break;
				}
			}
		}

		$updSeq = false;
		if(isset($updList[1][PROF_DIR_S])) {
			for($updIdx=1 ;$updIdx<=$updIdxMax ;$updIdx++) {
				if(strcmp($currList[$updIdx][PROF_DIR_S] ,$updList[$updIdx][PROF_DIR_S]) != 0) {
					for($currSeq=1 ;$currSeq<=$currMax ;$currSeq++) {
						if(strcmp($currList[$currSeq][PROF_DIR_S] ,$updList[$updIdx][PROF_DIR_S]) == 0) {
							$newList[$updIdx] = $currList[$currSeq];
							break;
						}
					}
					$updSeq = true;
				} else {
					$newList[$updIdx] = $currList[$updIdx];
				}
			}
		}

		if($updONOFF || $updSeq) {
			$this->writeList($branchNo ,$newList);
		}
	}


	/********************
	プロファイルリストの更新(ディレクトリの更新)
	パラメータ：店No
	　　　　　　旧識別子
	　　　　　　新識別子
	戻り値　　：
	********************/
	function updDirName($branchNo ,$oldDir ,$newDir) {

		$updDT = dateTime5C::getCurrDT();

		$currProf = $this->readList($branchNo);
		$profList = $currProf['profList'];
		$profMax  = $currProf['count'];

		$upd = false;

		for($dirSeq=1 ;$dirSeq<=$profMax ;$dirSeq++) {
					/* print $currProf['profList'][$dirSeq][PROF_DIR_S]; */
			if(strcmp($profList[$dirSeq][PROF_DIR_S] ,$oldDir) == 0) {
						/* print 'hit'; */
				$profList[$dirSeq][PROF_DIR_S] = $newDir;
				$upd = true;
				break;
			}
		}

		if($upd) {
					/* print 'update'; */
			$this->writeList($branchNo ,$profList);
		}
	}


	/********************
	プロファイルリストの出力
	パラメータ：店No
	　　　　　　プロファイルリスト
	戻り値　　：
	********************/
	function writeList($branchNo ,$profList) {

		$idxMax = count($profList);
		for($idx=1 ;$idx<=$idxMax ;$idx++) {
			$newList[$idx] = $profList[$idx];
			$newList[$idx][PROF_DISP_SEQ_S] = $idx;
		}

		$json = new Services_JSON();
		$jsonStr = $json->encode($newList);

		/*** ニュースの再出力 ***/
		$fileName = $this->getFileName($branchNo);

		$fp = fopen($fileName['path'] . '.' . $fileName['ext'] ,'w');
		fwrite($fp ,$jsonStr);
		fclose($fp);
	}


	/********************
	プロファイルの有無
	パラメータ：店No
	　　　　　　プロファイル
	戻り値　　：'ALREADY' 有り
	　　　　　　'NOTYET'  ナシ
	********************/
	function existProfileDir($branchNo ,$dir) {

		$ret = 'NOTYET';

		/***** リストファイルの読み込み *****/
		$fileName = $this->getFileName($branchNo);
		$profLine = funcs5C::readFile($fileName['path'] . '.' . $fileName['ext']);

		$json = new Services_JSON();
		$jsonData = $json->decode($profLine);

		foreach($jsonData as $read1) {
			$tempValue = (array)$read1;
			if(strcmp($tempValue[PROF_DIR_S] ,$dir) == 0) {
				$ret = 'ALREADY';
				break;
			}
		}

		return $ret;
	}






/********************************************************/




	/********************
	プロファイルデータの読み込み(1件)
	パラメータ：店No
	　　　　　　ディレクトリ
	戻り値　　：プロファイルリスト
	********************/
	function readProf1($branchNo ,$profDir) {

		$fileName = $this->getFileName($branchNo);

		/***** リストファイルの読み込み *****/
		$profIdx = $this->readList($branchNo);
		$profIdxList = $profIdx['profList'];

		/***** データ本体の読み込み *****/
		$prof = array();
		$idxMax = $profIdx['count'];

		for($idx=1 ;$idx<=$idxMax ;$idx++) {
			$dirStr = $profIdxList[$idx][PROF_DIR_S];

			if(strcmp($dirStr ,$profDir) == 0) {
				$profLine = funcs5C::readFile($fileName['path'] . '-' . $dirStr . 'Prof.' . $fileName['ext']);
				$dataLine = $this->readProfData($profLine);

				$prof = $dataLine;
				$prof[PROF_DIR       ] = $profIdxList[$idx][PROF_DIR       ];
				$prof[PROF_DISP_ONOFF_S] = $profIdxList[$idx][PROF_DISP_ONOFF_S];

				break;
			}
		}

		return $prof;
	}





	/********************
	写真表示の読み込み
	パラメータ：店No
	　　　　　　ディレクトリ
	　　　　　　プロファイルリスト
	戻り値　　：写真表示情報
	********************/
	function getShowPhoto($branchNo ,$profData) {

		$ret['SHOWMODE']['L'] = $profData[PROF_FLD_PHOTO_SHOW];
		$ret['SHOWMODE']['M'] = $profData[PROF_FLD_PHOTO_SHOW];
		$ret['SHOWMODE']['S'] = $profData[PROF_FLD_PHOTO_SHOW];

				/* print 'name ' . $profData[PROF_NAME_S] . ' mode ' . $profData[PROF_FLD_PHOTO_SHOW] . $ret['SHOWMODE']['S']; */
		/***** 表示可なら写真ファイルの有無の確認 *****/
		if(strcmp($profData[PROF_FLD_PHOTO_SHOW] ,PROF_PHOTO_SHOW_OK) == 0) {
			$basePath = files4C::getFileName('PHOTO_BASEDIR'  ,$branchNo);
			$baseDir = $basePath['PC'] . '/' . $profData[PROF_DIR_S] . '/';

			$ret['1'] = $this->setUsePhoto($baseDir ,$profData[PROF_DIR_S] ,'1' ,$profData[PROF_PHOTOUSE_1_S] ,$profData[PROF_PHOTOEXT_1_S]);
			$ret['2'] = $this->setUsePhoto($baseDir ,$profData[PROF_DIR_S] ,'2' ,$profData[PROF_PHOTOUSE_2_S] ,$profData[PROF_PHOTOEXT_2_S]);
			$ret['3'] = $this->setUsePhoto($baseDir ,$profData[PROF_DIR_S] ,'3' ,$profData[PROF_PHOTOUSE_3_S] ,$profData[PROF_PHOTOEXT_3_S]);
			$ret['4'] = $this->setUsePhoto($baseDir ,$profData[PROF_DIR_S] ,'4' ,$profData[PROF_PHOTOUSE_4_S] ,$profData[PROF_PHOTOEXT_4_S]);
			$ret['5'] = $this->setUsePhoto($baseDir ,$profData[PROF_DIR_S] ,'5' ,$profData[PROF_PHOTOUSE_5_S] ,$profData[PROF_PHOTOEXT_5_S]);

			$ret['S'] = $this->setUsePhoto($baseDir ,$profData[PROF_DIR_S] ,'-s' ,$profData[PROF_PHOTOUSE_S_S] ,$profData[PROF_PHOTOEXT_S_S]);
			$ret['M'] = $this->setUsePhoto($baseDir ,$profData[PROF_DIR_S] ,'-m' ,$profData[PROF_PHOTOUSE_M_S] ,$profData[PROF_PHOTOEXT_M_S]);


			$ret['SHOW'] = array();

			$showNum = 0;
			if(strcmp($ret['1'] ,PROF_PHOTO_USE) == 0) {
				$showNum++;
				$ret['SHOW'][$showNum] = '1';
			}
			if(strcmp($ret['2'] ,PROF_PHOTO_USE) == 0) {
				$showNum++;
				$ret['SHOW'][$showNum] = '2';
			}
			if(strcmp($ret['3'] ,PROF_PHOTO_USE) == 0) {
				$showNum++;
				$ret['SHOW'][$showNum] = '3';
			}
			if(strcmp($ret['4'] ,PROF_PHOTO_USE) == 0) {
				$showNum++;
				$ret['SHOW'][$showNum] = '4';
			}
			if(strcmp($ret['5'] ,PROF_PHOTO_USE) == 0) {
				$showNum++;
				$ret['SHOW'][$showNum] = '5';
			}

			$ret['NUM'] = $showNum;

			/***** 表示する写真がなければ「写真ナシ」状態 *****/
			if($showNum <= 0) {
				$ret['SHOWMODE']['L'] = PROF_PHOTO_SHOW_NOT;
			}

			if(strcmp($ret['M'] ,PROF_PHOTO_NOT) == 0) {
				$ret['SHOWMODE']['M'] = PROF_PHOTO_SHOW_NOT;
			}

			if(strcmp($ret['S'] ,PROF_PHOTO_NOT) == 0) {
				$ret['SHOWMODE']['S'] = PROF_PHOTO_SHOW_NOT;
			}
		}


		return $ret;
	}


	function setUsePhoto($baseDir ,$profDir ,$photoID ,$use ,$ext) {

		$ret = PROF_PHOTO_NOT;

		if(strcmp($use ,PROF_PHOTO_USE) == 0) {
			if(strlen($ext) >= 1) {
				$fileFullName = $baseDir . $profDir . $photoID . '.' . $ext;
				if(is_file($fileFullName)) {
					$ret = PROF_PHOTO_USE;
				}
			}
		}

		return $ret;
	}



/********************************************************/
	/********************
	出勤データの読み込み
	パラメータ：店No
	戻り値　　：プロファイルリスト
	********************/
	function readWork($branchNo ,$days) {

		$fileName = $this->getFileName($branchNo);

		/***** リストファイルの読み込み *****/
		$profIdx = $this->readList($branchNo);
		$profIdxList = $profIdx['profList'];

		/***** データ本体の読み込み *****/
		$prof = array();
		$idxMax = $profIdx['count'];
		for($idx=1 ;$idx<=$idxMax ;$idx++) {
			$dirStr = $profIdxList[$idx][PROF_DIR];

			$profLine = funcs5C::readFile($fileName['path'] . '-' . $dirStr . 'Work.' . $fileName['ext']);
			$workLine = $this->readWorkData($profLine ,$days);
			$prof[$idx]['times'] = $workLine;
			$prof[$idx][PROF_DIR_S] = $profIdxList[$idx][PROF_DIR_S];

			$profLine = funcs5C::readFile($fileName['path'] . '-' . $dirStr . 'Prof.' . $fileName['ext']);
			$dataLine = $this->readProfData($profLine);
			$prof[$idx][PROF_NAME_S] = $dataLine[PROF_NAME_S];
		}

		$ret['workInfo'] = $prof;
		$ret['count'   ] = count($prof);

		return $ret;
	}


	/********************
	表示可の出勤データの読み込み
	パラメータ：店No
	戻り値　　：ニュースリスト
	********************/
	function readShowableWork($branchNo ,$days) {

		$fileName = $this->getFileName($branchNo);

		/***** リストファイルの読み込み *****/
		$profIdx = $this->readList($branchNo);
		$profIdxList = $profIdx['profList'];

		/***** データ本体の読み込み *****/
		$validIdx = 1;
		$prof = array();
		$idxMax = $profIdx['count'];
		for($idx=1 ;$idx<=$idxMax ;$idx++) {
			if(strcmp($profIdxList[$idx][PROF_DISP_ONOFF_S] ,PROF_DISP_ON) == 0) {
				$dirStr = $profIdxList[$idx][PROF_DIR_S];

				$workFileName = $fileName['path'] . '-' . $dirStr . 'Work.' . $fileName['ext'];
				if(is_file($workFileName)) {
					$profLine = funcs5C::readFile($workFileName);
					$workLine = $this->readWorkData($profLine ,$days);
				} else {
					$workLine = $days;
				}
				$prof[$validIdx]['times'] = $workLine;
				$prof[$validIdx][PROF_DIR_S] = $profIdxList[$idx][PROF_DIR_S];

				$profLine = funcs5C::readFile($fileName['path'] . '-' . $dirStr . 'Prof.' . $fileName['ext']);
				$dataLine = $this->readProfData($profLine);
				$prof[$validIdx][PROF_NAME_S] = $dataLine[PROF_NAME_S];

				$validIdx++;
			}
		}

		$ret['workInfo'] = $prof;
		$ret['count'   ] = count($prof);

		return $ret;
	}


	function setTime() {

	}



	function readWorkData($workLine ,$days) {

		$ret = $days;
		$json = new Services_JSON();
		$jsonData = $json->decode($workLine);

		for($dayCnt=0 ;$dayCnt<=6 ;$dayCnt++) {
			$dayStr = $days[$dayCnt][PROF_WORK_DATE_S];

			foreach($jsonData as $read1) {
				$tempValue = (array)$read1;

				if(isset($tempValue[PROF_WORK_DATE_S])) {
					if(strcmp($dayStr ,$tempValue[PROF_WORK_DATE_S]) == 0) {
						if(isset($tempValue[PROF_WORK_FROM_S])) {
							$ret[$dayCnt][PROF_WORK_FROM_S] = $tempValue[PROF_WORK_FROM_S];
						}
						if(isset($tempValue[PROF_WORK_TO_S])) {
							$ret[$dayCnt][PROF_WORK_TO_S] = $tempValue[PROF_WORK_TO_S];
						}
						if(isset($tempValue[PROF_WORK_MODE_S])) {
							$ret[$dayCnt][PROF_WORK_MODE_S] = $tempValue[PROF_WORK_MODE_S];
						}
					}
				}
			}
		}

		return $ret;
	}


	/********************
	出勤データの読み込み
	パラメータ：店No
	　　　　　　プロファイル名
	戻り値　　：出勤リスト
	********************/
	function getWorkList($branchNo ,$dir ,$days) {

		$workLine = array();
		$fileName = $this->getFileName($branchNo);
		$workFileName = $fileName['path'] . '-' . $dir . 'Work.' . $fileName['ext'];

		if(is_file($workFileName)) {
			$profLine = funcs5C::readFile($workFileName);
			$workLine = $this->readWorkData($profLine ,$days);
		} else {
			$workLine = $days;	/* array(); */
		}

		return $workLine;
	}


/********************************************************/



	/********************
	プロファイルの追加
	パラメータ：店No
	　　　　　　ディレクトリ
	　　　　　　新人
	　　　　　　名前
	　　　　　　年齢
	　　　　　　身長
	　　　　　　スリーサイズ
	　　　　　　コメント
	　　　　　　誕生日
	　　　　　　星座
	　　　　　　血液型
	　　　　　　出勤変更分
	戻り値　　：
	********************/
	function addORG($branchNo ,$dir ,$newFace ,$name ,$age ,$height ,$size ,$comment ,$birdhDate ,$zodiac ,$bloodType ,$workM) {

		$addDT = dateTime5C::getCurrDT();

		/****** インデックスファイルの更新 ******/
		$fileName = $this->getFileName($branchNo);
		$profLine = funcs5C::readFile($fileName['path'] . '.' . $fileName['ext']);

		$json = new Services_JSON();
		$jsonData = $json->decode($profLine);



			/*
			$profNo = 1;
			foreach($jsonData as $read1) {
				$profNo++;
			}
			*/

		$profIdx[1][PROF_DIR       ] = $dir;
		$profIdx[1][PROF_DISP_SEQ  ] = 1;
		$profIdx[1][PROF_DISP_ONOFF_S] = PROF_DISP_OFF;
		$profIdx[1][PROF_ADD_DT    ] = $addDT;
		$profIdx[1][PROF_UPD_DT    ] = '';

		$idx = 2;
		foreach($jsonData as $read1) {
			$tempValue = (array)$read1;
			$profIdx[$idx] = $tempValue;		/* $ret['profInfo'][$idx] = (array)$value; */
			$profIdx[$idx][PROF_DISP_SEQ_S]++;
			$idx++;
		}

		/*** リストの再出力 ***/
		$jsonStr = $json->encode($profIdx);
		$fp = fopen($fileName['path'] . '.' . $fileName['ext'] ,'w');
		fwrite($fp ,$jsonStr);
		fclose($fp);


		/***** 新プロファイルの出力 *****/
		$newProf[PROF_WORK_DEF_SUN_F_S] = $workM[PROF_WORK_DEF_SUN_F_S];
		$newProf[PROF_WORK_DEF_MON_F_S] = $workM[PROF_WORK_DEF_MON_F_S];
		$newProf[PROF_WORK_DEF_TUE_F_S] = $workM[PROF_WORK_DEF_TUE_F_S];
		$newProf[PROF_WORK_DEF_WED_F_S] = $workM[PROF_WORK_DEF_WED_F_S];
		$newProf[PROF_WORK_DEF_THU_F_S] = $workM[PROF_WORK_DEF_THU_F_S];
		$newProf[PROF_WORK_DEF_FRI_F_S] = $workM[PROF_WORK_DEF_FRI_F_S];
		$newProf[PROF_WORK_DEF_SAT_F_S] = $workM[PROF_WORK_DEF_SAT_F_S];

		$newProf[PROF_WORK_DEF_SUN_T_S] = $workM[PROF_WORK_DEF_SUN_T_S];
		$newProf[PROF_WORK_DEF_MON_T_S] = $workM[PROF_WORK_DEF_MON_T_S];
		$newProf[PROF_WORK_DEF_TUE_T_S] = $workM[PROF_WORK_DEF_TUE_T_S];
		$newProf[PROF_WORK_DEF_WED_T_S] = $workM[PROF_WORK_DEF_WED_T_S];
		$newProf[PROF_WORK_DEF_THU_T_S] = $workM[PROF_WORK_DEF_THU_T_S];
		$newProf[PROF_WORK_DEF_FRI_T_S] = $workM[PROF_WORK_DEF_FRI_T_S];
		$newProf[PROF_WORK_DEF_SAT_T_S] = $workM[PROF_WORK_DEF_SAT_T_S];

		$newProf[PROF_WORK_DEF_SUN_MO_S] = $workM[PROF_WORK_DEF_SUN_MO_S];
		$newProf[PROF_WORK_DEF_MON_MO_S] = $workM[PROF_WORK_DEF_MON_MO_S];
		$newProf[PROF_WORK_DEF_TUE_MO_S] = $workM[PROF_WORK_DEF_TUE_MO_S];
		$newProf[PROF_WORK_DEF_WED_MO_S] = $workM[PROF_WORK_DEF_WED_MO_S];
		$newProf[PROF_WORK_DEF_THU_MO_S] = $workM[PROF_WORK_DEF_THU_MO_S];
		$newProf[PROF_WORK_DEF_FRI_MO_S] = $workM[PROF_WORK_DEF_FRI_MO_S];
		$newProf[PROF_WORK_DEF_SAT_MO_S] = $workM[PROF_WORK_DEF_SAT_MO_S];


		$newProf[PROF_NAME     ] = $name;		/* 名前 */
		$newProf[PROF_AGE      ] = $age;		/* 年齢 */
		$newProf[PROF_HEIGHT   ] = $height;	/* 身長 */
		$newProf[PROF_SIZES    ] = $size;		/* スリーサイズ */
		$newProf[PROF_BLOODTYPE_S] = $bloodType;/* 血液型 */

		$newProf[PROF_NEWFACE  ] = $newFace;	/* 新人 */
		$newProf[PROF_COMMENT  ] = $comment;	/* コメント */

		$newProf[PROF_BIRTHDATE_S] = $birdhDate;	/* 誕生日 */
		$newProf[PROF_ZODIAC   ] = $zodiac;		/* 星座 */

		$newProf[PROF_ADD_PROF_DT_S] = $addDT;	/* レコード登録日時 */
		$newProf[PROF_UPD_PROF_DT_S] = '';		/* レコード更新日時 */

		$this->writeProf($branchNo ,$dir ,$newProf);
	}


	/********************
	プロファイルの更新
	パラメータ：店No
	　　　　　　ディレクトリ
	　　　　　　新人
	　　　　　　名前
	　　　　　　年齢
	　　　　　　身長
	　　　　　　スリーサイズ
	　　　　　　コメント
	　　　　　　誕生日
	　　　　　　星座
	　　　　　　血液型
	　　　　　　出勤変更分
	戻り値　　：
	********************/
	function updOLD($branchNo ,$dir ,$newFace ,$name ,$age ,$height ,$size ,$comment ,$birdhDate ,$zodiac ,$bloodType ,$workM) {

		$updDT = dateTime5C::getCurrDT();

		/*** 既存プロファイルの読み込み ***/
		$fileName = $this->getFileName($branchNo);

		$fileFullPath = $fileName['path'] . '-' . $dir . 'Prof.' . $fileName['ext'];
		$currNews = funcs5C::readFile($fileFullPath);

		$json = new Services_JSON();
		$read1 = $json->decode($currNews);
		$jsonData = (array)$read1;


		/*** 既存プロファイル更新 ***/
		$updDT = dateTime5C::getCurrDT();

					/* $jsonData = $workM; */
		$jsonData[PROF_WORK_DEF_SUN_F_S] = $workM[PROF_WORK_DEF_SUN_F_S];
		$jsonData[PROF_WORK_DEF_MON_F_S] = $workM[PROF_WORK_DEF_MON_F_S];
		$jsonData[PROF_WORK_DEF_TUE_F_S] = $workM[PROF_WORK_DEF_TUE_F_S];
		$jsonData[PROF_WORK_DEF_WED_F_S] = $workM[PROF_WORK_DEF_WED_F_S];
		$jsonData[PROF_WORK_DEF_THU_F_S] = $workM[PROF_WORK_DEF_THU_F_S];
		$jsonData[PROF_WORK_DEF_FRI_F_S] = $workM[PROF_WORK_DEF_FRI_F_S];
		$jsonData[PROF_WORK_DEF_SAT_F_S] = $workM[PROF_WORK_DEF_SAT_F_S];

		$jsonData[PROF_WORK_DEF_SUN_T_S] = $workM[PROF_WORK_DEF_SUN_T_S];
		$jsonData[PROF_WORK_DEF_MON_T_S] = $workM[PROF_WORK_DEF_MON_T_S];
		$jsonData[PROF_WORK_DEF_TUE_T_S] = $workM[PROF_WORK_DEF_TUE_T_S];
		$jsonData[PROF_WORK_DEF_WED_T_S] = $workM[PROF_WORK_DEF_WED_T_S];
		$jsonData[PROF_WORK_DEF_THU_T_S] = $workM[PROF_WORK_DEF_THU_T_S];
		$jsonData[PROF_WORK_DEF_FRI_T_S] = $workM[PROF_WORK_DEF_FRI_T_S];
		$jsonData[PROF_WORK_DEF_SAT_T_S] = $workM[PROF_WORK_DEF_SAT_T_S];

		$jsonData[PROF_WORK_DEF_SUN_MO_S] = $workM[PROF_WORK_DEF_SUN_MO_S];
		$jsonData[PROF_WORK_DEF_MON_MO_S] = $workM[PROF_WORK_DEF_MON_MO_S];
		$jsonData[PROF_WORK_DEF_TUE_MO_S] = $workM[PROF_WORK_DEF_TUE_MO_S];
		$jsonData[PROF_WORK_DEF_WED_MO_S] = $workM[PROF_WORK_DEF_WED_MO_S];
		$jsonData[PROF_WORK_DEF_THU_MO_S] = $workM[PROF_WORK_DEF_THU_MO_S];
		$jsonData[PROF_WORK_DEF_FRI_MO_S] = $workM[PROF_WORK_DEF_FRI_MO_S];
		$jsonData[PROF_WORK_DEF_SAT_MO_S] = $workM[PROF_WORK_DEF_SAT_MO_S];


		$jsonData[PROF_NAME     ] = $name;		/* 名前 */
		$jsonData[PROF_AGE      ] = $age;			/* 年齢 */
		$jsonData[PROF_HEIGHT   ] = $height;		/* 身長 */
		$jsonData[PROF_SIZES    ] = $size;		/* スリーサイズ */
		$jsonData[PROF_BLOODTYPE_S] = $bloodType;	/* 血液型 */

		$jsonData[PROF_NEWFACE  ] = $newFace;		/* 新人 */
		$jsonData[PROF_COMMENT  ] = $comment;		/* コメント */

		$jsonData[PROF_BIRTHDATE_S] = $birdhDate;	/* 誕生日 */
		$jsonData[PROF_ZODIAC   ] = $zodiac;		/* 星座 */

		$jsonData[PROF_UPD_PROF_DT_S] = $updDT;		/* レコード更新日時 */


		$this->writeProf($branchNo ,$dir ,$jsonData);
	}


	/********************
	写真表示の更新
	パラメータ：店No
	　　　　　　ディレクトリ
	　　　　　　写真1
	　　　　　　写真2
	　　　　　　写真3
	　　　　　　サムネイル
	　　　　　　大写真
	戻り値　　：
	********************/
	function updShowPhoto($branchNo ,$dir ,$useP1 ,$useP2 ,$useP3 ,$useP4 ,$useP5 ,$useTN ,$useML   ,$extP1 ,$extP2 ,$extP3 ,$extP4 ,$extP5 ,$extTN ,$extML ,$photoUSE) {

		$updDT = dateTime5C::getCurrDT();

		/*** 既存プロファイルの読み込み ***/
		$fileName = $this->getFileName($branchNo);

		$fileFullPath = $fileName['path'] . '-' . $dir . 'Prof.' . $fileName['ext'];
		$currNews = funcs5C::readFile($fileFullPath);

		$json = new Services_JSON();
		$read1 = $json->decode($currNews);
		$jsonData = (array)$read1;

		/*** 既存プロファイル更新 ***/
		$updDT = dateTime5C::getCurrDT();

		$jsonData[PROF_PHOTOUSE_1_S] = $useP1;
		$jsonData[PROF_PHOTOUSE_2_S] = $useP2;
		$jsonData[PROF_PHOTOUSE_3_S] = $useP3;
		$jsonData[PROF_PHOTOUSE_4_S] = $useP4;
		$jsonData[PROF_PHOTOUSE_5_S] = $useP5;

		$jsonData[PROF_PHOTOUSE_S_S] = $useTN;
		$jsonData[PROF_PHOTOUSE_M_S] = $useML;

					/*
					print 'ext1' . $extP1 . '<br />';
					print 'ext2' . $extP2 . '<br />';
					print 'ext3' . $extP3 . '<br />';
					print 'ext4' . $extP4 . '<br />';
					print 'ext5' . $extP5 . '<br />';
					*/


		if(strlen($extP1) >= 1) {
			$jsonData[PROF_PHOTOEXT_1_S] = $extP1;
		}
		if(strlen($extP2) >= 1) {
			$jsonData[PROF_PHOTOEXT_2_S] = $extP2;
		}
		if(strlen($extP3) >= 1) {
			$jsonData[PROF_PHOTOEXT_3_S] = $extP3;
		}
		if(strlen($extP4) >= 1) {
			$jsonData[PROF_PHOTOEXT_4_S] = $extP4;
		}
		if(strlen($extP5) >= 1) {
			$jsonData[PROF_PHOTOEXT_5_S] = $extP5;
		}

					/*
					print 'ext1' . $jsonData[PROF_PHOTOEXT_1_S] . '<br />';
					print 'ext2' . $jsonData[PROF_PHOTOEXT_2_S] . '<br />';
					print 'ext3' . $jsonData[PROF_PHOTOEXT_3_S] . '<br />';
					print 'ext4' . $jsonData[PROF_PHOTOEXT_4_S] . '<br />';
					print 'ext5' . $jsonData[PROF_PHOTOEXT_5_S] . '<br />';
					*/

		if(strlen($extTN) >= 1) {
			$jsonData[PROF_PHOTOEXT_S_S] = $extTN;
		}
		if(strlen($extML) >= 1) {
			$jsonData[PROF_PHOTOEXT_M_S] = $extML;
		}

		$jsonData[PROF_FLD_PHOTO_SHOW] = $photoUSE;
		$jsonData[PROF_UPD_PROF_DT_S] = $updDT;


		$this->writeProf($branchNo ,$dir ,$jsonData);
	}


	function writeProf($branchNo ,$dir ,$profData) {

		$json = new Services_JSON();
		$jsonStr = $json->encode($profData);

		/*** プロファイルの再出力 ***/
		$fileName = $this->getFileName($branchNo);

		$fileFullPath = $fileName['path'] . '-' . $dir . 'Prof.' . $fileName['ext'];

		$fp = fopen($fileFullPath ,'w');
		fwrite($fp ,$jsonStr);
		fclose($fp);
	}


	/********************
	ファイル名構築
	パラメータ：店No
	戻り値　　：
	********************/
	function getFileName($branchNo) {

		$noS = $this->setFormat($branchNo);
		$ret['path'] = realpath(dirname(__FILE__) . '/../../dataFiles') . '/profile' . $noS['branchNo'];
		$ret['ext' ] = 'json';

		return $ret;
	}

	/********************
	Noパディング
	パラメータ：店No
	戻り値　　：
	********************/
	function setFormat($branchNo) {

		$ret['branchNo'] = sprintf('%03d' ,$branchNo);

		return $ret;
	}


	/********************
	プロファイルの取得
	パラメータ：店No
	　　　　　　ディレクトリ
	戻り値　　：
	********************/
	function getOLD($branchNo ,$dir) {

		$ret = array();

		/*** 既存プロファイルの読み込み ***/
		$fileName = $this->getFileName($branchNo);

		$fileFullPath = $fileName['path'] . '-' . $dir . 'Prof.' . $fileName['ext'];
		$prof = funcs5C::readFile($fileFullPath);

		$json = new Services_JSON();
		$read1 = $json->decode($prof);

		$ret = $this->readProfData($prof);

//		$ret = (array)$read1;

		return $ret;
	}


	/********************
	プロファイルの取得
	パラメータ：店No
	　　　　　　ディレクトリ
	戻り値　　：
	********************/
	function getEmpty($branchNo) {

		$ret = array();

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


		$ret[PROF_WORK_DEF_SUN_F_S] = '';	/* 日曜 */
		$ret[PROF_WORK_DEF_MON_F_S] = '';	/* 月曜 */
		$ret[PROF_WORK_DEF_TUE_F_S] = '';	/* 火曜 */
		$ret[PROF_WORK_DEF_WED_F_S] = '';	/* 水曜 */
		$ret[PROF_WORK_DEF_THU_F_S] = '';	/* 木曜 */
		$ret[PROF_WORK_DEF_FRI_F_S] = '';	/* 金曜 */
		$ret[PROF_WORK_DEF_SAT_F_S] = '';	/* 土曜 */

		$ret[PROF_WORK_DEF_SUN_T_S] = '';	/* 日曜 */
		$ret[PROF_WORK_DEF_MON_T_S] = '';	/* 月曜 */
		$ret[PROF_WORK_DEF_TUE_T_S] = '';	/* 火曜 */
		$ret[PROF_WORK_DEF_WED_T_S] = '';	/* 水曜 */
		$ret[PROF_WORK_DEF_THU_T_S] = '';	/* 木曜 */
		$ret[PROF_WORK_DEF_FRI_T_S] = '';	/* 金曜 */
		$ret[PROF_WORK_DEF_SAT_T_S] = '';	/* 土曜 */

		$ret[PROF_WORK_DEF_SUN_MO_S] = PROF_WORK_MODE_TO;	/* 日曜 */
		$ret[PROF_WORK_DEF_MON_MO_S] = PROF_WORK_MODE_TO;	/* 月曜 */
		$ret[PROF_WORK_DEF_TUE_MO_S] = PROF_WORK_MODE_TO;	/* 火曜 */
		$ret[PROF_WORK_DEF_WED_MO_S] = PROF_WORK_MODE_TO;	/* 水曜 */
		$ret[PROF_WORK_DEF_THU_MO_S] = PROF_WORK_MODE_TO;	/* 木曜 */
		$ret[PROF_WORK_DEF_FRI_MO_S] = PROF_WORK_MODE_TO;	/* 金曜 */
		$ret[PROF_WORK_DEF_SAT_MO_S] = PROF_WORK_MODE_TO;	/* 土曜 */


//		$ret = (array)$read1;

		return $ret;
	}
}
?>
