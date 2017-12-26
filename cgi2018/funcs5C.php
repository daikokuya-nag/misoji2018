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

		$ret = file_get_contents($fileName);		/* file */

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
}
?>
