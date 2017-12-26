<?php
/**
 * 仮のタグ
 *
 * 今回使用するのは装飾タグの削除で、他の関数は互換用の残してある。
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */


	require_once dirname(__FILE__) . '/html/htmlNews5C.php';

	require_once dirname(__FILE__) . '/siteConst5C.php';
	require_once dirname(__FILE__) . '/strings5C.php';

/**
 * 仮のタグ
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */
class proviTag5C {

	/***** キーワード *****/
	/*** 記事本体へリンク ***/
	const MAIN_CONTENT_KWD = 'CNTLINK';
	const MAIN_CONTENT_BEG = '<CNTLINK';		/* 開始 */
	const MAIN_CONTENT_END = '</CNTLINK>';		/* 終了 */

	/*** 画像 ***/
	const IMGLINK_KWD = 'IMGLINK';
	const IMGLINK_BEG = '<IMGLINK';		/* 開始 */
	const IMGLINK_END = '</IMGLINK>';	/* 終了 */

	/*** 女性 ***/
	const GIRLLINK_KWD = 'GIRLLINK';
	const GIRLLINK_BEG = '<GIRLLINK';		/* 開始 */
	const GIRLLINK_END = '</GIRLLINK>';		/* 終了 */

	/*** 色 ***/
	const COLOR_KWD = 'COLOR';
	const COLOR_BEG = '<COLOR';			/* 開始 */
	const COLOR_END = '</COLOR>';		/* 終了 */

	const COLOR_BLACK  = 'Black';
	const COLOR_GRAY   = 'Gray';
	const COLOR_SILVER = 'Silver';
	const COLOR_WHITE  = 'White';
	const COLOR_RED    = 'Red';
	const COLOR_YELLOW = 'Yellow';
	const COLOR_LIME   = 'Lime';
	const COLOR_AQUA   = 'Aqua';
	const COLOR_BLUE   = 'Blue';
	const COLOR_ORANGE = 'Orange';

	const COLOR_FUCHSIA = 'Fuchsia';
	const COLOR_MAROON  = 'Maroon';
	const COLOR_OLIVE   = 'Olive';
	const COLOR_GREEN   = 'Green';
	const COLOR_TEAL    = 'Teal';
	const COLOR_NAVY    = 'Navy';
	const COLOR_PURPLE  = 'Purple';

	/*** ボールド ***/
	const BOLD_KWD = 'BOLD';
	const BOLD_BEG = '<BOLD>';				/* 開始 */
	const BOLD_END = '</BOLD>';				/* 終了 */


	/*** 画像表示 ***/
	const SHOWIMG_KWD = 'IMG';
	const SHOWIMG_BEG = '<IMG>';			/* 開始 */
	const SHOWIMG_END = '</IMG>';			/* 終了 */


	/*** 文字サイズ ***/
	const FONT_SIZE_KWD = 'FONTSZ';
	const FONT_SIZE_BEG = '<FONTSZ';		/* 開始 */
	const FONT_SIZE_END = '</FONTSZ>';		/* 終了 */

	/*** 文字サイズ　一回り大きい ***/
	const FONT_SIZE_L  = 'L';
	const FONT_SIZE_P1 = '+1';


	/*** 出勤情報 ***/
	const WORKINFO_KWD = 'WORKINFO';
	const WORKINFO_BEG = '<WORKINFO';		/* 開始 */
	const WORKINFO_END = '</WORKINFO>';		/* 終了 */

	/*** 出勤情報のサイト ***/
	const WORKINFO_YO = '_YO';
	const WORKINFO_HN = '_HN';


	/*** 電話番号表示 ***/
	const TELNO_DISP_KWD = 'TELNO_DISP';
	const TELNO_DISP_BEG = '<TELNO_DISP>';


	/*** 電話をかける ***/
	const TELCALL_KWD = 'TEL_CALL';
	const TELCALL_BEG = '<TEL_CALL>';		/* 開始 */
	const TELCALL_END = '</TEL_CALL>';		/* 終了 */


	/*** 新着情報へ ***/
	const TONEWS_KWD = 'TONEWS';
	const TONEWS_BEG = '<TONEWS';		/* 開始 */
	const TONEWS_END = '</TONEWS>';		/* 終了 */


	/*** 外部リンク ***/
	const OUT_LINK_KWD = 'OUTLINK';
	const OUT_LINK_BEG = '<OUTLINK';	/* 開始 */
	const OUT_LINK_END = '</OUTLINK>';	/* 終了 */

	/*** 外部リンクのtarget指定 ***/
	const OUT_LINK_TARGET_SAME  = "0";	/* 同一画面 */
	const OUT_LINK_TARGET_OTHER = "2";	/* 別画面 */


	const SP_KWD = ':';					/* キーワードセパレータ */

	/* リンクタグの置き換え */
	const CURL_REPL  = 'URL';
	const CURL_ERASE = 'DEL';

	var $str;

/**
 * 新着情報の装飾タグの削除
 *
 * @access
 * @param string $orgStr 元の文字列
 * @return string 加工後の文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function delNewsTag($orgStr) {

		$this->str = $orgStr;

		$this->delTagMain(self::MAIN_CONTENT_KWD);	/* 記事本体へのリンク */
		$this->delTagMain(self::IMGLINK_KWD);		/* 画像 */
		$this->delTagMain(self::GIRLLINK_KWD);		/* 女性 */
		$this->delTagMain(self::COLOR_KWD);			/* 色 */
		$this->delTagMain(self::BOLD_KWD);			/* ボールド */
		$this->delTagMain(self::SHOWIMG_KWD);		/* 画像表示 */
		$this->delTagMain(self::FONT_SIZE_KWD);		/* 文字サイズ */
		$this->delTagMain(self::TONEWS_KWD);		/* 新着情報へ */
		$this->delTagMain(self::OUT_LINK_KWD);		/* 外部リンク */
		$this->delTagMain(self::WORKINFO_KWD);		/* 出勤情報 */

		return $this->str;
	}

/**
 * ブログ記事の装飾タグの削除
 *
 * @access
 * @param string $orgStr 元の文字列
 * @return string 加工後の文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function delBlogTag($orgStr) {

		$this->str = $orgStr;

		$this->delTagMain(self::MAIN_CONTENT_KWD);	/* 記事本体へのリンク */
		$this->delTagMain(self::IMGLINK_KWD);		/* 画像 */
		$this->delTagMain(self::GIRLLINK_KWD);		/* 女性 */
		$this->delTagMain(self::COLOR_KWD);			/* 色 */
		$this->delTagMain(self::BOLD_KWD);			/* ボールド */
		$this->delTagMain(self::SHOWIMG_KWD);		/* 画像表示 */
		$this->delTagMain(self::FONT_SIZE_KWD);		/* 文字サイズ */
		$this->delTagMain(self::TONEWS_KWD);		/* 新着情報へ */
		$this->delTagMain(self::OUT_LINK_KWD);		/* 外部リンク */
		$this->delTagMain(self::WORKINFO_KWD);		/* 出勤情報 */

		return $this->str;
	}


/**
 * 装飾タグの削除の本体
 *
 * @access
 * @param string $kwd 削除するタグ名
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	private function delTagMain($kwd) {

		$str = $this->str;

		$begTag = '<' . $kwd;			/* 開始タグ */
		$endTag = '</' . $kwd . '>';	/* 終了タグ */

		/***** 終了タグの削除 *****/
		$str = mb_ereg_replace($endTag ,'' ,$str);

		/***** 開始タグの削除 *****/
		while(true) {
			$begPos = strings5C::mb_existStr($str ,$begTag);
			/*** 開始タグの開始位置がなければ終了 ***/
			if($begPos <= -1) {
				break;
			}

			/*** 開始タグの終了位置の取出し ***/
			$endIdx = mb_strpos($str ,'>' ,$begPos);
			if($endIdx === false) {
				break;
			} else {
				$endPos = $endIdx;
			}

			$delLen = $endPos - $begPos + 1;
			$delStr = mb_substr($str ,$begPos ,$delLen);

			/*** タグ文字の削除 ***/
			$str = mb_ereg_replace($delStr ,'' ,$str);
		}

		$this->str = $str;
	}

/**
 * 装飾の変換(styleで変換)
 *
 * @access
 * @param string $orgStr 元の文字列
 * @return string 変換後の文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function convKWDS($orgStr) {

		$ret = $orgStr;

		$ret = mb_ereg_replace(self::BOLD_BEG ,'<b>'  ,$ret);
		$ret = mb_ereg_replace(self::BOLD_END ,'</b>' ,$ret);

		$ret = mb_ereg_replace(self::FONT_SIZE_BEG . self::FONT_SIZE_L . '>' ,'<span style="font-size:large;">' ,$ret);
		$ret = mb_ereg_replace(self::FONT_SIZE_END ,'</span>' ,$ret);

		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_BLACK)   ,'<span style="color:black;">'   ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_GRAY)    ,'<span style="color:gray;">'    ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_SILVER)  ,'<span style="color:silver;">'  ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_WHITE)   ,'<span style="color:white;">'   ,$ret);

		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_RED)     ,'<span style="color:red;">'     ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_YELLOW)  ,'<span style="color:yellow;">'  ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_LIME)    ,'<span style="color:lime;">'    ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_AQUA)    ,'<span style="color:aqua;">'    ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_BLUE)    ,'<span style="color:blue;">'    ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_ORANGE)  ,'<span style="color:orange;">'  ,$ret);

		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_FUCHSIA) ,'<span style="color:fuchsia;">' ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_MAROON)  ,'<span style="color:maroon;">'  ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_OLIVE)   ,'<span style="color:olive;">'   ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_GREEN)   ,'<span style="color:green;">'   ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_TEAL)    ,'<span style="color:teal;">'    ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_NAVY)    ,'<span style="color:navy;">'    ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_PURPLE)  ,'<span style="color:purple;">'  ,$ret);

		$ret = mb_ereg_replace(self::COLOR_END ,'</span>' ,$ret);

		return $ret;
	}

/**
 * 装飾の変換(classで変換)
 *
 * @access
 * @param string $orgStr 元の文字列
 * @return string 変換後の文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function convKWDC($orgStr) {

		$ret = $orgStr;

		$ret = mb_ereg_replace(self::BOLD_BEG ,'<b>'  ,$ret);
		$ret = mb_ereg_replace(self::BOLD_END ,'</b>' ,$ret);

		$ret = mb_ereg_replace(self::FONT_SIZE_BEG . self::FONT_SIZE_L . '>' ,'<span class="fontLarge">' ,$ret);
		$ret = mb_ereg_replace(self::FONT_SIZE_END ,'</span>' ,$ret);

		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_BLACK)   ,'<span class="colorBlack">'   ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_GRAY)    ,'<span class="colorGray">'    ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_SILVER)  ,'<span class="colorSilver">'  ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_WHITE)   ,'<span class="colorWhite">'   ,$ret);

		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_RED)     ,'<span class="colorRed">'     ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_YELLOW)  ,'<span class="colorYellow">'  ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_LIME)    ,'<span class="colorLime">'    ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_AQUA)    ,'<span class="colorAqua">'    ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_BLUE)    ,'<span class="colorBlue">'    ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_ORANGE)  ,'<span class="colorOrange">'  ,$ret);

		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_FUCHSIA) ,'<span class="colorFuchsia">' ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_MAROON)  ,'<span class="colorMaroon">'  ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_OLIVE)   ,'<span class="colorOlive">'   ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_GREEN)   ,'<span class="colorGreen">'   ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_TEAL)    ,'<span class="colorTeal">'    ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_NAVY)    ,'<span class="colorNavy">'    ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_PURPLE)  ,'<span class="colorPurple">'  ,$ret);

		$ret = mb_ereg_replace(self::COLOR_END ,'</span>' ,$ret);

		return $ret;
	}

/**
 * 装飾の変換(style sheet以外で変換)
 *
 * @access
 * @param string $orgStr 元の文字列
 * @return string 変換後の文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function convKWDF($orgStr) {

		$ret = $orgStr;

		$ret = mb_ereg_replace(self::BOLD_BEG ,'<b>'  ,$ret);
		$ret = mb_ereg_replace(self::BOLD_END ,'</b>' ,$ret);

		$ret = mb_ereg_replace(self::FONT_SIZE_BEG . self::FONT_SIZE_L . '>' ,'<font size="+1">' ,$ret);
		$ret = mb_ereg_replace(self::FONT_SIZE_END ,'</font>' ,$ret);

		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_BLACK)   ,'<font color="#000000">' ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_GRAY)    ,'<font color="#808080">' ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_SILVER)  ,'<font color="#c0c0c0">' ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_WHITE)   ,'<font color="#ffffff">' ,$ret);

		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_RED)     ,'<font color="#ff0000">' ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_YELLOW)  ,'<font color="#ffff00">' ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_LIME)    ,'<font color="#00ff00">' ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_AQUA)    ,'<font color="#00ffff">' ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_BLUE)    ,'<font color="#0000ff">' ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_ORANGE)  ,'<font color="#ffa500">' ,$ret);

		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_FUCHSIA) ,'<font color="#ff00ff">' ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_MAROON)  ,'<font color="#800000">' ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_OLIVE)   ,'<font color="#808000">' ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_GREEN)   ,'<font color="#008000">' ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_TEAL)    ,'<font color="#008080">' ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_NAVY)    ,'<font color="#000080">' ,$ret);
		$ret = mb_ereg_replace(self::setColorKWD(self::COLOR_PURPLE)  ,'<font color="#800080">' ,$ret);

		$ret = mb_ereg_replace(self::COLOR_END ,'</font>' ,$ret);

		return $ret;
	}

/**
 * 色指定の仮のタグの組み立て
 *
 * @access
 * @param string $color 色
 * @return string 変換後の文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	private function setColorKWD($color) {

		return self::COLOR_BEG . $color . '>';
	}

/**
 * ニュースページへのリンクの変換
 *
 * @access
 * @param string $orgStr 元の文字列
 * @return string 変換後の文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function goNewsTop($orgStr) {

		$ret = $orgStr;

		$ret = mb_ereg_replace(self::TONEWS_BEG . '>' ,'<a href="news.html">' ,$ret);
		$ret = mb_ereg_replace(self::TONEWS_END       ,'</a>'                 ,$ret);

		return $ret;
	}

/**
 * リンク指定の変換
 *
 * @access
 * @param string $str 元の文字列
 * @param string $mode リンクタグの扱い　URL：本来のタグへ変換、DEL：削除（タグは表示しない）
 * @return string 変換後の文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	private function convURL($str ,$mode) {

		$ret = $str;

		while(true) {
			$begPos = strings5C::existStr($ret ,self::OUT_LINK_BEG);

			if($begPos >= 0) {
				$endPos = strpos($ret ,'>' ,$begPos);
				if($endPos === false) {
					break;
				}

				/***** リンク指定があったとき *****/
				$partA = substr($ret ,0 ,$begPos);
				$partB = substr($ret ,$endPos + 1);

				if(strcmp($mode ,htmlNews5C::CURL_REPL) == 0) {
					/* リンクタグの置き換え */
					$len = $endPos - $begPos;
					$partCORG = substr($ret ,$begPos ,$len);

					$URL    = substr($partCORG ,8 + 2 ,$len - 1);
					$target = substr($partCORG ,8     ,1);

					if(strcmp($target ,self::OUT_LINK_TARGET_OTHER) == 0) {
						/* 別画面表示 */
						$targetStr = ' target="_blank"';
					} else {
						/* 同一画面表示 */
						$targetStr = '';
					}
					$partC  = '<a href="' . $URL . '"' . $targetStr . '>';

					$ret = $partA . $partC . $partB;
				} else {
					/* 消すとき */
					$ret = $partA . $partB;
				}
			} else {
				/* リンク指定がないとき */
				break;
			}
		}

		$ret = str_replace(self::OUT_LINK_END ,'</a>'  ,$ret);

		return $ret;
	}


/**
 * リンク指定の変換（マルチバイト対応）
 *
 * @access
 * @param string $str 元の文字列
 * @param string $mode リンクタグの扱い　URL：本来のタグへ変換、DEL：削除（タグは表示しない）
 * @return string 変換後の文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function convURLMB($str ,$mode) {

		$ret = $str;

		while(true) {
			$begPos = strings5C::mb_existStr($ret ,self::OUT_LINK_BEG);

			if($begPos >= 0) {
				$endPos = mb_strpos($ret ,'>' ,$begPos);
				if($endPos === false) {
					break;
				}

				/***** リンク指定があったとき *****/
				$partA = mb_substr($ret ,0 ,$begPos);
				$partB = mb_substr($ret ,$endPos + 1);

				if(strcmp($mode ,htmlNews5C::CURL_REPL) == 0) {
					/* リンクタグの置き換え */
					$len = $endPos - $begPos;
					$partCORG = mb_substr($ret ,$begPos ,$len);

					$URL    = mb_substr($partCORG ,8 + 2 ,$len - 1);
					$target = mb_substr($partCORG ,8     ,1);

					if(strcmp($target ,self::OUT_LINK_TARGET_OTHER) == 0) {
						/* 別画面表示 */
						$targetStr = ' target="_blank"';
					} else {
						/* 同一画面表示 */
						$targetStr = '';
					}
					$partC  = '<a href="' . $URL . '"' . $targetStr . '>';

					$ret = $partA . $partC . $partB;
				} else {
					/* 消すとき */
					$ret = $partA . $partB;
				}
			} else {
				/* リンク指定がないとき */
				break;
			}
		}

		$ret = str_replace(self::OUT_LINK_END ,'</a>'  ,$ret);

		return $ret;
	}

/**
 * 出勤情報の変換（マルチバイト対応）
 *
 * @access
 * @param string $str 元の文字列
 * @param string $mode リンクタグの扱い　URL：本来のタグへ変換、DEL：削除（タグは表示しない）
 * @return string 変換後の文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function convWorkInfoMB($str ,$mode) {

		$ret = $str;
					/*print 'convWorkInfoMB()' . $ret;*/
		while(true) {
			$begPos = strings5C::mb_existStr($ret ,self::WORKINFO_BEG);
					/*print $begPos;*/
			if($begPos >= 0) {
				$endPos = mb_strpos($ret ,'>' ,$begPos);
				if($endPos === false) {
					break;
				}

				/***** リンク指定があったとき *****/
				$begEndPos = $begPos + strlen(self::WORKINFO_BEG);
				$uriKwd    = mb_substr($ret ,$begEndPos ,3);
				$URL = '';
				if(strcmp($uriKwd ,self::WORKINFO_YO) == 0) {
					$URL = siteConst5C::WORKINFO_URI_YORU;
				}
				if(strcmp($uriKwd ,self::WORKINFO_HN) == 0) {
					$URL = siteConst5C::WORKINFO_URI_HEAVEN;
				}

				$partA = mb_substr($ret ,0 ,$begPos);
				$partB = mb_substr($ret ,$endPos + 1);

				if(strcmp($mode ,htmlNews5C::CURL_REPL) == 0) {
					/* リンクタグの置き換え */
					$len = $endPos - $begPos;
					$partCORG = mb_substr($ret ,$begPos ,$len);

					/* 表示は別画面で固定 */
							/* $URL    = mb_substr($partCORG ,8 + 2 ,$len - 1); */
							/* $target = mb_substr($partCORG ,8     ,1); */
					$target = self::OUT_LINK_TARGET_OTHER;

					if(strcmp($target ,self::OUT_LINK_TARGET_OTHER) == 0) {
						/* 別画面表示 */
						$targetStr = ' target="_blank"';
					} else {
						/* 同一画面表示 */
						$targetStr = '';
					}
					$partC  = '<a href="' . $URL . '"' . $targetStr . '>';

					$ret = $partA . $partC . $partB;
				} else {
					/* 消すとき */
					$ret = $partA . $partB;
				}
			} else {
				/* リンク指定がないとき */
				break;
			}
		}

		$ret = str_replace(self::WORKINFO_END ,'</a>'  ,$ret);

		return $ret;
	}

/**
 * 電話番号の変換（マルチバイト対応）
 *
 * @access
 * @param string $str 元の文字列
 * @param string $mode リンクタグの扱い　URL：本来のタグへ変換、DEL：削除（タグは表示しない）
 * @return string 変換後の文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function convTelNoMB($str ,$mode) {

		$ret = $str;
					/*print 'convTelNoMB()' . $ret;*/
		while(true) {
			$begPos = strings5C::mb_existStr($ret ,self::TELCALL_BEG);

			if($begPos >= 0) {
				$endPos = mb_strpos($ret ,'>' ,$begPos);
				if($endPos === false) {
					break;
				}

				$partA = mb_substr($ret ,0 ,$begPos);
				$partB = mb_substr($ret ,$endPos + 1);

				if(strcmp($mode ,htmlNews5C::CURL_REPL) == 0) {
					$partC = '<a href="tel:' . KWD_TEL_NO_CALL . '">';
					$ret = $partA . $partC . $partB;
				} else {
					/* 消すとき */
					$ret = $partA . $partB;
				}

			} else {
				/* リンク指定がないとき */
				break;
			}
		}

		$ret = str_replace(self::TELCALL_END ,'</a>' ,$ret);
		$ret = str_replace(self::TELNO_DISP_BEG ,KWD_TEL_NO_DISP ,$ret);

		return $ret;
	}

/**
 * 記事本体へのリンクの変換（マルチバイト対応）
 *
 * @access
 * @param string $str          元の文字列
 * @param string $mode         リンクタグの扱い　URL：本来のタグへ変換、DEL：削除（タグは表示しない）
 * @param string $newsIdx      ニュースのID
 * @param string $newsPageFile ニュース本体のページ名
 * @return string 変換後の文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function convToMainContent($str ,$mode ,$newsIdx ,$newsPageFile) {

		$ret = $str;

		while(true) {
			$begPos = strings5C::mb_existStr($ret ,self::MAIN_CONTENT_BEG);

			if($begPos >= 0) {
				$endPos = mb_strpos($ret ,'>' ,$begPos);
				if($endPos === false) {
					break;
				}

				$partA = mb_substr($ret ,0 ,$begPos);
				$partB = mb_substr($ret ,$endPos + 1);

				if(strcmp($mode ,htmlNews5C::CURL_REPL) == 0) {
					if(strlen($newsPageFile) >= 1) {
						$partC = '<a href="' . $newsPageFile . '#newsID' . $newsIdx . '">';
					} else {
						$partC = '';
					}

					$ret = $partA . $partC . $partB;
				} else {
					/* 消すとき */
					$ret = $partA . $partB;
				}

			} else {
				/* リンク指定がないとき */
				break;
			}
		}

		$ret = mb_ereg_replace(self::MAIN_CONTENT_END ,'</a>'  ,$ret);

		return $ret;
	}

/**
 * プロファイルリンク指定の変換（マルチバイト対応）
 *
 * @access
 * @param string $str             元の文字列
 * @param string $profilePagePath 紹介ページのページ名
 * @param string $mode            リンクタグの扱い　URL：本来のタグへ変換、DEL：削除（タグは表示しない）
 * @return string 変換後の文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function convProfile($str ,$profilePagePath ,$mode) {

		$ret = $str;

		while(true) {
			$begPos = strings5C::mb_existStr($ret ,'<GIRLLINK');

			if($begPos >= 0) {
				$endPos = mb_strpos($ret ,'>' ,$begPos);
				if($endPos === false) {
					break;
				}

				/***** リンク指定があったとき *****/
				$partA = mb_substr($ret ,0 ,$begPos);
				$partB = mb_substr($ret ,$endPos + 1);

				if(strcmp($mode ,htmlNews5C::CURL_REPL) == 0) {
					/* リンクタグの置き換え */
					$len = $endPos - $begPos;
					$partCORG = mb_substr($ret ,$begPos ,$len);

					$profileName = mb_substr($partCORG ,8 + 1 ,$len - 1);

					$linkTag = mb_ereg_replace('NAME' ,$profileName ,$profilePagePath);

					$partC  = '<a href="' . $linkTag . '">';
					$ret = $partA . $partC . $partB;
				} else {
					/* 消すとき */
					$ret = $partA . $partB;
				}
			} else {
				/* リンク指定がないとき */
				break;
			}
		}

		$ret = mb_ereg_replace(self::GIRLLINK_END ,'</a>'  ,$ret);

		return $ret;
	}

/**
 * 画像表示の変換
 *
 * @access
 * @param string $str      元の文字列
 * @param string $mode     リンクタグの扱い　URL：本来のタグへ変換、DEL：削除（タグは表示しない）
 * @param string $deviceID 対象デバイス
 * @return string 変換後の文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	function replaceShowImage($str ,$mode ,$deviceID) {

		$ret = $str;

//		while(true) {
//			$begPos = strings5C::mb_existStr($ret ,'<IMGLINK');
//
//			if($begPos >= 0) {
//				$endPos = mb_strpos($ret ,'>' ,$begPos);
//				if($endPos === false) {
//					break;
//				}
//
//				/***** リンク指定があったとき *****/
//				$partA = mb_substr($ret ,0 ,$begPos);
//				$partB = mb_substr($ret ,$endPos + 1);
//
//				if(strcmp($mode ,htmlNews5C::CURL_REPL) == 0) {
//					/* リンクタグの置き換え */
//					$len = $endPos - $begPos;
//					$partCORG = mb_substr($ret ,$begPos ,$len);
//
//					$linkParamStr = mb_substr($partCORG ,7 + 1 ,$len - 1);
//					$linkParamExp = explode(':' ,$linkParamStr);
//
//					$groupNo  = $linkParamExp[0];
//					$branchNo = $linkParamExp[1];
//					$imageNo  = $linkParamExp[2];
//
//					$imageInfo = imageDB4C::readByNo($groupNo ,$branchNo ,$imageNo);
//
//					if(strlen($imageInfo[imageFILE_NAME]) >= 1) {
//						$partC  = '<img src="' . $imageInfo[imageFILE_NAME] . '" width="' . $imageInfo[imageWIDTH] . '" height="' . $imageInfo[imageHEIGHT] . '" />';
//					} else {
//						$partC  = '';
//					}
//					$ret = $partA . $partC . $partB;
//				} else {
//					/* 消すとき */
//					$ret = $partA . $partB;
//				}
//
//			} else {
//				/* リンク指定がないとき */
//				break;
//			}
//		}
//
		return $ret;
	}
}
?>
