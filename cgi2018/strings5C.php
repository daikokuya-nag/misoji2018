<?php
/**
 * 文字列操作関数群
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */


/**
 * 文字列操作関数群
 *
 * @copyright
 * @license
 * @author
 * @link
 */
class strings5C {

	/********************
	
	パラメータ：対象文字列
	戻り値：　　加工後の文字列
	********************/


/**
 * 改行コードの削除
 *
 * @access
 * @param string $str 元の文字列
 * @return string 改行コードを削除した文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function eraseCRLF($str) {

		$wkStr = $str;
		$wkStr = mb_ereg_replace("\r\n" ,'' ,$wkStr);
		$wkStr = mb_ereg_replace("\n"   ,'' ,$wkStr);
		$wkStr = mb_ereg_replace("\r"   ,'' ,$wkStr);

		return $wkStr;
	}

/**
 * 改行コードを<br />に変換
 *
 * 改行コードを<br />に変換し、<br>の後ろに改行コードが入る。
 *
 * @access
 * @param string $str 元の文字列
 * @return string 改行コードを<br />に変換した文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function cnvCRLFToBR($str) {

		$wkStr = $str;

		$wkStr = mb_ereg_replace("\r\n" ,"\n"     ,$wkStr);
		$wkStr = mb_ereg_replace("\r"   ,"\n"     ,$wkStr);
		$wkStr = mb_ereg_replace("\n"   ,"<br>\n" ,$wkStr);

		return $wkStr;
	}

/**
 * 改行コードを<br />に変換
 *
 * 改行コードを<br />に変換する。cnvCRLFToBRとは異なり<br>の後ろには改行コードは入らない。
 *
 * @access
 * @param string $str 元の文字列
 * @return string 改行コードを<br />に変換した文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function cnvCRLFToBRNOCR($str) {

		$wkStr = $str;

		$wkStr = mb_ereg_replace("\r\n" ,"\n"   ,$wkStr);
		$wkStr = mb_ereg_replace("\r"   ,"\n"   ,$wkStr);
		$wkStr = mb_ereg_replace("\n"   ,'<br>' ,$wkStr);

		return $wkStr;
	}

/**
 * 改行コードを' 'に変換
 *
 * @access
 * @param string $str 元の文字列
 * @return string 改行コードを' 'に変換した文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function cnvCRLFToSPC($str) {

		$wkStr = $str;

		$wkStr = mb_ereg_replace("\r\n" ,"\n" ,$wkStr);
		$wkStr = mb_ereg_replace("\r"   ,"\n" ,$wkStr);
		$wkStr = mb_ereg_replace("\n"   ,' '  ,$wkStr);

		return $wkStr;
	}

/**
 * 改行コードを改行文字に変換
 *
 * @access
 * @param string $str 元の文字列
 * @return string 改行コードを改行文字に変換した文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function cnvCRLFToSTR($str) {

		$wkStr = $str;

		$wkStr = str_replace("\r\n" ,'\r\n' ,$wkStr);
		$wkStr = str_replace("\r"   ,'\r'   ,$wkStr);
		$wkStr = str_replace("\n"   ,'\n'   ,$wkStr);

		return $wkStr;
	}

/**
 * 改行文字を改行コードに変換
 *
 * @access
 * @param string $str 元の文字列
 * @return string 改行文字を改行コードに変換した文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function cnvSTRToCRLF($str) {

		$wkStr = $str;

		$wkStr = str_replace("\\r\\n" ,'\r\n' ,$wkStr);
		$wkStr = str_replace("\\r"    ,"\r"   ,$wkStr);
		$wkStr = str_replace("\\n"    ,"\n"   ,$wkStr);

		return $wkStr;
	}

/**
 * 文字列内の文字列の有無
 *
 * @access
 * @param string $str    元の対象文字列
 * @param string $strSub 検索する文字列
 * @return string 検索する文字列があった位置（-1のときは検索する文字列ナシ）
 * @link
 * @see
 * @throws
 * @todo
 */
	function existStr($str ,$strSub) {

		$endStr = strpos($str ,$strSub);
		if($endStr === false) {
			$retCD = -1;
		} else {
			$retCD = $endStr;
		}

		return $retCD;
	}

/**
 * 文字列内の文字列の有無（マルチバイト）
 *
 * @access
 * @param string $str    元の対象文字列
 * @param string $strSub 検索する文字列
 * @return string 検索する文字列があった位置（-1のときは検索する文字列ナシ）
 * @link
 * @see
 * @throws
 * @todo
 */
	function mb_existStr($str ,$strSub) {

		$endStr = mb_strpos($str ,$strSub);
		if($endStr === false) {
			$retCD = -1;
		} else {
			$retCD = $endStr;
		}

		return $retCD;
	}

/**
 * 文字列内の<>&"の一括変換
 *
 * @access
 * @param string $orgStr 対象文字列
 * @return string 加工後の文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	/********************
	文字列内の<>&"の一括変換
	パラメータ：対象文字列
	戻り値　　：加工後の文字列
	********************/
	function cnvSPChar($orgStr) {
			/*
			元の記号 10進数 16進数 変換後
			"""      &#34;  &#x22; &quot;  quotation mark = APL quote
			"&"      &#38;  &#x26; &amp;   ampersand
			"<"      &#60;  &#x3C; &lt;    less-than sign
			">"      &#62;  &#x3E; &gt;    greater-than sign
			" "      &#160; &#xA0; &nbsp;  no-break space = non-breaking space
			*/

		$wkStr = htmlspecialchars($orgStr);		/* <>&"を一括変換する。 */
		$wkStr = mb_ereg_replace(" "  ,'&nbsp;' ,$wkStr);

		return $wkStr;
	}


/**
 * 文字列の右寄せ
 *
 * @access
 * @param string $orgStr 対象文字列
 * @param string maxLen  半角での最大長さ
 * @return string 加工後の文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function setPadding($str ,$maxLen) {

		$ret = '';

		$len = strlen($str);
		if($len < $maxLen) {
			$cntMax = $maxLen - $len;
			for($cnt=1 ;$cnt<=$cntMax ;$cnt++) {
				$ret = $ret . ' ';
			}
		}
		$ret = $ret . $str;

		return $ret;
	}
}
?>
