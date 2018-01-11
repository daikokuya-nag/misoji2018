<?php
/**
 * HTMLファイル生成
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */

	require_once dirname(__FILE__) . '/../sql5C.php';
	require_once dirname(__FILE__) . '/../fileName5C.php';
	require_once dirname(__FILE__) . '/../templateConst5C.php';
	require_once dirname(__FILE__) . '/../sess/sess5C.php';
	require_once dirname(__FILE__) . '/../common5C.php';
	require_once dirname(__FILE__) . '/../siteConst5C.php';
	require_once dirname(__FILE__) . '/../photo5C.php';
	require_once dirname(__FILE__) . '/../strings5C.php';

/**
 * HTMLファイル生成
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */
class html5C {

	const MAX_FILE_SIZE_MO = 20000;		/* 20000 */	/* 4000 */

	var $handle;			//DBハンドル
	var $branchNo;			//店No
	var $fileID;			//出力するHTMLファイルの識別
	var $profDir;			//プロファイルディレクトリ

	var $templateFileName;	//テンプレートファイル名
	var $outFileName;		//出力先ファイル名

	var $fileSect;			//セクションごとに分割されたテンプレートファイルの内容
	var $outSect;			//出力する内容

	var $rangeList;		//セクションキーワードリスト
	var $begValList;	//セクション開始キーワードリスト
	var $endValList;	//セクション終了キーワードリスト

	var $detail1;

/**
 * コンストラクタ
 *
 * 店No、ファイルの識別、プロファイルのディレクトリを保持し、出力ファイル名を特定する。また、各セクションの開始と終了のキーワードを保持する。
 *
 * @access
 * @param string $branchNo 店No
 * @param string $fileID 出力するファイルの識別
 * @param string $profDir 出力するプロファイルのディレクトリ
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function html5C($branchNo ,$fileID ,$profDir=null) {

		$this->handle = new sql5C();
		$this->branchNo = $branchNo;
		$this->fileID   = $fileID;
		$this->profDir  = $profDir;

		//出力ファイル名の特定
		if(strcmp($fileID ,'PROFILE') == 0) {
			$this->outFileName = fileName5C::getFileName('PC' ,$this->fileID ,'' ,$this->branchNo ,$profDir);
		} else {
			$this->outFileName = fileName5C::getFileName('PC' ,$this->fileID ,'' ,$this->branchNo);
		}

		$templateVals = new templateConst5C();
		$rangeList = $templateVals->getSectList();
		$this->rangeList  = $rangeList;
		$this->begValList = $rangeList['BEG_LIST'];
		$this->endValList = $rangeList['END_LIST'];
	}


/**
 * セクションデータの取り出し
 *
 * @access
 * @param
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function getFileSect() {

		$this->fileSect = sess5C::getOutSect($this->fileID);

				//print_r($this->fileSect);
	}


/**
 * キーワードの実データへの変換
 *
 * @access
 * @param
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function cnv() {

		$fileSect = $this->fileSect;

		$outSect = array();

		$idxMax = count($fileSect);
		for($idx=0 ;$idx<$idxMax ;$idx++) {
			$sect1    = $fileSect[$idx];	//分割した塊
			$line1st = $sect1[0];			//分割した塊の1行目
			$begKwd  = $this->searchSect($line1st);

			if(strlen($begKwd) <= 0) {
				//開始キーワードのどれにも一致しなかったときはそのまま出力
				$cnv = $sect1;
			} else {
				//開始キーワードに一致したときはキーワードに応じて変換
				$cnv = $this->cnvLine($sect1 ,$begKwd);
			}

			$lineMax = count($cnv);
			for($lineIdx=0 ;$lineIdx<$lineMax ;$lineIdx++) {
				$outSect[] = $cnv[$lineIdx];
			}
		}

		$this->outSect = $outSect;
	}


/**
 * 開始キーワードの検索
 *
 * @access
 * @param string $line 行データ
 * @return string 一致した開始キーワード どれにも一致しなかったときはカラ
 * @link
 * @see
 * @throws
 * @todo
 */
	private function searchSect($line) {

		$retKwd = '';
		foreach($this->begValList as $sectIdx => $begKWD) {
					//print $sectIdx . ' ' . $begKWD . "\n";
			if(strcmp($line ,$begKWD) == 0) {
				//$sectKWD = $this->sectionList[$sectIdx];
				$retKwd = $sectIdx;		//$begKWD;
				break;
			}
		}

		return $retKwd;
	}



/**
 * 開始キーワードの検索
 *
 * 指定された開始キーワードに応じてセクション情報を実データに変換する
 *
 * @access
 * @param string $sect1 セクション情報
 * @param string $begKwd 開始キーワード
 * @return string 実データで変換した文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function cnvLine($sect1 ,$begKwd) {

		$ret = $sect1;

		if(strcmp($begKwd ,'RECRUIT') == 0) {
			$ret = $this->setRecruit($sect1 ,$begKwd);
		}
		if(strcmp($begKwd ,'SYSTEM' ) == 0) {
			$ret = $this->setSystem($sect1 ,$begKwd);
		}

		if(strcmp($begKwd ,'NEWS_DIGEST') == 0) {
			$ret = $this->setNewsDigest($sect1 ,$begKwd);
		}
		if(strcmp($begKwd ,'NEWS_MAIN'  ) == 0) {
			$ret = $this->setNewsMain($sect1 ,$begKwd);
		}

		if(strcmp($begKwd ,'PROFILE') == 0) {
			$ret = $this->setProfile($sect1 ,$begKwd);
		}
		if(strcmp($begKwd ,'ALBUM'  ) == 0) {
			$ret = $this->setAlbum($sect1 ,$begKwd);
		}

		if(strcmp($begKwd ,'TOP_PAGE_HEADER') == 0) {
			$ret = $this->setTopPageHeader($sect1 ,$begKwd);
		}

		if(strcmp($begKwd ,'TOP_PAGE_MENU') == 0) {
			$ret = $this->setTopPageMenu($sect1 ,$begKwd);
		}
		if(strcmp($begKwd ,'OTHER_PAGE_MENU') == 0) {
			$ret = $this->setOtherPageMenu($sect1 ,$begKwd);
		}

		return $ret;
	}


/**
 * 求人データの変換
 *
 * @access
 * @param string $sect セクション情報
 * @param string $kwd セクションID
 * @return string 実データで変換した文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	private function setRecruit($sect ,$kwd) {

		$ret = array();
		$lineMax = count($sect);
		for($idx=0 ;$idx<$lineMax ;$idx++) {
			$line1 = $sect[$idx];

			if(strcmp($line1 ,templateConst5C::RECRUIT_STR_VAL) == 0) {
				$ret[] = sess5C::getOutVals($kwd);
			} else {
				if(strcmp($line1 ,$this->begValList[$kwd]) == 0
				|| strcmp($line1 ,$this->endValList[$kwd]) == 0) {
				} else {
					$ret[] = $line1;
				}
			}
		}

		return $ret;
	}

/**
 * 料金データの変換
 *
 * @access
 * @param string $sect セクション情報
 * @param string $kwd セクションID
 * @return string 実データで変換した文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	private function setSystem($sect ,$kwd) {

		$ret = array();
		$lineMax = count($sect);
		for($idx=0 ;$idx<$lineMax ;$idx++) {
			$line1 = $sect[$idx];

			if(strcmp($line1 ,templateConst5C::SYSTEM_STR_VAL) == 0) {
				$ret[] = sess5C::getOutVals($kwd);
			} else {
				if(strcmp($line1 ,$this->begValList[$kwd]) == 0
				|| strcmp($line1 ,$this->endValList[$kwd]) == 0) {
				} else {
					$ret[] = $line1;
				}
			}
		}

		return $ret;
	}

/**
 * ニュースダイジェストの変換
 *
 * ニュース情報を変換する
 *
 * @access
 * @param string $sect セクション情報
 * @param string $kwd セクションID
 * @return string 実データで変換した文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	private function setNewsDigest($sect ,$kwd) {

		$ret = array();
		$detailStr = array();
		$lineMax = count($sect);
		for($idx=0 ;$idx<$lineMax ;$idx++) {
			$line1 = $sect[$idx];
			if(strcmp($line1 ,$this->begValList[$kwd]) == 0
			|| strcmp($line1 ,$this->endValList[$kwd]) == 0) {
			} else {
				$detailStr[] = $line1;
			}
		}

		$newsVal = sess5C::getOutVals($kwd);
		$ret[] = $this->setNewsDetail($detailStr ,$newsVal);

		return $ret;
	}

/**
 * ニュース情報の変換
 *
 * ニュース情報を変換する
 *
 * @access
 * @param string $sect セクション情報
 * @param string $kwd セクションID
 * @return string 実データで変換した文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	private function setNewsMain($sect ,$kwd) {

		$ret = array();
		$detailStr = array();
		$lineMax = count($sect);
		for($idx=0 ;$idx<$lineMax ;$idx++) {
			$line1 = $sect[$idx];
			if(strcmp($line1 ,$this->begValList[$kwd]) == 0
			|| strcmp($line1 ,$this->endValList[$kwd]) == 0) {
			} else {
				$detailStr[] = $line1;
			}
		}

		$newsVal = sess5C::getOutVals($kwd);
		$ret[] = $this->setNewsDetail($detailStr ,$newsVal);

		return $ret;
	}


	private function setNewsDetail($detailStr ,$newsVal) {

		$ret = '';
		$tagStr = '';

		$detailMax = count($detailStr);
		$idxMax = count($newsVal);
		for($idx=0 ;$idx<$idxMax ;$idx++) {
			$news1 = $newsVal[$idx];


			//出力タグへ変換
			for($detailIdx=0 ;$detailIdx<$detailMax ;$detailIdx++) {
				$this->detail1 = $detailStr[$detailIdx];

				$this->replaceStr(templateConst5C::KWD_NEWS_TITLE_S   ,$news1[dbNews5C::FLD_TITLE]);	//タイトル
				$this->replaceStr(templateConst5C::KWD_NEWS_DIGEST_S  ,$news1[dbNews5C::FLD_DIGEST]);	//概要
				$this->replaceStr(templateConst5C::KWD_NEWS_CONTENT_S ,$news1[dbNews5C::FLD_CONTENT]);	//内容
				$this->replaceStr(templateConst5C::KWD_NEWS_DATE_S    ,$news1[dbNews5C::FLD_DATE]);		//日付

				if(strlen($this->detail1) >= 1) {
					$tagStr = $tagStr . $this->detail1 . common5C::CSRC_NL_CODE;
				}
			}
		}

		$ret = $ret . $tagStr;

		return $ret;
	}


/**
 * プロファイル情報の変換
 *
 * @access
 * @param string $sect セクション情報
 * @param string $kwd セクションID
 * @return string 実データで変換した文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	private function setProfile($sect ,$kwd) {

		$ret = array();
		$detailStr = array();
		$lineMax = count($sect);
		for($idx=0 ;$idx<$lineMax ;$idx++) {
			$line1 = $sect[$idx];
			if(strcmp($line1 ,$this->begValList[$kwd]) == 0
			|| strcmp($line1 ,$this->endValList[$kwd]) == 0) {
			} else {
				$detailStr[] = $line1;
			}
		}

		$profVal = sess5C::getOutVals($kwd);
		$ret[] = $this->setProfileDetail($detailStr ,$profVal);

		return $ret;
	}

	private function setProfileDetail($detailStr ,$profVal) {

		$ret = '';

		$photoVal = new photo5C();
		$photoID  = array('TN');

		$photoDir = fileName5C::FILEID_PHOTO_DIR;	//写真ディレクトリ
		$profDir  = fileName5C::PROFILE_DIR;		//紹介ページディレクトリ

		$idxMax = count($profVal);
		for($idx=0 ;$idx<$idxMax ;$idx++) {
			$prof1 = $profVal[$idx];
			$dir  = $prof1[dbProfile5C::FLD_DIR];
			if(strcmp($dir ,$this->profDir) == 0) {
				break;
			}
		}

		$dir  = $prof1[dbProfile5C::FLD_DIR];
		$name = $prof1[dbProfile5C::FLD_NAME];

		//新人印の表示
		if(strcmp($prof1[dbProfile5C::FLD_NEWFACE] ,dbProfile5C::NEW_FACE) == 0) {
			$newFace = '			<span class="newFaceMark">NEW!</span>';
		} else {
			$newFace = '';
		}

		//サムネイル表示判定
		$photoUse = $photoVal->getUsePhoto($dir ,$photoID);


		//出力タグへ変換
		$tagStr = '';
		$detailMax = count($detailStr);
		for($detailIdx=0 ;$detailIdx<$detailMax ;$detailIdx++) {
			$this->detail1 = $detailStr[$detailIdx];

			$this->replaceStr(templateConst5C::KWD_NAME_S     ,$name);		//名前
			$this->replaceStr(templateConst5C::KWD_PROF_DIR_S ,$dir);		//ディレクトリ

			$this->replaceLine(templateConst5C::KWD_AGE_S       ,$prof1[dbProfile5C::FLD_AGE]);			//年齢
			$this->replaceLine(templateConst5C::KWD_ZODIAC_S    ,$prof1[dbProfile5C::FLD_ZODIAC]);		//星座
			$this->replaceLine(templateConst5C::KWD_BLOODTYPE_S ,$prof1[dbProfile5C::FLD_BLOODTYPE]);	//血液型
			$this->replaceLine(templateConst5C::KWD_HEIGHT_S    ,$prof1[dbProfile5C::FLD_HEIGHT]);		//身長
			$this->replaceLine(templateConst5C::KWD_SIZES_S     ,$prof1[dbProfile5C::FLD_SIZES]);		//スリーサイズ
			$this->replaceLine(templateConst5C::KWD_WORK_TIME_S ,$prof1[dbProfile5C::FLD_WORK_TIME]);	//出勤時間
			$this->replaceLine(templateConst5C::KWD_WORK_DAY_S  ,$prof1[dbProfile5C::FLD_WORK_DAY]);	//出勤日
			$this->replaceLine(templateConst5C::KWD_REST_DAY_S  ,$prof1[dbProfile5C::FLD_REST_DAY]);	//公休日
			$this->replaceLine(templateConst5C::KWD_MASTERS_COMMENT_S ,$prof1[dbProfile5C::FLD_MASTERS_COMMENT]);	//店長コメント
			$this->replaceLine(templateConst5C::KWD_APPEAL_COMMENT_S  ,$prof1[dbProfile5C::FLD_APPEAL_COMMENT]);	//アピールコメント


			//写真表示
			$kwdPos = strings5C::mb_existStr($this->detail1 ,templateConst5C::KWD_PHOTO_SHOW_OK);		/* 表示可 */
			if($kwdPos >= 0) {
				if(strcmp($photoUse['TN'] ,dbProfile5C::PHOTO_SHOW_OK) == 0) {
					$this->detail1 = str_replace(templateConst5C::KWD_PHOTO_SHOW_OK ,'' ,$this->detail1);
				} else {
					//写真可の行で写真表示指定が可以外の時はその行を表示しない
					$this->detail1 = '';
				}
			}

			$kwdPos = strings5C::mb_existStr($this->detail1 ,templateConst5C::KWD_PHOTO_SHOW_NG);		/* 写真NG */
			if($kwdPos >= 0) {
				if(strcmp($photoUse['TN'] ,dbProfile5C::PHOTO_SHOW_NG) == 0) {
					$this->detail1 = str_replace(templateConst5C::KWD_PHOTO_SHOW_NG ,'' ,$this->detail1);
				} else {
					//写真NGの行で写真表示指定がNG以外の時はその行を表示しない
					$this->detail1 = '';
				}
			}

			$kwdPos = strings5C::mb_existStr($this->detail1 ,templateConst5C::KWD_PHOTO_SHOW_NP);		/* 写真準備中 */
			if($kwdPos >= 0) {
				if(strcmp($photoUse['TN'] ,dbProfile5C::PHOTO_SHOW_NP) == 0) {
					$this->detail1 = str_replace(templateConst5C::KWD_PHOTO_SHOW_NP ,'' ,$this->detail1);
				} else {
					//写真準備中の行で写真表示指定が準備中以外の時はその行を表示しない
					$this->detail1 = '';
				}
			}

			$kwdPos = strings5C::mb_existStr($this->detail1 ,templateConst5C::KWD_PHOTO_SHOW_NOT);		/* 写真なし */
			if($kwdPos >= 0) {
				if(strcmp($photoUse['TN'] ,dbProfile5C::PHOTO_SHOW_NOT) == 0) {
					$this->detail1 = str_replace(templateConst5C::KWD_PHOTO_SHOW_NOT ,'' ,$this->detail1);
				} else {
					//写真なしの行で写真表示指定が写真ナシ以外の時はその行を表示しない
					$this->detail1 = '';
				}
			}

			if(strlen($this->detail1) >= 1) {
				$tagStr = $tagStr . $this->detail1 . common5C::CSRC_NL_CODE;
			}
		}

		$ret = $ret . $tagStr;

		return $ret;
	}


/**
 * アルバム情報の変換
 *
 * @access
 * @param string $sect セクション情報
 * @param string $kwd セクションID
 * @return string 実データで変換した文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	private function setAlbum($sect ,$kwd) {

		$ret = array();
		$detailStr = array();
		$lineMax = count($sect);
		for($idx=0 ;$idx<$lineMax ;$idx++) {
			$line1 = $sect[$idx];
			if(strcmp($line1 ,$this->begValList[$kwd]) == 0
			|| strcmp($line1 ,$this->endValList[$kwd]) == 0) {
			} else {
				$detailStr[] = $line1;
			}
		}

		$profVal = sess5C::getOutVals($kwd);
		$ret[] = $this->setAlbumDetail($detailStr ,$profVal);

		return $ret;
	}

/**
 * アルバム情報のタグの組み立て
 *
 * @access
 * @param array $detailStr セクション情報
 * @param array $profVal プロファイル情報
 * @return string 実データで変換した文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	private function setAlbumDetail($detailStr ,$profVal) {

		$ret = '';

		$photoVal = new photo5C();
		$photoID  = array('TN');

		$photoDir = fileName5C::FILEID_PHOTO_DIR;	//写真ディレクトリ
		$profDir  = fileName5C::PROFILE_DIR;		//紹介ページディレクトリ

		$wivesTN = array(
			2 => 1 ,
			3 => 1 ,
			4 => 1 ,
			5 => 1 ,
			6 => 1 ,
			7 => 1 ,
			8 => 1 ,
			9 => 1
		);

		$detailMax = count($detailStr);

		$idxMax = count($profVal);
		for($idx=0 ;$idx<$idxMax ;$idx++) {
			$prof1 = $profVal[$idx];
			$dir  = $prof1[dbProfile5C::FLD_DIR];
			$name = $prof1[dbProfile5C::FLD_NAME];

			$TNNo =
				'wivesTN2' . $wivesTN[2] . ' ' . 'wivesTN3' . $wivesTN[3] . ' ' . 'wivesTN4' . $wivesTN[4] . ' ' . 'wivesTN5' . $wivesTN[5] . ' ' .
				'wivesTN6' . $wivesTN[6] . ' ' . 'wivesTN7' . $wivesTN[7] . ' ' . 'wivesTN8' . $wivesTN[8] . ' ' . 'wivesTN9' . $wivesTN[9];

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

			//新人印の表示
			if(strcmp($prof1[dbProfile5C::FLD_NEWFACE] ,dbProfile5C::NEW_FACE) == 0) {
				$newFace = '			<span class="newFaceMark">NEW!</span>';
			} else {
				$newFace = '';
			}

			//サムネイル表示判定
			$photoUse = $photoVal->getUsePhoto($dir ,$photoID);


			//出力タグへ変換
			$tagStr = '';
			for($detailIdx=0 ;$detailIdx<$detailMax ;$detailIdx++) {
				$this->detail1 = $detailStr[$detailIdx];

				$this->replaceStr(templateConst5C::KWD_NAME_S     ,$name);		//名前
				$this->replaceStr(templateConst5C::KWD_PROF_DIR_S ,$dir);		//ディレクトリ
				$this->replaceStr(templateConst5C::KWD_NEW_FACE_S ,$newFace);	//新人印


				//写真表示
				$kwdPos = strings5C::mb_existStr($this->detail1 ,templateConst5C::KWD_PHOTO_SHOW_OK);		/* 表示可 */
				if($kwdPos >= 0) {
					if(strcmp($photoUse['TN'] ,dbProfile5C::PHOTO_SHOW_OK) == 0) {
						$this->detail1 = str_replace(templateConst5C::KWD_PHOTO_SHOW_OK ,'' ,$this->detail1);
					} else {
						//写真可の行で写真表示指定が可以外の時はその行を表示しない
						$this->detail1 = '';
					}
				}

				$kwdPos = strings5C::mb_existStr($this->detail1 ,templateConst5C::KWD_PHOTO_SHOW_NG);		/* 写真NG */
				if($kwdPos >= 0) {
					if(strcmp($photoUse['TN'] ,dbProfile5C::PHOTO_SHOW_NG) == 0) {
						$this->detail1 = str_replace(templateConst5C::KWD_PHOTO_SHOW_NG ,'' ,$this->detail1);
					} else {
						//写真NGの行で写真表示指定がNG以外の時はその行を表示しない
						$this->detail1 = '';
					}
				}

				$kwdPos = strings5C::mb_existStr($this->detail1 ,templateConst5C::KWD_PHOTO_SHOW_NP);		/* 写真準備中 */
				if($kwdPos >= 0) {
					if(strcmp($photoUse['TN'] ,dbProfile5C::PHOTO_SHOW_NP) == 0) {
						$this->detail1 = str_replace(templateConst5C::KWD_PHOTO_SHOW_NP ,'' ,$this->detail1);
					} else {
						//写真準備中の行で写真表示指定が準備中以外の時はその行を表示しない
						$this->detail1 = '';
					}
				}

				$kwdPos = strings5C::mb_existStr($this->detail1 ,templateConst5C::KWD_PHOTO_SHOW_NOT);		/* 写真なし */
				if($kwdPos >= 0) {
					if(strcmp($photoUse['TN'] ,dbProfile5C::PHOTO_SHOW_NOT) == 0) {
						$this->detail1 = str_replace(templateConst5C::KWD_PHOTO_SHOW_NOT ,'' ,$this->detail1);
					} else {
						//写真なしの行で写真表示指定が写真ナシ以外の時はその行を表示しない
						$this->detail1 = '';
					}
				}

				if(strlen($this->detail1) >= 1) {
					$tagStr = $tagStr . $this->detail1 . common5C::CSRC_NL_CODE;
				}
			}

			$ret = $ret . $tagStr;
		}

		return $ret;
	}


/**
 * 文字列の変換
 *
 * 文字列内の$kwdで指定する文字列を$valで指定する文字列に変換する。文字列内に$kwdで指定する文字列がないときは元の文字列のまま返す
 *
 * @access
 * @param string $kwd 変換対象になる文字列
 * @param string $val 変換する文字列
 * @return string 変換した文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	private function replaceStr($kwd ,$val) {

		$kwdPos = strings5C::mb_existStr($this->detail1 ,$kwd);
		if($kwdPos >= 0) {
			$this->detail1 = str_replace($kwd ,$val ,$this->detail1);
		}
	}


/**
 * 文字列の変換
 *
 * 文字列内の$kwdで指定する文字列を$valで指定する文字列に変換する。文字列内に$kwdで指定する文字列がないときは対象文字列をカラにする
 *
 * @access
 * @param string $kwd 変換対象になる文字列
 * @param string $val 変換する文字列
 * @return string 変換した文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	private function replaceLine($kwd ,$val) {

		$kwdPos = strings5C::mb_existStr($this->detail1 ,$kwd);
		if($kwdPos >= 0) {
			if(strlen($val) >= 1) {
				$this->detail1 = str_replace($kwd ,$val ,$this->detail1);
			} else {
				$this->detail1 = '';
			}
		}
	}


/**
 * TOPページのヘッダの変換
 *
 * @access
 * @param string $sect セクション情報
 * @param string $kwd セクションID
 * @return string 実データで変換した文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	private function setTopPageHeader($sect ,$kwd) {

		$ret = array();
		$lineMax = count($sect);
		for($idx=0 ;$idx<$lineMax ;$idx++) {
			$line1 = $sect[$idx];
			if(strcmp($line1 ,templateConst5C::TOP_PAGE_HEADER_STR_VAL) == 0) {
				$headerVal = sess5C::getOutVals($kwd);
							//print_r($headerVal);
				$disp = $this->setTopPageHeaderImg($headerVal);
				//表示する画像が1枚の時と2枚以上の時で表示タグを分ける
				if(count($disp) >= 2) {
					//2枚以上あるとき
					$ret[] = $this->setTopHeaderSlideShow($disp);
				} else {
					//1枚の時
					$ret[] = $this->setTopHeader1Img($disp);
				}
			} else {
				if(strcmp($line1 ,$this->begValList[$kwd]) == 0
				|| strcmp($line1 ,$this->endValList[$kwd]) == 0) {
				} else {
					$ret[] = $line1;
				}
			}
		}

		return $ret;
	}

/**
 * TOPページに表示する画像の取得
 *
 * @access
 * @param string $headerVal 画像指定情報
 * @return array 画像ファイルへのパス
 * @link
 * @see
 * @throws
 * @todo
 */
	private function setTopPageHeaderImg($headerVal) {

		$imgDir  = 'img/' . $this->branchNo . '/TOP_HEADER/';
		$imgRoot = realpath(dirname(__FILE__) . '/../..') . '/' . $imgDir;

		$expSeq = explode(':' ,$headerVal['SEQ']);
		$expUse = explode(':' ,$headerVal['USE']);
		$expNo  = explode(':' ,$headerVal['NO' ]);
		$imgList = $headerVal['IMGLIST'];

		$imgMax = count($imgList);
		$idxMax = count($expSeq);
		for($idx=0 ;$idx<$idxMax ;$idx++) {
			$fileExist = false;

			//画像番号のファイルの有無を調べる
			$dispImgNo1 = $expNo[$idx];

			for($imgIdx=0 ;$imgIdx<$imgMax ;$imgIdx++) {
				$img1 = $imgList[$imgIdx];
				if($dispImgNo1 == $img1[dbImage5C::FLD_IMG_NO]) {
					$ext = $img1[dbImage5C::FLD_ORG_EXT];
					$imgFullPath = $imgRoot . $dispImgNo1 . '.' . $ext;
					$imgDispPath = $imgDir  . $dispImgNo1 . '.' . $ext;
					if(is_file($imgFullPath)) {
						$fileExist = true;
					}
					break;
				}
			}

			//画像ファイルがあり、表示状態になっていればtrue
			if($fileExist && strcmp($expUse[$idx] ,'U') == 0) {
				$imgUse[$idx ] = true;
				$imgPath[$idx] = $imgDispPath;
			} else {
				$imgUse[$idx ] = false;
				$imgPath[$idx] = '';
			}
		}

		$seqUseA = array(
			'A' => $imgUse[0] ,
			'B' => $imgUse[1] ,
			'C' => $imgUse[2] ,
			'D' => $imgUse[3]
		);
		$seqPathA = array(
			'A' => $imgPath[0] ,
			'B' => $imgPath[1] ,
			'C' => $imgPath[2] ,
			'D' => $imgPath[3]
		);

		$seqNo = array(
			'A' => $expSeq[0] ,
			'B' => $expSeq[1] ,
			'C' => $expSeq[2] ,
			'D' => $expSeq[3]
		);

		foreach($seqNo as $seq) {
			$seqUse[]  = $seqUseA[$seq];
			$seqPath[] = $seqPathA[$seq];
		}

		$disp = array();
		for($idx=0 ;$idx<$idxMax ;$idx++) {
			$use1  = $seqUse[$idx];
			$path1 = $seqPath[$idx];

			if($use1 && strlen($path1) >= 1) {
				$disp[] = $path1;
			}
		}

		return $disp;
	}


/**
 * TOPページに表示する画像が1枚の時のタグの組み立て
 *
 * @access
 * @param array $imgList 画像ファイルへのパスのリスト
 * @return array 画像ファイルへのパス
 * @link
 * @see
 * @throws
 * @todo
 */
	 private function setTopHeader1Img($imgList) {

		$ret = '<img src=\'' . $imgList[0] . '\' class="img-responsive center-block" id="topImg">';

		return $ret;
	}


/**
 * TOPページに表示する画像が2枚以上の時のタグの組み立て
 *
 * @access
 * @param array $imgList 画像ファイルへのパスのリスト
 * @return array 画像ファイルへのパス
 * @link
 * @see
 * @throws
 * @todo
 */
	 private function setTopHeaderSlideShow($imgList) {

		$li   = '';
		$item = '';

		$imgIdx = count($imgList);
		for($idx=0 ;$idx<$imgIdx ;$idx++) {
			if($idx == 0) {
				$liParam   = 'active';
				$itemParam = ' active';
			} else {
				$liParam   = '';
				$itemParam = '';
			}

			$li   = $li   . '<li data-target="#carousel1" data-slide-to="' . $idx . '" class="' . $liParam . '"></li>';
			$item = $item . '<div class="item' . $itemParam . '"><img src="' . $imgList[$idx] . '" alt="" class="center-block img-responsive"></div>';
		}

		$cr = common5C::CSRC_NL_CODE;

		$ret = 
'					<div id="carousel1" class="carousel slide center-block" data-ride="carousel">' . $cr .
'						<ol class="carousel-indicators">' . $cr .
					$li . $cr .
'						</ol>' . $cr .
'						<div class="carousel-inner" role="listbox">' . $cr .
							$item . $cr .
'						</div>' . $cr .
'						<a class="left carousel-control" href="#carousel1" role="button" data-slide="prev">' . $cr .
'							<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span class="sr-only">Previous</span>' . $cr .
'						</a>' . $cr .
'						<a class="right carousel-control" href="#carousel1" role="button" data-slide="next">' . $cr .
'							<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span><span class="sr-only">Next</span>' . $cr .
'						</a>' . $cr .
'					</div>';

		return $ret;
	}



/**
 * TOPページのメニューの変換
 *
 * @access
 * @param string $sect セクション情報
 * @param string $kwd セクションID
 * @return string 実データで変換した文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function setTopPageMenu($sect ,$kwd) {

		$ret = array();
		$lineMax = count($sect);
		for($idx=0 ;$idx<$lineMax ;$idx++) {
			$line1 = $sect[$idx];

			if(strcmp($line1 ,templateConst5C::TOP_PAGE_MENU_STR_VAL) == 0) {
				$ret[] = $this->setMenuStr($kwd);
			} else {
				if(strcmp($line1 ,$this->begValList[$kwd]) == 0
				|| strcmp($line1 ,$this->endValList[$kwd]) == 0) {
				} else {
					$ret[] = $line1;
				}
			}
		}

		return $ret;
	}

/**
 * TOPページ以外のメニューの変換
 *
 * @access
 * @param string $sect セクション情報
 * @param string $kwd セクションID
 * @return string 実データで変換した文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function setOtherPageMenu($sect ,$kwd) {

		$ret = array();
		$lineMax = count($sect);
		for($idx=0 ;$idx<$lineMax ;$idx++) {
			$line1 = $sect[$idx];

			if(strcmp($line1 ,templateConst5C::OTHER_PAGE_MENU_STR_VAL) == 0) {
				$ret[] = $this->setMenuStr($kwd);
			} else {
				if(strcmp($line1 ,$this->begValList[$kwd]) == 0
				|| strcmp($line1 ,$this->endValList[$kwd]) == 0) {
				} else {
					$ret[] = $line1;
				}
			}
		}

		return $ret;
	}

/**
 * メニューの変換
 *
 * @access
 * @param string $kwd セクションID
 * @return string 実データで変換した文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	private function setMenuStr($kwd) {

		$ret = '';

		$menuVals = sess5C::getOutVals($kwd);
				//print_r($menuVals);
				//print $this->fileID;

		$currFileID = $this->fileID;
		$menuList = siteConst5C::getHtmlFileIDList();
		$menuStr  = siteConst5C::getMenuStrList();		//メニューに表示する文字列

		$menuMax = count($menuList);
		for($idx=0 ;$idx<$menuMax ;$idx++) {
			$targetID = $menuList[$idx];

			if(isset($menuVals[$targetID])) {
				$target = $menuVals[$targetID]['SITE'];
			} else {
				$target = 'OWN_SITE';
			}

			if(strcmp($target ,'OWN_SITE') == 0) {
				$urlList = fileName5C::getFileName('PC' ,$targetID ,'' ,'');
				$url = $urlList['fileName'];
				if(strcmp($this->fileID ,'PROFILE') == 0) {
					$url = '../' . $url;		//女性紹介ページのときは階層を一つ上がる
				}
			} else {
				$url = $menuVals[$targetID]['URL'];
			}

			if(strcmp($targetID ,$currFileID) == 0) {
				//自ページの時はリンクを入れない
				$lineStr = '				<li class="active"><a href="#">' . $menuStr[$targetID] . '<span class="sr-only">(current)</span></a></li>';
			} else {
				//自ページでないとき
				$lineStr = '				<li><a href="' . $url . '">' . $menuStr[$targetID] . '</a></li>';
			}

			$ret = $ret . $lineStr . common5C::CSRC_NL_CODE;
		}

		return $ret;
	}


/**
 * ファイル出力
 *
 * 変換されたセクション情報をファイルに出力する
 *
 * @access
 * @param
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function outFile() {

		$outSect = array();
		$lineMax = count($this->outSect);
		for($idx=0 ;$idx<$lineMax ;$idx++) {
			$outSect[$idx] = $this->outSect[$idx] . common5C::CSRC_NL_CODE;
		}

		file_put_contents($this->outFileName['fullPath'] ,$outSect);
	}
}
?>
