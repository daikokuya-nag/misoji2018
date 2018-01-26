<?php
/********************
使用ページ情報の読み込み Version 1.0
PHP5
2016 Feb. 20 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../sql5C.php';
	require_once dirname(__FILE__) . '/../../db/dbPageParam5C.php';

	$branchNo = $_REQUEST['branchNo'];	/* 店No */

	$ret[dbPageParam5C::PAGE_ALBUM     ]['USE'] = dbPageParam5C::OWN;
	$ret[dbPageParam5C::PAGE_NEWS      ]['USE'] = dbPageParam5C::OWN;
	$ret[dbPageParam5C::PAGE_RECRUIT   ]['USE'] = dbPageParam5C::OWN;
	$ret[dbPageParam5C::PAGE_SYSTEM    ]['USE'] = dbPageParam5C::OWN;
	$ret[dbPageParam5C::PAGE_PHOTODIARY]['USE'] = dbPageParam5C::OTHER;

	$ret[dbPageParam5C::PAGE_ALBUM     ][dbPageParam5C::OBJ_USE_PAGE] = '';
	$ret[dbPageParam5C::PAGE_NEWS      ][dbPageParam5C::OBJ_USE_PAGE] = '';
	$ret[dbPageParam5C::PAGE_RECRUIT   ][dbPageParam5C::OBJ_USE_PAGE] = '';
	$ret[dbPageParam5C::PAGE_SYSTEM    ][dbPageParam5C::OBJ_USE_PAGE] = '';
	$ret[dbPageParam5C::PAGE_PHOTODIARY][dbPageParam5C::OBJ_USE_PAGE] = '';


	$handle  = new sql5C();
	$paramDB = new dbPageParam5C($handle);
	$useData = $paramDB->readUsePage($branchNo);

	$idxMax = $useData['count'];
	for($idx=0 ;$idx<$idxMax ;$idx++) {

		$page1 = $useData['pageVal'][$idx];

		$pageID  = $page1[dbPageParam5C::FLD_PAGE_ID];
		$useOwn  = $page1[dbPageParam5C::FLD_VALUE1];
		$usePage = $page1[dbPageParam5C::FLD_VALUE2];

		if(isset($ret[$pageID])) {
			$ret[$pageID]['USE'] = $useOwn;
			$ret[$pageID][dbPageParam5C::OBJ_USE_PAGE] = $usePage;
		}
	}

	print json_encode($ret);
?>
