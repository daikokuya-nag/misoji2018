/*************************
求人 Version 1.0
2016 Jan. 25 ver 1.0
*************************/

var RECRUIT_STR;

/***** 初期化 *****/
$(document).ready(function(){

	/***** 求人内容データの読み込み *****/
	getRecruitVal();
});

$(window).load(function(){
});


/********************
求人内容の読み込み
********************/
function getRecruitVal() {

var result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getRecruitVal.php" ,
		data : {
			branchNo : BRANCH_NO
		} ,

		cache : false
	});

	result.done(function(response) {
					//console.debug(response);
		RECRUIT_STR = response;
	});

	result.fail(function(response, textStatus, errorThrown) {
					console.debug('error at getRecruitVal:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
		$("#recruitStr").val(RECRUIT_STR);
	});
}

/********************
求人内容の出力
********************/
function writeRecruitVal() {

var result;
var str = CKEDITOR.instances.recruitStr.getData();

	if(str.length >= 1) {
		result = $.ajax({
			type : "post" ,
			url  : "../cgi2018/ajax/mtn/writeRecruitVal.php" ,
			data : {
				branchNo : BRANCH_NO ,
				str      : str
			} ,

			cache    : false ,
			dataType : 'json'
		});

		result.done(function(response) {
						console.debug(response);
			if(response['SESSCOND'] == SESS_OWN_INTIME) {
				selectWriteFile('RECRUIT');		//出力対象ファイルの抽出→ファイル出力
			} else {
				jAlert(
					'長時間操作がなかったため接続が切れました。ログインしなおしてください。' ,
					'メンテナンス' ,
					function() {
						location.href = 'login.html';
					}
				);
			}
		});

		result.fail(function(response, textStatus, errorThrown) {
						console.debug('error at writeRecruitVal:' + response.status + ' ' + textStatus);
		});

		result.always(function() {
		});
	}
}
