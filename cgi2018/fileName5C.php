<?php
/**
 * ファイル名の組み立て
 *
 * @package    
 * @subpackage 
 * @version    1.0.1
 * @since      
 * @author     
 */

	require_once dirname(__FILE__) . '/funcs5C.php';
	require_once dirname(__FILE__) . '/common5C.php';

/**
 * ファイル名の組み立て
 *
 * @package    
 * @subpackage 
 * @version    1.0.1
 * @since      
 * @author     
 */
class fileName5C {

	// CSSファイル
	const FILEID_CSS_OTHER = 'otherStyle';	/* top以外 */


	// 基準ディレクトリ識別
	// OS
	const DIR_OS_ROOT = 'OSRoot';	// OSのroot
	const DIR_OS_PC   = 'OSPC';		// OSのrootから見たPC
	const DIR_OS_SP   = 'OSSP';		// OSのrootから見たSP
	const DIR_OS_MO   = 'OSMO';		// OSのrootから見たMO

	// web
	const DIR_WEB_ROOT = 'WebRoot';		// webのroot
	const DIR_WEB_PC   = 'WebPC';		// webのrootから見たPC
	const DIR_WEB_SP   = 'WebSP';		// webのrootから見たSP
	const DIR_WEB_MO   = 'WebMO';		// webのrootから見たMO

	const FILEID_PHOTO_DIR = 'photo';		// 写真ディレクトリ
	const FILEID_IMG_DIR   = 'img';			// 画像ディレクトリ
	const PROFILE_DIR      = 'profile2018';	// 女性紹介ページディレクトリ
	const FILEID_PROFILE   = 'PROFILE';


	static $fileIDList = array(			// 取得するファイルの識別
		// ページファイル
/*
		'404'            => array('' ,'404'       ) ,
		'TOP'            => array('' ,'top'       ) ,
		'NEWS'           => array('' ,'news'      ) ,
		'ALBUM'          => array('' ,'album'     ) ,
		'RECRUIT'        => array('' ,'recruit'   ) ,
		'SCHEDULE'       => array('' ,'schedule'  ) ,
		'SYSTEM'         => array('' ,'system'    ) ,
		'SYSTEML'        => array('' ,'system'    ) ,
		'LINK'           => array('' ,'link'      ) ,
		'MAILMEMBER'     => array('' ,'mailmember') ,
		'INQUIRY'        => array('' ,'inquiry'   ) ,
		'QUESTIONNAIRE'  => array('' ,'questionnaire') ,
		'MASTERSBLOG'    => array('' ,'mastersBlog'  ) ,
*/
		// ページファイルのテスト用
		'404'            => array('' ,'4042018'       ) ,
		'TOP'            => array('' ,'top2018'       ) ,
		'NEWS'           => array('' ,'news2018'      ) ,
		'ALBUM'          => array('' ,'album2018'     ) ,
		'RECRUIT'        => array('' ,'recruit2018'   ) ,
		'SCHEDULE'       => array('' ,'schedule2018'  ) ,
		'SYSTEM'         => array('' ,'system2018'    ) ,
		'SYSTEML'        => array('' ,'system2018'    ) ,
		'LINK'           => array('' ,'link2018'      ) ,
		'MAILMEMBER'     => array('' ,'mailmember2018') ,
		'INQUIRY'        => array('' ,'inquiry2018'   ) ,
		'QUESTIONNAIRE'  => array('' ,'questionnaire2018') ,
		'MASTERSBLOG'    => array('' ,'mastersBlog2018'  ) ,

		// テンプレートファイル
		'404_TEMPLATE'           => array('' ,'404TEMPLATE'       ) ,
		'TOP_TEMPLATE'           => array('' ,'topTEMPLATE'       ) ,
		'NEWS_TEMPLATE'          => array('' ,'newsTEMPLATE'      ) ,
		'ALBUM_TEMPLATE'         => array('' ,'albumTEMPLATE'     ) ,
		'RECRUIT_TEMPLATE'       => array('' ,'recruitTEMPLATE'   ) ,
		'SCHEDULE_TEMPLATE'      => array('' ,'scheduleTEMPLATE'  ) ,
		'SYSTEM_TEMPLATE'        => array('' ,'systemTEMPLATE'    ) ,
		'SYSTEML_TEMPLATE'       => array('' ,'systemTEMPLATE'    ) ,
		'LINK_TEMPLATE'          => array('' ,'linkTEMPLATE'      ) ,
		'MAILMEMBER_TEMPLATE'    => array('' ,'mailmemberTEMPLATE') ,
		'INQUIRY_TEMPLATE'       => array('' ,'inquiryTEMPLATE'   ) ,
		'QUESTIONNAIRE_TEMPLATE' => array('' ,'questionnaireTEMPLATE') ,
		'MASTERSBLOG_TEMPLATE'   => array('' ,'mastersBlogTEMPLATE'  ) ,

		'PROFILE'           => array('profile2018/' ,'profile'        ) ,
		'PROFILE_TEMPLATE'  => array('profile2018/' ,'profileTEMPLATE') ,
	);


/**
 * ファイル名取得
 *
 * @param string $device   対象デバイス
 * @param string $fileID   対象ファイル種別
 * @param string $branchDir 店のディレクトリ
 * @param int    $branchNo 店No
 * @param string $profileFileName 紹介ページのファイル名
 * @return string フルパスファイル名
 */
	static function getFileName($device ,$fileID ,$branchDir ,$branchNo ,$profileFileName='') {

		if(isset(self::$fileIDList[$fileID])) {
			//対象デバイス用のサブディレクトリを定義
			$subDirA = '';
			if(strcmp($device ,common5C::DEVICE_PC) == 0) {
				$subDirA = common5C::DIR_PC;
			}
			if(strcmp($device ,common5C::DEVICE_SP) == 0) {
				$subDirA = common5C::DIR_SP;
			}
			if(strcmp($device ,common5C::DEVICE_MO) == 0) {
				$subDirA = common5C::DIR_MO;
			}
			if(strlen($subDirA) >= 1) {
				$subDirA = $subDirA . '/';
			}

			//店のディレクトリがあればファイル名の前に追加
			$subDirC = '';
			if(strlen($branchDir) >= 1) {
				$subDirC = $branchDir . '/';
			}
			$subDirC = $subDirC . self::$fileIDList[$fileID][0];

						/* print 'subDirC: ' . $subDirC; */

			//紹介ページでファイル名が指定されているときはそのファイル名を追加
			if((strcmp($fileID ,'PROFILE') == 0)
			&& strlen($profileFileName) >= 1) {
				$fileName = $profileFileName;
			} else {
				$fileName = self::$fileIDList[$fileID][1];
			}
						/* print 'fileName: ' . $fileName; */

			/*** 拡張子の指定があれば取り出す　なければ'html' */
			if(isset(self::$fileIDList[$fileID][2])) {
				$ext = self::$fileIDList[$fileID][2];
			} else {
				$ext = 'html';
			}

			$fileName = $fileName . '.' . $ext;

			/***** ディレクトリとファイル名の連結 *****/
			$baseDirBase = realpath(dirname(__FILE__) . '/..') . '/';

			$fullPath = $baseDirBase . $subDirA . $subDirC . $fileName;
			$fullPath = str_replace('BRANCH_NO' ,$branchNo ,$fullPath);
		} else {
			$fullPath = '';
			$fileName = '';
		}

		$ret = array(
			'fullPath' => $fullPath ,
			'fileName' => $fileName
		);

		return $ret;
	}


/**
 * 女性の写真ファイルを格納する基準ディレクトリ相対パス取得
 *
 * @param int    $branchNo 店No
 * @param string $baseDir  基準位置
 * @return string 相対パス
 */
	function getPhotoRelativeDir($branchNo ,$baseDir) {

		$baseDirBase = realpath(dirname(__FILE__) . '/..') . '/';

		if(strcmp($baseDir ,self::DIR_OS_ROOT) == 0) {
			$ret = $baseDirBase . self::FILEID_PHOTO_DIR;
		}

		return $ret;
	}


/**
 * 画像ディレクトリ相対パス取得
 *
 * @param int    $branchNo 店No
 * @param string $baseDir  基準位置（現在、self::DIR_OS_ROOTのみ指定可）
 * @return string 相対パス
 */
	function getImgRelativeDir($branchNo ,$baseDir) {

		$baseDirBase = realpath(dirname(__FILE__) . '/..') . '/';

		if(strcmp($baseDir ,self::DIR_OS_ROOT) == 0) {
			$ret = $baseDirBase . self::FILEID_IMG_DIR;
		}

		return $ret;
	}

/**
 * 女性の写真ファイルのディレクトリ相対パス取得
 *
 * @param int    $profNo  プロファイルNo
 * @param string $baseDir 基準位置
 * @return string 相対パス
 */
	function getProfImgRelativeDir($profNo ,$baseDir) {

		$baseDirBase = realpath(dirname(__FILE__) . '/..') . '/';

		if(strcmp($baseDir ,self::DIR_OS_ROOT) == 0) {
			$ret = $baseDirBase . self::FILEID_IMG_DIR . '/prof';
		}

		return $ret;
	}
}
?>
