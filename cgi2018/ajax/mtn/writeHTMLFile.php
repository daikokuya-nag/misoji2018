<?php
/********************
ファイル出力 Version 1.0
PHP5
2016 Feb. 23 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../html/html5C.php';
	require_once dirname(__FILE__) . '/../../sess/sess5C.php';


	$branchNo = $_REQUEST['branchNo'];	/* 店No */
	$fileID   = $_REQUEST['fileID'  ];	/* ファイルID */
	$profName = $_REQUEST['profName'];	/* ファイル名（要るか？） */

					//print $fileID . "\n";

	$div  = sess5C::getOutSect($fileID);
	$vals = sess5C::getOutVals($fileID);

	if(strcmp($fileID ,'PROFILE') == 0) {
		$out = new html5C($branchNo ,$fileID ,$profName);
	} else {
		$out = new html5C($branchNo ,$fileID);
	}

	$out->getFileSect();
	$out->cnv();
	$out->outFile();
?>
