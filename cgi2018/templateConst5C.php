<?php
/**
 * テンプレート定数
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */


/**
 * テンプレート定数
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */
class templateConst5C {

	/***** 開始と終了 *****/
	/*** 求人 ***/
	const RECRUIT_BEG_LINE = '<!-- RECRUIT_STR_BEG -->';
	const RECRUIT_END_LINE = '<!-- RECRUIT_STR_END -->';

	/*** 料金 ***/
	const SYSTEM_BEG_LINE = '<!-- SYSTEM_STR_BEG -->';
	const SYSTEM_END_LINE = '<!-- SYSTEM_STR_END -->';

	/*** ニュース ***/
	/*** ダイジェストの範囲 ***/
	const NEWS_DIGEST_BEG_LINE = '<!-- NEWS_DIGEST_LIST_BEG -->';
	const NEWS_DIGEST_END_LINE = '<!-- NEWS_DIGEST_LIST_END -->';

	/*** 本文の範囲 ***/
	const NEWS_MAIN_BEG_LINE = '<!-- NEWS_MAIN_LIST_BEG -->';
	const NEWS_MAIN_END_LINE = '<!-- NEWS_MAIN_LIST_END -->';

	/*** 女性紹介 ***/
	const PROFILE_BEG_LINE = '<!-- PROFILE_BEG -->';	/* プロファイル出力開始 */
	const PROFILE_END_LINE = '<!-- PROFILE_END -->';	/* プロファイル出力終了 */

	/*** アルバム ***/
	const ALBUM_BEG_LINE = '<!-- ALBUM_BEG -->';	/* アルバム開始 */
	const ALBUM_END_LINE = '<!-- ALBUM_END -->';	/* アルバム終了 */

	/*** TOPページのヘッダ ***/
	const TOP_PAGE_HEADER_BEG_LINE = '<!-- TOP_PAGE_HEADER_BEG -->';	/* TOPページのヘッダ開始 */
	const TOP_PAGE_HEADER_END_LINE = '<!-- TOP_PAGE_HEADER_END -->';	/* TOPページのヘッダ終了 */

	/*** TOPページのメニュー項目 ***/
	const TOP_PAGE_MENU_BEG_LINE = '<!-- TOP_PAGE_MENU_BEG -->';	/* TOPページのメニュー項目開始 */
	const TOP_PAGE_MENU_END_LINE = '<!-- TOP_PAGE_MENU_END -->';	/* TOPページのメニュー項目終了 */

	/*** TOPページ以外のメニュー項目 ***/
	const OTHER_PAGE_MENU_BEG_LINE = '<!-- OTHER_PAGE_MENU_BEG -->';	/* TOPページ以外のメニュー項目開始 */
	const OTHER_PAGE_MENU_END_LINE = '<!-- OTHER_PAGE_MENU_END -->';	/* TOPページ以外のメニュー項目終了 */


	/*** セクションの開始-終了キーワード ***/
	var $begList = array(
		'RECRUIT'     => self::RECRUIT_BEG_LINE     ,
		'SYSTEM'      => self::SYSTEM_BEG_LINE      ,
		'NEWS_DIGEST' => self::NEWS_DIGEST_BEG_LINE ,
		'NEWS_MAIN'   => self::NEWS_MAIN_BEG_LINE   ,

		'PROFILE' => self::PROFILE_BEG_LINE ,
		'ALBUM'   => self::ALBUM_BEG_LINE   ,

		'TOP_PAGE_HEADER' => self::TOP_PAGE_HEADER_BEG_LINE ,

		'TOP_PAGE_MENU'   => self::TOP_PAGE_MENU_BEG_LINE   ,
		'OTHER_PAGE_MENU' => self::OTHER_PAGE_MENU_BEG_LINE ,

		'BEG_A' ,'BEG_B' ,'BEG_C'
	);
	var $endList = array(
		'RECRUIT'     => self::RECRUIT_END_LINE     ,
		'SYSTEM'      => self::SYSTEM_END_LINE      ,
		'NEWS_DIGEST' => self::NEWS_DIGEST_END_LINE ,
		'NEWS_MAIN'   => self::NEWS_MAIN_END_LINE   ,

		'PROFILE' => self::PROFILE_END_LINE ,
		'ALBUM'   => self::ALBUM_END_LINE   ,

		'TOP_PAGE_HEADER' => self::TOP_PAGE_HEADER_END_LINE ,

		'TOP_PAGE_MENU'   => self::TOP_PAGE_MENU_END_LINE   ,
		'OTHER_PAGE_MENU' => self::OTHER_PAGE_MENU_END_LINE ,

		'END_A' ,'END_B' ,'END_C'
	);


	/***** 求人の内容 *****/
	const RECRUIT_STR_VAL = '<!-- RECRUIT_STR -->';			/* 求人内容 */

	/***** 料金の内容 *****/
	const SYSTEM_STR_VAL = '<!-- SYSTEM_STR -->';			/* 料金内容 */


	/***** アルバムの内容 *****/
	const ALBUM_STR_VAL = '<!-- ALBUM_STR -->';			/* アルバム内容 */


	/***** TOPページヘッダの内容 *****/
	const TOP_PAGE_HEADER_STR_VAL = '<!-- TOP_PAGE_HEADER_STR -->';			/*TOPページヘッダの内容 */

	/***** メニューの内容 *****/
	const TOP_PAGE_MENU_STR_VAL   = '<!-- TOP_PAGE_MENU_STR -->';	/* TOPページのメニューの内容 */
	const OTHER_PAGE_MENU_STR_VAL = '<!-- OTHER_PAGE_MENU_STR -->';	/* TOPページ以外のメニューの内容 */


	/***** ニュース *****/
	const KWD_NEWS_TITLE_S    = '<!-- NEWS_TITLE -->';			/* タイトル */
	const KWD_NEWS_CATEGORY_S = '<!-- NEWS_CATEGORY -->';		/* 種類 */
	const KWD_NEWS_DIGEST_S   = '<!-- NEWS_DIGEST -->';			/* 概要 */
	const KWD_NEWS_CONTENT_S  = '<!-- NEWS_CONTENT -->';		/* 本文 */
	const KWD_NEWS_DATE_S     = '<!-- NEWS_DATE -->';			/* 日付 */


	/***** プロファイル *****/
	const KWD_PROF_DIR_S  = '<!-- PROF_DIR -->';			/* プロファイルディレクトリ */
	const KWD_NAME_S      = '<!-- PROF_NAME -->';			/* 名前 */
	const KWD_AGE_S       = '<!-- PROF_AGE -->';			/* 年齢 */
	const KWD_ZODIAC_S    = '<!-- PROF_ZODIAC -->';			/* 星座 */
	const KWD_BLOODTYPE_S = '<!-- PROF_BLOODTYPE -->';		/* 血液型 */

	const KWD_HEIGHT_S    = '<!-- PROF_HEIGHT -->';			/* 身長 */
	const KWD_SIZES_S     = '<!-- PROF_SIZES -->';			/* スリーサイズ */

	const KWD_WORK_TIME_S = '<!-- PROF_WORK_TIME -->';		/* 出勤時間 */
	const KWD_WORK_DAY_S  = '<!-- PROF_WORK_DAY -->';		/* 出勤日 */
	const KWD_REST_DAY_S  = '<!-- PROF_REST_DAY -->';		/* 公休日 */
	const KWD_MASTERS_COMMENT_S = '<!-- PROF_MASTERS_COMMENT -->';		/* 店長コメント */
	const KWD_APPEAL_COMMENT_S  = '<!-- PROF_APPEAL_COMMENT -->';		/* アピールコメント */

	/*** 強制出力 ***/
	const KWD_NAME_F_S      = '<!-- PROF_NAME_F -->';			/* 名前 */
	const KWD_COMMENT_F_S   = '<!-- PROF_COMMENT_F -->';		/* コメント */


	const KWD_DIARY_URI_S = '<!-- PROF_DIARY_URI -->';		/* 日記URI */
	const KWD_NEW_FACE_S  = '<!-- PROF_NEW_FACE -->';		/* 新人 */

	const KWD_DIR_FOR_DIARY_S = '<!-- PROF_DIR_FOR_DIARY -->';		/* 新人 */

	/*** 写真 ***/
	const KWD_PHOTO_SHOW_L_S   = '<!-- PROFILE_DISPLAY_PHOTO_L -->';	/* 写真表示 */
	const KWD_PHOTO_SHOW_M_S   = '<!-- PROFILE_DISPLAY_PHOTO_M -->';	/* 写真表示 */
	const KWD_PHOTO_SHOW_S_S   = '<!-- PROFILE_DISPLAY_PHOTO_S -->';	/* 写真表示 */

	const KWD_PHOTO_NO_S       = '<!-- PROF_PHOTO_NO -->';			/* 写真No */

	const KWD_PHOTO_SELE_NO_S  = '<!-- PROF_PHOTO_SELE_NO -->';		/* 写真No */
	const KWD_PHOTO_SELE_BTN_S = '<!-- PROF_PHOTO_SELE_BTN -->';	/* 写真選択ボタン */


	const KWD_PHOTOEXT_S_S = '<!-- PROF_PHOTOEXT_S -->';			/* サムネイル拡張子 */


		/*	const KWD_PHOTO_SHOW_M_S   = '<!-- PROF_PHOTO_SHOW_M -->';*/		/* 中写真表示ボタン */


	const KWD_PHOTO_SHOW_OK  = '<!-- PROF_PHOTO_SHOW_SHOW -->';		/* 表示可 */
	const KWD_PHOTO_SHOW_NG  = '<!-- PROF_PHOTO_SHOW_NG -->';		/* 写真NG */
	const KWD_PHOTO_SHOW_NP  = '<!-- PROF_PHOTO_SHOW_NP -->';		/* 写真準備中 */
	const KWD_PHOTO_SHOW_NOT = '<!-- PROF_PHOTO_SHOW_NOT -->';		/* 写真なし */



	/***** オススメJS *****/
	const RECOMM_BEG_LINE = '/***** LIST_BEG *****/';	/* JSプロファイルリスト出力開始位置 */
	const RECOMM_END_LINE = '/***** LIST_END *****/';	/* JSプロファイルリスト出力終了位置 */

	const RECOMM_PROF_DIR   = 'PROF_DIR';			/* プロファイルディレクトリ */
	const RECOMM_NAME       = 'PROF_NAME';			/* 名前 */
	const RECOMM_PHOTO_TN   = 'PROF_PHOTO_NAME_TN';	/* 写真ファイル名 */
	const RECOMM_PHOTO_SHOW = 'PROF_PHOTO_SHOW';	/* 写真表示区分 */


	/***** TOPページ *****/
	const RECOMM_JS_VER = 'JS_VER';			/* オススメJSのバージョン */



	function getSectList() {

		$ret = array(
			'BEG_LIST' => $this->begList ,
			'END_LIST' => $this->endList
		);

		return $ret;
	}
}
?>
