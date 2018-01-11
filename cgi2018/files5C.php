<?php
/**
 * ファイル名の生成
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */

/**
 * ファイル名の取得
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
   fileName5Cに吸収予定
 */
class files5C {

/**
 * ファイル名の取得
 *
 * @access
 * @param string $divi    種別
 * @param string $groupNo グループNo（ダミー）
 * @param string $brnchNo 店No（ダミー）
 * @param string $param1  その他のパラメータ
 * @return array デバイス毎のファイル名
 * @link
 * @see
 * @throws
 * @todo
 */
	function getFileName($divi ,$groupNo ,$branchNo ,$param1='') {

		$ret['PC'] = '';
		$ret['MO'] = '';

		$baseDir   = realpath(dirname(__FILE__) . '/..') . '/';
		$mobileDir = 'mobile/';
		$womenDir  = 'women/';

		/* $mode = 'TEST'; */
		$mode = 'REL';


		// ニュースダイジェスト ページ
		if(strcmp($divi ,'DIGEST_TEMPLATE') == 0) {
			// テンプレートファイル
			$ret['PC'] = $baseDir . 'topTEMPLATE.html';
			$ret['MO'] = $baseDir . $mobileDir . 'newTEMPLATE.html';
		}

		if(strcmp($divi ,'DIGEST_OUTPUT') == 0) {
			// 出力ファイル
			$files['TEST'] = array('PC' => 'topTEST.html' ,'MO' => $mobileDir . 'newTEST.html');
			$files['REL' ] = array('PC' => 'top.html'     ,'MO' => $mobileDir . 'new.html');

			$ret = self::setOutFileName($baseDir ,$files ,$mode);
		}

		if(strcmp($divi ,'NEWS_DIGEST_TO_MAIN') == 0) {
			// ダイジェストから見たニュース本文ファイル
			$files['TEST'] = array('PC' => 'newsTEST.html' ,'MO' => 'otoku4TEST.html');
			$files['REL' ] = array('PC' => 'news.html'     ,'MO' => 'otoku4.html');

			$ret['PC'] = $files[$mode]['PC'];
			$ret['MO'] = $files[$mode]['MO'];
		}

		if(strcmp($divi ,'NEWS_DIGEST_TO_PROF_MAIN') == 0) {
			// ダイジェストから見たプロファイルページ
			$files['TEST'] = array('PC' => $womenDir . 'NAMETEST.html' ,'MO' => $mobileDir . $womenDir . 'NAMETEST.php');
			$files['REL' ] = array('PC' => $womenDir . 'NAME.html'     ,'MO' => $mobileDir . $womenDir . 'NAME.php');

			$ret['PC'] = $files[$mode]['PC'];
			$ret['MO'] = $files[$mode]['MO'];
		}



		// ニュース本体 ページ
		if(strcmp($divi ,'NEWS_TEMPLATE') == 0) {
			// テンプレートファイル
			$ret['PC'] = $baseDir . 'newsTEMPLATE.html';
			$ret['MO'] = $baseDir . $mobileDir . 'newTEMPLATE.html';
		}

		if(strcmp($divi ,'NEWS_OUTPUT') == 0) {
			// 出力ファイル
			$files['TEST'] = array('PC' => 'newsTEST.html' ,'MO' => $mobileDir . 'newsTEST.php');
			$files['REL' ] = array('PC' => 'news.html'     ,'MO' => $mobileDir . 'new.html');

			$ret = self::setOutFileName($baseDir ,$files ,$mode);
		}

		if(strcmp($divi ,'NEWS_TEMPORARY') == 0) {
			// 一時ファイル
			$ret['PC'] = $baseDir . 'indexTEMPORARY.html';
			$ret['MO'] = $baseDir . $mobileDir . 'indexTEMPORARY.html';
		}

		if(strcmp($divi ,'NEWS_MAIN_TO_PROF_MAIN') == 0) {
			// ニュース本文から見たプロファイルページ
			$files['TEST'] = array('PC' => $womenDir . 'NAMETEST.html' ,'MO' => $womenDir . 'NAMETESTA.html');
			$files['REL' ] = array('PC' => $womenDir . 'NAME.html'     ,'MO' => $womenDir . 'NAME.html');

			$ret['PC'] = $files[$mode]['PC'];
			$ret['MO'] = $files[$mode]['MO'];
		}


		// プロファイルページ
		if(strcmp($divi ,'PROF_TEMPLATE') == 0) {
			// テンプレートファイル
			$ret['PC' ] = $baseDir . $womenDir . 'TEMPLATE.html';
			$ret['MO' ] = $baseDir . $mobileDir . $womenDir . 'TEMPLATE.html';
			$ret['MO2'] = $baseDir . $mobileDir . $womenDir . 'TEMPLATE2.html';
		}

		if(strcmp($divi ,'PROF_OUTPUT') == 0) {
			// 出力ファイル
			$ret['PC' ] = $baseDir . $womenDir . 'NAME.html';
			$ret['MO' ] = $baseDir . $mobileDir . $womenDir . 'NAME.html';
			$ret['MO2'] = $baseDir . $mobileDir . $womenDir . 'NAME2.html';
		}

		if(strcmp($divi ,'PROF_BASEDIR') == 0) {
			// 出力ファイルディレクトリ
			$ret['PC'] = $baseDir . $womenDir;
			$ret['MO'] = $baseDir . $mobileDir . $womenDir;
		}


		// 写真ファイルディレクトリ
		if(strcmp($divi ,'PHOTO_BASEDIR') == 0) {
			$ret['PC'] = $baseDir . 'photos';
			$ret['MO'] = $baseDir . 'photos';
		}


		// オススメのJSソースコード
		if(strcmp($divi ,'RECOMM_JS_TEMPLATE') == 0) {
			// テンプレートファイル
			$ret['PC'] = $baseDir . 'js/photoSetTEMPLATE.js';
		}

		if(strcmp($divi ,'RECOMM_JS_OUTPUT') == 0) {
			// 出力ファイル
			$files['TEST'] = array('PC' => 'js/photoSetTEST.js');
			$files['REL' ] = array('PC' => 'js/photoSet.js');

			$ret['PC'] = $files[$mode]['PC'];
		}


		// topページ
		if(strcmp($divi ,'TOP_PAGE_TEMPLATE') == 0) {
			// テンプレートファイル
			$ret['PC'] = $baseDir . 'indexTEMPLATE.html';
			$ret['MO'] = $baseDir . $mobileDir . 'top.html';
		}

		if(strcmp($divi ,'TOP_PAGE_OUTPUT') == 0) {
			// 出力ファイル
			$files['TEST'] = array('PC' => 'indexTEST.html' ,'MO' => $mobileDir . 'topTEST.html');
			$files['REL' ] = array('PC' => 'index.html'     ,'MO' => $mobileDir . 'top.html');

			$ret = self::setOutFileName($baseDir ,$files ,$mode);
		}


		// アルバムページ
		if(strcmp($divi ,'PROF_ALBUM_TEMPLATE') == 0) {
			// テンプレートファイル
			$ret['PC'] = $baseDir . 'wivesListTEMPLATE.html';
			$ret['MO'] = $baseDir . $mobileDir . 'wivesTEMPLATE.html';
		}

		if(strcmp($divi ,'PROF_ALBUM_OUTPUT') == 0) {
			// 出力ファイル
			$files['TEST'] = array('PC' => 'wivesTEST.html' ,'MO' => $mobileDir . 'girlsTEST.php');
			$files['REL' ] = array('PC' => 'wivesList.html' ,'MO' => $mobileDir . 'wives.html');

			$ret = self::setOutFileName($baseDir ,$files ,$mode);
		}

		return $ret;
	}


/**
 * ファイル名の取得
 *
 * @access
 * @param string $baseDir OS上のこのファイルがあるディレクトリ
 * @param string $files ファイル名
 * @param string $mode テスト用出力、本出力の区分
 * @return array デバイス毎のファイル名
 * @link
 * @see
 * @throws
 * @todo
 */
	private function setOutFileName($baseDir ,$files ,$mode) {

		$ret['PC'] = $baseDir . $files[$mode]['PC'];
		$ret['MO'] = $baseDir . $files[$mode]['MO'];

		return $ret;
	}
}
?>
