/********************/
/* ログイン */
/********************/

/***** 初期化 *****/
jQuery(document).ready(function(){
});

$(window).load(function(){

	$("body").keypress(function(e){
		if(e.which == 13) {
			login();
		}
	});

	init();
});

function init() {

var sess;
var msg = '';
var result;

	result = getSess();

	result.done(function(response) {
					//console.debug(response);
		sess = response;
	});

	result.fail(function(result, textStatus, errorThrown) {
					console.debug('error at login:' + result.status + ' ' + textStatus);
	});

	result.always(function() {
		if(sess == SESS_OTHER_INTIME) {
			/* 他IDでログイン中　「他でログイン中」のダイアログ、ログイン不可 */
							console.debug('他ID ログイン中');
			msg = '他の端末からログインしているため、この端末からはログインできません。';
		}
		$("#msg").html(msg);
	});
}

function login() {

var sess;
var goLogin = 0;
var result;

	result = getSess();

	result.done(function(response) {
					//console.debug(response);
		sess = response;
	});

	result.fail(function(result, textStatus, errorThrown) {
					console.debug('error at login:' + result.status + ' ' + textStatus);
	});

	result.always(function() {
		if(sess == SESS_NO_ID) {
			/* セッションデータナシ　ログイン判定へ */
						console.debug('セッションファイルナシ');
			goLogin = 2;
		}

		if(sess == SESS_OWN_TIMEOUT) {
			/* 自IDでタイムアウト　ログイン判定へ */
							console.debug('自ID タイムアウト');
			goLogin = 2;
		}

		if(sess == SESS_OTHER_TIMEOUT) {
			/* 他IDでタイムアウト　ログイン判定へ */
							console.debug('他ID タイムアウト');
			goLogin = 2;
		}

		if(sess == SESS_OWN_INTIME) {
			/* 自IDでログイン中　ログイン判定へ */
							console.debug('自ID ログイン中');
			goLogin = 2;
		}

		if(sess == SESS_OTHER_INTIME) {
			/* 他IDでログイン中　「他でログイン中」のダイアログ、ログイン不可 */
							console.debug('他ID ログイン中');
			alert('他の端末からログインしているため、この端末からはログインできません。');
		}


		if(goLogin == 2) {
			//ログイン判定へ
			loginMain();
		}
	});
}

function loginMain() {

var id2 = $('#id2').val();

var branchNo = $('#branchNo').val();
var mtnMode  = '';
var result;

	result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/login.php" ,
		data : {
			id2      : id2 ,
			branchNo : branchNo
		} ,

		cache    : false  ,
		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);
		if(response['BRANCHNO'].length >= 1) {
			updSess();
			location.href = 'index.php';
		}
	});

	result.fail(function(result, textStatus, errorThrown) {
					console.debug('error at loginMain:' + result.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}
