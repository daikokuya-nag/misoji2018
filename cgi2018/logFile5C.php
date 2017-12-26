<?php
/**
 * ログ出力
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */

	require_once dirname(__FILE__) . '/dateTime5C.php';
	require_once dirname(__FILE__) . '/common5C.php';

/**
 * ログ出力
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */
class logFile5C {

	/***** ログファイルローテーション *****/
	/***** ファイルサイズの上限 *****/
	const LOG_FILE_SIZE_MAX = 1047552;		/* 1024*100 */		/* 1047552 = 1024*1023 */


/**
 * 一般ログ
 *
 * @access
 * @param string $logStr 出力文字列
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function general($logStr) {

		self::outLogMain($logStr ,'GeneralLog');
	}

/**
 * DBアクセスログ
 *
 * @access
 * @param string $logStr 出力文字列
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function SQL($logStr) {

		self::outLogMain($logStr ,'SQLLog');
	}

/**
 * warningログ
 *
 * @access
 * @param string $logStr 出力文字列
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function warning($logStr) {

		self::outLogMain($logStr ,'WarningLog');
	}

/**
 * エラーログ
 *
 * @access
 * @param string $logStr 出力文字列
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function error($logStr) {

		self::outLogMain($logStr ,'ErrorLog');
	}

/**
 * デバッグログ
 *
 * @access
 * @param string $logStr 出力文字列
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function debug($logStr) {

		self::outLogMain($logStr ,'DebugLog');
	}

/**
 * 会員関係のログ
 *
 * @access
 * @param string $logStr 出力文字列
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function members($logStr) {

		self::outLogMain($logStr ,'MemberAccessLog');
	}

/**
 * 予約関係のログ
 *
 * @access
 * @param string $logStr 出力文字列
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function reserve($logStr) {

		self::outLogMain($logStr ,'ReserveLog');
	}

/**
 * メール関係のエラーログ
 *
 * @access
 * @param string $logStr 出力文字列
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function office($logStr) {

		self::outLogMain($logStr ,'officeLog');
	}

/**
 * メール関係のエラー
 *
 * @access
 * @param string $logStr 出力文字列
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function mailErr($logStr) {

		self::outLogMain($logStr ,'MailErrLog');
	}

/**
 * ログ出力の本体
 *
 * @access
 * @param string $logStr          出力文字列
 * @param string $logFileNameBody 出力先ファイル名
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function outLogMain($logStr ,$logFileNameBody) {

		/***** ファイル名 *****/
		$logDir = realpath(dirname(__FILE__) . '/../logFile2018');						/* ログ出力先ディレクトリ '?????/log' が返る */

		$ext = '.txt';

		$logFileName = $logDir . '/'       . $logFileNameBody . $ext;		/* 書き出すログファイル名 */
		$oldFileName = $logDir . '/older/' . $logFileNameBody;				/* ローテーション先ファイル名 */

		/***** ファイルロック *****/
		$lockFileName = $logFileName . '.lock';
		$lockFhn = self::setLockFile($lockFileName);

		/***** ローテーションが必要ならファイルをコピーする *****/
		self::rotateLogFile($logFileName ,$oldFileName ,$ext);
		$fhn = fopen($logFileName ,'a');
		flock($fhn ,LOCK_EX);
				/* fwrite($fhn ,dateTime5C::getCurrDT() . ' ' . $_SERVER['HTTP_USER_AGENT'] . common5C::CTXT_NL_CODE . $logStr . ' ' . common5C::CTXT_NL_CODE); */
		fwrite($fhn ,dateTime5C::getCurrDT() . ' ' . $logStr . common5C::CTXT_NL_CODE);
		flock($fhn ,LOCK_UN);
		fclose($fhn);
					/*print $logFileName;*/
		chmod($logFileName ,0755);

		/***** ファイルアンロック *****/
		self::unsetLockFile($lockFileName ,$lockFhn);
	}

/**
 * ログファイルローテーション
 *
 * @access
 * @param string $logFileName ログファイル名
 * @param string $arcFileName アーカイブファイル名
 * @param string $arcFileExt  アーカイブファイルの拡張子
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function rotateLogFile($logFileName ,$arcFileName ,$arcFileExt) {

		/* ファイルサイズが一定値以上になっているとき、ファイルをリネームする */
		@$fileSize = filesize($logFileName);	/* ファイルがないときのエラーを回避 */
		if($fileSize >= self::LOG_FILE_SIZE_MAX) {
			dateTime5C::setTimeZone();
			$newFileName = $arcFileName . date('Ymd-His') . $arcFileExt;
			rename($logFileName ,$newFileName);
			touch($logFileName);	/* 新しいログファイルを生成 */
		}
	}

/**
 * ロックファイル設定
 *
 * @access
 * @param string $lockFileName ロックファイル名
 * @return fileHandle ファイルハンドル
 * @link
 * @see
 * @throws
 * @todo
 */
	function setLockFile($lockFileName) {

			/* touch($lockFileName); */
		$fhn = fopen($lockFileName ,'a');
		flock($fhn ,LOCK_EX);

		return $fhn;
	}

/**
 * ロックファイル解除
 *
 * @access
 * @param string $lockFileName ロックファイル名
 * @param fileHandle $lockFhn ファイルハンドル
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function unsetLockFile($lockFileName ,$lockFhn) {

		flock($lockFhn ,LOCK_UN);
		fclose($lockFhn);
		/*unlink($lockFileName);*/
	}
}
?>
