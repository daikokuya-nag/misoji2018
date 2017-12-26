/*************************
求人 Version 1.0
2016 Jan. 25 ver 1.0
*************************/

var RECRUIT_STR;

/***** 初期化 *****/
$(document).ready(function(){

	/***** 求人内容データの読み込み *****/
	getRecruitVal();

	CKEDITOR.instances.recruitStr.on("blur", function(e) {
		CKEDITOR.instances.recruitStr.updateElement();
		var str = $("#recruitStr").val();
		var msg;

		if(str.length >= 1) {
			msg = '';
		} else {
			msg = 'any error';
		}
		$("#warnRecruitStr").html(msg);
	});
});

$(window).load(function(){
});


/********************
求人内容の読み込み
********************/
function getRecruitVal() {

var result;
var str;
var branchNo = $('#branchNo').val();

	result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getRecruitVal.php" ,
		data : {
			branchNo : branchNo
		} ,

		cache : false
	});

	result.done(function(response) {
					//console.debug(response);
		RECRUIT_STR = response;
	});

	result.fail(function(result, textStatus, errorThrown) {
					console.debug('error at getRecruitVal:' + result.status + ' ' + textStatus);
	});

	result.always(function() {
		$("#recruitStr").val(RECRUIT_STR);
		CKEDITOR.instances.recruitStr.setData(RECRUIT_STR);
	});
}

/********************
求人内容の出力
********************/
function writeRecruitVal() {

var result;
var branchNo = $('#branchNo').val();
var str = CKEDITOR.instances.recruitStr.getData();

	if(str.length >= 1) {
		result = $.ajax({
			type : "post" ,
			url  : "../cgi2018/ajax/mtn/writeRecruitVal.php" ,
			data : {
				branchNo : branchNo ,
				str      : str
			} ,

			cache : false    ,
			dataType :'json'
		});

		result.done(function(response) {
						//console.debug(response);
			if(response['SESSCOND'] == SESS_OWN_INTIME) {
			} else {
				alert('長時間操作がなかったため接続が切れました。ログインしなおしてください。');
				location.href = 'login.html';
			}
		});

		result.fail(function(result, textStatus, errorThrown) {
						console.debug('error at writeRecruitVal:' + result.status + ' ' + textStatus);
		});

		result.always(function() {
		});
	}
}
