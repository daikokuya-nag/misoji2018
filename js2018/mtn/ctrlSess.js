/********************
セッション制御
********************/

var SESS_NO_ID         = 1;	//セッションデータナシ　ログイン画面へ

/*** タイムアウト ***/
var SESS_OWN_TIMEOUT   = 2;	//自IDでタイムアウト　タイムアウトのダイアログ、ログイン画面へ
var SESS_OTHER_TIMEOUT = 3;	//他IDでタイムアウト　ログイン画面へ

/*** not タイムアウト ***/
var SESS_OWN_INTIME    = 4;	//自IDでログイン中　メンテ画面へ
var SESS_OTHER_INTIME  = 5;	//他IDでログイン中　「他でログイン中」のダイアログ、ログイン不可


function getSess() {

var jqXHR;

	jqXHR = $.ajax({
		type  : "get" ,
		url   : "../cgi2018/ajax/mtn/getSess.php" ,
		cache : false ,
	});

	return jqXHR;
}

/********************
セッション情報の更新
********************/
function updSess() {

var jqXHR;

	jqXHR = $.ajax({
		type  : "get" ,
		url   : "../cgi2018/ajax/mtn/updSess.php" ,
		cache : false ,
	});

	return jqXHR;
}
