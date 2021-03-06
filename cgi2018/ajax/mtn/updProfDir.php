<?php
/********************
識別子の変更 Version 1.0
PHP4
2014 Sep. 13 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../sess/sess5C.php';
	require_once dirname(__FILE__) . '/../../db/dbProfile5C.php';
	require_once dirname(__FILE__) . '/../../db/dbWorks5C.php';
	require_once dirname(__FILE__) . '/../../fileName5C.php';

	$cond = sess5C::getSessCond();

	if($cond == sess5C::OWN_INTIME) {
		updDirName();
		sess5C::updSessCond();

		$news = new dbNews5C();
		$dispCnt = $news->updDisp($_POST);
		$ret['DISPCOUNT'] = $dispCnt;
	}

	$ret['SESSCOND'] = $cond;
	print json_encode($ret);


function updDirName() {

	$branchNo = $_POST['branchNo'];

	$oldDir = $_POST['old'];
	$newDir = $_POST['new'];

	// DB項目の更新　プロファイル
	$prof = new dbProfile5C();
	$prof->setBranchNo($branchNo);
	$prof->updDir($oldDir ,$newDir);
	$db = $prof->handle;

	//DB項目の更新　週間出勤表
	$works = new dbWorks5C($branchNo ,$db);
	$works->updDir($oldDir ,$newDir);

	//紹介ページのファイル名変更
	$outBaseDir = realpath(dirname(__FILE__) . '/../../..') . '/' . fileName5C::PROFILE_DIR;
	$oldDirPath = $outBaseDir . '/' . $oldDir . '.html';
	$newDirPath = $outBaseDir . '/' . $newDir . '.html';
	rename($oldDirPath ,$newDirPath);

	//写真ファイル名の更新
	updPhotoFileName($branchNo ,$oldDir ,$newDir ,$prof);
}


function updPhotoFileName($branchNo ,$oldDir ,$newDir ,$prof) {

	$profVal = $prof->get($branchNo ,$newDir);

	$outBaseDir = realpath(dirname(__FILE__) . '/../../..') . '/' . fileName5C::FILEID_PHOTO_DIR;

	$oldDirPath = $outBaseDir . '/' . $oldDir;
	$newDirPath = $outBaseDir . '/' . $newDir;
	rename($oldDirPath ,$newDirPath);

	$oldFile = $newDirPath . '/' . $oldDir . '1' . '.' . $profVal[dbProfile5C::FLD_PHOTOEXT_1];
			/* print 'oldFile:' . $oldFile; */
	if(is_file($oldFile)) {
		$newFile = $newDirPath . '/' . $newDir . '1' . '.' . $profVal[dbProfile5C::FLD_PHOTOEXT_1];
				/* print 'newFile:' . $newFile; */
		rename($oldFile ,$newFile);
	}

	$oldFile = $newDirPath . '/' . $oldDir . '2' . '.' . $profVal[dbProfile5C::FLD_PHOTOEXT_2];
			/* print 'oldFile:' . $oldFile; */
	if(is_file($oldFile)) {
		$newFile = $newDirPath . '/' . $newDir . '2' . '.' . $profVal[dbProfile5C::FLD_PHOTOEXT_2];
				/* print 'newFile:' . $newFile; */
		rename($oldFile ,$newFile);
	}

	$oldFile = $newDirPath . '/' . $oldDir . '3' . '.' . $profVal[dbProfile5C::FLD_PHOTOEXT_3];
			/* print 'oldFile:' . $oldFile; */
	if(is_file($oldFile)) {
		$newFile = $newDirPath . '/' . $newDir . '3' . '.' . $profVal[dbProfile5C::FLD_PHOTOEXT_3];
				/* print 'newFile:' . $newFile; */
		rename($oldFile ,$newFile);
	}

	$oldFile = $newDirPath . '/' . $oldDir . '4' . '.' . $profVal[dbProfile5C::FLD_PHOTOEXT_4];
			/* print 'oldFile:' . $oldFile; */
	if(is_file($oldFile)) {
		$newFile = $newDirPath . '/' . $newDir . '4' . '.' . $profVal[dbProfile5C::FLD_PHOTOEXT_4];
				/* print 'newFile:' . $newFile; */
		rename($oldFile ,$newFile);
	}

	$oldFile = $newDirPath . '/' . $oldDir . '5' . '.' . $profVal[dbProfile5C::FLD_PHOTOEXT_5];
			/* print 'oldFile:' . $oldFile; */
	if(is_file($oldFile)) {
		$newFile = $newDirPath . '/' . $newDir . '5' . '.' . $profVal[dbProfile5C::FLD_PHOTOEXT_5];
				/* print 'newFile:' . $newFile; */
		rename($oldFile ,$newFile);
	}

	$oldFile = $newDirPath . '/' . $oldDir . 'TN' . '.' . $profVal[dbProfile5C::FLD_PHOTOEXT_S];
			/* print 'oldFile:' . $oldFile; */
	if(is_file($oldFile)) {
		$newFile = $newDirPath . '/' . $newDir . 'TN' . '.' . $profVal[dbProfile5C::FLD_PHOTOEXT_S];
				/* print 'newFile:' . $newFile; */
		rename($oldFile ,$newFile);
	}

	$oldFile = $newDirPath . '/' . $oldDir . 'M' . '.' . $profVal[dbProfile5C::FLD_PHOTOEXT_M];
			/* print 'oldFile:' . $oldFile; */
	if(is_file($oldFile)) {
		$newFile = $newDirPath . '/' . $newDir . 'M' . '.' . $profVal[dbProfile5C::FLD_PHOTOEXT_M];
				/* print 'newFile:' . $newFile; */
		rename($oldFile ,$newFile);
	}
}
?>
