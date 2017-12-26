<?php
/*************************
セッション Version 1.0
*************************/

	require_once dirname(__FILE__) . '/../funcs5C.php';
	require_once dirname(__FILE__) . '/../dateTime5C.php';
	require_once dirname(__FILE__) . '/../logFile5C.php';

class sess5C {

	/********************
	ログイン結果
	********************/
	const NO_ID         = 1;	/* セッションデータナシ　ログイン画面へ */

	/*** タイムアウト ***/
	const OWN_TIMEOUT   = 2;	/* 自IDでタイムアウト　タイムアウトのダイアログ、ログイン画面へ */
	const OTHER_TIMEOUT = 3;	/* 他IDでタイムアウト　ログイン画面へ */

	/*** not タイムアウト ***/
	const OWN_INTIME    = 4;	/* 自IDでログイン中　メンテ画面へ */
	const OTHER_INTIME  = 5;	/* 他IDでログイン中　「他でログイン中」のダイアログ、ログイン不可 */

	const SEPT = ',';


	/********************
	ファイル名
	パラメータ：-
	戻り値　　：-
	********************/
	function getFileName() {

		$ret = realpath(dirname(__FILE__) . '/../../dataFiles') . '/sess2018.txt';

		return $ret;
	}


	/********************
	セッション状態取得
	パラメータ：自セッションID
	戻り値　　：-
	********************/
	function getSessCond($ownSessID=null) {

		if(is_null($ownSessID)) {
			$sessID = session_id();
		} else {
			$sessID = $ownSessID;
		}

		$fileName = self::getFileName();

		if(file_exists($fileName)) {
			$line = funcs5C::readFile($fileName);
					logFile5C::debug('セッションファイル有');
			$sessData = explode(self::SEPT ,$line);
			$ret = self::getTimeoutCond($sessID ,$sessData[0] ,$sessData[1]);
		} else {
			/***** ファイルナシ *****/
					logFile5C::debug('セッションファイルナシ');
			$ret = self::NO_ID;
		}

		return $ret;
	}


	/********************
	セッション管理ファイル削除
	パラメータ：-
	戻り値　　：-
	********************/
	function delSessCond($ownSessID=null) {

		if(is_null($ownSessID)) {
			$sessID = session_id();
		} else {
			$sessID = $ownSessID;
		}

		$timestamp = strtotime('-30 minute');	/* -10 minute */
		$format    = 'Y' . dateTime5C::DATE_SEP . 'm' . dateTime5C::DATE_SEP . 'd' . ' ' . 'H' . dateTime5C::TIME_SEP . 'i' . dateTime5C::TIME_SEP . 's';
		$currDT    = date($format, $timestamp);


		$str = $sessID . self::SEPT . $currDT;

		$fileName = self::getFileName();

					logFile5C::debug('ログアウト時のセッションファイル更新');
		$fp = fopen($fileName ,'w');
		fwrite($fp ,$str);
		fclose($fp);
	}


	/********************
	セッション状態更新
	パラメータ：自セッションID
	戻り値　　：-
	********************/
	function updSessCond($ownSessID=null) {

				/* $currDT = dateTime5C::getCurrDT(); */
		if(is_null($ownSessID)) {
			$sessID = session_id();
		} else {
			$sessID = $ownSessID;
		}


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


	/********************
	セッションタイムアウト状態取得
	パラメータ：自セッションID
	　　　　　　ファイル上のセッションID
	　　　　　　制限日時
	戻り値　　：-
	********************/
	function getTimeoutCond($ownSessID ,$sessID ,$limitTime) {

					logFile5C::debug('getTimeoutCond()セッションID:' . $ownSessID . ' ファイル上のセッションID:' . $sessID);

		$currDT = dateTime5C::getCurrDT();
		$timeDiff = dateTime5C::compareDT($currDT ,$limitTime);
					logFile5C::debug('current time:' . $currDT . ' limit time:' . $limitTime);
		if($timeDiff < 0) {
			$timeOver = true;
		} else {
			$timeOver = false;
		}

		$mtnMode = false;
		if(isset($_SESSION['MTN'])) {
			if(strcmp($_SESSION['MTN'] ,'Y') == 0) {
				$mtnMode = true;
			}
		}

		if($mtnMode) {
			$ret = self::OWN_INTIME;
		} else {
			if(strcmp($ownSessID ,$sessID) == 0) {
				/***** 自ID *****/
				if($timeOver) {
					/***** タイムアウト *****/
							logFile5C::debug('own ID timeout');
					$ret = self::OWN_TIMEOUT;
				} else {
					/***** インタイム *****/
							logFile5C::debug('own ID intime');
					$ret = self::OWN_INTIME;
				}
			} else {
				/***** 他ID *****/
				if($timeOver) {
					/***** タイムアウト *****/
							logFile5C::debug('other ID timeout');
					$ret = self::OTHER_TIMEOUT;
				} else {
					/***** インタイム *****/
							logFile5C::debug('other ID intime');
					$ret = self::OTHER_INTIME;
				}
			}
		}

		return $ret;
	}
}
?>
