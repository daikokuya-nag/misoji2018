<?php
/**
 * 各種関数
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */

	require_once dirname(__FILE__) . '/strings5C.php';
	require_once dirname(__FILE__) . '/common5C.php';

/**
 * 各種関数
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */
class funcs5C {

/**
 * ディレクトリ削除
 *
 * 指定されたディレクトリを削除する
 *
 * @access
 * @param string $dir 対象ディレクトリ
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function removeDir($dir) {

		if($handle = opendir($dir)) {
			while (false !== ($item = readdir($handle))) {
				if(strcmp($item ,'.' ) != 0 
				&& strcmp($item ,'..') != 0) {
					$str = $dir . '/' . $item;
					if (is_dir($str)) {
						self::removeDir($str);
					} else {
						unlink($str);
					}
				}
			}
			closedir($handle);
			rmdir($dir);
		}
	}

/**
 * ファイル読み込み
 *
 * @access
 * @param string $fileName ファイル名
 * @return array ファイルの内容
 * @link
 * @see
 * @throws
 * @todo
 */
	function readFile($fileName) {

		// ファイルを読み込み、行ごとに分解する
		$lineTmpA = file($fileName);
		if(count($lineTmpA) <= 1) {
			// file()で改行コードで分離されないときは強制的に分離する
			$lineTmpB = explode(common5C::CTXT_NL_CODE ,$lineTmpA[0]);
		} else {
			$lineTmpB = $lineTmpA;
		}

		// 行末の改行コードを消す
		$ret = array();
		foreach($lineTmpB as $line1) {
			$ret[] = strings5C::eraseCRLF($line1);
		}

		return $ret;
	}

/**
 * 配列内容の連結
 *
 * @access
 * @param array $arr 対象配列
 * @param string $quote 囲み文字
 * @return string 連結した内容
 * @link
 * @see
 * @throws
 * @todo
 */
	function setFldArrToList($arr ,$quote='') {

		$ret = $quote . $arr[0] . $quote;

		$arrMax = count($arr);
		if($arrMax >= 2) {
			for($idx=1 ;$idx<$arrMax ;$idx++) {
				$ret = $ret . ',' . $quote . $arr[$idx] . $quote;
			}
		}

		return $ret;
	}

/**
 * 色の明るさの取得
 *
 * @access
 * @param string $color #から始まる7桁のカラーコード
 * @return int 明るさ(0～255)
 * @link
 * @see
 * @throws
 * @todo
 */
	function getBrightness($color) {

		$r = substr($color ,1 ,2);
		$g = substr($color ,3 ,2);
		$b = substr($color ,5 ,2);

		$rDec = hexdec($r);
		$gDec = hexdec($g);
		$bDec = hexdec($b);

		$brightness = $rDec * 0.21 + $gDec * 0.72 + $bDec * 0.07;

		return $brightness;
	}
}
?>
