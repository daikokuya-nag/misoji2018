<?php
/**
 * 共通項目
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */


/**
 * 共通項目
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */
class common5C {

	const PRE_MOVE_LOCATION = 'HTTP/1.1 301 Moved Permanently';

	/* CR \r 0x0d */
	/* LF \n 0x0a */
	const CTXT_NL_CODE = "\r";	/* テキストファイル */
	const CSRC_NL_CODE = "\r";	/* PHPソースコード,HTMLソースコード */	/* define('CR_CODE' , "\r"); */

	/***** 装飾指定 *****/
	const DECO_CSS_CLASS = 'C';	/* class */
	const DECO_CSS_STYLE = 'S';	/* style */
	const DECO_FONT      = 'F';	/* style sheet以外 */


/**
 * 不足関数の追加
 *
 * @access
 * @param
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function compatible() {
		if(!function_exists("json_encode")) {
			function json_encode($object) {
				/* require_once("Services/JSON.php"); */
				require_once dirname(__FILE__) . '/JSON.php';
				$json = new Services_JSON();
				return $json->encode($object);
			}
		}
	}
}
?>
