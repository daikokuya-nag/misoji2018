<?php
/**
 * セッション
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */

	require_once dirname(__FILE__) . '/../funcs5C.php';
	require_once dirname(__FILE__) . '/../dateTime5C.php';
	require_once dirname(__FILE__) . '/../logFile5C.php';

	require_once dirname(__FILE__) . '/../db/dbGeneral5C.php';
	require_once dirname(__FILE__) . '/../db/dbPageParam5C.php';
	require_once dirname(__FILE__) . '/../db/dbImage5C.php';
	require_once dirname(__FILE__) . '/../db/dbImage5C.php';
	require_once dirname(__FILE__) . '/../db/dbProfile5C.php';
	require_once dirname(__FILE__) . '/../db/dbNews5C.php';

/**
 * セッション
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */
class sess5C {

	const NO_ID         = 1;	// ログイン結果 - セッションデータナシ　ログイン画面へ
	const OWN_TIMEOUT   = 2;	// ログイン結果 - 自IDでタイムアウト　タイムアウトのダイアログ、ログイン画面へ
	const OTHER_TIMEOUT = 3;	// ログイン結果 - 他IDでタイムアウト　ログイン画面へ
	const OWN_INTIME    = 4;	// ログイン結果 - 自IDでログイン中　メンテ画面へ
	const OTHER_INTIME  = 5;	// ログイン結果 - 他IDでログイン中　「他でログイン中」のダイアログ、ログイン不可

	const SEPT = ',';

	const OUTDATA   = 'outdata';	// 出力データ保持
	const FILE_DIVI = 'fileDivi';	// ファイル分割

/**
 * セッション管理ファイル名の取得
 *
 * @access
 * @param
 * @return string ファイル名
 * @link
 * @see
 * @throws
 * @todo
 */
	static function getFileName() {

		$ret = realpath(dirname(__FILE__) . '/../../dataFiles') . '/sess2018.txt';

		return $ret;
	}


/**
 * セッション状態取得
 *
 * @access
 * @param string $ownSessID 自セッションID
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	static function getSessCond($ownSessID=null) {

		if(is_null($ownSessID)) {
			$sessID = session_id();
		} else {
			$sessID = $ownSessID;
		}

		$fileName = self::getFileName();

		if(file_exists($fileName)) {
			$line = funcs5C::readFile($fileName);
					logFile5C::debug('セッションファイル有');
			$sessData = explode(self::SEPT ,$line[0]);
			$ret = self::getTimeoutCond($sessID ,$sessData[0] ,$sessData[1]);
		} else {
			/***** ファイルナシ *****/
					logFile5C::debug('セッションファイルナシ');
			$ret = self::NO_ID;
		}

		return $ret;
	}


/**
 * セッション管理ファイル削除
 *
 * @access
 * @param string $ownSessID 自セッションID
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	static function delSessCond($ownSessID=null) {

		if(is_null($ownSessID)) {
			$sessID = session_id();
		} else {
			$sessID = $ownSessID;
		}

		$format    = 'Y' . dateTime5C::DATE_SEP . 'm' . dateTime5C::DATE_SEP . 'd' . ' ' . 'H' . dateTime5C::TIME_SEP . 'i' . dateTime5C::TIME_SEP . 's';
		$timestamp = strtotime('-30 minute');	/* -10 minute */
		$currDT    = date($format, $timestamp);


		$str = $sessID . self::SEPT . $currDT;

		$fileName = self::getFileName();

					logFile5C::debug('ログアウト時のセッションファイル更新');
		$fp = fopen($fileName ,'w');
		fwrite($fp ,$str);
		fclose($fp);
	}


/**
 * セッション状態更新
 *
 * @access
 * @param string $ownSessID 自セッションID
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	static function updSessCond($ownSessID=null) {

		if(is_null($ownSessID)) {
			$sessID = session_id();
		} else {
			$sessID = $ownSessID;
		}

		//メンテモードの判定
		$mtnMode = false;
		if(isset($_SESSION['MTN'])) {
			if(strcmp($_SESSION['MTN'] ,'Y') == 0) {
				$mtnMode = true;
			}
		}

		if(!$mtnMode) {
			$timestamp = strtotime('+30 minute');
			$format    = 'Y' . dateTime5C::DATE_SEP . 'm' . dateTime5C::DATE_SEP . 'd' . ' ' . 'H' . dateTime5C::TIME_SEP . 'i' . dateTime5C::TIME_SEP . 's';
			$currDT    = date($format, $timestamp);

			$str = $sessID . self::SEPT . $currDT;

			$fileName = self::getFileName();

						logFile5C::debug('セッションファイル更新');
			$fp = fopen($fileName ,'w');
			fwrite($fp ,$str);
			fclose($fp);
		}
	}


/**
 * セッションタイムアウト状態取得
 *
 * @access
 * @param string $ownSessID 自セッションID
 * @param string $sessID セッションID
 * @param string $limitTime 制限時間
 * @return int ログイン状態
 * @link
 * @see
 * @throws
 * @todo
 */
	static function getTimeoutCond($ownSessID ,$sessID ,$limitTime) {

					logFile5C::debug('getTimeoutCond()セッションID:' . $ownSessID . ' ファイル上のセッションID:' . $sessID);

		$currDT = dateTime5C::getCurrDT();
		$timeDiff = dateTime5C::compareDT($currDT ,$limitTime);
					logFile5C::debug('current time:' . $currDT . ' limit time:' . $limitTime);
		if($timeDiff < 0) {
			$timeOver = true;
		} else {
			$timeOver = false;
		}

		//メンテモードの判定
		$mtnMode = false;
		if(isset($_SESSION['MTN'])) {
			if(strcmp($_SESSION['MTN'] ,'Y') == 0) {
				$mtnMode = true;
			}
		}

		if($mtnMode) {
			$ret = self::OWN_INTIME;	// メンテモードであれば自IDでログイン状態を返す
		} else {						// メンテモードでなければログイン状態を返す
			if(strcmp($ownSessID ,$sessID) == 0) {	// 自ID
				if($timeOver) {			// タイムアウト
							logFile5C::debug('own ID timeout');
					$ret = self::OWN_TIMEOUT;
				} else {				// インタイム
							logFile5C::debug('own ID intime');
					$ret = self::OWN_INTIME;
				}
			} else {					// 他ID
				if($timeOver) {			// タイムアウト
							logFile5C::debug('other ID timeout');
					$ret = self::OTHER_TIMEOUT;
				} else {				// インタイム
							logFile5C::debug('other ID intime');
					$ret = self::OTHER_INTIME;
				}
			}
		}

		return $ret;
	}



/**
 * 出力情報のリセット
 *
 * @access
 * @param string $ID セクションID
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	static function resetOutVals($ID='') {

		if(strlen($ID) >= 1) {
			if(isset($_SESSION[self::OUTDATA][$ID])) {
				unset($_SESSION[self::OUTDATA][$ID]);
			}
		} else {
			if(isset($_SESSION[self::OUTDATA])) {
				unset($_SESSION[self::OUTDATA]);
			}
		}
	}


/**
 * 出力情報の保持
 *
 * セクションIDに応じた、出力に必要な情報を保持する
 *
 * @access
 * @param string $ID セクションID
 * @param int $branchNo 店No
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	static function setOutVals($ID ,$branchNo) {

		$val = '';

		if(strcmp($ID ,'RECRUIT') == 0) {
			$db = new dbGeneral5C();
			$dbVal = $db->read($branchNo ,dbGeneral5C::CATE_RECRUIT);
			$val = $dbVal['vals'][0][dbGeneral5C::FLD_STR];
		}

		if(strcmp($ID ,'SYSTEM') == 0) {
			$db = new dbGeneral5C();
			$dbVal = $db->read($branchNo ,dbGeneral5C::CATE_SYSTEM);
			$val = $dbVal['vals'][0][dbGeneral5C::FLD_STR];
		}

		if(strcmp($ID ,'NEWS') == 0) {
			$db = new dbNews5C();
			$dbVal = $db->readShowable($branchNo ,'');
			$val = $dbVal['newsInfo'];
		}

		if(strcmp($ID ,'PROFILE') == 0) {
			$db = new dbProfile5C();
			$dbVal = $db->readShowableProf($branchNo);
			$val = $dbVal['profInfo'];
		}

		if(strcmp($ID ,'ALBUM') == 0) {
			$db = new dbProfile5C();
			$dbVal = $db->readShowableProf($branchNo);
			$val = $dbVal['profInfo'];
		}

		if(strcmp($ID ,'TOP_PAGE_HEADER') == 0) {
			$db = new dbPageParam5C();
			$dbVal = $db->readAll($branchNo ,'TOP' ,'HEADER');

			$dbVal1 = $dbVal['pageVal'][0];
			$val['SEQ'] = $dbVal1[dbPageParam5C::FLD_VALUE1];
			$val['USE'] = $dbVal1[dbPageParam5C::FLD_VALUE2];
			$val['NO' ] = $dbVal1[dbPageParam5C::FLD_VALUE3];

			$handle = $db->getHandle();
			$dbImgList = new dbImage5C($handle);

			$imgList = $dbImgList->readShowable($branchNo);
			$val['IMGLIST'] = $imgList['imgInfo'];
		}

		if(strcmp($ID ,'PAGE_MENU'  ) == 0) {
			$db = new dbPageParam5C();
			$dbVal  = $db->readByObj($branchNo ,'USEPAGE');

			$pageVal = $dbVal['pageVal'];
			$recMax  = $dbVal['count'  ];
			for($idx=0 ;$idx<$recMax ;$idx++) {
				$dbVal1 = $pageVal[$idx];
				$pageID = $dbVal1[dbPageParam5C::FLD_PAGE_ID];
				$val[$pageID]['SITE'] = $dbVal1[dbPageParam5C::FLD_VALUE1];
				$val[$pageID]['URL' ] = $dbVal1[dbPageParam5C::FLD_VALUE2];
			}
		}

		$_SESSION[self::OUTDATA][$ID] = $val;
	}

/**
 * 出力情報の取り出し
 *
 * 指定したセクションIDの出力に必要な情報を取得する
 *
 * @access
 * @param string $ID セクションID
 * @return array セクションに必要な情報
 * @link
 * @see
 * @throws
 * @todo
 */
	static function getOutVals($ID) {

		if(isset($_SESSION[self::OUTDATA][$ID])) {
			$ret = $_SESSION[self::OUTDATA][$ID];
		} else {
			$ret = '';
		}

		return $ret;
	}


/**
 * セクション情報のリセット
 *
 * @access
 * @param string $fileID ファイルID
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	static function resetOutSect($fileID='') {

		if(strlen($fileID) >= 1) {
			if(isset($_SESSION[self::FILE_DIVI][$fileID])) {
				unset($_SESSION[self::FILE_DIVI][$fileID]);
			}
		} else {
			if(isset($_SESSION[self::FILE_DIVI])) {
				unset($_SESSION[self::FILE_DIVI]);
			}
		}
	}

/**
 * セクション情報のセット
 *
 * 指定されたファイルIDに含まれているセクションを保持する
 *
 * @access
 * @param string $fileID ファイルID
 * @param array $div セクション情報
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	static function setOutSect($fileID ,$div) {

		$_SESSION[self::FILE_DIVI][$fileID] = $div;
	}

/**
 * セクション情報の取得
 *
 * 指定されたファイルIDに含まれているセクションを取り出す
 *
 * @access
 * @param string $fileID ファイルID
 * @return array セクション情報
 * @link
 * @see
 * @throws
 * @todo
 */
	static function getOutSect($fileID) {

		if(isset($_SESSION[self::FILE_DIVI][$fileID])) {
			$ret = $_SESSION[self::FILE_DIVI][$fileID];
		} else {
			$ret = '';
		}

		return $ret;
	}
}
?>
