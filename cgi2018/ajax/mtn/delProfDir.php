<?php
/********************
識別子の削除 Version 1.0
PHP4
2014 Sep. 13 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbProfile5C.php';
	require_once dirname(__FILE__) . '/../../db/dbWorks5C.php';
	require_once dirname(__FILE__) . '/../../sess/sess5C.php';
	require_once dirname(__FILE__) . '/../../fileName5C.php';

	require_once dirname(__FILE__) . '/../../logFile5C.php';

	$cond = sess5C::getSessCond();
	if($cond == sess5C::OWN_INTIME) {
		delProfMain();
	}

	$ret['SESSCOND'] = $cond;
	print json_encode($ret);


	function delProfMain() {
	$branchNo = $_POST['branchNo'];
	$dir      = $_POST['dir'     ];			/* ディレクトリ */

	/***** 当該識別子削除 *****/
	$prof = new dbProfile5C();
	$prof->setBranchNo($branchNo);
	$db = $prof->handle;
	$dirQ = $db->setQuote($dir);

	$where =
		dbProfile5C::FLD_BRANCH_NO . '=' . $branchNo . ' and ' .
		dbProfile5C::FLD_DIR       . '=' . $dirQ;

	$exist = $db->existRec(dbProfile5C::TABLE_NAME ,$where);
	if($exist) {
		$prof->del($dir);
		logFile5C::debug('識別子削除' . $dir);
	}

	/***** 週間出勤表削除 *****/
	$works = new dbWorks5C($branchNo ,$db);
	$where =
		dbWorks5C::FLD_BRANCH_NO . '=' . $branchNo . ' and ' .
		dbWorks5C::FLD_DIR       . '=' . $dirQ;

	$exist = $db->existRec(dbWorks5C::TABLE_NAME ,$where);
	if($exist) {
		$works->del($dir);
		logFile5C::debug('識別子削除' . $dir);
	}


	$rootPath = realpath(dirname(__FILE__) . '/../../..');

	/***** 写真ファイル削除 *****/
	$path = $rootPath . '/' . fileName5C::FILEID_PHOTO_DIR . '/' . $dir;
	$dh = opendir($path);
	while(($filename = readdir($dh))) {
		if($filename == '.' || $filename == '..') {
			continue;
		} else {
			$realpath = $path . '/' . $filename;
			if (is_link($realpath)) {
				continue;
			} else {
				if(is_file($realpath)) {
					unlink($realpath);
					logFile5C::debug('写真ファイル削除' . $realpath);
				}
			}
		}
	}
	closedir($dh);
	$path = $rootPath . '/' . fileName5C::FILEID_PHOTO_DIR . '/'   . $dir;
	if(is_dir($path)) {
		rmdir($path);
		logFile5C::debug('写真ファイルディレクトリ削除' . $path);
	}

	/***** 紹介ページ削除 *****/
	$path = $rootPath . '/' . fileName5C::PROFILE_DIR . '/'   . $dir . '.html';
	if(is_file($path)) {
		unlink($path);
		logFile5C::debug('紹介ページ削除' . $path);
	}

	$path = $rootPath . '/mo/' . fileName5C::PROFILE_DIR . '/' . $dir . '.html';
	if(is_file($path)) {
		unlink($path);
		logFile5C::debug('紹介ページ削除' . $path);
	}
}
?>
