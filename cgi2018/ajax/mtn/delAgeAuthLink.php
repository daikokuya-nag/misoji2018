<?php
/********************
年齢認証ページのリンク削除 Version 1.0
PHP5
2016 Feb. 29 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbPageParam5C.php';
	require_once dirname(__FILE__) . '/../../db/dbLinkExchange5C.php';

	require_once dirname(__FILE__) . '/../../sess/sess5C.php';

	$cond = sess5C::getSessCond();
	if($cond == sess5C::OWN_INTIME) {
		sess5C::updSessCond();

		$linkExchange = new dbLinkExchange5C();

		$branchNo = $_POST['branchNo'];
		$editNo   = $_POST['editNo'];

		$linkExchange->del($branchNo ,'TOP' ,$editNo);
	}

	$ret['SESSCOND'] = $cond;
	print json_encode($ret);
?>
