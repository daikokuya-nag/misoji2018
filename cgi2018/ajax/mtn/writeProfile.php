<?php
/********************
プロファイル出力 Version 1.1
PHP5
2016 Feb. 24 ver 1.0
2016 May  23 ver 1.1 アピールコメント追加
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbProfile5C.php';
	require_once dirname(__FILE__) . '/../../db/dbWorks5C.php';
	require_once dirname(__FILE__) . '/../../sess/sess5C.php';

	$cond = sess5C::getSessCond();

	if($cond == sess5C::OWN_INTIME) {
		sess5C::updSessCond();

		setProfile($_POST);
		setWork($_POST);
	}

	$ret['SESSCOND'] = $cond;
	print json_encode($ret);


	function setProfile($postVals) {

		$branchNo = $postVals['branchNo'];
		$dir      = $postVals['profDir'];			/* ディレクトリ */
		$newProf  = $postVals['newProf'];

		$birthDate = $postVals['profBirthDate'];	/* 誕生日 */

		$prof = new dbProfile5C($branchNo);

		$prof->setVal(dbProfile5C::FLD_NEWFACE ,$postVals['newFace' ]);		/* 新人 */
		$prof->setVal(dbProfile5C::FLD_NAME    ,$postVals['profName']);		/* 名前 */

		$prof->setVal(dbProfile5C::FLD_AGE     ,$postVals['profAge'   ]);	/* 年齢 */
		$prof->setVal(dbProfile5C::FLD_HEIGHT  ,$postVals['profHeight']);	/* 身長 */
		$prof->setVal(dbProfile5C::FLD_SIZES   ,$postVals['profSize'  ]);	/* スリーサイズ */

		$prof->setVal(dbProfile5C::FLD_ZODIAC    ,$postVals['profZodiac']);		/* 星座 */
		$prof->setVal(dbProfile5C::FLD_BLOODTYPE ,$postVals['profBloodType']);	/* 血液型 */


		$prof->setVal(dbProfile5C::FLD_MASTERS_COMMENT ,$postVals['mastComment']);		/* 店長コメント */
		$prof->setVal(dbProfile5C::FLD_APPEAL_COMMENT  ,$postVals['appComment']);		/* アピールコメント */

		$prof->setVal(dbProfile5C::FLD_PCD ,$postVals['profPCode']);		/* パスコード */


		/*** Bデータ ***/
		if(isset($postVals['ba'])) {
			$prof->setVal(dbProfile5C::FLD_B1 ,$postVals['ba']);
		}
		if(isset($postVals['bb'])) {
			$prof->setVal(dbProfile5C::FLD_B2 ,$postVals['bb']);
		}
		if(isset($postVals['bc'])) {
			$prof->setVal(dbProfile5C::FLD_B3 ,$postVals['bc']);
		}
		if(isset($postVals['bd'])) {
			$prof->setVal(dbProfile5C::FLD_B4 ,$postVals['bd']);
		}
		if(isset($postVals['be'])) {
			$prof->setVal(dbProfile5C::FLD_B5 ,$postVals['be']);
		}
		if(isset($postVals['bf'])) {
			$prof->setVal(dbProfile5C::FLD_B6 ,$postVals['bf']);
		}
		if(isset($postVals['bg'])) {
			$prof->setVal(dbProfile5C::FLD_B7 ,$postVals['bg']);
		}
		if(isset($postVals['bh'])) {
			$prof->setVal(dbProfile5C::FLD_B8 ,$postVals['bh']);
		}
		if(isset($postVals['bi'])) {
			$prof->setVal(dbProfile5C::FLD_B9 ,$postVals['bi']);
		}
		if(isset($postVals['bj'])) {
			$prof->setVal(dbProfile5C::FLD_B10 ,$postVals['bj']);
		}
		if(isset($postVals['bk'])) {
			$prof->setVal(dbProfile5C::FLD_B11 ,$postVals['bk']);
		}
		if(isset($postVals['bl'])) {
			$prof->setVal(dbProfile5C::FLD_B12 ,$postVals['bl']);
		}
		if(isset($postVals['bm'])) {
			$prof->setVal(dbProfile5C::FLD_B13 ,$postVals['bm']);
		}


		/*** QA ***/
		$prof->setVal(dbProfile5C::FLD_QA1  ,$postVals['qa1']);
		$prof->setVal(dbProfile5C::FLD_QA2  ,$postVals['qa2']);
		$prof->setVal(dbProfile5C::FLD_QA3  ,$postVals['qa3']);
		$prof->setVal(dbProfile5C::FLD_QA4  ,$postVals['qa4']);
		$prof->setVal(dbProfile5C::FLD_QA5  ,$postVals['qa5']);
		$prof->setVal(dbProfile5C::FLD_QA6  ,$postVals['qa6']);
		$prof->setVal(dbProfile5C::FLD_QA7  ,$postVals['qa7']);
		$prof->setVal(dbProfile5C::FLD_QA8  ,$postVals['qa8']);
		$prof->setVal(dbProfile5C::FLD_QA9  ,$postVals['qa9']);
		$prof->setVal(dbProfile5C::FLD_QA10 ,$postVals['qa10']);
		$prof->setVal(dbProfile5C::FLD_QA11 ,$postVals['qa11']);
		$prof->setVal(dbProfile5C::FLD_QA12 ,$postVals['qa12']);
		$prof->setVal(dbProfile5C::FLD_QA13 ,$postVals['qa13']);
		$prof->setVal(dbProfile5C::FLD_QA14 ,$postVals['qa14']);


		/*** 写真表示 ***/
		$prof->setVal(dbProfile5C::FLD_PHOTOUSE_1 ,$postVals['useP1']);
		$prof->setVal(dbProfile5C::FLD_PHOTOUSE_2 ,$postVals['useP2']);
		$prof->setVal(dbProfile5C::FLD_PHOTOUSE_3 ,$postVals['useP3']);
		$prof->setVal(dbProfile5C::FLD_PHOTOUSE_4 ,$postVals['useP4']);
		$prof->setVal(dbProfile5C::FLD_PHOTOUSE_5 ,$postVals['useP5']);

		$prof->setVal(dbProfile5C::FLD_PHOTOUSE_S ,$postVals['useTN']);
		$prof->setVal(dbProfile5C::FLD_PHOTOUSE_M ,$postVals['useML']);


		$photoShow1 = $postVals['photoShow'];
		if(strcmp($photoShow1 ,'P') == 0) {
			$photoShow = dbProfile5C::PHOTO_SHOW_NP;
		}
		if(strcmp($photoShow1 ,'O') == 0) {
			$photoShow = dbProfile5C::PHOTO_SHOW_OK;
		}
		if(strcmp($photoShow1 ,'G') == 0) {
			$photoShow = dbProfile5C::PHOTO_SHOW_NG;
		}
		if(strcmp($photoShow1 ,'N') == 0) {
			$photoShow = dbProfile5C::PHOTO_SHOW_NOT;
		}
		$prof->setVal(dbProfile5C::FLD_PHOTO_SHOW ,$photoShow);


		/***** 写真ファイル *****/
		$path = realpath(dirname(__FILE__) . '/../../..') . '/photos/' . $dir;

		/*** 新規なら写真格納ディレクトリを作成 ***/
			/*if(strcmp($newProf ,'new') == 0) {*/
		if(!is_dir($path)) {
			mkdir($path);
		}

		if(isset($_FILES['attF1'])) {
			copyImgFile($_FILES['attF1'] ,$path , $dir . '1');
			$fileExt = pathinfo($_FILES['attF1']["name"], PATHINFO_EXTENSION);
			$prof->setVal(dbProfile5C::FLD_PHOTOEXT_1 ,$fileExt);
		}
		if(isset($_FILES['attF2'])) {
			copyImgFile($_FILES['attF2'] ,$path , $dir . '2');
			$fileExt = pathinfo($_FILES['attF2']["name"], PATHINFO_EXTENSION);
			$prof->setVal(dbProfile5C::FLD_PHOTOEXT_2 ,$fileExt);
		}
		if(isset($_FILES['attF3'])) {
			copyImgFile($_FILES['attF3'] ,$path , $dir . '3');
			$fileExt = pathinfo($_FILES['attF3']["name"], PATHINFO_EXTENSION);
			$prof->setVal(dbProfile5C::FLD_PHOTOEXT_3 ,$fileExt);
		}
		if(isset($_FILES['attF4'])) {
			copyImgFile($_FILES['attF4'] ,$path , $dir . '4');
			$fileExt = pathinfo($_FILES['attF4']["name"], PATHINFO_EXTENSION);
			$prof->setVal(dbProfile5C::FLD_PHOTOEXT_4 ,$fileExt);
		}
		if(isset($_FILES['attF5'])) {
			copyImgFile($_FILES['attF5'] ,$path , $dir . '5');
			$fileExt = pathinfo($_FILES['attF5']["name"], PATHINFO_EXTENSION);
			$prof->setVal(dbProfile5C::FLD_PHOTOEXT_5 ,$fileExt);
		}

		if(isset($_FILES['attML'])) {
			copyImgFile($_FILES['attML'] ,$path , $dir . '-m');
			$fileExt = pathinfo($_FILES['attML']["name"], PATHINFO_EXTENSION);
			$prof->setVal(dbProfile5C::FLD_PHOTOEXT_M ,$fileExt);
		}
		if(isset($_FILES['attTN'])) {
			copyImgFile($_FILES['attTN'] ,$path , $dir . '-s');
			$fileExt = pathinfo($_FILES['attTN']["name"], PATHINFO_EXTENSION);
			$prof->setVal(dbProfile5C::FLD_PHOTOEXT_S ,$fileExt);
		}

		if(strcmp($newProf ,'edit') == 0) {
			/*** 既存 ***/
			$prof->upd($dir);
		} else {
			/*** 新規 ***/
			$prof->add($dir);
		}
	}



	function setWork($postVals) {

		$branchNo = $postVals['branchNo'];

		$newProf  = $postVals['newProf'];
		$dir      = $postVals['profDir'];			/* ディレクトリ */

		$works = new dbWorks5C($branchNo);

		/***** 週間出勤表 *****/
		$works->setVal(dbWorks5C::FLD_SUN_F ,$postVals['sunF']);
		$works->setVal(dbWorks5C::FLD_SUN_T ,$postVals['sunT']);
		$works->setVal(dbWorks5C::FLD_SUN_M ,$postVals['sunM']);

		$works->setVal(dbWorks5C::FLD_MON_F ,$postVals['monF']);
		$works->setVal(dbWorks5C::FLD_MON_T ,$postVals['monT']);
		$works->setVal(dbWorks5C::FLD_MON_M ,$postVals['monM']);

		$works->setVal(dbWorks5C::FLD_TUE_F ,$postVals['tueF']);
		$works->setVal(dbWorks5C::FLD_TUE_T ,$postVals['tueT']);
		$works->setVal(dbWorks5C::FLD_TUE_M ,$postVals['tueM']);

		$works->setVal(dbWorks5C::FLD_WED_F ,$postVals['wedF']);
		$works->setVal(dbWorks5C::FLD_WED_T ,$postVals['wedT']);
		$works->setVal(dbWorks5C::FLD_WED_M ,$postVals['wedM']);

		$works->setVal(dbWorks5C::FLD_THU_F ,$postVals['thuF']);
		$works->setVal(dbWorks5C::FLD_THU_T ,$postVals['thuT']);
		$works->setVal(dbWorks5C::FLD_THU_M ,$postVals['thuM']);

		$works->setVal(dbWorks5C::FLD_FRI_F ,$postVals['friF']);
		$works->setVal(dbWorks5C::FLD_FRI_T ,$postVals['friT']);
		$works->setVal(dbWorks5C::FLD_FRI_M ,$postVals['friM']);

		$works->setVal(dbWorks5C::FLD_SAT_F ,$postVals['satF']);
		$works->setVal(dbWorks5C::FLD_SAT_T ,$postVals['satT']);
		$works->setVal(dbWorks5C::FLD_SAT_M ,$postVals['satM']);


		/***** 予定外 *****/
		$works->setVal(dbWorks5C::FLD_DIFF1_D ,$postVals['diffDate1']);
		$works->setVal(dbWorks5C::FLD_DIFF1_F ,$postVals['diff1F']);
		$works->setVal(dbWorks5C::FLD_DIFF1_T ,$postVals['diff1T']);
		$works->setVal(dbWorks5C::FLD_DIFF1_M ,$postVals['diff1M']);

		$works->setVal(dbWorks5C::FLD_DIFF2_D ,$postVals['diffDate2']);
		$works->setVal(dbWorks5C::FLD_DIFF2_F ,$postVals['diff2F']);
		$works->setVal(dbWorks5C::FLD_DIFF2_T ,$postVals['diff2T']);
		$works->setVal(dbWorks5C::FLD_DIFF2_M ,$postVals['diff2M']);

		$works->setVal(dbWorks5C::FLD_DIFF3_D ,$postVals['diffDate3']);
		$works->setVal(dbWorks5C::FLD_DIFF3_F ,$postVals['diff3F']);
		$works->setVal(dbWorks5C::FLD_DIFF3_T ,$postVals['diff3T']);
		$works->setVal(dbWorks5C::FLD_DIFF3_M ,$postVals['diff3M']);

		$works->setVal(dbWorks5C::FLD_DIFF4_D ,$postVals['diffDate4']);
		$works->setVal(dbWorks5C::FLD_DIFF4_F ,$postVals['diff4F']);
		$works->setVal(dbWorks5C::FLD_DIFF4_T ,$postVals['diff4T']);
		$works->setVal(dbWorks5C::FLD_DIFF4_M ,$postVals['diff4M']);

		$works->setVal(dbWorks5C::FLD_DIFF5_D ,$postVals['diffDate5']);
		$works->setVal(dbWorks5C::FLD_DIFF5_F ,$postVals['diff5F']);
		$works->setVal(dbWorks5C::FLD_DIFF5_T ,$postVals['diff5T']);
		$works->setVal(dbWorks5C::FLD_DIFF5_M ,$postVals['diff5M']);

		$works->setVal(dbWorks5C::FLD_DIFF6_D ,$postVals['diffDate6']);
		$works->setVal(dbWorks5C::FLD_DIFF6_F ,$postVals['diff6F']);
		$works->setVal(dbWorks5C::FLD_DIFF6_T ,$postVals['diff6T']);
		$works->setVal(dbWorks5C::FLD_DIFF6_M ,$postVals['diff6M']);

		$works->setVal(dbWorks5C::FLD_DIFF7_D ,$postVals['diffDate7']);
		$works->setVal(dbWorks5C::FLD_DIFF7_F ,$postVals['diff7F']);
		$works->setVal(dbWorks5C::FLD_DIFF7_T ,$postVals['diff7T']);
		$works->setVal(dbWorks5C::FLD_DIFF7_M ,$postVals['diff7M']);

		$db = $works->handle;

		$where =
			dbWorks5C::FLD_BRANCH_NO . '=' . $branchNo . ' and ' .
			dbWorks5C::FLD_DIR       . '=' . $db->setQuote($dir);

		$exist = $db->existRec(dbWorks5C::TABLE_NAME ,$where);

				/*if(strcmp($newProf ,'edit') == 0) {*/
		if($exist) {
			/*** 既存 ***/
			$works->upd($dir);
		} else {
			/*** 新規 ***/
			$prof = new dbProfile5C($branchNo);
			$profVal = $prof->get($dir);

			$disp = $profVal[dbProfile5C::FLD_DISP];
			if(strcmp($disp ,dbProfile5C::DISP_ON) == 0) {
				$dispVal = dbProfile5C::DISP_ON;
			} else {
				$dispVal = dbProfile5C::DISP_OFF;
			}
			$works->add($dir ,$dispVal);
		}
	}

	function copyImgFile($imgFile ,$path ,$dir) {

		$fileExt = pathinfo($imgFile["name"], PATHINFO_EXTENSION);	/*** 拡張子 ***/
		$destFileName = $path . '/' . $dir . '.' . $fileExt;
		if(is_file($destFileName)) {
			clearstatcache();
		}

		move_uploaded_file($imgFile["tmp_name"] ,$destFileName);
	}
?>
