/**
* 料金表
*
* @version 1.0.1
* @date 2018.1.17
*/

var SYSTEM_STR;

$(document).ready(function(){

	getPriceVal();
});

$(window).load(function(){
});

/**
* 料金表情報の読み込み
*
* @param
* @return
*/
function getPriceVal() {

var result;
var str;

	result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getPriceVal.php" ,
		data : {
			branchNo : BRANCH_NO
		} ,

		cache : false
	});

	result.done(function(response) {
					//console.debug(response);
		SYSTEM_STR = response;
	});

	result.fail(function(response, textStatus, errorThrown) {
					console.debug('error at getPriceVal:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
		$("#systemStr").val(SYSTEM_STR);
	});
}

/**
* 料金表情報の出力
*
* @param
* @return
*/
function writePriceVal() {

var result;
var str = CKEDITOR.instances.systemStr.getData();

	if(str.length >= 1) {
		result = $.ajax({
			type : "post" ,
			url  : "../cgi2018/ajax/mtn/writePriceVal.php" ,
			data : {
				branchNo : BRANCH_NO ,
				str      : str
			} ,

			cache    : false ,
			dataType : 'json'
		});

		result.done(function(response) {
						//console.debug(response);
			if(response['SESSCOND'] == SESS_OWN_INTIME) {
				selectWriteFile('SYSTEM');		//出力対象ファイルの抽出→ファイル出力
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
						console.debug('error at writePriceVal:' + response.status + ' ' + textStatus);
		});

		result.always(function() {
		});
	}
}
