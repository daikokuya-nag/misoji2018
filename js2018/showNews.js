
$(function() {

//	showNewestNews();
});


/********************
ニュースの再表示
********************/
function showNewestNews(){

var result = ajaxSetNewestNews('N');

	result.done(function(response) {
					console.debug(response);

		if(response['newNews'] >= 1) {
			/*** 新規表示ニュースが1件以上あれば再表示 ***/
			console.debug('reload');
			location.reload(true);	//サーバからリロード
		}
	});

	result.fail(function(result, textStatus, errorThrown) {
			console.log("error for ajaxSetNewestNews:" + result.status + ' ' + textStatus);
	});

	result.always(function() {
					//console.debug(ret['SEQ']);
	});
}


/********************
ニュースの再表示(ajax)
********************/
function ajaxSetNewestNews(mode) {

var branchNo = $('#branchNo').val();
var jqXHR;

	jqXHR = $.ajax({
		type : "get" ,
		url  : "../cgi/ajax/mtn/reshowNews.php" ,
		data : {
			branchNo : branchNo ,
			mode     : mode
		} ,

		cache    : false  ,
		dataType : 'json'
	});

	return jqXHR;
}
