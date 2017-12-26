<?php
/**
 * ファイル名の組み立て
 *
 * @package    
 * @subpackage 
 * @version    1.0
 * @since      
 * @author     
 */

	require_once dirname(__FILE__) . '/funcs5C.php';
	require_once dirname(__FILE__) . '/common5C.php';

class fileName5C {

	/***** CSSファイル *****/
	const FILEID_CSS_OTHER = 'otherStyle';	/* top以外 */


	/***** 基準ディレクトリ識別 *****/
	/*** OS ***/
	const DIR_OS_ROOT = 'OSRoot';	/*** root ***/
	const DIR_OS_PC   = 'OSPC';		/*** PC ***/
	const DIR_OS_SP   = 'OSSP';		/*** SP ***/
	const DIR_OS_MO   = 'OSMO';		/*** MO ***/

	/*** web ***/
	const DIR_WEB_ROOT = 'WebRoot';		/*** root ***/
	const DIR_WEB_PC   = 'WebPC';		/*** PC ***/
	const DIR_WEB_SP   = 'WebSP';		/*** SP ***/
	const DIR_WEB_MO   = 'WebMO';		/*** MO ***/

	/***** 写真ディレクトリ *****/
	const FILEID_PHOTO_DIR = 'photo';

	/***** 画像ディレクトリ *****/
	const FILEID_IMG_DIR = 'img';

	const FILEID_PROFILE  = 'profile';
	const FILEID_PROFILE2 = 'PROFILE';

/**
 * ファイル名取得
 *
 * @param string $device   対象デバイス
 * @param string $fileID   対象ファイル種別
 * @param string $dir      ディレクトリ
 * @param int    $branchNo 店No
 * @param string $param1 追加パラメータ1
 * @param string $param2 追加パラメータ2
 * @return string フルパスファイル名
 */
	static function getFileName($device ,$fileID ,$dir ,$branchNo ,$param1='' ,$param2='') {

		$baseDirBase = realpath(dirname(__FILE__) . '/..') . '/';

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

	/***** 取得したいファイルの識別 *****/
	$fileIDList = array(
		/* ページファイル */
		'404'            => array('' ,'404'       ) ,
		'TOP'            => array('' ,'index'     ) ,
		'index'          => array('' ,'top'       ) ,
		'NEWS'           => array('' ,'news'      ) ,
		'ALBUM'          => array('' ,'album'     ) ,
		'album'          => array('' ,'album'     ) ,
		'RECRUIT'        => array('' ,'recruit'   ) ,

		'SCHEDULE'       => array('' ,'schedule'  ) ,

		'SYSTEM'         => array('' ,'system'    ) ,
		'PRICEL'         => array('' ,'system'    ) ,

		'LINK'           => array('' ,'link'      ) ,
		'MAILMEMBER'     => array('' ,'mailmember') ,
		'INQUIRY'        => array('' ,'inquiry'   ) ,

		'QUESTIONNAIRE'  => array('' ,'questionnaire') ,
		'MASTERSBLOG'    => array('' ,'mastersBlog'  ) ,


		'PREVIEW'    => array('' ,'indexPREVIEW') ,
		'TOPPREVIEW' => array('' ,'indexPREVIEW') ,


		/* テンプレートファイル */
		'404_TEMPLATE'           => array('' ,'404TEMPLATE'       ) ,
		'TOP_TEMPLATE'           => array('' ,'indexTEMPLATE'     ) ,
		'index_TEMPLATE'         => array('' ,'indexTEMPLATE'     ) ,
		'NEWS_TEMPLATE'          => array('' ,'newsTEMPLATE'      ) ,
		'ALBUM_TEMPLATE'         => array('' ,'albumTEMPLATE'     ) ,
		'album_TEMPLATE'         => array('' ,'albumTEMPLATE'     ) ,
		'RECRUIT_TEMPLATE'       => array('' ,'recruitTEMPLATE'   ) ,

		'SCHEDULE_TEMPLATE'      => array('' ,'scheduleTEMPLATE'  ) ,

		'SYSTEM_TEMPLATE'        => array('' ,'systemTEMPLATE'    ) ,
		'PRICEL_TEMPLATE'        => array('' ,'systemTEMPLATE'    ) ,

		'LINK_TEMPLATE'          => array('' ,'linkTEMPLATE'      ) ,
		'MAILMEMBER_TEMPLATE'    => array('' ,'mailmemberTEMPLATE') ,
		'INQUIRY_TEMPLATE'       => array('' ,'inquiryTEMPLATE'   ) ,

		'QUESTIONNAIRE_TEMPLATE' => array('' ,'questionnaireTEMPLATE') ,
		'MASTERSBLOG_TEMPLATE'   => array('' ,'mastersBlogTEMPLATE'  ) ,


		'TOP_PREVIEW_TEMPLATE' => array(''   ,'indexPREVIEWTEMPLATE') ,
		'TOPPREVIEW_TEMPLATE'  => array(''   ,'indexPREVIEWTEMPLATE') ,


		/* スケルトンファイル */
		'404_SKELETON'          => array('' ,'404SKELETON'     ) ,
		'TOP_SKELETON'          => array('' ,'indexSKELETON'   ) ,
		'NEWS_SKELETON'         => array('' ,'newsSKELETON'    ) ,
		'ALBUM_SKELETON'        => array('' ,'albumSKELETON'   ) ,
		'album_SKELETON'        => array('' ,'albumSKELETON'   ) ,
		'RECRUIT_SKELETON'      => array('' ,'recruitSKELETON' ) ,

		'SCHEDULE_SKELETON'     => array('' ,'scheduleSKELETON') ,

		'SYSTEM_SKELETON'       => array('' ,'systemSKELETON'  ) ,
		'PRICEL_SKELETON'       => array('' ,'systemSKELETON'  ) ,

		'LINK_SKELETON'         => array('' ,'linkSKELETON'    ) ,
		'MAILMEMBER_SKELETON'   => array('' ,'mailmemberSKELETON') ,
		'INQUIRY_SKELETON'      => array('' ,'inquirySKELETON'   ) ,

		'QUESTIONNAIRE_SKELETON' => array('' ,'questionnaireSKELETON') ,
		'MASTERSBLOG_SKELETON'   => array('' ,'mastersBlogSKELETON'  ) ,


		'TOP_PREVIEW_SKELETON' => array('' ,'indexPREVIEWSKELETON') ,
		'TOPPREVIEW_SKELETON'  => array('' ,'indexPREVIEWSKELETON') ,


		'PROFILE'           => array('profile/' ,'profile'        ) ,
		'profile'           => array('profile/' ,'profile'        ) ,
		'PROFILE_TEMPLATE'  => array('profile/' ,'profileTEMPLATE') ,
		'profile_TEMPLATE'  => array('profile/' ,'profileTEMPLATE') ,
		'PROFILE_SKELETON'  => array('profile/' ,'profileSKELETON') ,

		'PHOTODIARY'          => array('../photoDiary/BRANCH_NAME/'       ,'index') ,
		'PHOTODIARY_TEMPLATE' => array('../photoDiaryTEMPLATE/BRANCH_NO/' ,'indexTEMPLATE') ,
		'PHOTODIARY_SKELETON' => array('../photoDiaryTEMPLATE/0/'         ,'indexSKELETON') ,

		'PHOTODIARYYM'          => array('../photoDiary/BRANCH_NAME/'            ,'index') ,
		'PHOTODIARYYM_TEMPLATE' => array('../photoDiaryTEMPLATE/BRANCH_NO/YYMM/' ,'indexTEMPLATE') ,
		'PHOTODIARYYM_SKELETON' => array('../photoDiaryTEMPLATE/0/YYMM/'         ,'indexSKELETON')
	);

		if(strcmp($fileID ,'PROFILE') == 0
		|| strcmp($fileID ,'profile') == 0) {
			if(strlen($dir) >= 1) {
				$subDirC = 'BRANCH_NAME/' . $fileIDList[$fileID][0];
			} else {
				$subDirC = $fileIDList[$fileID][0];
			}
		} else {
			if(strlen($dir) >= 1) {
				$subDirC = 'BRANCH_NAME/' . $fileIDList[$fileID][0];
			} else {
				$subDirC = $fileIDList[$fileID][0];
			}
		}
					/* print 'subDirC: ' . $subDirC; */

		if((strcmp($fileID ,'PROFILE') == 0 || strcmp($fileID ,'profile') == 0)
		&& strlen($param1) >= 1) {
			$fileName = $param1;
		} else {
			$fileName = $fileIDList[$fileID][1];
		}
					/* print 'fileName: ' . $fileName; */

		/*** 拡張子の指定があれば取り出す　なければ'html' */
		if(isset($fileIDList[$fileID][2])) {
			$ext = $fileIDList[$fileID][2];
		} else {
			$ext = 'html';
		}

		/***** ディレクトリとファイル名の連結 *****/
		$deviceList = funcs5C::getSetDeviceSet($device);
		foreach($deviceList as $device1) {
			if(strcmp($device1 ,common5C::DEVICE_PC) == 0
			|| strcmp($device1 ,common5C::DEVICE_SP) == 0
			|| strcmp($device1 ,common5C::DEVICE_MO) == 0) {

				$fullPath = $baseDirBase . $subDirA . $subDirC . $fileName . '.' . $ext;
				$fullPath = str_replace('BRANCH_NO'   ,$branchNo ,$fullPath);
				$fullPath = str_replace('BRANCH_NAME' ,$dir      ,$fullPath);

				$ret[$device1] = $fullPath;
			}
		}
		return $ret;
	}


/**
 * CSSファイル名取得
 *
 * @param string $fileID   ファイル名
 * @param string $branchNo 店No
 * @return string フルパスファイル名
 */
	function getCssFileName($fileID ,$branchNo) {

		$baseDirBase = realpath(dirname(__FILE__) . '/..') . '/css';

		if(strlen($branchNo) >= 1) {
			$baseDirBase = $baseDirBase . '/' . $branchNo;
			if(!is_dir($baseDirBase)) {
				mkdir($baseDirBase);
			}
		}
		$ret = $baseDirBase . '/' . $fileID . '.css';

		return $ret;
	}


/**
 * 写メ日記用ファイル名取得
 *
 * @param string $device 対象デバイス
 * @param string $fileID 対象ファイル種別
 * @param string $dir    ディレクトリ
 * @param string $param1 追加パラメータ1
 * @param string $param2 追加パラメータ2
 * @return string フルパスファイル名
 */
	function getPDFileName($device ,$fileID ,$dir ,$param1=null ,$param2='') {

		$mode = $param1['mode'];
		$fileParam = $mode . $fileID;

		if(isset($param1['loginID'])) {
			$loginID = $param1['loginID'];
		} else {
			$loginID = '';
		}

		if(isset($param1['pageIdx'])) {
			$pageIdx = $param1['pageIdx'];
		} else {
			$pageIdx = 0;
		}

		$baseDirBase = realpath(dirname(__FILE__) . '/..') . '/';

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

		$profileTemplate = 'index' . $param2 . 'TEMPLATE';
		$fileIDList = array(
			'BRANCH'      => array(''      ,'DEFAULT') ,
			'BRANCHMONTH' => array('YYMM/' ,'DEFAULT') ,
			'BRANCHDAY'   => array('YYMM/' ,'DZZ9' ) ,

			'PROF'      => array(''      ,'DEFAULT') ,
			'PROFMONTH' => array('YYMM/' ,'DEFAULT') ,
			'PROFDAY'   => array('YYMM/' ,'ZZ9' ) ,


			'BRANCHTEMPLATE'      => array(''      ,'indexTEMPLATE') ,
			'BRANCHMONTHTEMPLATE' => array('YYMM/' ,'indexTEMPLATE') ,
			'BRANCHDAYTEMPLATE'   => array('YYMM/' ,'indexTEMPLATE') ,

			'PROFTEMPLATE'      => array(''      ,$profileTemplate) ,
			'PROFMONTHTEMPLATE' => array('YYMM/' ,$profileTemplate) ,
			'PROFDAYTEMPLATE'   => array('YYMM/' ,$profileTemplate)
		);
		$fileID1 = $fileIDList[$fileParam];

		$subDir1 = $fileID1[0];

		if(strcmp($fileID ,'TEMPLATE') == 0) {
			$str = 'TEMPLATE';
		} else {
			if(strcmp($fileID1[0] ,'YYMM/') == 0) {
				if(isset($param1['yy'])) {
					$strYY = substr($param1['yy'] ,2 ,2);
					$strMM = sprintf('%02d' ,$param1['mm']);
					$yymm  = $strYY . $strMM;
				} else {
					$yymm = date('y') . date('m');
				}

				$subDir1 = mb_ereg_replace('YYMM' ,$yymm ,$fileID1[0]);
			}

			$str = '';
		}
		$str = 'photoDiary' . $str . '/';

		if(strncmp($fileParam ,'BRANCH' ,6) == 0) {
			/*** 店の時 ***/
			$subDirC = $str . $dir . '/' . $subDir1;
		} else {
			/*** プロファイルの時 ***/
			$subDirC = $str . 'PROFILE/' . $subDir1;
			if(strlen($loginID) >= 1) {
				if(strcmp($fileID ,'TEMPLATE') != 0) {
					$subDirC = mb_ereg_replace('PROFILE' ,$loginID ,$subDirC);
					$subDirC = mb_ereg_replace('profile' ,$loginID ,$subDirC);
				}
			}
		}

		if((strcmp($fileID ,self::FILEID_PROFILE) == 0 || strcmp($fileID ,self::FILEID_PROFILE2) == 0)
		&& strlen($param1) >= 1) {
			$fileName = $param1;
		} else {
			$fileName = $fileID1[1];
			if(isset($param1['dayStr'])) {
				$pageStr = $pageIdx + 1;
				$pageStr = 'D' . $param1['dayStr'] . '-' . $pageStr;
			} else {
				if($pageIdx <= 0) {
					$pageStr = 'index';
				} else {
					$pageStr = $pageIdx + 1;
				}
			}

			$fileName = mb_ereg_replace('DEFAULT' ,$pageStr ,$fileName);

			$pageStr = $pageIdx + 1;
			$pageStr = 'D' . $pageStr;
			$fileName = mb_ereg_replace('ZZ9' ,$pageStr ,$fileName);
		}

		/*** 拡張子の指定があれば取り出す　なければ'html' */
		if(isset($fileID1[2])) {
			$ext = $fileID1[2];
		} else {
			$ext = 'html';
		}

		/***** ディレクトリとファイル名の連結 *****/
		$deviceList = funcs5C::getSetDeviceSet($device);
		foreach($deviceList as $device1) {
			if(strcmp($device1 ,common5C::DEVICE_PC) == 0
			|| strcmp($device1 ,common5C::DEVICE_SP) == 0
			|| strcmp($device1 ,common5C::DEVICE_MO) == 0) {
				$ret[$device1] = $baseDirBase . $subDirA . $subDirC . $fileName . '.' . $ext;
			}
		}

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

				/***** 基準ディレクトリ識別 *****/
				/*** OS ***/
				/* const DIR_OS_ROOT','OSRoot'); */	/*** root ***/
				/* const DIR_OS_PC'  ,'OSPC'); */		/*** PC ***/
				/* const DIR_OS_SP'  ,'OSSP'); */		/*** SP ***/
				/* const DIR_OS_MO'  ,'OSMO'); */		/*** MO ***/

				/*** web ***/
				/* const DIR_WEB_ROOT' ,'WebRoot'); */		/*** root ***/
				/* const DIR_WEB_PC'   ,'WebPC'); */		/*** PC ***/
				/* const DIR_WEB_SP'   ,'WebSP'); */		/*** SP ***/
				/* const DIR_WEB_MO'   ,'WebMO') */;		/*** MO ***/

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
