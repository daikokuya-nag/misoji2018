<?php
/********************
プロファイル表示順出力 Version 1.1
PHP5
2016 Feb. 24 ver 1.0
2016 May  23 ver 1.1 アピールコメント追加
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbProfile5C.php';
	require_once dirname(__FILE__) . '/../../sess/sess5C.php';

	$cond = sess5C::getSessCond();
	if($cond == sess5C::OWN_INTIME) {
		sess5C::updSessCond();

		$branchNo = $_POST['branchNo'];
		$sortVal  = $_POST['sort'];

		$prof = new dbProfile5C($branchNo);

		/***** 表示順の更新 *****/
		$sortMax = count($sortVal);
		for($idx=0 ;$idx<$sortMax ;$idx++) {
			$dir = $sortVal[$idx];
			$key = 'disp' . $dir;
			if(isset($_POST[$key])) {
				$disp = dbProfile5C::DISP_ON;
			} else {
				$disp = dbProfile5C::DISP_OFF;
			}
			$dispSeq = $idx + 1;

			$prof->updDispSeq($dir ,$dispSeq ,$disp);
		}
	}

	$ret['SESSCOND'] = $cond;
	print json_encode($ret);
?>
