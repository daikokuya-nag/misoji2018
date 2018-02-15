<?php
/********************
年齢認証ページのパラメータの出力 Version 1.0
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

		$pageParam = new dbPageParam5C();
		$handle = $pageParam->getHandle();
		$linkExchange = new dbLinkExchange5C($handle);

		setAgeAuthParam($pageParam);	// 画像出力	
		setAgeAuthLink($linkExchange);	// リンク出力
	}

	$ret['SESSCOND'] = $cond;
	print json_encode($ret);


	function setAgeAuthParam($pageParam) {

		$handle = $pageParam->getHandle();
		$branchNo = $_POST['branchNo'];

		//value3 表示する画像No
		$value3 = $_POST['img'];

		//str1 表示する文言
		$str1 = $_POST['str'];

		$pageParam->setVal(dbPageParam5C::FLD_VALUE1 ,'');
		$pageParam->setVal(dbPageParam5C::FLD_VALUE2 ,'');
		$pageParam->setVal(dbPageParam5C::FLD_VALUE3 ,$value3);
		$pageParam->setVal(dbPageParam5C::FLD_VALUE4 ,'');
		$pageParam->setVal(dbPageParam5C::FLD_VALUE5 ,'');
		$pageParam->setVal(dbPageParam5C::FLD_STR1   ,$str1);

		$cond = dbPageParam5C::FLD_BRANCH_NO . '=' . $branchNo                     . ' and ' .
				dbPageParam5C::FLD_PAGE_ID   . '=' . $handle->setQuote('AGE_AUTH') . ' and ' .
				dbPageParam5C::FLD_OBJ       . '=' . $handle->setQuote('TOP');
		$exist = $handle->existRec(dbPageParam5C::TABLE_NAME ,$cond);

		if($exist) {
			//更新
			$pageParam->upd($branchNo ,'AGE_AUTH' ,'TOP');
		} else {
			//レコード追加
			$pageParam->add($branchNo ,'AGE_AUTH' ,'TOP');
		}
	}


	function setAgeAuthLink($linkExchange) {

		$branchNo = $_POST['branchNo'];
		$handle = $linkExchange->getHandle();

		$linkMax = $_POST['linkSeq'];	// 総リンク数
		for($idx=1 ;$idx<=$linkMax ;$idx++) {
			$siteName = $_POST['name' . $idx];
			$url      = $_POST['url'  . $idx];
			$img      = $_POST['img'  . $idx];

			$linkExchange->setVal(dbLinkExchange5C::FLD_SITE_NAME ,$siteName);
			$linkExchange->setVal(dbLinkExchange5C::FLD_URL       ,$url);
			$linkExchange->setVal(dbLinkExchange5C::FLD_IMG_FILE  ,$img);

			$cond = dbLinkExchange5C::FLD_BRANCH_NO . '=' . $branchNo                . ' and ' .
					dbLinkExchange5C::FLD_PAGE_ID   . '=' . $handle->setQuote('TOP') . ' and ' .
					dbLinkExchange5C::FLD_SEQ       . '=' . $idx;
			$exist = $handle->existRec(dbLinkExchange5C::TABLE_NAME ,$cond);

			if($exist) {
				//更新
				$linkExchange->upd($branchNo ,'TOP' ,$idx);
			} else {
				//レコード追加
				$linkExchange->add($branchNo ,'TOP' ,$idx);
			}
		}
	}
?>
