<?php
/**
 * 出勤予定者生成
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */

	require_once dirname(__FILE__) . '/../db/dbWorks5C.php';
	require_once dirname(__FILE__) . '/../db/dbProfile5C.php';
	require_once dirname(__FILE__) . '/../dateTime5C.php';
	require_once dirname(__FILE__) . '/../siteConst5C.php';
	require_once dirname(__FILE__) . '/../fileName5C.php';
	require_once dirname(__FILE__) . '/../photo5C.php';

	require_once dirname(__FILE__) . '/../logFile5C.php';


class htmlWorker5C {

	/***** 写真表示 *****/
	const SHOW_PHOTO_SOON_SRC = 'photo/tnNP.jpg';
	const SHOW_ALT_SOON       = '準備中';

	const SHOW_PHOTO_NG_SRC = 'photo/tnNG.jpg';
	const SHOW_ALT_NG       = '顔出しNG';

	var $fldListD = array(dbWorks5C::FLD_DIFF1_D ,dbWorks5C::FLD_DIFF2_D ,dbWorks5C::FLD_DIFF3_D ,dbWorks5C::FLD_DIFF4_D ,dbWorks5C::FLD_DIFF5_D ,dbWorks5C::FLD_DIFF6_D ,dbWorks5C::FLD_DIFF7_D);
	var $fldListF = array(dbWorks5C::FLD_DIFF1_F ,dbWorks5C::FLD_DIFF2_F ,dbWorks5C::FLD_DIFF3_F ,dbWorks5C::FLD_DIFF4_F ,dbWorks5C::FLD_DIFF5_F ,dbWorks5C::FLD_DIFF6_F ,dbWorks5C::FLD_DIFF7_F);
	var $fldListT = array(dbWorks5C::FLD_DIFF1_T ,dbWorks5C::FLD_DIFF2_T ,dbWorks5C::FLD_DIFF3_T ,dbWorks5C::FLD_DIFF4_T ,dbWorks5C::FLD_DIFF5_T ,dbWorks5C::FLD_DIFF6_T ,dbWorks5C::FLD_DIFF7_T);
	var $fldListM = array(dbWorks5C::FLD_DIFF1_M ,dbWorks5C::FLD_DIFF2_M ,dbWorks5C::FLD_DIFF3_M ,dbWorks5C::FLD_DIFF4_M ,dbWorks5C::FLD_DIFF5_M ,dbWorks5C::FLD_DIFF6_M ,dbWorks5C::FLD_DIFF7_M);

	var $handle;
	var $workerDB;
	var $profDB;

	var $workerList;
	var $prof;

	/********************
	コンストラクタ(DB接続)
	パラメータ：-
	戻り値　　：-
	********************/
	function htmlWorker5C($handle=null) {

		if(is_null($handle)) {
			$this->handle = new sql5C();
		} else {
			$this->handle = $handle;
		}
	}

	/********************
	店Noセット
	パラメータ：店No
	戻り値　　：-
	********************/
	function setBranchNo($branchNo) {

		$this->branchNo = $branchNo;
	}


	/********************
	出勤データ取得
	パラメータ：-
	戻り値　　：-
	********************/
	function readWorkerList() {

		$workerDB   = new dbWorks5C($this->branchNo);
		$workerList = $workerDB->readForTop2();
		$handle     = $workerDB->getHandle();

		$profDB = new dbProfile5C($handle);
		$profDB->setBranchNo($this->branchNo);

		$this->workerList = $workerList;
		$this->workerDB   = $workerDB;
		$this->prorDB     = $profDB;

		$this->handle     = $handle;
	}


	function getWorkerList() {

		$prof = $this->prorDB;
		$daysList = dateTime5C::setDateList1W();

		$dirList = $this->workerList['workInfo'];
		$listMax = $this->workerList['count'   ];
		$dowDest = $this->workerList['dowDest' ];

		$workerList[0] = array();
		$workerList[1] = array();

		$tagIdx = 0;
		for($listIdx=0 ;$listIdx<$listMax ;$listIdx++) {
			$list1 = $dirList[$listIdx];
					/*$workInfo = $this->getWorkInfo($list1 ,$todayStr);*/
			$workInfo = $this->getWorkInfo2($list1 ,$daysList ,$dowDest);
			if(strlen($workInfo['FROM']) >= 1) {
				$tmp['DIR'      ] = $list1[dbWorks5C::FLD_DIR];
				$tmp['NAME'     ] = $list1[dbProfile5C::FLD_NAME];
				$tmp['PHOTOSHOW'] = $prof->getTNPhotoShow($tmp['DIR']);

				$tmp['DAYCLASS' ] = $workInfo['DAYCLASS'];
				$tmp['DAY'      ] = $workInfo['DAY'];
				$tmp['TIME'     ] = $workInfo['FROM'] . '～' . $workInfo['TO'];

				$tagIdx = $workInfo['TODAYIDX'];
				$workerList[$tagIdx][] = $tmp;
			}
		}

		$wivesTN = array(2 => 1 ,3 => 1 ,4 => 1 ,5 => 1 ,6 => 1 ,7 => 1 ,8 => 1 ,9 => 1);

		$tag[0] = '';
		$tag[1] = '';

		// 表示する写真
		$photoVal = new photo5C();
		$photoVal->getAllDirPhoto();
		$photoID  = array('TN');

		for($tagIdx=0 ;$tagIdx<=1 ;$tagIdx++) {
			$currList = $workerList[$tagIdx];
			$listMax = count($currList);
			for($listIdx=0 ;$listIdx<$listMax ;$listIdx++) {
				$workInfo = $currList[$listIdx];

				$dir      = $workInfo['DIR' ];
				$name     = $workInfo['NAME'];
				$workDate = $workInfo['DAY' ];
				$workTime = $workInfo['TIME'];

				$dayClass = $workInfo['DAYCLASS'];

				// 表示する写真
				$photoUse = $photoVal->getUsePhoto($dir ,$photoID);
				$tnCond   = $photoUse['TN']['cond'];
				if(isset($photoUse['TN']['fileName'])) {
					$tnFileName = $photoUse['TN']['fileName'];
				} else {
					$tnFileName = '';
				}

				$photoShow = $prof->getTNPhotoShow($dir);
				if(strcmp($tnCond ,dbProfile5C::PHOTO_SHOW_OK) == 0) {		/* 表示可 $photoShow['SHOW'] */
					$photoTagSrc = 'photo/' . $dir . '/' . $tnFileName . '.' . $photoShow['EXT'];
					$photoTagAlt = $name;
				}

				if(strcmp($tnCond ,dbProfile5C::PHOTO_SHOW_NG) == 0) {		/* 写真NG $photoShow['SHOW'] */
					$photoTagSrc = self::SHOW_PHOTO_NG_SRC;
					$photoTagAlt = self::SHOW_ALT_NG;
				}

				if(strcmp($tnCond ,dbProfile5C::PHOTO_SHOW_NP) == 0) {		/* 写真準備中 $photoShow['SHOW'] */
					$photoTagSrc = self::SHOW_PHOTO_SOON_SRC;
					$photoTagAlt = self::SHOW_ALT_SOON;
				}

				if(strcmp($tnCond ,dbProfile5C::PHOTO_SHOW_NOT) == 0) {		/* 写真なし $photoShow['SHOW'] */
					$photoTagSrc = self::SHOW_PHOTO_SOON_SRC;
					$photoTagAlt = self::SHOW_ALT_SOON;
				}


				$TNNo =
					'workerTN2' . $wivesTN[2] . ' ' . 'workerTN3' . $wivesTN[3] . ' ' . 'workerTN4' . $wivesTN[4] . ' ' . 'workerTN5' . $wivesTN[5] . ' ' .
					'workerTN6' . $wivesTN[6] . ' ' . 'workerTN7' . $wivesTN[7] . ' ' . 'workerTN8' . $wivesTN[8] . ' ' . 'workerTN9' . $wivesTN[9];

				if($wivesTN[2] >= 2) {
					$wivesTN[2] = 1;
				} else {
					$wivesTN[2]++;
				}

				if($wivesTN[3] >= 3) {
					$wivesTN[3] = 1;
				} else {
					$wivesTN[3]++;
				}

				if($wivesTN[4] >= 4) {
					$wivesTN[4] = 1;
				} else {
					$wivesTN[4]++;
				}

				if($wivesTN[5] >= 5) {
					$wivesTN[5] = 1;
				} else {
					$wivesTN[5]++;
				}

				if($wivesTN[6] >= 6) {
					$wivesTN[6] = 1;
				} else {
					$wivesTN[6]++;
				}

				if($wivesTN[7] >= 7) {
					$wivesTN[7] = 1;
				} else {
					$wivesTN[7]++;
				}

				if($wivesTN[8] >= 8) {
					$wivesTN[8] = 1;
				} else {
					$wivesTN[8]++;
				}

				if($wivesTN[9] >= 9) {
					$wivesTN[9] = 1;
				} else {
					$wivesTN[9]++;
				}


				$tag[$tagIdx] = $tag[$tagIdx] . '<div class="thumbnail workerTN ' . $TNNo . '">' .
					'<a href="' . fileName5C::PROFILE_DIR . '/' . $dir . '.html">' .
					'<div class="tnOuter">' .
					'<img src="' . $photoTagSrc . '" alt="' . $photoTagAlt . '" width="110" height="145">' .
					'</div>' .
					'<div class="caption text-center">' . $name . '</div>' .

					'<div class="workDate' . $dayClass . ' text-center">' . $workDate . '</div>' .
					'<div class="workTime' . $dayClass . ' text-center">' . $workTime . '</div>' .
					'</a>' .
					'</div>';
			}
		}

		$tag = $tag[0] . $tag[1];
		if(strlen($tag) <= 0) {
			$tag = '<a href="tel:' . siteConst5C::TEL_NO_CALL . '">お電話(' . siteConst5C::TEL_NO_DISP . ')にてお問い合わせください。</a>';	/* . '<br>' .
				'<a href="tel:' . siteConst5C::TEL_NO_CALL . '"><img src="img/img01.gif">電話をかける</a>';*/
		}

		$ret['tag'] = $tag;
		$ret['num'] = $tagIdx;

		return $ret;
	}


	private function getWorkInfo($list ,$todayStr) {

		$ret['FROM'] = '';
		$ret['TO'  ] = '';
		$ret['MODE'] = '';

		if(strlen($list['FROM']) >= 1) {
			$ret['FROM'] = $list['FROM'];

			if(strlen($list['TO']) >= 1) {
				$ret['TO'] = $list['TO'];
			}
			$ret['MODE'] = $list['MODE'];
		}

		for($dayIdx=0 ;$dayIdx<7 ;$dayIdx++) {
			if(strcmp($this->fldListD[$dayIdx] ,$todayStr) == 0) {
				if(strlen($list['FROM']) >= 1) {
					$ret['FROM'] = $this->fldListF[$dayIdx];

					if(strlen($list['TO']) >= 1) {
						$ret['TO'] = $this->fldListT[$dayIdx];
					}
					$ret['MODE'] = $this->fldListM[$dayIdx];
				}

				break;
			}
		}

		return $ret;
	}


	private function getWorkInfo2($list ,$daysList ,$dowDest) {

				/* print_r($list); */
		$ret['DAY' ] = '';
		$ret['DAYCLASS'] = '';
		$ret['FROM'] = '';
		$ret['TO'  ] = '';
		$ret['MODE'] = '';

		for($dayIdx=0 ;$dayIdx<7 ;$dayIdx++) {
			/*** その曜日の値があるか ***/
			$dowOffset = $dayIdx * 3;
			$dowFrom1 = $dowDest[$dowOffset    ];
			$dowTo1   = $dowDest[$dowOffset + 1];
			$dowMode1 = $dowDest[$dowOffset + 2];

			if(strlen($list[$dowFrom1]) >= 1) {
				$ret['FROM'] = $list[$dowFrom1];

				if(strlen($list[$dowTo1]) >= 1) {
					$ret['TO'] = $list[$dowTo1];
				}
				$ret['MODE'] = $list[$dowMode1];
			}

			/*** その日付の値があるか ***/
			$diffDayStr = $list[$this->fldListD[$dayIdx]];
					/* print $diffDayStr; */
					/* logFile5C::debug($this->fldListD[$dayIdx] . ' ' . $daysList[$dayIdx]); */
			if(strcmp($diffDayStr ,$daysList[$dayIdx]) == 0) {
				$diffFromIdx = $this->fldListF[$dayIdx];
				if(strlen($list[$diffFromIdx]) >= 1) {

					$absent = false;
					if(strcmp($list[$diffFromIdx] ,'休') == 0) {
						$absent = true;
					}

					if(strlen($list[$diffToIdx]) >= 1) {
						if(strcmp($list[$diffToIdx] ,'休') == 0) {
							$absent = true;
						}
					}

					if($absent) {
						$ret['FROM'] = '';
						$ret['TO'  ] = '';
						$ret['MODE'] = '';
					} else {
						$ret['FROM'] = $list[$diffFromIdx];

						$diffToIdx = $this->fldListT[$dayIdx];
						if(strlen($list[$diffToIdx]) >= 1) {
							$ret['TO'] = $list[$diffToIdx];
						}

						$diffModeIdx = $this->fldListM[$dayIdx];
						$ret['MODE'] = $this->fldListM[$dayIdx];
					}
				}
			}

			/*** その曜日または日付の値があればそれを返す ***/
			if(strlen($ret['FROM']) >= 1) {
				if($dayIdx == 0) {
					$ret['TODAYIDX'] = 0;
					$ret['DAY'     ] = '本日出勤！';
					$ret['DAYCLASS'] = ' todayWork';
				} else {
					$ret['TODAYIDX'] = 1;
					$ret['DAY'     ] = $this->setNextWorkDay($daysList[$dayIdx]);
				}
				break;
			}
		}

		return $ret;
	}


	private function setNextWorkDay($dayStr) {

		$dayExp = explode('-' ,$dayStr);
		$mmI = intval($dayExp[1]);
		$ddI = intval($dayExp[2]);

		$ret = '次回出勤：' . $mmI . '/' . $ddI;

		return $ret;
	}
}
?>
