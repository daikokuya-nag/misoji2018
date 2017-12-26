<?php
/*************************
テンプレートファイル読み込み Version 1.0
PHP5
2016 Mar. 3 ver 1.0
*************************/

	require_once dirname(__FILE__) . '/../strings5C.php';
	require_once dirname(__FILE__) . '/../common5C.php';

class htmlTemplate5C {

	/********************
	テンプレートファイル読み込み
	パラメータ：テンプレートファイル名
	戻り値　　：-
	********************/
	function read($templateFile) {

		$newsLineTmp = file($templateFile);
		if(count($newsLineTmp) <= 1) {
			$line = explode(common5C::CSRC_NL_CODE ,$newsLineTmp[0]);
		} else {
			$line = $newsLineTmp;
		}

		$ret['count'] = count($line);

		for($lineIdx=0 ;$lineIdx<$ret['count'] ;$lineIdx++) {
			$ret['line'][$lineIdx] = strings5C::eraseCRLF($line[$lineIdx]);
		}

		return $ret;
	}
}
?>
