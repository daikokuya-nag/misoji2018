/********************
index.html
********************/
/*** 人妻たち ***/
var listIDA   = 'div#wivesListI';
var tnClassA  = 'div.wivesListTN';
var tnPrefixA = 'div.wList';

/*** 出勤予定 ***/
var listIDB   = 'div#todaysWorksList';
var tnClassB  = 'div.workerTN';
var tnPrefixB = 'div.worker';

/**********
初期化
**********/
$().ready(function(){

	/*setCarousel();*/	//showPhotoList.js
	hdImgW();	//ヘッダ画像のクロスフェード
	showTodaysWorker();	/* 本日の出勤者リスト */
});

$(window).load(function(){

});

$(window).resize(function() {
	resetHeader();
	setSidebarHeight();
});


$(function() {

	alignWives(listIDA ,tnClassA ,tnPrefixA);

	$(listIDA).exResize(function(api){
		alignWives(listIDA ,tnClassA ,tnPrefixA);
		alignWives(listIDB ,tnClassB ,tnPrefixB);
	})
});

$.isEnabled();


/********************
本日の出勤者リスト
********************/
function showTodaysWorker() {

var branchNo = BRANCH_NO;
var listTag  = '';

var result = $.ajax({
		type : "get" ,
		url  : "cgi2018/ajax/getWorkerList.php" ,
		data : {
			branchNo : branchNo
		} ,

		cache    : false ,
		dataType : 'json'
	});


	result.done(function(response) {
					console.debug(response);

		listTag    = response['tag'];
		workersNum = response['num'];

		$("#todaysWorksList").html(listTag);
		alignWives(listIDB ,tnClassB ,tnPrefixB);

				/*
				var width = parseInt($('div#todaysWorksList').css('width'));
						//console.debug('  width : ' + width);

				var mainAllWidth = parseInt($('div#mainAll').css('width'));
						//console.debug(' * mainAllWidth : ' + mainAllWidth);

				if(mainAllWidth <= 750 && width <= 681 && workersNum >= 1) {
							//console.debug('reset2');
				}
				*/

		setSidebarHeight();
	});

	result.fail(function(response, textStatus, errorThrown) {
					console.debug('error at showTodaysWorker:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}


function hdImgW() {

	resetHeader();

var setImg = '#hdW';
var fadeSpeed   = 1500;
var switchDelay = 5000;

	$(setImg).children('img').css({opacity:'0'});
	$(setImg + ' img:first').stop().animate({opacity:'1',zIndex:'20'},fadeSpeed);

	setInterval(function(){
		$(setImg + ' :first-child').animate({opacity:'0'},fadeSpeed).next('img').animate({opacity:'1'},fadeSpeed).end().appendTo(setImg);
	},switchDelay);
}

function resetHeader() {

	//2枚目以降の画像が左に寄るため、1枚目のマージンの値をコピー
var leftVal = $("img.imgStd").css('margin-left');

	$("img.img2").css('left' ,leftVal);
}


function setSidebarHeight() {

var mainAllWidth = window.innerWidth;
				//console.debug('main width:' + mainAllWidth);

	if(mainAllWidth < 767) {
		$("#sideBar").css('height' ,'auto');
	} else {
		var heightMain = $("#mainArea").height();
				//console.debug(heightMain);
		$("#sideBar").height(heightMain - 16);
	}
}
