/**
* 求人
*
* @version 1.0.1
* @date 2018.1.17
*/

var RECRUIT_STR;

$(document).ready(function(){

	getRecruitVal();
});

$(window).load(function(){
});

/**
* 求人内容の読み込み
*
* @param
* @return
*/
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

/**
* 求人内容の出力
*
* @param
* @return
*/
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
					TIMEOUT_MSG_STR ,
					TIMEOUT_MSG_TITLE ,
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
