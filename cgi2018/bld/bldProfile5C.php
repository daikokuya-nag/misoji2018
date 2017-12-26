<?php
/*************************
プロファイルリスト Version 1.0
PHP5
2016 Feb. 20 ver 1.0
*************************/

	require_once dirname(__FILE__) . '/../db/dbProfile5C.php';
	require_once dirname(__FILE__) . '/../db/dbWorks5C.php';
	require_once dirname(__FILE__) . '/../photo5C.php';
	require_once dirname(__FILE__) . '/../proviTag5C.php';
	require_once dirname(__FILE__) . '/../dateTime5C.php';
	require_once dirname(__FILE__) . '/../strings5C.php';
	require_once dirname(__FILE__) . '/../logFile5C.php';

class bldProfile5C {

	/***** テンプレートキーワード *****/
	const BEG_LINE  = '<!-- LIST_BEG -->';	/* プロファイル出力開始位置 */
	const END_LINE  = '<!-- LIST_END -->';	/* プロファイル出力終了位置 */

	const KWD_PROF_DIR_S   = '<!-- PROF_DIR -->';			/* プロファイルディレクトリ */
	const KWD_NAME_S       = '<!-- PROF_NAME -->';			/* 名前 */
	const KWD_AGE_S        = '<!-- PROF_AGE -->';			/* 年齢 */
	const KWD_BIRTHDATE_S  = '<!-- PROF_BIRTHDATE -->';		/* 誕生日 */
	const KWD_ZODIAC_S     = '<!-- PROF_ZODIAC -->';		/* 星座 */
	const KWD_BLOODTYPE_S  = '<!-- PROF_BLOODTYPE -->';		/* 血液型 */

	const KWD_HEIGHT_S     = '<!-- PROF_HEIGHT -->';		/* 身長 */
	const KWD_SIZES_S      = '<!-- PROF_SIZES -->';			/* スリーサイズ */

	const KWD_WORK_TIME_S  = '<!-- PROF_WORK_TIME -->';		/* 出勤時間 */
	const KWD_WORK_DAY_S   = '<!-- PROF_WORK_DAY -->';		/* 出勤日 */
	const KWD_REST_DAY_S   = '<!-- PROF_REST_DAY -->';		/* 公休日 */
	const KWD_COMMENT_S    = '<!-- PROF_COMMENT -->';		/* コメント */

	/*** 強制出力 ***/
	const KWD_NAME_F_S       = '<!-- PROF_NAME_F -->';			/* 名前 */
	const KWD_COMMENT_F_S    = '<!-- PROF_COMMENT_F -->';		/* コメント */


	const KWD_DIARY_URI_S  = '<!-- PROF_DIARY_URI -->';		/* 日記URI */
	const KWD_NEW_FACE_S   = '<!-- PROF_NEW_FACE -->';		/* 新人 */

	const KWD_DIR_FOR_DIARY_S  = '<!-- PROF_DIR_FOR_DIARY -->';		/* 新人 */

	/*** 写真 ***/
	const KWD_PHOTO_SHOW_L_S    = '<!-- PROFILE_DISPLAY_PHOTO_L -->';	/* 写真表示 */
	const KWD_PHOTO_SHOW_M_S    = '<!-- PROFILE_DISPLAY_PHOTO_M -->';	/* 写真表示 */
	const KWD_PHOTO_SHOW_S_S    = '<!-- PROFILE_DISPLAY_PHOTO_S -->';	/* 写真表示 */

	const KWD_PHOTO_NO_S        = '<!-- PROF_PHOTO_NO -->';			/* 写真No */

	const KWD_PHOTO_SELE_NO_S   = '<!-- PROF_PHOTO_SELE_NO -->';	/* 写真No */
	const KWD_PHOTO_SELE_BTN_S  = '<!-- PROF_PHOTO_SELE_BTN -->';	/* 写真選択ボタン */


	const KWD_PHOTOEXT_S_S  = '<!-- PROF_PHOTOEXT_S -->';			/* サムネイル拡張子 */

	const KWD_PHOTO_SHOW_OK   = '<!-- PROF_PHOTO_SHOW_SHOW -->';	/* 表示可 */
	const KWD_PHOTO_SHOW_NG   = '<!-- PROF_PHOTO_SHOW_NG -->';		/* 写真NG */
	const KWD_PHOTO_SHOW_NP   = '<!-- PROF_PHOTO_SHOW_NP -->';		/* 写真準備中 */
	const KWD_PHOTO_SHOW_NOT  = '<!-- PROF_PHOTO_SHOW_NOT -->';		/* 写真なし */


	/***** オススメJS *****/
	const RECOMM_BEG_LINE = '/***** LIST_BEG *****/';	/* JSプロファイルリスト出力開始位置 */
	const RECOMM_END_LINE = '/***** LIST_END *****/';	/* JSプロファイルリスト出力終了位置 */

	const RECOMM_PROF_DIR   = 'PROF_DIR';			/* プロファイルディレクトリ */
	const RECOMM_NAME       = 'PROF_NAME';			/* 名前 */
	const RECOMM_PHOTO_TN   = 'PROF_PHOTO_NAME_TN';	/* 写真ファイル名 */
	const RECOMM_PHOTO_SHOW = 'PROF_PHOTO_SHOW';	/* 写真表示区分 */


	/***** TOPページ *****/
	const RECOMM_JS_VER = 'JS_VER';			/* オススメJSのバージョン */


	var $photo;


	/********************
	ニュース埋め込み用リストタグ構築
	パラメータ：店No
	　　　　　　プロファイルデータ
	戻り値　　：タグ
	********************/
	function bldNewsList($branchNo ,$list) {

		$ret['title'] = '<tr id="profSeqListHTR">' .
/*			'<th class="dir">ディレクトリ</tg>' . */
			'<th class="name">名前</th>'        .
			'</tr>';

		$ret['data' ] = '';
		$profList = $list['profInfo'];
		$listMax = $list['count'];

		for($i=0 ;$i<$listMax ;$i++) {
			$ret['data'] = $ret['data'] . $this->bldNews1($profList[$i] ,true);
		}

		return $ret;
	}

	/********************
	1行分のニュースリストタグ構築
	パラメータ：リスト
	　　　　　　trの有無
	戻り値　　：タグ
	********************/
	function bldNews1($prof1 ,$showTR) {

		if($showTR) {
			$trBeg = '<tr>';
			$trEnd = '</tr>';
		} else {
			$trBeg = '';
			$trEnd = '';
		}

		$dirBtn = '<input type="button" value="' . $prof1[dbProfile5C::FLD_NAME] . '" onclick="setProfileLink(\'' . $prof1[dbProfile5C::FLD_DIR] . '\')" class="profileLinkBtn" />';

		$ret = $trBeg .
			'<td>' . $dirBtn . '</td>' .
/*			'<td>' . $prof1[PROF_NAME_S] . '</td>' .*/
			$trEnd;

		return $ret;
	}


	/*******************************************************************************************************************************/
	/********************
	表示順リストタグ構築
	パラメータ：店No
	　　　　　　プロファイルデータ
	戻り値　　：タグ
	********************/
	function bldSeqList($branchNo ,$list) {

		$this->photo = new photo5C();
		$photo = $this->photo;
		$photo->getAllDirPhoto();

		$ret['title'] = '<tr class="nodrop nodrag" id="profSeqListHTR">' .
			'<th class="disp">表示</th>'         .
			'<th class="dir">識別子</th>'        .
			'<th class="name">名前</th>'         .
			'<th class="workRest">コメント</th>' .
			'<th class="edit">編集</th>'         .
			'</tr>';

		$ret['data' ] = '';
		$profList = $list['profInfo'];
		$listMax = $list['count'];

			$logStr = '読み込み時:';

		for($i=0 ;$i<$listMax ;$i++) {
			$ret['data'] = $ret['data'] . $this->bldSeqList1($profList[$i] ,$i ,true);

				$logStr = $logStr . $profList[$i][dbProfile5C::FLD_DIR] . ' ';
		}
			logFile5C::debug($logStr);

		return $ret;
	}

	/********************
	1行分のニュースリストタグ構築
	パラメータ：リスト
	　　　　　　行インデックス
	　　　　　　trの有無
	戻り値　　：タグ

	<div id="sort-1014" class="prof1 ui-state-default">
		<div class="tnOut"><img src="../photo/tnNP.jpg" width="110" height="145"></div>
		<div class="profItem profName">ともよ</div>
		<div class="profItem profEdit"><input type="button" value="編集" class="toEdit" onclick="editProf(1014)"></div>
	</div>
	********************/
	function bldSeqList1($prof1 ,$idx ,$showTR) {

		$profID = $prof1[dbProfile5C::FLD_DIR];

		$photo = $this->photo;
		$photoVal = $photo->getUsePhoto($profID ,array('TN'));

		/* 写真 */
		$photoUse = $photoVal['TN'];

		if(strcmp($photoUse ,dbProfile5C::PHOTO_SHOW_NG ) == 0) {	/* NG */
			$photoFile = '../photos/tnNG.jpg';
		}
		if(strcmp($photoUse ,dbProfile5C::PHOTO_SHOW_OK ) == 0) {
			$photoInfo = $photo->getPhotoInfo($profID ,'TN');
			$photoFile = '../photos/' . $profID . '/' . 'TN' . '.' . $photoInfo;
		}
		if(strcmp($photoUse ,dbProfile5C::PHOTO_SHOW_NP ) == 0) {	/* 準備中 */
			$photoFile = '../photos/tnNP.jpg';
		}
		if(strcmp($photoUse ,dbProfile5C::PHOTO_SHOW_NOT) == 0) {	/* 写真ナシ */
			$photoFile = '../photos/tnNP.jpg';
		}
		$photoStr = '<img src="' . $photoFile . '" width="110" height="145">';

		/* 名前 */
		$name = $prof1[dbProfile5C::FLD_NAME];

		/* 識別子 */
		$id = $prof1[dbProfile5C::FLD_DIR ];

		/* 編集ボタン */
		$editBtn = '<input type="button" value="　" style="font-size:0.9em;" onclick="editProf(\'' . $profID . '\')" />';

		/* 表示/非表示 */
		if(strcmp($prof1[dbProfile5C::FLD_DISP] ,dbProfile5C::DISP_ON) == 0) {
			$disp = ' checked';
		} else {
			$disp = '';
		}
		$dispCB = '<input type="checkbox" id="disp' . $profID . '" name="disp' . $profID . '" value="U"' . $disp . ' onchange="enableWriteProfSeq();" class="dispProfSW" />';

		$ret = '<div id="sort-' . $id . '" class="prof1 ui-state-default">' .
			'<div class="tnOut">' . $photoStr . '</div>' .
			'<div class="profItem profName">' . $name . '</div>' .
			'<div class="profItem profEdit"><div class="editBtn">' . $editBtn . '</div><div class="dispBtn">' . $dispCB . '</div></div>' .
			'</div>';

		return $ret;
	}





	/*******************************************************************************************************************************/
	/********************
	表示順リストタグ構築
	パラメータ：店No
	　　　　　　プロファイルデータ
	戻り値　　：タグ
	********************/
	function bldSeqList2($branchNo ,$list) {

		$ret['title'] = '<tr class="ui-state-default" id="profSeqListHTR2">' .
			'<th class="disp">表示</th>'         .
			'<th class="dir">識別子</th>'        .
			'<th class="name">名前</th>'         .
			'<th class="workRest">コメント</th>' .
			'<th class="edit">編集</th>'         .
			'</tr>';

		$ret['data' ] = '';
		$profList = $list['profInfo'];
		$listMax = $list['count'];

			$logStr = '読み込み時:';

		for($i=0 ;$i<$listMax ;$i++) {
			$ret['data'] = $ret['data'] . $this->bldSeqList21($profList[$i] ,$i ,true);

				$logStr = $logStr . $profList[$i][dbProfile5C::FLD_DIR] . ' ';
		}
			logFile5C::debug($logStr);

		return $ret;
	}

	/********************
	1行分のニュースリストタグ構築
	パラメータ：リスト
	　　　　　　行インデックス
	　　　　　　trの有無
	戻り値　　：タグ
	********************/
	function bldSeqList21($prof1 ,$idx ,$showTR) {

		$profID = $prof1[dbProfile5C::FLD_DIR];

		if($showTR) {
			$trBeg = '<tr id="' . $prof1[dbProfile5C::FLD_DIR ] . '" class="ui-state-default profSeqListD2TR">';		/* $idx     class="ui-state-default"    */
			$trEnd = '</tr>';
		} else {
			$trBeg = '';
			$trEnd = '';
		}

		if(strcmp($prof1[dbProfile5C::FLD_DISP] ,dbProfile5C::DISP_ON) == 0) {
			$disp = ' checked';
		} else {
			$disp = '';
		}
		$dispCB = '<input type="checkbox" id="disp' . $profID . '" name="disp' . $profID . '" value="U"' . $disp . ' onchange="enableWriteProfSeq2();" class="dispProfSW2" />';


		$workRest = $prof1[dbProfile5C::FLD_MASTERS_COMMENT];

		if(mb_strlen($workRest ,'UTF-8') >= 35) {
			$workRest = mb_substr($workRest ,0 ,35 ,'UTF-8');
		}


		$editBtn = '<input type="button" value="　" style="font-size:0.9em;" onclick="editProf(\'' . $profID . '\')" />';

		$ret = $trBeg .
			'<td class="disp">'     . $dispCB   . '</td>' .
			'<td class="dir">'      . $prof1[dbProfile5C::FLD_DIR ] . '</td>' .
			'<td class="name">'     . $prof1[dbProfile5C::FLD_NAME] . '</td>' .
			'<td class="workRest">' . $workRest . '</td>' .
			'<td class="edit">'     . $editBtn  . '</td>' .
			$trEnd;

		return $ret;
	}







	function bldWork($workByDate) {

		$tagListT = '';	/***** 曜日ごと *****/
		$tagListD = '';
		$tagDiffT = '';	/***** 日毎の差分 *****/
		$tagDiffD = '';

		$tagIdx = 0;
		foreach($workByDate as $day1 => $times) {
			/***** 各曜日の出勤予定 *****/
			/*** 曜日 ***/
			$dow = $times[dbWorks5C::IDX_DOW];
			$dowStr = dateTime5C::getDOWStr($dow);

			$dowClass = '';
			if($dow == 0) {
				$dowClass = ' dowSun';
			}
			if($dow == 6) {
				$dowClass = ' dowSat';
			}

			/*** 出勤時刻 ***/
			if(strlen($times[dbWorks5C::IDX_F]) >= 1) {
				$fromExp = explode(':' ,$times[dbWorks5C::IDX_F]);
				$from = $fromExp[0] . ':' . $fromExp[1];
			} else {
				$from = '';
			}

			/*** 退勤時刻 ***/
			if(strlen($times[dbWorks5C::IDX_T]) >= 1) {
				if(strcmp($times[dbWorks5C::IDX_T] ,'LAST') == 0) {
					$to = $times[dbWorks5C::IDX_T];
				} else {
					$toExp = explode(':' ,$times[dbWorks5C::IDX_T]);
					$to = $toExp[0] . ':' . $toExp[1];
				}
			} else {
				$to = '';
			}

			$mode = $times[dbWorks5C::IDX_M];
			$timeSeleTag = $this->setTimeSelector('workDef' . $dow ,$from ,$to ,'');


			$seleName = 'workSele' . $dow;
			$seleID1   = $seleName . dbWorks5C::WORK_MODE_RECEPT;
			$seleID2   = $seleName . dbWorks5C::WORK_MODE_TO;

			$checked1 = '';
			$checked2 = ' checked';
			if(strcmp($mode ,dbWorks5C::WORK_MODE_RECEPT) == 0) {
				$checked1 = ' checked';
			}

			$modeTag =
				'<div class="endModeSele">' .
				'<input type="radio" id="' . $seleID1 . '"  name="' . $seleName . '" value="' . dbWorks5C::WORK_MODE_RECEPT . '"' . $checked1 . ' class="seleTo">' .
				'<label for="' . $seleID1 . '">受付</label><br />' .
				'<input type="radio" id="' . $seleID2 . '"  name="' . $seleName . '" value="' . dbWorks5C::WORK_MODE_TO     . '"' . $checked2 . ' class="seleTo">' .
				'<label for="' . $seleID2 . '">まで</label>' .
				'</div>';

			$tagListT = $tagListT . '<td class="dowStr' . $dowClass . '">' . $dowStr . '</td>';
			$tagListD = $tagListD . '<td>' . $timeSeleTag['from'] . '～' . $timeSeleTag['to'] . $modeTag . '</td>';


			/***** 予定外 *****/
			if(isset($times[dbWorks5C::IDX_DIFF])) {
				$diff = $times[dbWorks5C::IDX_DIFF];

				if(strlen($diff[dbWorks5C::IDX_DIFF_F]) >= 1) {
					if(strings5C::mb_existStr($diff[dbWorks5C::IDX_DIFF_F] ,'休') >= 0) {
						$from = $diff[dbWorks5C::IDX_DIFF_F];
					} else {
						$fromExp = explode(':' ,$diff[dbWorks5C::IDX_DIFF_F]);
						$from = $fromExp[0] . ':' . $fromExp[1];
					}
				} else {
					$from = '';
				}

				if(strlen($diff[dbWorks5C::IDX_DIFF_T]) >= 1) {
					if(strings5C::mb_existStr($diff[dbWorks5C::IDX_DIFF_T] ,'休') >= 0) {
						$to = $diff[dbWorks5C::IDX_DIFF_T];
					} else {
						$toExp = explode(':' ,$diff[dbWorks5C::IDX_DIFF_T]);
						$to = $toExp[0] . ':' . $toExp[1];
					}
				} else {
					$to = '';
				}

				$mode = $diff[dbWorks5C::IDX_DIFF_M];
			} else {
				$from = '';
				$to   = '';
				$mode = dbWorks5C::WORK_MODE_TO;
			}

			$timeSeleTag = $this->setTimeSelector('workDiff' . $tagIdx ,$from ,$to ,'休');

			$seleName = 'workDiff' . $tagIdx;
			$seleID1   = $seleName . dbWorks5C::WORK_MODE_RECEPT;
			$seleID2   = $seleName . dbWorks5C::WORK_MODE_TO;

			$checked1 = '';
			$checked2 = ' checked';
			if(strcmp($mode ,dbWorks5C::WORK_MODE_RECEPT) == 0) {
				$checked1 = ' checked';
			}

			$modeTag =
				'<div class="endModeSele">' .
				'<input type="radio" id="' . $seleID1 . '"  name="' . $seleName . '" value="' . dbWorks5C::WORK_MODE_RECEPT . '"' . $checked1 . ' class="seleTo">' .
				'<label for="' . $seleID1 . '">受付</label><br />' .
				'<input type="radio" id="' . $seleID2 . '"  name="' . $seleName . '" value="' . dbWorks5C::WORK_MODE_TO     . '"' . $checked2 . ' class="seleTo">' .
				'<label for="' . $seleID2 . '">まで</label>' .
				'</div>';

			$dateTag = '<input type="hidden" id="dateList' . $tagIdx . '" value="' . $day1 . '">';

			$tagDiffT = $tagDiffT . '<td class="dayStr' . $dowClass . '">' . $day1 . '</td>';
			$tagDiffD = $tagDiffD . '<td>' . $timeSeleTag['from'] . '～' . $timeSeleTag['to'] . $modeTag . $dateTag . '</td>';

			$tagIdx++;
		}

		$ret['workList'] = '<tr>' . $tagListT . '</tr>' . '<tr>' . $tagListD . '</tr>';
		$ret['workDiff'] = '<tr>' . $tagDiffT . '</tr>' . '<tr>' . $tagDiffD . '</tr>';

		return $ret;
	}


	function setTimeSelector($id ,$from ,$to ,$topStr) {

		if(strlen($topStr) >= 1) {
			$fromList = array(
				'' ,$topStr ,
				' 9:00' ,' 9:30' ,
				'10:00' ,'10:30' ,'11:00' ,'11:30' ,
				'12:00' ,'12:30' ,'13:00' ,'13:30' ,
				'14:00' ,'14:30' ,'15:00' ,'15:30' ,
				'16:00' ,'16:30' ,'17:00' ,'17:30' ,
				'18:00' ,'18:30' ,'19:00' ,'19:30' ,
				'20:00' ,'20:30' ,'21:00' ,'21:30' ,
				'22:00' ,'22:30' ,'23:00' ,'23:30' ,
				'24:00');

			$toList = array(
				'' ,$topStr ,
				' 9:00' ,' 9:30' ,
				'10:00' ,'10:30' ,'11:00' ,'11:30' ,
				'12:00' ,'12:30' ,'13:00' ,'13:30' ,
				'14:00' ,'14:30' ,'15:00' ,'15:30' ,
				'16:00' ,'16:30' ,'17:00' ,'17:30' ,
				'18:00' ,'18:30' ,'19:00' ,'19:30' ,
				'20:00' ,'20:30' ,'21:00' ,'21:30' ,
				'22:00' ,'22:30' ,'23:00' ,'23:30' ,
				'24:00' ,'LAST');
		} else {
			$fromList = array(
				'' ,
				' 9:00' ,' 9:30' ,
				'10:00' ,'10:30' ,'11:00' ,'11:30' ,
				'12:00' ,'12:30' ,'13:00' ,'13:30' ,
				'14:00' ,'14:30' ,'15:00' ,'15:30' ,
				'16:00' ,'16:30' ,'17:00' ,'17:30' ,
				'18:00' ,'18:30' ,'19:00' ,'19:30' ,
				'20:00' ,'20:30' ,'21:00' ,'21:30' ,
				'22:00' ,'22:30' ,'23:00' ,'23:30' ,
				'24:00');

			$toList = array(
				'' ,
				' 9:00' ,' 9:30' ,
				'10:00' ,'10:30' ,'11:00' ,'11:30' ,
				'12:00' ,'12:30' ,'13:00' ,'13:30' ,
				'14:00' ,'14:30' ,'15:00' ,'15:30' ,
				'16:00' ,'16:30' ,'17:00' ,'17:30' ,
				'18:00' ,'18:30' ,'19:00' ,'19:30' ,
				'20:00' ,'20:30' ,'21:00' ,'21:30' ,
				'22:00' ,'22:30' ,'23:00' ,'23:30' ,
				'24:00' ,'LAST');
		}

		/*** 開始時刻の選択肢 ***/
		$fromSele = '';
		foreach($fromList as $sele) {
			$selected = '';
			if(strcmp($sele ,$from) == 0) {
				$selected = ' selected';
			}
			$fromSele = $fromSele . '<option value="' . $sele . '"' . $selected . '>' . $sele . '</option>';
		}

		/*** 終了時刻の選択肢 ***/
		$toSele = '';
		foreach($toList as $sele) {
			$selected = '';
			if(strcmp($sele ,$to) == 0) {
				$selected = ' selected';
			}
			$toSele = $toSele . '<option value="' . $sele . '"' . $selected . '>' . $sele . '</option>';
		}


		$tagID = $id . 'F';
		$ret['from'] = '<select name="' . $tagID . '" id="' . $tagID . '" class="seleTime">' . $fromSele . '</select>';

		$tagID = $id . 'T';
		$ret['to'  ] = '<select name="' . $tagID . '" id="' . $tagID . '" class="seleTime">' . $toSele . '</select>';

		return $ret;
	}



	/*******************************************************************************************************************************/
	/*******************************************************************************************************************************/
	/********************
	表示順リストタグ構築
	パラメータ：店No
	　　　　　　プロファイルデータ
	戻り値　　：タグ
	********************/
	function bldWorkList($branchNo ,$list ,$days) {

		$dayList = '';
		$strList = '';
		foreach($days as $day1 => $times) {

			$dayList = $dayList . '<th class="workDay" colspan="2">' . $day1 . '</th>';
				/*
					$dayList = $dayList . '<th class="workDay">' . $day1 . '</th>';
				*/

			$strList = $strList . '<th>出勤</th>' . '<th>退勤</th>';
		}

		$ret['title'] = '<tr>' . '<th class="workName">名前</th>' . $dayList . '</tr>';

		$ret['data' ] = '';
		$profList = $list['workInfo'];
		$listMax = $list['count'];

		for($i=1 ;$i<=$listMax ;$i++) {
			$ret['data'] = $ret['data'] . $this->bldWorkList1($profList[$i] ,$i ,true ,$days);
		}

		return $ret;
	}

	/********************
	1行分のリストタグ構築
	パラメータ：リスト
	　　　　　　行インデックス
	　　　　　　trの有無
	戻り値　　：タグ
	********************/
	function bldWorkList1($prof1 ,$idx ,$showTR ,$days) {

		$profID   = $prof1[dbProfile5C::FLD_DIR ];
		$profName = $prof1[PROF_NAME_S];
		$tiemData = $prof1['times'];

		if($showTR) {
			$trBeg = '<tr>';		/* $idx */
			$trEnd = '</tr>';
		} else {
			$trBeg = '';
			$trEnd = '';
		}

		$ret = $trBeg . '<td class="workName">' . $profName . '</td>';
		foreach($days as $day1 => $times) {
			$times = $tiemData[$day1];

			$timeSele = $this->setSelector($profID ,$day1 ,$times[PROF_WORK_FROM_S] ,$times[PROF_WORK_TO_S]);

			$seleToIDN = 'SE' . '--' . $profID . '--' . $day1;
			$seleToID1 = $seleToIDN . '--' . PROF_WORK_MODE_RECEPT;
			$seleToID2 = $seleToIDN . '--' . PROF_WORK_MODE_TO;

			$seleMode[PROF_WORK_MODE_RECEPT] = '';
			$seleMode[PROF_WORK_MODE_TO] = '';

			$mode = $times[PROF_WORK_MODE_S];
			if(strcmp($mode ,PROF_WORK_MODE_RECEPT) == 0) {
				$seleMode[PROF_WORK_MODE_RECEPT] = ' checked';
			}

			if(strcmp($mode ,PROF_WORK_MODE_TO) == 0) {
				$seleMode[PROF_WORK_MODE_TO] = ' checked';
			}

			$toSele = 
				'<input type="radio" id="' . $seleToID1 . '"  name="' . $seleToIDN . '" value="R"' . $seleMode[PROF_WORK_MODE_RECEPT] . ' class="seleTo">' .
				'<label for="' . $seleToID1 . '">受付</label><br />' .
				'<input type="radio" id="' . $seleToID2 . '"  name="' . $seleToIDN . '" value="T"' . $seleMode[PROF_WORK_MODE_TO] . ' class="seleTo">' .
				'<label for="' . $seleToID2 . '">まで</label>';

			$ret = $ret . 
					'<td class="sepFromToFrom">' . $timeSele['from'] . '～' . '</td>' .
					'<td class="sepFromToTo">'   . $timeSele['to'  ] . '<br />' . $toSele  . '</td>';

					/*
				'<td class="sepFromToFrom">' . $timeSele['from'] . '～' . $timeSele['to'  ] . '<br />' . $toSele  . '</td>';
					*/

		}
		$ret = $ret . $trEnd;

		return $ret;
	}


	function setTimeEnter($day1 ,$str ,$profID ,$id) {

		$tagID = 'WK' . '--' . $profID . '--' . $day1 . '--' . $id;
		$param = '\'' . $profID . '\',\'' . $day1 . '\',\'' . $id . '\'';
		$ret = '<input type="text" id="' . $tagID . '" name="' . $tagID . '" value="' . $str . '"  class="workTM" maxlength="2" size="2" onblur="setWorkTime(' . $param . ')" />';

		return $ret;
	}



	function setSelector($profID ,$day1 ,$fromTime ,$toTime) {

		$fromList = array(
			'' ,
			' 9:00' ,' 9:30' ,
			'10:00' ,'10:30' ,'11:00' ,'11:30' ,
			'12:00' ,'12:30' ,'13:00' ,'13:30' ,
			'14:00' ,'14:30' ,'15:00' ,'15:30' ,
			'16:00' ,'16:30' ,'17:00' ,'17:30' ,
			'18:00' ,'18:30' ,'19:00' ,'19:30' ,
			'20:00' ,'20:30' ,'21:00' ,'21:30' ,
			'22:00' ,'22:30' ,'23:00' ,'23:30' ,
			'24:00');

		$toList = array(
			'' ,
			' 9:00' ,' 9:30' ,
			'10:00' ,'10:30' ,'11:00' ,'11:30' ,
			'12:00' ,'12:30' ,'13:00' ,'13:30' ,
			'14:00' ,'14:30' ,'15:00' ,'15:30' ,
			'16:00' ,'16:30' ,'17:00' ,'17:30' ,
			'18:00' ,'18:30' ,'19:00' ,'19:30' ,
			'20:00' ,'20:30' ,'21:00' ,'21:30' ,
			'22:00' ,'22:30' ,'23:00' ,'23:30' ,
			'24:00' ,'LAST');


		$fromSele = '';
		foreach($fromList as $sele) {
			$selected = '';
			if(strcmp($sele ,$fromTime) == 0) {
				$selected = ' selected';
			}
			$fromSele = $fromSele . '<option value="' . $sele . '"' . $selected . '>' . $sele . '</option>';
		}

		$toSele = '';
		foreach($toList as $sele) {
			$selected = '';
			if(strcmp($sele ,$toTime) == 0) {
				$selected = ' selected';
			}
			$toSele = $toSele . '<option value="' . $sele . '"' . $selected . '>' . $sele . '</option>';
		}


		$tagID = 'WK' . '--' . $profID . '--' . $day1 . '--' . PROF_WORK_FROM_S;
		$ret['from'] = '<select name="' . $tagID . '" id="' . $tagID . '" class="seleTime">' . $fromSele . '</select>';

		$tagID = 'WK' . '--' . $profID . '--' . $day1 . '--' . PROF_WORK_TO_S;
		$ret['to'  ] = '<select name="' . $tagID . '" id="' . $tagID . '" class="seleTime">' . $toSele . '</select>';

		return $ret;
	}





	/*******************************************************************************************************************************/
	/********************
	表示順リストタグ構築
	パラメータ：プロファイルデータ
	戻り値　　：タグ
	********************/
	function bldWorkListP($workList) {

		$tr1 = '<tr>';
		$tr2 = '<tr>';
		for($dayCnt=0 ;$dayCnt<=6 ;$dayCnt++) {

			$work1 = $workList[$dayCnt];
			$dayStr = $work1[PROF_WORK_DATE_S];

			$tr1 = $tr1 . '<td colspan="2">' . $dayStr . '</td>';

			$tagIDF = PROF_WORK_FROM_S . $dayCnt;	/*$dayStr;*/
			$tagIDT = PROF_WORK_TO_S   . $dayCnt;	/*$dayStr;*/

			$timeSele = $this->setSelectorP($work1[PROF_WORK_FROM_S] ,$work1[PROF_WORK_TO_S] ,$tagIDF ,$tagIDT);

			$seleToIDN = 'SE' . $dayCnt;	/*$dayStr;*/
			$seleToID1 = $seleToIDN . PROF_WORK_MODE_RECEPT;
			$seleToID2 = $seleToIDN . PROF_WORK_MODE_TO;


			$mode = $work1[PROF_WORK_MODE_S];

			$seleMode[PROF_WORK_MODE_RECEPT] = '';
			$seleMode[PROF_WORK_MODE_TO    ] = ' checked';
			if(strcmp($mode ,PROF_WORK_MODE_RECEPT) == 0) {
				$seleMode[PROF_WORK_MODE_RECEPT] = ' checked';
				$seleMode[PROF_WORK_MODE_TO    ] = '';
			}

			$dateStr = '<input type="hidden" id="date' . $dayCnt . '" value="' . $dayStr . '">';

			$toSele = 
				'<input type="radio" id="' . $seleToID1 . '"  name="' . $seleToIDN . '" value="R"' . $seleMode[PROF_WORK_MODE_RECEPT] . ' class="seleTo">' .
				'<label for="' . $seleToID1 . '">受付</label><br />' .
				'<input type="radio" id="' . $seleToID2 . '"  name="' . $seleToIDN . '" value="T"' . $seleMode[PROF_WORK_MODE_TO] . ' class="seleTo">' .
				'<label for="' . $seleToID2 . '">まで</label>';

			$tr2 = $tr2 .
					'<td class="sepFromToFrom">' . $timeSele['from'] . '～' . '</td>' .
					'<td class="sepFromToTo">'   . $timeSele['to'  ] . '<br />' . $toSele  . $dateStr . '</td>';
		}

		return $tr1 . '</tr>' . $tr2 . '</tr>';
	}


	function bldWorkDef($profData) {

		$now = time();
		for($dayCnt=0 ;$dayCnt<=6 ;$dayCnt++) {
			$paramStr = '+' . $dayCnt . ' day';
			$timeStr = strtotime($paramStr ,$now);

			/* $day = dateTime4C::getDOW($timeStr); */

			$format    = 'Y' . dateTimeCDATE_SEP . 'm' . dateTimeCDATE_SEP . 'd' . ' ' . 'H' . dateTimeCTIME_SEP . 'i' . dateTimeCTIME_SEP . 's';
			$dtStr     = date($format, $timeStr);

			$dowCD[$dayCnt ] = dateTime4C::getDOW($dtStr);		/* $timeStr */
			$dowStr[$dayCnt] = dateTime4C::getDOWStr($dowCD[$dayCnt ]);
			$dowIdx[$dayCnt] = dateTime4C::getDOWIdx($dowCD[$dayCnt ]);
		}


		$tr1 = '<tr>';
		$tr2 = '<tr>';
		for($dayCnt=0 ;$dayCnt<=6 ;$dayCnt++) {

			$tr1 = $tr1 . '<td colspan="2">' . $dowStr[$dayCnt] . '</td>';

			$timeSele = $this->setSelectorP($profData[$dowIdx[$dayCnt]['F']] ,$profData[$dowIdx[$dayCnt]['T']] ,$dowIdx[$dayCnt]['F'] ,$dowIdx[$dayCnt]['T']);

			$seleToIDN = $dowIdx[$dayCnt]['MO'];
			$seleToID1 = $dowIdx[$dayCnt]['MO'] . '--' . PROF_WORK_MODE_RECEPT;
			$seleToID2 = $dowIdx[$dayCnt]['MO'] . '--' . PROF_WORK_MODE_TO;

			$mode = $profData[$dowIdx[$dayCnt]['MO']];

			$seleMode[PROF_WORK_MODE_RECEPT] = '';
			if(strcmp($mode ,PROF_WORK_MODE_RECEPT) == 0) {
				$seleMode[PROF_WORK_MODE_RECEPT] = ' checked';
			}

			$seleMode[PROF_WORK_MODE_TO] = '';
			if(strcmp($mode ,PROF_WORK_MODE_TO) == 0) {
				$seleMode[PROF_WORK_MODE_TO] = ' checked';
			}

			$toSele = 
				'<input type="radio" id="' . $seleToID1 . '"  name="' . $seleToIDN . '" value="R"' . $seleMode[PROF_WORK_MODE_RECEPT] . ' class="seleTo">' .
				'<label for="' . $seleToID1 . '">受付</label><br />' .
				'<input type="radio" id="' . $seleToID2 . '"  name="' . $seleToIDN . '" value="T"' . $seleMode[PROF_WORK_MODE_TO] . ' class="seleTo">' .
				'<label for="' . $seleToID2 . '">まで</label>';

			$tr2 = $tr2 .
					'<td class="sepFromToFrom">' . $timeSele['from'] . '～' . '</td>' .
					'<td class="sepFromToTo">'   . $timeSele['to'  ] . '<br />' . $toSele  . '</td>';
		}

		return $tr1 . '</tr>' . $tr2 . '</tr>';
	}


	function bldWorkDefNoData($profData) {

		$now = time();
		for($dayCnt=0 ;$dayCnt<=6 ;$dayCnt++) {
			$paramStr = '+' . $dayCnt . ' day';
			$timeStr = strtotime($paramStr ,$now);

			/* $day = dateTime4C::getDOW($timeStr); */

			$format    = 'Y' . dateTimeCDATE_SEP . 'm' . dateTimeCDATE_SEP . 'd' . ' ' . 'H' . dateTimeCTIME_SEP . 'i' . dateTimeCTIME_SEP . 's';
			$dtStr     = date($format, $timeStr);

			$dowCD[$dayCnt ] = dateTime4C::getDOW($dtStr);		/* $timeStr */
			$dowStr[$dayCnt] = dateTime4C::getDOWStr($dowCD[$dayCnt ]);
			$dowIdx[$dayCnt] = dateTime4C::getDOWIdx($dowCD[$dayCnt ]);
		}


		$tr1 = '<tr>';
		$tr2 = '<tr>';
		for($dayCnt=0 ;$dayCnt<=6 ;$dayCnt++) {

			$tr1 = $tr1 . '<td colspan="2">' . $dowStr[$dayCnt] . '</td>';

			$timeSele = $this->setSelectorP($profData[$dowIdx[$dayCnt]['F']] ,$profData[$dowIdx[$dayCnt]['T']] ,$dowIdx[$dayCnt]['F'] ,$dowIdx[$dayCnt]['T']);

			$seleToIDN = $dowIdx[$dayCnt]['MO'];
			$seleToID1 = $dowIdx[$dayCnt]['MO'] . '--' . PROF_WORK_MODE_RECEPT;
			$seleToID2 = $dowIdx[$dayCnt]['MO'] . '--' . PROF_WORK_MODE_TO;

			$seleMode[PROF_WORK_MODE_RECEPT] = '';
			$seleMode[PROF_WORK_MODE_TO] = ' checked';

			$toSele = 
				'<input type="radio" id="' . $seleToID1 . '"  name="' . $seleToIDN . '" value="R"' . $seleMode[PROF_WORK_MODE_RECEPT] . ' class="seleTo">' .
				'<label for="' . $seleToID1 . '">受付</label><br />' .
				'<input type="radio" id="' . $seleToID2 . '"  name="' . $seleToIDN . '" value="T"' . $seleMode[PROF_WORK_MODE_TO] . ' class="seleTo">' .
				'<label for="' . $seleToID2 . '">まで</label>';

			$tr2 = $tr2 .
					'<td class="sepFromToFrom">' . $timeSele['from'] . '～' . '</td>' .
					'<td class="sepFromToTo">'   . $timeSele['to'  ] . '<br />' . $toSele  . '</td>';
		}

		return $tr1 . '</tr>' . $tr2 . '</tr>';
	}


	function setSelectorP($fromTime ,$toTime ,$tagIDF ,$tagIDT) {

		$fromList = array(
			'' , '休' ,
			'10:00' ,'10:30' ,'11:00' ,'11:30' ,
			'12:00' ,'12:30' ,'13:00' ,'13:30' ,
			'14:00' ,'14:30' ,'15:00' ,'15:30' ,
			'16:00' ,'16:30' ,'17:00' ,'17:30' ,
			'18:00' ,'18:30' ,'19:00' ,'19:30' ,
			'20:00' ,'20:30' ,'21:00' ,'21:30' ,
			'22:00' ,'22:30' ,'23:00' ,'23:30' ,
			'24:00');

		$toList = array(
			'' , '休' ,
			'10:00' ,'10:30' ,'11:00' ,'11:30' ,
			'12:00' ,'12:30' ,'13:00' ,'13:30' ,
			'14:00' ,'14:30' ,'15:00' ,'15:30' ,
			'16:00' ,'16:30' ,'17:00' ,'17:30' ,
			'18:00' ,'18:30' ,'19:00' ,'19:30' ,
			'20:00' ,'20:30' ,'21:00' ,'21:30' ,
			'22:00' ,'22:30' ,'23:00' ,'23:30' ,
			'24:00');


		$fromSele = '';
		foreach($fromList as $sele) {
			$selected = '';
			if(strcmp($sele ,$fromTime) == 0) {
				$selected = ' selected';
			}
			$fromSele = $fromSele . '<option value="' . $sele . '"' . $selected . '>' . $sele . '</option>';
		}

		$toSele = '';
		foreach($toList as $sele) {
			$selected = '';
			if(strcmp($sele ,$toTime) == 0) {
				$selected = ' selected';
			}
			$toSele = $toSele . '<option value="' . $sele . '"' . $selected . '>' . $sele . '</option>';
		}

		$ret['from'] = '<select name="' . $tagIDF . '" id="' . $tagIDF . '" class="seleTime">' . $fromSele . '</select>';
		$ret['to'  ] = '<select name="' . $tagIDT . '" id="' . $tagIDT . '" class="seleTime">' . $toSele   . '</select>';

		return $ret;
	}
}
?>
