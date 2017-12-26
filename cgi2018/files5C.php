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
 * 各種関数
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
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


		/***** ニュースダイジェスト ページ *****/
		if(strcmp($divi ,'DIGEST_TEMPLATE') == 0) {
			/*** テンプレートファイル ***/
			$ret['PC'] = $baseDir . 'topTEMPLATE.html';
			$ret['MO'] = $baseDir . $mobileDir . 'newTEMPLATE.html';
		}

		if(strcmp($divi ,'DIGEST_OUTPUT') == 0) {
			/*** 出力ファイル ***/
			$files['TEST'] = array('PC' => 'topTEST.html' ,'MO' => $mobileDir . 'newTEST.html');
			$files['REL' ] = array('PC' => 'top.html'     ,'MO' => $mobileDir . 'new.html');

			$ret = self::setOutFileName($baseDir ,$files ,$mode);
		}

		if(strcmp($divi ,'NEWS_DIGEST_TO_MAIN') == 0) {
			/*** ダイジェストから見たニュース本文ファイル ***/
			$files['TEST'] = array('PC' => 'newsTEST.html' ,'MO' => 'otoku4TEST.html');
			$files['REL' ] = array('PC' => 'news.html'     ,'MO' => 'otoku4.html');

			$ret['PC'] = $files[$mode]['PC'];
			$ret['MO'] = $files[$mode]['MO'];
		}

		if(strcmp($divi ,'NEWS_DIGEST_TO_PROF_MAIN') == 0) {
			/*** ダイジェストから見たプロファイルページ ***/
			$files['TEST'] = array('PC' => $womenDir . 'NAMETEST.html' ,'MO' => $mobileDir . $womenDir . 'NAMETEST.php');
			$files['REL' ] = array('PC' => $womenDir . 'NAME.html'     ,'MO' => $mobileDir . $womenDir . 'NAME.php');

			$ret['PC'] = $files[$mode]['PC'];
			$ret['MO'] = $files[$mode]['MO'];
		}



		/***** ニュース本体 ページ *****/
		if(strcmp($divi ,'NEWS_TEMPLATE') == 0) {
			/*** テンプレートファイル ***/
			$ret['PC'] = $baseDir . 'newsTEMPLATE.html';
			$ret['MO'] = $baseDir . $mobileDir . 'newTEMPLATE.html';
		}

		if(strcmp($divi ,'NEWS_OUTPUT') == 0) {
			/*** 出力ファイル ***/
			$files['TEST'] = array('PC' => 'newsTEST.html' ,'MO' => $mobileDir . 'newsTEST.php');
			$files['REL' ] = array('PC' => 'news.html'     ,'MO' => $mobileDir . 'new.html');

			$ret = self::setOutFileName($baseDir ,$files ,$mode);
		}

		if(strcmp($divi ,'NEWS_TEMPORARY') == 0) {
			/*** 一時ファイル ***/
			$ret['PC'] = $baseDir . 'indexTEMPORARY.html';
			$ret['MO'] = $baseDir . $mobileDir . 'indexTEMPORARY.html';
		}

		if(strcmp($divi ,'NEWS_MAIN_TO_PROF_MAIN') == 0) {
			/*** ニュース本文から見たプロファイルページ ***/
			$files['TEST'] = array('PC' => $womenDir . 'NAMETEST.html' ,'MO' => $womenDir . 'NAMETESTA.html');
			$files['REL' ] = array('PC' => $womenDir . 'NAME.html'     ,'MO' => $womenDir . 'NAME.html');

			$ret['PC'] = $files[$mode]['PC'];
			$ret['MO'] = $files[$mode]['MO'];
		}


		/***** プロファイルページ *****/
		if(strcmp($divi ,'PROF_TEMPLATE') == 0) {
			/*** テンプレートファイル ***/
			$ret['PC' ] = $baseDir . $womenDir . 'TEMPLATE.html';
			$ret['MO' ] = $baseDir . $mobileDir . $womenDir . 'TEMPLATE.html';
			$ret['MO2'] = $baseDir . $mobileDir . $womenDir . 'TEMPLATE2.html';
		}

		if(strcmp($divi ,'PROF_OUTPUT') == 0) {
			/*** 出力ファイル ***/
			$ret['PC' ] = $baseDir . $womenDir . 'NAME.html';
			$ret['MO' ] = $baseDir . $mobileDir . $womenDir . 'NAME.html';
			$ret['MO2'] = $baseDir . $mobileDir . $womenDir . 'NAME2.html';
		}

		if(strcmp($divi ,'PROF_BASEDIR') == 0) {
			/*** 出力ファイルディレクトリ ***/
			$ret['PC'] = $baseDir . $womenDir;
			$ret['MO'] = $baseDir . $mobileDir . $womenDir;
		}


		/***** 写真ファイルディレクトリ *****/
		if(strcmp($divi ,'PHOTO_BASEDIR') == 0) {
			$ret['PC'] = $baseDir . 'photos';
			$ret['MO'] = $baseDir . 'photos';
		}


		/***** オススメのJSソースコード *****/
		if(strcmp($divi ,'RECOMM_JS_TEMPLATE') == 0) {
			/*** テンプレートファイル ***/
			$ret['PC'] = $baseDir . 'js/photoSetTEMPLATE.js';
		}

		if(strcmp($divi ,'RECOMM_JS_OUTPUT') == 0) {
			/*** 出力ファイル ***/
			$files['TEST'] = array('PC' => 'js/photoSetTEST.js');
			$files['REL' ] = array('PC' => 'js/photoSet.js');

			$ret['PC'] = $files[$mode]['PC'];
		}


		/***** topページ *****/
		if(strcmp($divi ,'TOP_PAGE_TEMPLATE') == 0) {
			/*** テンプレートファイル ***/
			$ret['PC'] = $baseDir . 'indexTEMPLATE.html';
			$ret['MO'] = $baseDir . $mobileDir . 'top.html';
		}

		if(strcmp($divi ,'TOP_PAGE_OUTPUT') == 0) {
			/*** 出力ファイル ***/
			$files['TEST'] = array('PC' => 'indexTEST.html' ,'MO' => $mobileDir . 'topTEST.html');
			$files['REL' ] = array('PC' => 'index.html'     ,'MO' => $mobileDir . 'top.html');

			$ret = self::setOutFileName($baseDir ,$files ,$mode);
		}


		/***** アルバムページ *****/
		if(strcmp($divi ,'PROF_ALBUM_TEMPLATE') == 0) {
			/*** テンプレートファイル ***/
			$ret['PC'] = $baseDir . 'wivesListTEMPLATE.html';
			$ret['MO'] = $baseDir . $mobileDir . 'wivesTEMPLATE.html';
		}

		if(strcmp($divi ,'PROF_ALBUM_OUTPUT') == 0) {
			/*** 出力ファイル ***/
			$files['TEST'] = array('PC' => 'wivesTEST.html' ,'MO' => $mobileDir . 'girlsTEST.php');
			$files['REL' ] = array('PC' => 'wivesList.html' ,'MO' => $mobileDir . 'wives.html');

			$ret = self::setOutFileName($baseDir ,$files ,$mode);
		}






		/*****************************************************************************************************/
		/***** 写メ日記本体 ページ *****/
		if(strcmp($divi ,'DIARY_TEMPLATE') == 0) {
			/*** テンプレートファイル ***/
			if(strlen($param1) >= 1) {
				/***** ディレクトリの指定があるとき *****/
				$body = 'profileTEMPLATE';
			} else {
				$body = 'indexTEMPLATE';
			}
			$ret['PC'] = $baseDir . '/photoDiary/' . $body . '.html';
			$ret['MO'] = $baseDir . 'newsTEMPLATE.html';	/*'m/newsTEMPLATE.php';*/
		}

		if(strcmp($divi ,'DIARY_OUTPUT') == 0) {
			/*** 出力ファイル ***/
			if(strlen($param1) >= 1) {
				/***** ディレクトリの指定があるとき *****/
				$body = $param1;
			} else {
				$body = 'index';
			}

			$files['TEST'] = array('PC' => 'photoDiary/' . $body . 'TEST.html' ,'MO' => 'm/' . $body . 'TEST.php');
			$files['REL' ] = array('PC' => 'photoDiary/' . $body . '.html'     ,'MO' => 'photoDiary/' . $body . 'A.html');		/* m/news.php */

			$ret = self::setOutFileName($baseDir ,$files ,$mode);
		}

		if(strcmp($divi ,'DIARY_TEMPORARY') == 0) {
			/*** 一時ファイル ***/
			$ret['PC'] = $baseDir . 'photoDiary/indexTEMPORARY.html';
			$ret['MO'] = $baseDir . 'photoDiary/indexTEMPORARYA.html';	/* m/otoku4TEMPORARY.html */
		}

		if(strcmp($divi ,'DIARY_MAIN_TO_PROF_MAIN') == 0) {
			/*** 写メ日記から見たプロファイルページ ***/
			$files['TEST'] = array('PC' => '../wives/NAMETEST.html' ,'MO' => '../wives/NAMETESTA.html');	/* girls/NAMETEST.php */
			$files['REL' ] = array('PC' => '../wives/NAME.html'     ,'MO' => '../girls/NAME.php');

			$ret['PC'] = $files[$mode]['PC'];
			$ret['MO'] = $files[$mode]['MO'];
		}



		/***** ブログ本体ページ *****/
		if(strcmp($divi ,'BLOG_TEMPLATE') == 0) {
			/*** テンプレートファイル ***/
			$dir = 'blog/dailyTemplate/dailyTemplate/';
			$ret['PC'] = $baseDir . $dir . 'template.html';
			$ret['MO'] = $baseDir . $dir . 'template.html';	/*'m/newsTEMPLATE.php';*/
		}

		if(strcmp($divi ,'BLOG_OUTPUT') == 0) {
			/*** 出力ファイル ***/
			$dir = 'blog/YEAR4/MONTH2/';
			$files['TEST'] = array('PC' => 'DAY2-H2M2TEST.html' ,'MO' => 'm/DAY2-H2M2TEST.php');
			$files['REL' ] = array('PC' => 'DAY2-H2M2.html'     ,'MO' => 'DAY2-H2M2A.html');		/* m/news.php */

			$ret = self::setOutFileName($baseDir .$dir ,$files ,$mode);
		}

		if(strcmp($divi ,'BLOG_TEMPORARY') == 0) {
			/*** 一時ファイル ***/
			$dir = 'blog/dailyTemplate/dailyTemplate/';
			$ret['PC'] = $baseDir . $dir .  'indexTEMPORARY.html';
			$ret['MO'] = $baseDir . $dir .  'indexTEMPORARYA.html';	/* m/otoku4TEMPORARY.html */
		}

		if(strcmp($divi ,'BLOG_MAIN_TO_PROF_MAIN') == 0) {
			/*** ブログ本文から見たプロファイルページ ***/
			$files['TEST'] = array('PC' => '../../../wives/NAMETEST.html' ,'MO' => '../../../wives/NAMETESTA.html');	/* girls/NAMETEST.php */
			$files['REL' ] = array('PC' => '../../../wives/NAME.html'     ,'MO' => '../../../girls/NAME.php');

			$ret['PC'] = $files[$mode]['PC'];
			$ret['MO'] = $files[$mode]['MO'];
		}



		/***** ブログリストページ *****/
		if(strcmp($divi ,'BLOG_LIST_TEMPLATE') == 0) {
			/*** テンプレートファイル ***/
			$dir = 'blog/';
			$ret['PC'] = $baseDir . $dir . 'indexTEMPLATE.html';
			$ret['MO'] = $baseDir . $dir . 'indexTEMPLATE.html';	/*'m/newsTEMPLATE.php';*/
		}

		if(strcmp($divi ,'BLOG_LIST_OUTPUT') == 0) {
			/*** 出力ファイル ***/
			$dir = 'blog/';
			$files['TEST'] = array('PC' => 'LISTPAGETEST.html' ,'MO' => 'm/LISTPAGETEST.php');
			$files['REL' ] = array('PC' => 'LISTPAGE.html'     ,'MO' => 'LISTPAGEA.html');

			$ret = self::setOutFileName($baseDir .$dir ,$files ,$mode);
		}

		if(strcmp($divi ,'BLOG_LIST_TEMPORARY') == 0) {
			/*** 一時ファイル ***/
			$dir = 'blog/';
			$ret['PC'] = $baseDir . $dir .  'LISTPAGETEMPORARY.html';
			$ret['MO'] = $baseDir . $dir .  'LISTPAGETEMPORARYA.html';
		}

		if(strcmp($divi ,'BLOG_LIST_TO_PROF_MAIN') == 0) {
			/*** ブログ本文から見たプロファイルページ ***/
			$files['TEST'] = array('PC' => '../wives/NAMETEST.html' ,'MO' => '../wives/NAMETESTA.html');	/* girls/NAMETEST.php */
			$files['REL' ] = array('PC' => '../wives/NAME.html'     ,'MO' => '../girls/NAME.php');

			$ret['PC'] = $files[$mode]['PC'];
			$ret['MO'] = $files[$mode]['MO'];
		}




		/***** サイトマップ *****/
		if(strcmp($divi ,'SITEMAP_TEMPLATE') == 0) {
			/*** テンプレートファイル ***/
			$ret['PC'] = $baseDir . 'sitemapTEMPLATE.xml';
			$ret['MO'] = $baseDir . 'sitemapMTEMPLATE.xml';
		}

		if(strcmp($divi ,'SITEMAP_OUTPUT') == 0) {
			/*** 出力ファイル ***/
			$ret['PC'] = $baseDir . 'sitemap.xml';
			$ret['MO'] = $baseDir . 'sitemapM.xml';
		}

		if(strcmp($divi ,'SITEMAP_OUTPUTA') == 0) {
			/*** 出力ファイル ***/
			$ret['PC'] = $baseDir . 'sitemapB.xml';
			$ret['MO'] = $baseDir . 'sitemapMB.xml';
		}

		if(strcmp($divi ,'SITEMAP_OUTPUTB') == 0) {
			/*** 出力ファイル ***/
			$ret['PC'] = $baseDir . 'sitemapC.xml';
			$ret['MO'] = $baseDir . 'sitemapMC.xml';
		}

		if(strcmp($divi ,'SITEMAP_OUTPUTAF') == 0) {
			/*** 出力ファイル ***/
			$ret['PC'] = 'sitemapB.xml';
			$ret['MO'] = 'sitemapMB.xml';
		}

		if(strcmp($divi ,'SITEMAP_OUTPUTBF') == 0) {
			/*** 出力ファイル ***/
			$ret['PC'] = 'sitemapC.xml';
			$ret['MO'] = 'sitemapMC.xml';
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
