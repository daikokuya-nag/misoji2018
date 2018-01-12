/********************
index.html
********************/
/*** 人妻たち ***/
var listIDA   = 'div#wivesListI';
var tnClassA  = 'div.wivesListTN';
var tnPrefixA = 'div.wList';
var modeA     = 'topWives';

/*** 出勤予定 ***/
var listIDB   = 'div#todaysWorksList';
var tnClassB  = 'div.workerTN';
var tnPrefixB = 'div.worker';
var modeB     = 'topTodayWork';

/**********
初期化
**********/
$().ready(function(){

	setCarousel();	//showPhotoList.js
});

$(function() {

//	alignWives(listIDA ,tnClassA ,tnPrefixA ,modeA);
//
//	$(listIDA).exResize(function(api){
//		alignWives(listIDA ,tnClassA ,tnPrefixA ,modeA);
//	})


	/***** 料金表の枠があれば表示 *****/
//	if($('#priceListListI').length){
//
//		var branchNo = $("#branchNo").val();
//		$.ajax({
//			type : 'get' ,
//			url  : '../cgi/ajax/showPriceList.php',
//			data : {
//				branchNo : branchNo
//			} ,
//			cache : false
//		})
//		.done(function (htmlStr) {
//			$('#priceListListI').html(htmlStr);
//		})
//		.fail(function (htmlStr) { });
//	}


});

$.isEnabled();
