<?php
/**
 * SQL関数群
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */

	require_once dirname(__FILE__) . '/sqlConstC.php';
	require_once dirname(__FILE__) . '/logFile5C.php';

/**
 * SQL関数群
 *
 * @copyright
 * @license
 * @author
 * @link
 */
class sql5C {

	/***** リターンコードのindex *****/
	const EXECUTE_RESULT = 'exeResult';	/* 実行結果 */
	const ERR_STR_INDEX  = 'errStr';		/* エラーメッセージ */
	const ERR_ID_INDEX   = 'errID';		/* エラーID */

	/***** 実行結果 *****/
	const RET_NO_ERROR  = 0;	/* エラーナシ */
	const CONNECT_ERROR = 1;	/* DB接続エラー */
	const OPEN_ERROR    = 2;	/* DB選択エラー */
	const ANY_ERROR     = 3;	/* 何かのエラー */

		/***** DBロック *****/
	const LOCK_TIMEOUT   = 4;	/* 指定した時間内にロックできなかった */
	const LOCK_ANY_ERROR = 5;	/* 何かのエラー */

		/***** トランザクション *****/
	const TRANS_BEGIN_ERR    = 6;	/* トランザクション開始エラー */
	const TRANS_WRITE_ERR    = 7;	/* トランザクション書き込みエラー */
	const TRANS_ROLLBACK_ERR = 8;	/* トランザクションロールバックエラー */


	/***** ログ出力 *****/
	const LOG_OUTPUT  = 1;
	const LOG_NOT_OUT = 0;

	const LOG_DEFAULT = 1;


	const SETCHARSET = 'set character set utf8';


/**
 * コンストラクタ(DB接続)
 *
 * @access
 * @param
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function sql5C() {

		$sqlResult[self::ERR_STR_INDEX] = '';					/* エラーメッセージ */
		$sqlResult[self::ERR_ID_INDEX ] = self::RET_NO_ERROR;	/* エラーID */

		/***** MySQLへ接続 *****/
		$this->connectID = mysql_connect(LOCATIONURL /*'127.0.0.1'*/ ,USER ,DBPASS ,false ,65536);

		if(!$this->connectID) {	/* 接続エラー */
			logFile5C::error('sql.DBConnect() DB接続エラー：' . LOCATIONURL . ',' . USER . ',' . DBPASS . '：' . mysql_error());
			$sqlResult[self::ERR_ID_INDEX] = self::DB_CONNECT_ERROR;
		}

		/***** データベースを開く *****/
		$this->seleID = mysql_select_db(DBNAME ,$this->connectID);
		if(!$this->seleID) {	/* 選択エラー */
			logFile5C::error('sql.DBConnect() DB選択エラー：' . DBNAME . '：' . mysql_error());
			$sqlResult[self::ERR_ID_INDEX] = self::DB_OPEN_ERROR;
		}
	}

/**
 * デストラクタ(DB切断)
 *
 * @access
 * @param
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function __destruct() {

		/*mysql_close($this->connectID);*/
	}

/**
 * 接続IDの取得
 *
 * @access
 * @param
 * @return resource DB接続ID
 * @link
 * @see
 * @throws
 * @todo
 */
	function getConnID() {

		return $this->connectID;
	}


/*********************** ロック ***********************/
/**
 * DBロック
 *
 * @access
 * @param string $lockName ロック名
 * @param string $waitTime 最大待ち時間
 * @param int    $printLog ログファイル出力識別
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function setLock($lockName ,$waitTime ,$printLog=self::LOG_DEFAULT) {

		$result[self::ERR_STR_INDEX] = '';
		$result[self::ERR_ID_INDEX ] = self::ANY_ERROR;	/* 初期値:何かのエラー */

		/***** 実行 *****/
		$sqlStr = 'SELECT GET_LOCK(' . $this->setQuote($lockName) . ',' . $waitTime . ')';
		$sqlResult = $this->execute($sqlStr ,$this->connectID ,$printLog);
		$result[self::EXECUTE_RESULT] = $sqlResult[self::EXECUTE_RESULT];

		if($result[self::EXECUTE_RESULT]) {
			$execResult = $result[self::EXECUTE_RESULT];
			$rows = $this->getResultRows($execResult);
			if($rows >= 1) {	/* 実行結果があったとき */
				$fetchRow = mysql_fetch_row($execResult);

				/***** 実行結果の0桁目が1であればロック完了、0のときはタイムアウト、それ以外は何かのエラー *****/
				if($fetchRow[0] != NULL) {
					$lockResult = $fetchRow[0];

					if($lockResult == 0) {
						$result[self::ERR_ID_INDEX] = self::DB_LOCK_TIMEOUT;	/* タイムアウト */
					} else {
						if($lockResult == 1) {
							$result[self::ERR_ID_INDEX] = self::RET_NO_ERROR;	/* エラーナシ */
						}
					}
				}
			}
			$this->freeResult($execResult);
		}

		if($printLog == self::LOG_OUTPUT) {
			logFile5C::general('sql.setLock() DBロック：ロック名...' . $lockName . ' 最大待ち時間...' . $waitTime . ' リザルト...' . $result[self::ERR_ID_INDEX]);
		}

		return $result;
	}

/**
 * DBロックの解除
 *
 * @access
 * @param string $lockName setLock()で指定したロック名
 * @param int    $printLog ログファイル出力識別
 * @return resource 解除結果
 * @link
 * @see
 * @throws
 * @todo
 */
	function releaseLock($lockName ,$printLog=self::LOG_DEFAULT) {

		$result[self::ERR_STR_INDEX] = '';
		$result[self::ERR_ID_INDEX ] = self::ANY_ERROR;	/* 初期値:何かのエラー */

		/***** 実行 *****/
		$sqlStr = 'SELECT RELEASE_LOCK(' . $this->setQuote($lockName) . ')';
		$sqlResult = $this->execute($sqlStr ,$printLog);
		$result[self::EXECUTE_RESULT] = $sqlResult[self::EXECUTE_RESULT];

		if($result[self::EXECUTE_RESULT]) {
			$execResult = $result[self::EXECUTE_RESULT];
			$rows = $this->getResultRows($execResult);
			if($rows >= 1) {	/* 実行結果があったとき */
				$fetchRow = mysql_fetch_row($execResult);

				/***** 実行結果が1であればアンロック完了、それ以外は何かのエラー *****/
				if($fetchRow[0] != NULL) {
					$lockResult = $fetchRow[0];
					if($lockResult == 1) {
						$result[self::ERR_ID_INDEX] = self::RET_NO_ERROR;	/* エラーナシ */
					}
				}
			}
			$this->freeResult($execResult);
		}

		if($printLog == self::LOG_OUTPUT) {
			logFile5C::general('sql.releaseLock() DBロック解除：ロック名...' . $lockName . ' リザルト...' . $result[self::ERR_ID_INDEX]);
		}

		return $result;
	}


/*********************** トランザクション ***********************/
/**
 * トランザクションの開始
 *
 * @access
 * @return resource 実行結果
 * @link
 * @see
 * @throws
 * @todo
 */
	function transBegin() {

		$sqlResult[self::ERR_STR_INDEX] = '';
		$sqlResult[self::ERR_ID_INDEX ] = self::RET_NO_ERROR;

		/***** 自動書き込みOFF実行 *****/
		$result = mysql_query('SET AUTOCOMMIT=0' ,$this->connectID);
		logFile5C::SQL($sqlStr);

		if(!$result) {
			logFile5C::error('sql.transBegin() トランザクション開始エラー：' . mysql_error());
			$sqlResult[self::ERR_ID_INDEX] = self::DB_TRANS_BEGIN_ERR;
		}

		/***** トランザクション開始実行 *****/
		$result = mysql_query('START TRANSACTION' ,$this->connectID);
		logFile5C::SQL($sqlStr);

		if(!$result) {
			logFile5C::error('sql.transBegin() トランザクション開始エラー：' . mysql_error());
			$sqlResult[self::ERR_ID_INDEX] = self::DB_TRANS_BEGIN_ERR;
		}

		return $sqlResult;
	}

/**
 * トランザクションの書き込み
 *
 * @access
 * @return resource 実行結果
 * @link
 * @see
 * @throws
 * @todo
 */
	function transCommit() {

		$sqlResult[self::ERR_STR_INDEX] = '';
		$sqlResult[self::ERR_ID_INDEX ] = self::RET_NO_ERROR;

		/***** トランザクション書き込み実行 *****/
		$result = mysql_query('COMMIT' ,$this->connectID);
		logFile5C::SQL($sqlStr);

		if(!$result) {
			logFile5C::error('sql.transCommit() トランザクション書き込みエラー：' . mysql_error());
			$sqlResult[self::ERR_ID_INDEX] = self::DB_TRANS_WRITE_ERR;
		}

		return $sqlResult;
	}

/**
 * トランザクションのロールバック
 *
 * @access
 * @return resource 実行結果
 * @link
 * @see
 * @throws
 * @todo
 */
	function transRollBack() {

		$sqlResult[self::ERR_STR_INDEX] = '';
		$sqlResult[self::ERR_ID_INDEX ] = self::RET_NO_ERROR;

		/***** ロールバック実行 *****/
		$result = mysql_query('ROLLBACK' ,$this->connectID);
		logFile5C::SQL($sqlStr);

		if(!$result) {
			logFile5C::error('sql.transRollBack() トランザクションロールバックエラー：' . mysql_error());
			$sqlResult[self::ERR_ID_INDEX] = self::DB_TRANS_ROLLBACK_ERR;
		}
		return $sqlResult;
	}

/**
 * 文字列の"での囲み
 *
 * @access
 * @param string $value 元の文字列
 * @return string 加工後の文字列r
 * @link
 * @see
 * @throws
 * @todo
 */
	function setQuote($value) {

		if(get_magic_quotes_gpc()) {
			$value = stripslashes($value);
		}
		$value = "'" . mysql_real_escape_string($value) . "'";
		return $value;
	}

/**
 * レコードの存在確認
 *
 * テーブル内に条件式に一致するレコードの有無を確認する
 *
 * @access
 * @param string $table テーブル名
 * @param string $cond  条件式
 * @param int    $printLog ログファイル出力識別
 * @return bool true：レコード有、false：レコードナシ
 * @link
 * @see
 * @throws
 * @todo
 */
	function existRec($table ,$cond ,$printLog=self::LOG_DEFAULT) {

		/***** 初期値:レコードナシ *****/
		$searchResult = false;

		$sqlStr = 'select count(*) from ' . $table . ' where ' . $cond;
		$result = $this->execute($sqlStr ,$printLog);

		if($result[self::EXECUTE_RESULT]) {
			$execResult = $result[self::EXECUTE_RESULT];
			$rows = $this->getResultRows($execResult);
			if($rows >= 1) {	/* 実行結果があったとき */
				$fetchRow = mysql_fetch_row($execResult);
				if($fetchRow[0] >= 1) {
					$searchResult = true;
					/* logFile5C::debug("sqlFuncs.existRecord() レコード存在確認：該当データあり"); */
				}
			} else {
					/* logFile5C::error("sqlFuncs.existRecord() レコード存在確認：該当データナシ"); */
			}
			$this->freeResult($execResult);
		} else {
					/* logFile5C::error("sqlFuncs.existRecord() レコード存在確認：検索エラー"); */
		}

		return $searchResult;
	}

/**
 * レコードの存在確認
 *
 * テーブル内に条件式に一致するレコード数を得る
 *
 * @access
 * @param string $table テーブル名
 * @param string $cond  条件式
 * @param int    $printLog ログファイル出力識別
 * @return int  レコード数
 * @link
 * @see
 * @throws
 * @todo
 */
	function recCount($tableName ,$where ,$printLog=self::LOG_DEFAULT) {

		$rows = 0;		/***** 規定値:レコード数0 *****/

		$sqlStr = 'select count(*) from ' . $tableName;
		if(strlen($where) >= 1) {
			$sqlStr = $sqlStr . ' where ' . $where;
		}

		$result = $this->execute($sqlStr ,$printLog);
		if($result[self::EXECUTE_RESULT]) {
			$execResult = $result[self::EXECUTE_RESULT];
			$rows = $this->getResultRows($execResult);
			if($rows <= 0) {	/* 実行結果がなかったとき */
				logFile5C::error('sqlFuncs.recCount() レコード数取得:レコード数0');
			} else {	/* 実行結果があったとき */
				$fetchRow = mysql_fetch_row($execResult);		/* レコード数取得の本体 */
				$rows     = $fetchRow[0];
			}
			$this->freeResult($execResult);
		} else {
			logFile5C::error('sqlFuncs.recCount() レコード数取得エラー');
		}

		return $rows;
	}

/**
 * SQL文実行
 *
 * @access
 * @param string $sqlStr SQL文
 * @param int    $printLog ログファイル出力識別
 * @return array 検索結果のデータセット
 * @link
 * @see
 * @throws
 * @todo
 */
	function executeSQL($sqlStr ,$printLog=self::LOG_DEFAULT) {

		$result[self::ERR_STR_INDEX] = '';
		$result[self::ERR_ID_INDEX ] = self::RET_NO_ERROR;

		$result = $this->execute($sqlStr ,$printLog);

		$result['rows'] = 0;
		if($result[self::EXECUTE_RESULT]) {
			$execResult = $result[self::EXECUTE_RESULT];
			$result['rows'] = $this->getResultRows($execResult);
			while($fetchRow = mysql_fetch_row($execResult)) {
				$result['fetch'][] = $fetchRow;
			}
			$this->freeResult($execResult);
		}

		return $result;
	}


/**
 * レコード取得
 *
 * @access
 * @param string $table テーブル名
 * @param string $list フィールドリスト
 * @param string $cond 条件式
 * @param string $order 抽出順序
 * @param string $other その他のパラメータ
 * @param int    $printLog ログファイル出力識別
 * @return array 検索結果のデータセット
 * @link
 * @see
 * @throws
 * @todo
 */
	function selectRec($table ,$list ,$cond='' ,$order='' ,$other='' ,$printLog=self::LOG_DEFAULT) {

		$result[self::ERR_STR_INDEX] = '';
		$result[self::ERR_ID_INDEX ] = self::RET_NO_ERROR;

		/***** 取り出すフィールドの設定 *****/
		$sqlStr = 'select ' . $list . ' from ' . $table;

		/***** 条件があれば指定 *****/
		if(strlen($cond) >= 1) {
			$sqlStr = $sqlStr . ' where ' . $cond;
		}

		/***** 順序があれば指定 *****/
		if(strlen($order) >= 1) {
			$sqlStr = $sqlStr . ' order by ' . $order;
		}

		/***** その他のパラメータがあれば指定 *****/
		if(strlen($other) >= 1) {
			$sqlStr = $sqlStr . ' ' . $other;
		}

		$result = $this->execute($sqlStr ,$printLog);

		$result['rows'] = 0;
		if($result[self::EXECUTE_RESULT]) {
			$execResult = $result[self::EXECUTE_RESULT];
			$result['rows'] = $this->getResultRows($execResult);
			while($fetchRow = mysql_fetch_row($execResult)) {
				$result['fetch'][] = $fetchRow;
			}
			$this->freeResult($execResult);
		}

		return $result;
	}

/**
 * レコード追加
 *
 * @access
 * @param string $table     テーブル名
 * @param string $fldList   フィールドリスト
 * @param string $valueList データリスト
 * @param int    $printLog ログファイル出力識別
 * @return bool true：成功、false：エラー発生
 * @link
 * @see
 * @throws
 * @todo
 */
	function insertRec($table ,$fldList ,$valueList ,$printLog=self::LOG_DEFAULT) {

		$sqlStr = 'insert into ' . $table . ' (' . $fldList . ') values (' . $valueList . ')';
		$result = $this->execute($sqlStr ,$printLog);

		if($result[self::EXECUTE_RESULT]) {
			$result = true;
		} else {
			$result = false;
		}

		return $result;
	}

/**
 * レコード削除
 *
 * @access
 * @param string $table    テーブル名
 * @param string $cond     条件式
 * @param int    $printLog ログファイル出力識別
 * @return bool true：成功、false：エラー発生
 * @link
 * @see
 * @throws
 * @todo
 */
	function delRec($table ,$cond ,$printLog=self::LOG_DEFAULT) {

		$sqlStr = 'delete from ' . $table . ' where ' . $cond;
		$result = $this->execute($sqlStr ,$printLog);

		if($result[self::EXECUTE_RESULT]) {
			$result = true;
		} else {
			$result = false;
		}

		return $result;
	}

/**
 * レコード更新
 *
 * @access
 * @param string $table    テーブル名
 * @param string $list     更新フィールドと値のデータセット
 * @param string $cond     条件式
 * @param int    $printLog ログファイル出力識別
 * @return bool true：成功、false：エラー発生
 * @link
 * @see
 * @throws
 * @todo
 */
	function updateRec($table ,$list ,$cond ,$printLog=self::LOG_DEFAULT) {

		$sqlStr = 'update ' . $table . ' set ' . $list . ' where ' . $cond;
		$result = $this->execute($sqlStr ,$printLog);

		if($result[self::EXECUTE_RESULT]) {
			$result = true;
		} else {
			$result = false;
		}

		return $result;
	}


/*********************** sql文実行 ***********************/
/**
 * sql実行
 *
 * @access
 * @param string $sqlStr SQL文
 * @param int    $printLog ログファイル出力識別
 * @return array 実行結果
 * @link
 * @see
 * @throws
 * @todo
 */
	private function execute($sqlStr ,$printLog=self::LOG_DEFAULT) {

		$sqlResult[self::ERR_STR_INDEX] = '';
		$sqlResult[self::ERR_ID_INDEX ] = self::RET_NO_ERROR;

		mysql_query(self::SETCHARSET);

		/***** select文の実行結果 *****/
		$sqlResult[self::EXECUTE_RESULT] = mysql_query($sqlStr ,$this->connectID);
		if($printLog == self::LOG_OUTPUT) {
			logFile5C::SQL($sqlStr);
		}

		/***** SQLのエラーが起きていたらエラー内容を返す *****/
		if(!$sqlResult[self::EXECUTE_RESULT]) {
			$sqlResult[self::ERR_ID_INDEX] = self::ANY_ERROR;
			logFile5C::error('SQLエラー：' . mysql_error() . '：' . $sqlStr);
		}

		return $sqlResult;
	}

/**
 * select文の結果の行数
 *
 * @access
 * @param array $result execute()で得た実行結果
 * @return int 行数
 * @link
 * @see
 * @throws
 * @todo
 */
	function getResultRows($result) {
		return mysql_num_rows($result);
	}

/**
 * select文の結果の開放
 *
 * @access
 * @param array $result execute()で得た実行結果
 * @return resource 開放した結果
 * @link
 * @see
 * @throws
 * @todo
 */
	function freeResult($result) {
		return mysql_free_result($result);
	}


/**
 * オートインクリメントのリセット
 *
 * @param string $table    テーブル名
 * @param int    $printLog ログファイル出力の有無
 * @return bool 成功時はtrue、エラー時はfalse
 */
	public function resetAutoInc($table ,$printLog=self::LOG_DEFAULT) {

		$sqlStr = 'ALTER TABLE ' . $table . ' AUTO_INCREMENT = 1';
		$result = $this->execute($sqlStr ,$printLog);

		if($result[self::EXECUTE_RESULT]) {
			$result = true;
		} else {
			$result = false;
		}

		return $result;
	}


/**
 * 直前のauto incrementの値
 *
 * @param string $table    テーブル名
 * @param int    $printLog ログファイル出力の有無
 * @return int auto incrementの値、エラー時は0
 */
	public function getLastInsertID($table ,$printLog=self::LOG_DEFAULT) {

					/* $sqlStr = 'select last_insert_id() from ' . $table; */
		$sqlStr = 'select last_insert_id()';
		$result = $this->execute($sqlStr ,$printLog);

		$ret = 0;
		if($result[self::EXECUTE_RESULT]) {
			$execResult = $result[self::EXECUTE_RESULT];
			$fetchRow = mysql_fetch_row($execResult);
			$ret = $fetchRow[0];

			$this->freeResult($execResult);
		}

		return $ret;
	}
}
?>
