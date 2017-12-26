<?php
/********************
ページパラメータの読み込み Version 1.0
PHP5
2016 Feb. 20 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbPageParam5C.php';
	require_once dirname(__FILE__) . '/../../bld/bldImgList5C.php';

	$branchNo = $_REQUEST['branchNo'];	/* 店No */
	$pageID   = $_REQUEST['page'    ];
	$obj      = $_REQUEST['obj'     ];

	$pageParam = new dbPageParam5C($branchNo);

	$pageVals = $pageParam->readAll($pageID ,$obj);
	$extList  = bldImgList5C::bldSeqList($branchNo);

	$ret['pageVal'] = $pageVals['pageVal'][0];
	$ret['extList'] = $extList['extList'];

	print json_encode($ret);
?>
