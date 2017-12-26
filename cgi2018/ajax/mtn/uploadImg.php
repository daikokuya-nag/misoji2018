<?php
/**
 * 画像登録
 *
 * 指定された画像ファイルを取り込み、その画像情報を登録する
 *
 * @package    
 * @subpackage 
 * @version    1.0
 * @since      
 * @author     
 * 
 * 渡される値 branchNo 店No
 *            title    画像のタイトル
 *            newFile  画像ファイル
 */

	require_once dirname(__FILE__) . '/../../fileUpload5C.php';
	require_once dirname(__FILE__) . '/../../db/dbImage5C.php';

	if(!isset($_REQUEST['branchNo'])) {
		die();
	}

	$branchNo = $_REQUEST['branchNo'];	/* 店No */
	$file     = $_FILES['newFile'];
	$title    = $_REQUEST['title'];
	$class    = $_REQUEST['class'];

	/***** ファイル名の取得 *****/
	$name = $file['name'];
	$fileName = pathinfo($name ,PATHINFO_FILENAME);
	$fileExt  = pathinfo($name ,PATHINFO_EXTENSION);

	/***** ファイルの画素数取得 */
	$imgSize  = getimagesize($file['tmp_name']);
	$width    = $imgSize[0];
	$height   = $imgSize[1];


	/***** db登録 *****/
	$handle = null;
	$imgDB  = new dbImage5C($branchNo ,$handle);
	$handle = $imgDB->getHandle();

	$imgDB->setVal(dbImage5C::FLD_BRANCH_NO ,$branchNo);
	$imgDB->setVal(dbImage5C::FLD_WIDTH  ,$width);
	$imgDB->setVal(dbImage5C::FLD_HEIGHT ,$height);
	$imgDB->setVal(dbImage5C::FLD_TITLE  ,$title);
	$imgDB->setVal(dbImage5C::FLD_CLASS  ,$class);

	/* 原稿のファイルの情報 */
	$imgDB->setVal(dbImage5C::FLD_ORG_WIDTH  ,$width);
	$imgDB->setVal(dbImage5C::FLD_ORG_HEIGHT ,$height);

	$imgDB->setVal(dbImage5C::FLD_ORG_FILENAME ,$fileName);	/* 原稿の画像ファイル名 */
	$imgDB->setVal(dbImage5C::FLD_ORG_EXT      ,$fileExt);	/* 原稿の画像ファイル名拡張子 */

	$imgDB->add();
	$newNo = $handle->getLastInsertID(dbImage5C::TABLE_NAME);

	/* 画像ファイルの取り込み */
	fileUpload5C::copyImgFile($file ,$newNo ,$branchNo ,$class);
?>
