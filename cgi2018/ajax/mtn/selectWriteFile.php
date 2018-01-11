<?php
/********************
出力ファイルの抽出 Version 1.0
PHP5
2016 Feb. 23 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../template5C.php';
	require_once dirname(__FILE__) . '/../../siteConst5C.php';
	require_once dirname(__FILE__) . '/../../fileName5C.php';

	require_once dirname(__FILE__) . '/../../sess/sess5C.php';
	require_once dirname(__FILE__) . '/../../db/dbProfile5C.php';

	$cond = sess5C::getSessCond();
	if($cond == sess5C::OWN_INTIME) {
		sess5C::updSessCond();
	}

	$branchNo = $_REQUEST['branchNo'];	/* 店No */
	$outID    = $_REQUEST['outItem' ];	/* 出力項目 */

	/***** 対象ファイルの抽出 *****/
	$outFile = array();	//対象ファイル
	$outItem = array();	//対象項目

	sess5C::resetOutSect();
	sess5C::resetOutVals();

	//紹介ページ
	$fileID = 'PROFILE';
	$outVal = readTemplateFile($fileID ,$outID ,$branchNo);
	if(isset($outVal['OUT_FILE_NAME']['fileName'])) {
		$outFile['PROFILE'] = setProfiles($branchNo);

		$useItem = $outVal['outItem'];
		$itemMax = count($useItem);
		for($idx=0 ;$idx<$itemMax ;$idx++) {
			$item = $useItem[$idx];
			$outItem[$item] = true;			//対象項目をON
		}
	}

	//紹介ページ以外
	$fileList = siteConst5C::getHtmlFileList();		//出力対象ファイル
	$idxMax = count($fileList);
	foreach($fileList as $fileID => $fileName) {
		//ファイルの中身を検索して当該要素があれば出力対象にする
		$outVal = readTemplateFile($fileID ,$outID ,$branchNo);
							//print_r($outVal);
		if(isset($outVal['OUT_FILE_NAME']['fileName'])) {
			$outFile[$fileID] = $outVal['OUT_FILE_NAME']['fileName'];		//出力対象

			$useItem = $outVal['outItem'];
			$itemMax = count($useItem);
			for($idx=0 ;$idx<$itemMax ;$idx++) {
				$item = $useItem[$idx];
				$outItem[$item] = true;			//対象項目をON
			}
		}
	}

	//出力する要素をセッションに保持
	foreach($outItem as $item1 => $val) {
					//print $item1;
		sess5C::setOutVals($item1 ,$branchNo);
	}
					//print sess5C::getOutVals('RECRUIT');
					//print sess5C::getOutVals('SYSTEM');

	print json_encode($outFile);


	function setProfiles($branchNo) {

		$ret = array();

		$prof = new dbProfile5C();
		$list = $prof->readShowableProf($branchNo);

		$profList = $list['profInfo'];
		$idxMax   = $list['count'];
		for($idx=0 ;$idx<$idxMax ;$idx++) {
			$prof1 = $profList[$idx];
			$dir = $prof1[dbProfile5C::FLD_DIR];

			$fileName = $dir . '.html';
			$ret[$dir] = $fileName;
		}

		return $ret;
	}


	function readTemplateFile($fileID ,$outID ,$branchNo) {

		//テンプレートファイル名の抽出
		$templateFileName = fileName5C::getFileName('PC' ,$fileID . '_TEMPLATE' ,'' ,$branchNo);
		//出力ファイル名の抽出
		$outFileName = fileName5C::getFileName('PC' ,$fileID ,'' ,$branchNo);

		//そのファイルの出力項目の取り出し
		$template = new template5C();
		$template->read($templateFileName['fullPath']);	//ファイル読み込み
		$template->divideSection();						//分割
		$outItem = $template->sectItems();

		$hit = false;
		$idxMax = count($outItem);
		for($idx=0 ;$idx<$idxMax ;$idx++) {
			$id1 = $outItem[$idx];
			if(strcmp($id1 ,$outID) == 0) {
				$hit = true;
				break;
			}
		}

		//対象項目が1つ以上あればそのファイルを出力対象に追加する
		if($idxMax >= 1 && $hit) {
				//print '対象ファイル' . "\n";
			$ret = array(
				'TEMPORARY_FILE_NAME' => $templateFileName ,
				'OUT_FILE_NAME'       => $outFileName
			);
			$div = $template->getDividedBySect();		//getUseSect() //getDividedBySect();

					/*
					print "set out div:" . $fileID ."\n";
					print_r($div);
					print "\n";
					*/

			sess5C::setOutSect($fileID ,$div);
		} else {
			$ret = array(
				'TEMPORARY_FILE_NAME' => '' ,
				'OUT_FILE_NAME'       => ''
			);
		}

		$ret['outItem'] = $outItem;
		return $ret;
	}
?>
