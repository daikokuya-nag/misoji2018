<?php
/**
 * ファイルアップロード
 *
 * 新システムではcopyImgFile() のみを使用する
 *
 * @package    
 * @subpackage 
 * @version    1.0
 * @since      
 * @author     
 */

	require_once dirname(__FILE__) . '/fileName5C.php';

class fileUpload5C {

/**
 * 画像ファイル保存
 *
 * @param string[] $fileINFO ファイル情報
 * @param string   $fileName ファイル名
 * @param int      $branchNo 店No
 * @param string   $subDir   サブディレクトリ
 * @return string[] ファイル拡張子
 */
	function copyImgFile($fileINFO ,$fileName ,$branchNo ,$subDir='') {

		/***** 写真ファイルのディレクトリがなければ作る *****/
		$dir = fileName5C::getImgRelativeDir($branchNo ,fileName5C::DIR_OS_ROOT);
		$dir = $dir . '/' . $branchNo;
		if(!is_dir($dir)) {
			mkdir($dir);
			chmod($dir ,0755);
		}

		if(strlen($subDir) >= 1) {
			$dir = $dir . '/' . $subDir;
			if(!is_dir($dir)) {
				mkdir($dir);
				chmod($dir ,0755);
			}
		}

		/*** 拡張子を取り出してコピー先のファイル名につなげる ***/
		$fileExt = pathinfo($fileINFO["name"], PATHINFO_EXTENSION);
		$destFileName = $dir . '/' . $fileName . '.' . $fileExt;

		move_uploaded_file($fileINFO["tmp_name"] ,$destFileName);

		$ret['EXT'] = $fileExt;
	}
}
?>
