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

	// 開始と終了
	const RECRUIT_BEG_LINE = '<!-- RECRUIT_STR_BEG -->';	// 求人の開始
	const RECRUIT_END_LINE = '<!-- RECRUIT_STR_END -->';	// 求人の終了

	const SYSTEM_BEG_LINE = '<!-- SYSTEM_STR_BEG -->';		// 料金の開始
	const SYSTEM_END_LINE = '<!-- SYSTEM_STR_END -->';		// 料金の終了

	const NEWS_BEG_LINE = '<!-- NEWS_BEG -->';				// ニュースの開始
	const NEWS_END_LINE = '<!-- NEWS_END -->';				// ニュースの終了

	const PROFILE_BEG_LINE = '<!-- PROFILE_BEG -->';		// プロファイルの開始
	const PROFILE_END_LINE = '<!-- PROFILE_END -->';		// プロファイルの終了

	const ALBUM_BEG_LINE = '<!-- ALBUM_BEG -->';			// アルバムの開始
	const ALBUM_END_LINE = '<!-- ALBUM_END -->';			// アルバムの終了

	const TOP_PAGE_HEADER_BEG_LINE = '<!-- TOP_PAGE_HEADER_BEG -->';	// TOPページのヘッダの開始
	const TOP_PAGE_HEADER_END_LINE = '<!-- TOP_PAGE_HEADER_END -->';	// TOPページのヘッダの終了

	const MENU_BEG_LINE = '<!-- MENU_BEG -->';		// メニュー項目の開始
	const MENU_END_LINE = '<!-- MENU_END -->';		// メニュー項目の終了

	var $begList = array(			// セクションの開始キーワードリスト
		'RECRUIT' => self::RECRUIT_BEG_LINE ,
		'SYSTEM'  => self::SYSTEM_BEG_LINE  ,
		'NEWS'    => self::NEWS_BEG_LINE    ,

		'PROFILE' => self::PROFILE_BEG_LINE ,
		'ALBUM'   => self::ALBUM_BEG_LINE   ,

		'TOP_PAGE_HEADER' => self::TOP_PAGE_HEADER_BEG_LINE ,

		'PAGE_MENU' => self::MENU_BEG_LINE
	);
	var $endList = array(			// セクションの終了キーワードリスト
		'RECRUIT' => self::RECRUIT_END_LINE ,
		'SYSTEM'  => self::SYSTEM_END_LINE  ,
		'NEWS'    => self::NEWS_END_LINE    ,

		'PROFILE' => self::PROFILE_END_LINE ,
		'ALBUM'   => self::ALBUM_END_LINE   ,

		'TOP_PAGE_HEADER' => self::TOP_PAGE_HEADER_END_LINE ,

		'PAGE_MENU' => self::MENU_END_LINE
	);


	const RECRUIT_STR_VAL         = '<!-- RECRUIT_STR -->';				// 求人内容
	const SYSTEM_STR_VAL          = '<!-- SYSTEM_STR -->';				// 料金内容
	const ALBUM_STR_VAL           = '<!-- ALBUM_STR -->';				// アルバム内容
	const TOP_PAGE_HEADER_STR_VAL = '<!-- TOP_PAGE_HEADER_STR -->';		// TOPページヘッダの内容
	const MENU_STR_VAL            = '<!-- MENU_STR -->';				// メニューの内容


	const KWD_NEWS_TITLE_S    = '<!-- NEWS_TITLE -->';			// ニュースタイトル
	const KWD_NEWS_CATEGORY_S = '<!-- NEWS_CATEGORY -->';		// ニュース種類
	const KWD_NEWS_DIGEST_S   = '<!-- NEWS_DIGEST -->';			// ニュース概要
	const KWD_NEWS_CONTENT_S  = '<!-- NEWS_CONTENT -->';		// ニュース本文
	const KWD_NEWS_DATE_S     = '<!-- NEWS_DATE -->';			// ニュース日付


	const KWD_PROF_DIR_S  = '<!-- PROF_DIR -->';			// プロファイルディレクトリ
	const KWD_NAME_S      = '<!-- PROF_NAME -->';			// 名前
	const KWD_AGE_S       = '<!-- PROF_AGE -->';			// 年齢
	const KWD_ZODIAC_S    = '<!-- PROF_ZODIAC -->';			// 星座
	const KWD_BLOODTYPE_S = '<!-- PROF_BLOODTYPE -->';		// 血液型

	const KWD_HEIGHT_S    = '<!-- PROF_HEIGHT -->';			// 身長
	const KWD_SIZES_S     = '<!-- PROF_SIZES -->';			// スリーサイズ

	const KWD_WORK_TIME_S = '<!-- PROF_WORK_TIME -->';		// 出勤時間
	const KWD_WORK_DAY_S  = '<!-- PROF_WORK_DAY -->';		// 出勤日
	const KWD_REST_DAY_S  = '<!-- PROF_REST_DAY -->';		// 公休日
	const KWD_MASTERS_COMMENT_S = '<!-- PROF_MASTERS_COMMENT -->';		// 店長コメント
	const KWD_APPEAL_COMMENT_S  = '<!-- PROF_APPEAL_COMMENT -->';		// アピールコメント

	const KWD_DIARY_URI_S = '<!-- PROF_DIARY_URI -->';		// 日記URL
	const KWD_NEW_FACE_S  = '<!-- PROF_NEW_FACE -->';		// 新人

	const KWD_DIR_FOR_DIARY_S = '<!-- PROF_DIR_FOR_DIARY -->';		// 日記のディレクトリ新人

	const KWD_PHOTO_SHOW_L_S   = '<!-- PROFILE_DISPLAY_PHOTO_L -->';	// 大写真表示
	const KWD_PHOTO_SHOW_M_S   = '<!-- PROFILE_DISPLAY_PHOTO_M -->';	// 中写真表示
	const KWD_PHOTO_SHOW_S_S   = '<!-- PROFILE_DISPLAY_PHOTO_S -->';	// 小写真表示

	const KWD_PHOTO_NO_S       = '<!-- PROF_PHOTO_NO -->';			// 写真No

	const KWD_PHOTO_SELE_NO_S  = '<!-- PROF_PHOTO_SELE_NO -->';		// 写真Noの選択
	const KWD_PHOTO_SELE_BTN_S = '<!-- PROF_PHOTO_SELE_BTN -->';	// 写真選択ボタン

	const KWD_PHOTOEXT_S_S = '<!-- PROF_PHOTOEXT_S -->';			// サムネイル拡張子


		/*	const KWD_PHOTO_SHOW_M_S   = '<!-- PROF_PHOTO_SHOW_M -->';*/		/* 中写真表示ボタン */

	const KWD_PHOTO_SHOW_OK  = '<!-- PROF_PHOTO_SHOW_SHOW -->';		// 写真表示 - 表示可
	const KWD_PHOTO_SHOW_NG  = '<!-- PROF_PHOTO_SHOW_NG -->';		// 写真表示 - 写真NG
	const KWD_PHOTO_SHOW_NP  = '<!-- PROF_PHOTO_SHOW_NP -->';		// 写真表示 - 写真準備中
	const KWD_PHOTO_SHOW_NOT = '<!-- PROF_PHOTO_SHOW_NOT -->';		// 写真表示 - 写真なし

	const KWD_QA1  = '<!-- PROF_QA1 -->';	// QA1
	const KWD_QA2  = '<!-- PROF_QA2 -->';	// QA2
	const KWD_QA3  = '<!-- PROF_QA3 -->';	// QA3
	const KWD_QA4  = '<!-- PROF_QA4 -->';	// QA4
	const KWD_QA5  = '<!-- PROF_QA5 -->';	// QA5
	const KWD_QA6  = '<!-- PROF_QA6 -->';	// QA6
	const KWD_QA7  = '<!-- PROF_QA7 -->';	// QA7
	const KWD_QA8  = '<!-- PROF_QA8 -->';	// QA8
	const KWD_QA9  = '<!-- PROF_QA9 -->';	// QA9
	const KWD_QA10 = '<!-- PROF_QA10 -->';	// QA10
	const KWD_QA11 = '<!-- PROF_QA11 -->';	// QA11
	const KWD_QA12 = '<!-- PROF_QA12 -->';	// QA12
	const KWD_QA13 = '<!-- PROF_QA13 -->';	// QA13
	const KWD_QA14 = '<!-- PROF_QA14 -->';	// QA14


	const RECOMM_BEG_LINE = '/***** LIST_BEG *****/';	// オススメのJSプロファイルリスト出力開始位置
	const RECOMM_END_LINE = '/***** LIST_END *****/';	// オススメのJSプロファイルリスト出力終了位置

	const RECOMM_PROF_DIR   = 'PROF_DIR';			// オススメのプロファイルディレクトリ
	const RECOMM_NAME       = 'PROF_NAME';			// オススメの名前表示位置
	const RECOMM_PHOTO_TN   = 'PROF_PHOTO_NAME_TN';	// オススメの写真ファイル名
	const RECOMM_PHOTO_SHOW = 'PROF_PHOTO_SHOW';	// オススメの写真表示区分

	const RECOMM_JS_VER = 'JS_VER';			// オススメJSのバージョン

	function getSectList() {

		$ret = array(
			'BEG_LIST' => $this->begList ,
			'END_LIST' => $this->endList
		);

		return $ret;
	}
}
?>
