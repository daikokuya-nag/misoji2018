<?php
/********************
ニュースの表示可否の出力 Version 1.0
PHP5
2016 Feb. 29 ver 1.0
********************/
	session_start();

	require_once dirname(__FILE__) . '/../../db/dbNews5C.php';
	require_once dirname(__FILE__) . '/../../sess/sess5C.php';

	$cond = sess5C::getSessCond();

	if($cond == sess5C::OWN_INTIME) {
		sess5C::updSessCond();

		$news = new dbNews5C();
		$dispCnt = $news->updDisp($_POST);
		$ret['DISPCOUNT'] = $dispCnt;
	}

	$ret['SESSCOND'] = $cond;
	print json_encode($ret);
?>
