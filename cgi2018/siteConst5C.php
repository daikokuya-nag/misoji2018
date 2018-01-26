<?php
/**
 * サイトの定数
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */

/**
 * サイトの定数
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */
class siteConst5C {
	// 出勤情報のURL
	const WORKINFO_URI_YORU   = 'http://www.yoasobi.co.jp/mb/syukkin_mise_list.cfm?mi=5123';	// よるとも
	const WORKINFO_URI_HEAVEN = 'http://www.cityheaven.net/t/erotopia/A7ShukkinYotei/';			// ヘブンネット

	// 電話番号
	const TEL_NO_DISP = '052-231-6328';	// 表示
	const TEL_NO_CALL = '0522316328';	// 架電


	static $htmlFileID = array(		// ファイル識別
		'PC' => array(
			'NEWS'    ,
			'ALBUM'   ,
			'RECRUIT' ,
			'SYSTEM'  ,
			'PHOTODIARY' ,
			'TOP'
		) ,

		'MO' => array(
			'NEWS'    ,
			'ALBUM'   ,
			'RECRUIT' ,
			'SYSTEM'  ,
			'PHOTODIARY' ,
			'INDEX'
		)
	);

	static $htmlFile = array(		// ファイル名
		'NEWS'    => 'news.html'    ,
		'ALBUM'   => 'album.html'   ,
		'RECRUIT' => 'recruit.html' ,
		'SYSTEM'  => 'system.html'  ,
		'TOP'     => 'top.html'     ,
		'PHOTODIARY' => 'photoDiary.html' ,
		'INDEX'   => 'index.html'   ,

		'COMMON_CSS' => 'branchCommon.css' ,
		'TOP_CSS'    => 'branchTop.css'    ,
		'ALBUM_CSS'  => 'branchAlbum.css'
	);

	static $outItem = array(		// ファイル出力の有無
		'NEWS'    => false ,
		'ALBUM'   => false ,
		'RECRUIT' => false ,
		'SYSTEM'  => false ,
		'TOP'     => false ,
		'PHOTODIARY' => false ,
		'INDEX'   => false ,

		'COMMON_CSS' => false ,
		'TOP_CSS'    => false ,
		'ALBUM_CSS'  => false
	);

	static $menuStr = array(		// メニューバーの表示文字列
		'NEWS'    => '新着情報' ,
		'ALBUM'   => '女性一覧' ,
		'RECRUIT' => '求人'     ,
		'SYSTEM'  => 'システム' ,
		'TOP'     => 'TOP'      ,
		'PHOTODIARY' => '写メ日記' ,
		'INDEX'   => 'メニュー'
	);


/**
 * 使用するファイルIDの取得
 *
 * @access
 * @param string 対象デバイス
 * @return string[] ファイルID
 * @link
 * @see
 * @throws
 * @todo
 */
	static function getHtmlFileIDList($device) {

		return self::$htmlFileID[$device];
	}

/**
 * 使用するファイル名の取得
 *
 * @access
 * @param
 * @return string[] ファイル名
 * @link
 * @see
 * @throws
 * @todo
 */
	static function getHtmlFileList() {

		return self::$htmlFile;
	}

	static function getOutItemList() {

		return self::$outItem;
	}

/**
 * メニュー表示文字の取得
 *
 * @access
 * @param
 * @return string[] メニュー表示文字
 * @link
 * @see
 * @throws
 * @todo
 */
	static function getMenuStrList() {

		return self::$menuStr;
	}
}
?>
