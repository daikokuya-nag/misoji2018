/*************************
料金表 Version 1.0
2016 Jan. 25 ver 1.0
*************************/

var PRICE_STR;

/***** 初期化 *****/
$(document).ready(function(){

	/***** 料金表データの読み込み *****/
	getPriceVal();

	CKEDITOR.instances.systemStr.on("blur", function(e) {
		CKEDITOR.instances.systemStr.updateElement();
		var str = $("#systemStr").val();
		var msg;

		if(str.length >= 1) {
			msg = '';
		} else {
			msg = 'any error';
		}
		$("#warnSystemStr").html(msg);
	});
});

$(window).load(function(){
});


/********************
料金表情報の読み込み
********************/
function getPriceVal() {

var result;
var str;
var branchNo = $('#branchNo').val();

	result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getPriceVal.php" ,
		data : {
			branchNo : branchNo
		} ,

		cache : false
	});

	result.done(function(response) {
					//console.debug(response);
		PRICE_STR = response;
	});

	result.fail(function(result, textStatus, errorThrown) {
					console.debug('error at getPriceVal:' + result.status + ' ' + textStatus);
	});

	result.always(function() {
		$("#systemStr").val(PRICE_STR);
		CKEDITOR.instances.systemStr.setData(PRICE_STR);
	});
}

/********************
料金表情報の出力
********************/
function writePriceVal() {

var result;
var branchNo = $('#branchNo').val();
var str = CKEDITOR.instances.systemStr.getData();

	if(str.length >= 1) {
		result = $.ajax({
			type : "post" ,
			url  : "../cgi2018/ajax/mtn/writePriceVal.php" ,
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
						console.debug('error at writePriceVal:' + result.status + ' ' + textStatus);
		});

		result.always(function() {
		});
	}
}
