<?php
/********************
ログイン Version 1.0
PHP5
2016 Mar. 3 ver 1.0
********************/
	session_start();

	$id2      = $_REQUEST['id2'];
	$branchNo = $_REQUEST['branchNo'];	/* 店No */

	$mtn         = 'N';	/* メンテモード */
	$retGroupNo  = '';
	$retBranchNo = '';

	if(strcmp($id2 ,'mosoji999') == 0
	|| strcmp($id2 ,'12345') == 0) {
		$retBranchNo = $branchNo;
	} else {
		if(strcmp($id2 ,'mosoji999') == 0) {
			$retBranchNo = $branchNo;
			$mtn = 'Y';
		}
	}

	$ret['BRANCHNO'] = $retBranchNo;

	$_SESSION['BRANCHNO'] = $retBranchNo;
	$_SESSION['MTN'     ] = $mtn;

	print json_encode($ret);
?>
