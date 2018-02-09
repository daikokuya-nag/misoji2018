<?php
/********************
プロファイル取出し Version 1.1
PHP5
2016 Feb. 22 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../sql5C.php';
	require_once dirname(__FILE__) . '/../../db/dbProfile5C.php';
	require_once dirname(__FILE__) . '/../../db/dbWorks5C.php';
	require_once dirname(__FILE__) . '/../../bld/bldProfile5C.php';
	require_once dirname(__FILE__) . '/../../dateTime5C.php';

	$branchNo = $_REQUEST['branchNo'];	/* 店No */

	$dir = $_REQUEST['dir'];

	$db = new sql5C();

	$profile = new dbProfile5C($db);
	$works   = new dbWorks5C($branchNo ,$db);
	$bldTag  = new bldProfile5C();

	$daysList = dateTime5C::setDateList1W();	/*** 1週間分の日付リスト ***/

	/***** プロファイルの取出し *****/
	if(strlen($dir) >= 1) {
		$ret = $profile->get($branchNo ,$dir);	/* プロファイル */

		$workVals = $works->get($dir);
		$workByDate = $works->setByDate($workVals ,$daysList);
		$ret["workTag"] = $bldTag->bldWork($workByDate);
	} else {
		$ret = $profile->getEmpty();	/* プロファイル */

		$workVals = $works->get($dir);
		$workByDate = $works->setByDate($workVals ,$daysList);
		$ret["workTag"] = $bldTag->bldWork($workByDate);
	}

	$photo = setPhotoInfo($branchNo ,$dir ,$ret);
	$ret['photo'] = $photo;

	print json_encode($ret);


	/***********************************************************************/
	/***** 写真ファイルの有無 *****/
	function setPhotoInfo($branchNo ,$dir ,$profData) {

		/***** 写真ファイルの有無 *****/
		$FILE_EXIST = 'EXIST';

		$ret['existF1'] = '';
		$ret['existF2'] = '';
		$ret['existF3'] = '';
		$ret['existF4'] = '';
		$ret['existF5'] = '';
		$ret['existTN'] = '';
		$ret['existML'] = '';

		/* 写真ファイル名 */
		$ret['fineNameF1'] = '';
		$ret['fineNameF2'] = '';
		$ret['fineNameF3'] = '';
		$ret['fineNameF4'] = '';
		$ret['fineNameF5'] = '';
		$ret['fineNameTN'] = '';
		$ret['fineNameML'] = '';

		/***** 写真ファイル使用/非使用 *****/
		$FILE_USE = dbProfile5C::PHOTO_USE;

		$ret['useF1'] = '';
		$ret['useF2'] = '';
		$ret['useF3'] = '';
		$ret['useF4'] = '';
		$ret['useF5'] = '';
		$ret['useTN'] = '';
		$ret['useML'] = '';

		/***** 写真表示 *****/
		$photoUse = dbProfile5C::PHOTO_SHOW_NP;		/* 写真準備中 */

		/*
		$photoUSE['O'] = '';
		$photoUSE['P'] = ' checked';
		$photoUSE['G'] = '';
		$photoUSE['N'] = '';
		*/

		if(strlen($dir) >= 1) {
			/***** 写真ファイルの有無 *****/
			/*
			$outBaseDir = files4C::getFileName('PROF_BASEDIR'  ,$branchNo);
			$path = $outBaseDir['PC'] . '/' . $dir;
			*/

			$existPath = realpath(dirname(__FILE__) . '/../../..') . '/photo/' . $dir;
			$dispPath  = '../photo/' . $dir;

			/*	$fileName = $existPath . '/' . $dir . '.' . $fileExt;*/
					/*print $existPath;*/
				/*
				print $existPath;
				print $profData[dbProfile5C::FLD_PHOTOEXT_1];
				*/
					/*
					print $existPath . '/' . $dir . '1.' . $profData[dbProfile5C::FLD_PHOTOEXT_1] . '<br />';
					print $existPath . '/' . $dir . '2.' . $profData[dbProfile5C::FLD_PHOTOEXT_2] . '<br />';
					print $existPath . '/' . $dir . '3.' . $profData[dbProfile5C::FLD_PHOTOEXT_3] . '<br />';
					print $existPath . '/' . $dir . '4.' . $profData[dbProfile5C::FLD_PHOTOEXT_4] . '<br />';
					print $existPath . '/' . $dir . '5.' . $profData[dbProfile5C::FLD_PHOTOEXT_5] . '<br />';
					*/


			if(is_file($existPath . '/' . $dir . '1.' . $profData[dbProfile5C::FLD_PHOTOEXT_1])) {
				$ret['fineNameF1'] = $dispPath . '/' . $dir . '1.' . $profData[dbProfile5C::FLD_PHOTOEXT_1];
			} else {
				if(is_file($existPath . '/largePhoto1.' . $profData[dbProfile5C::FLD_PHOTOEXT_1])) {
					$ret['fineNameF1'] = $dispPath . '/largePhoto1.' . $profData[dbProfile5C::FLD_PHOTOEXT_1];
				}
			}

			if(is_file($existPath . '/' . $dir . '2.' . $profData[dbProfile5C::FLD_PHOTOEXT_2])) {
				$ret['fineNameF2'] = $dispPath . '/' . $dir . '2.' . $profData[dbProfile5C::FLD_PHOTOEXT_2];
			} else {
				if(is_file($existPath . '/largePhoto2.' . $profData[dbProfile5C::FLD_PHOTOEXT_2])) {
					$ret['fineNameF2'] = $dispPath . '/largePhoto2.' . $profData[dbProfile5C::FLD_PHOTOEXT_2];
				}
			}

			if(is_file($existPath . '/' . $dir . '3.' . $profData[dbProfile5C::FLD_PHOTOEXT_3])) {
				$ret['fineNameF3'] = $dispPath . '/' . $dir . '3.' . $profData[dbProfile5C::FLD_PHOTOEXT_3];
			} else {
				if(is_file($existPath . '/largePhoto3.' . $profData[dbProfile5C::FLD_PHOTOEXT_3])) {
					$ret['fineNameF3'] = $dispPath . '/largePhoto3.' . $profData[dbProfile5C::FLD_PHOTOEXT_3];
				}
			}

			if(is_file($existPath . '/' . $dir . '4.' . $profData[dbProfile5C::FLD_PHOTOEXT_4])) {
				$ret['fineNameF4'] = $dispPath . '/' . $dir . '4.' . $profData[dbProfile5C::FLD_PHOTOEXT_4];
			} else {
				if(is_file($existPath . '/largePhoto4.' . $profData[dbProfile5C::FLD_PHOTOEXT_4])) {
					$ret['fineNameF4'] = $dispPath . '/largePhoto4.' . $profData[dbProfile5C::FLD_PHOTOEXT_4];
				}
			}

			if(is_file($existPath . '/' . $dir . '5.' . $profData[dbProfile5C::FLD_PHOTOEXT_5])) {
				$ret['fineNameF5'] = $dispPath . '/' . $dir . '5.' . $profData[dbProfile5C::FLD_PHOTOEXT_5];
			} else {
				if(is_file($existPath . '/largePhoto5.' . $profData[dbProfile5C::FLD_PHOTOEXT_5])) {
					$ret['fineNameF5'] = $dispPath . '/largePhoto5.' . $profData[dbProfile5C::FLD_PHOTOEXT_5];
				}
			}


			if(is_file($existPath . '/' . $dir . 'TN.' . $profData[dbProfile5C::FLD_PHOTOEXT_S])) {
				$ret['fineNameTN'] = $dispPath . '/' . $dir . 'TN.' . $profData[dbProfile5C::FLD_PHOTOEXT_S];
			} else {
				if(is_file($existPath . '/thumbNail.' . $profData[dbProfile5C::FLD_PHOTOEXT_S])) {
					$ret['fineNameTN'] = $dispPath . '/thumbNail.' . $profData[dbProfile5C::FLD_PHOTOEXT_S];
				}
			}


			if(is_file($existPath . '/' . $dir . '-m.' . $profData[dbProfile5C::FLD_PHOTOEXT_M])) {
				$ret['fineNameML'] = $dispPath . '/' . $dir . '-m.' . $profData[dbProfile5C::FLD_PHOTOEXT_M];
			}



			if(strlen($ret['fineNameF1']) >= 1) {
				$ret['existF1'] = $FILE_EXIST;
			}
			if(strlen($ret['fineNameF2']) >= 1) {
				$ret['existF2'] = $FILE_EXIST;
			}
			if(strlen($ret['fineNameF3']) >= 1) {
				$ret['existF3'] = $FILE_EXIST;
			}
			if(strlen($ret['fineNameF4']) >= 1) {
				$ret['existF4'] = $FILE_EXIST;
			}
			if(strlen($ret['fineNameF5']) >= 1) {
				$ret['existF5'] = $FILE_EXIST;
			}

			if(strlen($ret['fineNameTN']) >= 1) {
				$ret['existTN'] = $FILE_EXIST;
			}

			if(strlen($ret['fineNameML']) >= 1) {
				$ret['existML'] = $FILE_EXIST;
			}







			/***** 写真ファイル使用/非使用 *****/
			if(strcmp($profData[dbProfile5C::FLD_PHOTOUSE_1] ,dbProfile5C::PHOTO_USE) == 0) {
				$ret['useF1'] = $FILE_USE;
			}
			if(strcmp($profData[dbProfile5C::FLD_PHOTOUSE_2] ,dbProfile5C::PHOTO_USE) == 0) {
				$ret['useF2'] = $FILE_USE;
			}
			if(strcmp($profData[dbProfile5C::FLD_PHOTOUSE_3] ,dbProfile5C::PHOTO_USE) == 0) {
				$ret['useF3'] = $FILE_USE;
			}
			if(strcmp($profData[dbProfile5C::FLD_PHOTOUSE_4] ,dbProfile5C::PHOTO_USE) == 0) {
				$ret['useF4'] = $FILE_USE;
			}
			if(strcmp($profData[dbProfile5C::FLD_PHOTOUSE_5] ,dbProfile5C::PHOTO_USE) == 0) {
				$ret['useF5'] = $FILE_USE;
			}

			if(strcmp($profData[dbProfile5C::FLD_PHOTOUSE_S] ,dbProfile5C::PHOTO_USE) == 0) {
				$ret['useTN'] = $FILE_USE;
			}
			if(strcmp($profData[dbProfile5C::FLD_PHOTOUSE_M] ,dbProfile5C::PHOTO_USE) == 0) {
				$ret['useML'] = $FILE_USE;
			}


			$photoShow = $profData[dbProfile5C::FLD_PHOTO_SHOW];
			if(strcmp($photoShow ,dbProfile5C::PHOTO_SHOW_OK) == 0) {
				$photoUse = dbProfile5C::PHOTO_SHOW_OK;
			}

			if(strcmp($photoShow ,dbProfile5C::PHOTO_SHOW_NP) == 0) {
				$photoUse = dbProfile5C::PHOTO_SHOW_NP;
			}

			if(strcmp($photoShow ,dbProfile5C::PHOTO_SHOW_NG) == 0) {
				$photoUse = dbProfile5C::PHOTO_SHOW_NG;
			}

			if(strcmp($photoShow ,dbProfile5C::PHOTO_SHOW_NOT) == 0) {
				$photoUse = dbProfile5C::PHOTO_SHOW_NOT;
			}
			$ret['photoShow'] = $photoUse;
		}

		return $ret;
	}
?>
