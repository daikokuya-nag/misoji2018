<?php
/**
 * テンプレートファイルのアクセス
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */

	require_once dirname(__FILE__) . '/templateConst5C.php';
	require_once dirname(__FILE__) . '/funcs5C.php';

/**
 * テンプレートファイルのアクセス
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */
class template5C {

	var $rangeList;		//範囲キーワードリスト
	var $begValList;	//開始キーワードリスト
	var $endValList;	//終了キーワードリスト

	var $dividedBySect;	//セクションごとに区切った内容
	var $useSect;		//使用しているセクション

	var $fileLines;		//ファイルの行毎の内容

/**
 * コンストラクタ
 *
 * @access
 * @param
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function template5C() {

		$templateVals = new templateConst5C();

		$rangeList = $templateVals->getSectList();
		$this->rangeList  = $rangeList;
		$this->begValList = $rangeList['BEG_LIST'];
		$this->endValList = $rangeList['END_LIST'];

		$this->fileLines = array();
	}


/**
 * テンプレートファイルの読み込み
 *
 * @access
 * @param string $fileName テンプレートファイル名
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function read($fileName) {

		if(is_file($fileName)) {
			$this->fileLines = funcs5C::readFile($fileName);
		} else {
			$this->fileLines[] = '';
		}
	}


/**
 * セクション毎に分離
 *
 * 読みこんだテンプレートファイルの内容をセクション毎に分離する
 *
 * @access
 * @param
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function divideSection() {

		$inAnySection = false;		// BEG-ENDの間か
		$divNum = 0;
		$endKwd = '';
		$dividedBySect = array();
		$useSect       = array();

		foreach($this->fileLines as $line1) {
			if($inAnySection) {				// BEG-ENDの間であればENDかどうかを判定。ENDであればBEG-ENDの終了　ENDでなければ続く
							//print 'in beg-end ' . "\n";
				if(strcmp($line1 ,$endKwd) == 0) {
					/* ENDのとき */
							//print 'matched end' . "\n";
					$dividedBySect[$divNum][] = $line1;
					$inAnySection = false;
					$divNum++;	// 次の塊へ
				} else {	// ENDでないとき
							// print 'unmatched beg<br>';
					$dividedBySect[$divNum][] = $line1;
				}
			} else {						// BEG-ENDの間でなければどのBEGかを探す。どれでもなければ「その他」
							//print 'not in beg-end ' . "\n";
				$hit = false;
				foreach($this->begValList as $sectIdx => $begKWD) {
							//print $sectIdx . ' ' . $begKWD . "\n";
					if(strcmp($line1 ,$begKWD) == 0) {
						//$sectKWD = $this->sectionList[$sectIdx];
						$hit = true;
						break;
					}
				}
				if($hit) {		// BEGに一致
									//print 'matched beg by ' . $sectIdx . "\n";
					if(isset($dividedBySect[$divNum])) {
						if(count($dividedBySect[$divNum]) >= 1) {
							$divNum++;
						}
					}
					$dividedBySect[$divNum][] = $line1;
					$useSect[$divNum]         = $sectIdx;

					$endKwd = $this->endValList[$sectIdx];
							//print 'end kwd...' . $endKwd . "\n";
					$inAnySection = true;
				} else {		// BEGに一致しない
							//print 'unmatched beg' . "\n";
					$dividedBySect[$divNum][] = $line1;
				}
			}
		}

		$this->dividedBySect = $dividedBySect;
						//print_r($dividedBySect);

		$this->useSect       = $useSect;
						//print_r($useSect);
	}


/**
 * 使用しているセクション名の取得
 *
 * @access
 * @param
 * @return array セクション名のリスト
 * @link
 * @see
 * @throws
 * @todo
 */
	function sectItems() {

		$ret = array();
		foreach($this->useSect as $idx => $ID) {
			$ret[] = $ID;
		}

		return $ret;
	}


/**
 * セクション毎のソースファイルの内容
 *
 * @access
 * @param
 * @return array セクション毎のソースファイルの内容
 * @link
 * @see
 * @throws
 * @todo
 */
	function getDividedBySect() {

		return $this->dividedBySect;
	}

/**
 * 使用しているセクション名リストの取得
 *
 * @access
 * @param
 * @return array セクション名のリスト
 * @link
 * @see
 * @throws
 * @todo
 */
	function getUseSect() {

		return $this->useSect;
	}
}
?>
