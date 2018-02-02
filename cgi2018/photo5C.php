<?php
/**
 * 写真ファイル
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */

	require_once dirname(__FILE__) . '/db/dbProfile5C.php';

/**
 * 写真ファイル
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */
class photo5C {

	var $useList;		// 各写真の使用/非使用
	var $extList;		// 各写真ファイルの拡張子リスト
	var $photoRootDir;	// 写真ディレクトリのパス

/**
 * コンストラクタ
 *
 * @access
 * @param
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function photo5C() {

		$this->useList   = null;
		$this->extList   = null;
		$this->photoShow = null;
		$this->photoRootDir = dirname(__FILE__) . '/../photo';
	}


/**
 * 全員分の写真情報の取得
 *
 * @access
 * @param
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function getAllDirPhoto() {

		$photoDir = $this->photoRootDir;

		$db = new dbProfile5C(null);
		$allVal = $db->readAll(1);
		$profList = $allVal['profInfo'];
		$profMax  = $allVal['count'   ];
		for($idx=0 ;$idx<$profMax ;$idx++) {
			$prof1 = $profList[$idx];

			$dir = $prof1[dbProfile5C::FLD_DIR];

			$this->useList[$dir] = array(
				'1'  => $prof1[dbProfile5C::FLD_PHOTOUSE_1] ,
				'2'  => $prof1[dbProfile5C::FLD_PHOTOUSE_2] ,
				'3'  => $prof1[dbProfile5C::FLD_PHOTOUSE_3] ,
				'4'  => $prof1[dbProfile5C::FLD_PHOTOUSE_4] ,
				'5'  => $prof1[dbProfile5C::FLD_PHOTOUSE_5] ,
				'TN' => $prof1[dbProfile5C::FLD_PHOTOUSE_S] ,
				'ML' => $prof1[dbProfile5C::FLD_PHOTOUSE_M]
			);

			$this->extList[$dir] = array(
				'1'  => $prof1[dbProfile5C::FLD_PHOTOEXT_1] ,
				'2'  => $prof1[dbProfile5C::FLD_PHOTOEXT_2] ,
				'3'  => $prof1[dbProfile5C::FLD_PHOTOEXT_3] ,
				'4'  => $prof1[dbProfile5C::FLD_PHOTOEXT_4] ,
				'5'  => $prof1[dbProfile5C::FLD_PHOTOEXT_5] ,
				'TN' => $prof1[dbProfile5C::FLD_PHOTOEXT_S] ,
				'ML' => $prof1[dbProfile5C::FLD_PHOTOEXT_M]
			);

			$this->photoShow[$dir] = $prof1[dbProfile5C::FLD_PHOTO_SHOW];
		}
	}

/**
 * 写真情報の取得
 *
 * @access
 * @param string $dir プロファイル識別
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	function getDirPhoto($dir) {

		$photoDir = $this->photoRootDir;

		$db  = new dbProfile5C();
		$val = $db->get(1 ,$dir);
					//print $dir;
		$this->useList[$dir] = array(
			'1'  => $val[dbProfile5C::FLD_PHOTOUSE_1] ,
			'2'  => $val[dbProfile5C::FLD_PHOTOUSE_2] ,
			'3'  => $val[dbProfile5C::FLD_PHOTOUSE_3] ,
			'4'  => $val[dbProfile5C::FLD_PHOTOUSE_4] ,
			'5'  => $val[dbProfile5C::FLD_PHOTOUSE_5] ,
			'TN' => $val[dbProfile5C::FLD_PHOTOUSE_S] ,
			'ML' => $val[dbProfile5C::FLD_PHOTOUSE_M]
		);

		$this->extList[$dir] = array(
			'1'  => $val[dbProfile5C::FLD_PHOTOEXT_1] ,
			'2'  => $val[dbProfile5C::FLD_PHOTOEXT_2] ,
			'3'  => $val[dbProfile5C::FLD_PHOTOEXT_3] ,
			'4'  => $val[dbProfile5C::FLD_PHOTOEXT_4] ,
			'5'  => $val[dbProfile5C::FLD_PHOTOEXT_5] ,
			'TN' => $val[dbProfile5C::FLD_PHOTOEXT_S] ,
			'ML' => $val[dbProfile5C::FLD_PHOTOEXT_M]
		);

		$this->photoShow[$dir] = $val[dbProfile5C::FLD_PHOTO_SHOW];
	}



/**
 * 写真ディレクトリの取得
 *
 * @access
 * @param string $dir 識別子
 * @return string 写真ディレクトリ
 * @link
 * @see
 * @throws
 * @todo
 */
	function photoDir($dir) {

		$ret = $this->photoRootDir . '/' . $dir;

		return $ret;
	}


/**
 * 写真ファイルの有無
 *
 * @access
 * @param string $dir プロファイル識別
 * @param string $imgID 写真識別
 * @return boolean 写真ファイル名
 * @link
 * @see
 * @throws
 * @todo
 */
	function existImgFile($dir ,$imgID) {

		$ret = '';

		$photoDir = self::photoDir($dir);			// そのプロファイルの写真のrootディレクトリ
		$extID    = $this->extList[$dir][$imgID];	// その写真の拡張子

		$photoFileName = $photoDir . '/' . $dir . $imgID . '.' . $extID;
						//print 'photo file name:' . $photoFileName . "\n";
		if(is_file($photoFileName)) {
			$ret = $dir . $imgID;
		} else {
			// 新版がなければ旧版を探す
			if(strcmp($imgID ,'TN') == 0) {
				$fileName = 'thumbNail';
			} else {
				$fileName = 'largePhoto' . $imgID;
			}

			$photoFileName = $photoDir . '/' . $fileName . '.' . $extID;
						//print 'photo file name:' . $photoFileName . "\n";
			if(is_file($photoFileName)) {
				$ret = $fileName;
			}
		}

		return $ret;
	}


/**
 * 写真を使用できるか
 *
 * @access
 * @param string $dir プロファイル識別
 * @param array $imgID 判定する写真識別リスト
 * @return array 写真を使用できるか
 * @link
 * @see
 * @throws
 * @todo
 */
	function getUsePhoto($dir ,$imgID) {

		$showMode = $this->photoShow[$dir];

					//print $dir . ' showMode:' . $showMode . "\n";
		if(strcmp($showMode ,dbProfile5C::PHOTO_SHOW_OK) == 0) {
			// 表示可のときは各ファイルの有無の状態に応じて設定
			foreach($imgID as $photoID) {
				// 使用/非使用の取り出し
				$use = false;
				if(isset($this->useList[$dir][$photoID])) {
					$use = $this->useList[$dir][$photoID];
				}
				$exist = $this->existImgFile($dir ,$photoID);		// 写真ファイルの有無

				if($use && strlen($exist) >= 1) {
					$ret[$photoID]['cond'] = dbProfile5C::PHOTO_SHOW_OK;	// 写真ファイルがあり、使用/非使用が使用であれば　表示可
					$ret[$photoID]['fileName'] = $exist;
							//$ret[$photoID]['style'   ] = 'style="width:110px;"';
				} else {
					$ret[$photoID]['cond'] = dbProfile5C::PHOTO_SHOW_NOT;	// 写真ファイルがない、または使用/非使用が非使用であれば　写真ナシ
				}
			}
		} else {
			// 表示可以外のときは無条件にその状態に設定
			foreach($imgID as $photoID) {
				$ret[$photoID]['cond'] = $showMode;
			}
		}
		$ret['ALL']['cond'] = $showMode;
		return $ret;
	}


/**
 * 写真情報の取得
 *
 * @access
 * @param string $dir プロファイル識別
 * @param array $imgID 判定する写真識別リスト
 * @return array 写真情報
 * @link
 * @see
 * @throws
 * @todo
 */
	function getPhotoInfo($dir ,$imgID) {

		$ret['USE'] = '';
		$ret['EXT'] = '';

		if(isset($this->useList[$dir][$imgID])) {
			$ret['USE'] = $this->useList[$dir][$imgID];
		}

		if(isset($this->extList[$dir][$imgID])) {
			$ret['EXT'] = $this->extList[$dir][$imgID];
		}

		return $ret;
	}
}
?>
