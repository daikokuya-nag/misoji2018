/**
* ニュース編集
*
* @version 1.0.1
* @date 2018.1.17
*/

var COLOR_CUBE_NAME = {aqua : 'bgColorAqua' ,lime : 'bgColorLime' ,orange : 'bgColorOrange' ,red : 'bgColorRed'};
var COLOR_CODE      = {aqua : '#00ffff'     ,lime : '#00ff00'     ,orange : '#ff6600'       ,red : '#ff0000'};

$(document).ready(function(){

	setCKEditNews();
});

$(window).load(function(){

var dtop = {
	closeText     : '閉じる' ,
	currentText   : '現在日時' ,
	timeOnlyTitle : '日時を選択' ,

	timeText     : '時間' ,
	hourText     : '時' ,
	minuteText   : '分' ,
	secondText   : '秒' ,
	millisecText : 'ミリ秒' ,
	microsecText : 'マイクロ秒' ,
	timezoneText : 'タイムゾーン' ,

	prevText : '&#x3c;前' ,
	nextText : '次&#x3e;' ,

	monthNames      : ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'] ,
	monthNamesShort : ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'] ,
	dayNames        : ['日曜日','月曜日','火曜日','水曜日','木曜日','金曜日','土曜日'] ,
	dayNamesShort   : ['日','月','火','水','木','金','土'] ,
	dayNamesMin     : ['日','月','火','水','木','金','土'] ,
	weekHeader      : '週' ,
	yearSuffix      : '年' ,

	dateFormat : 'yy-mm-dd' ,
	timeFormat : 'HH:mm'    ,

	inline    : true
};

	$.datepicker.setDefaults($.datepicker.regional['ja']);
	$("#newsDate").datepicker({
		dateFormat : 'yy-mm-dd' ,
		inline     : true
	});

	$("#begDate").datepicker({
		dateFormat : 'yy-mm-dd' ,
		inline     : true
	});
	$("#endDate").datepicker({
		dateFormat : 'yy-mm-dd' ,
		inline     : true
	});

	$('#dispBegDate').datetimepicker(dtop);

	$("#warnContent").html('');


	if(TARGET_NEWS.length <= 0) {
		newNews();
	} else {
		editNews(TARGET_NEWS);
	}
});


function selectBGColor(colorCode) {

var selectedColor = COLOR_CODE[colorCode];

	$(".bgColor").css("border", "#ffffff solid 2px");
	$("#" + COLOR_CUBE_NAME[colorCode]).css("border", "#aaaaaa solid 2px");

	$("#bgColorCode").val(selectedColor);
	$("iframe").contents().find("body").css("background-color" ,selectedColor);
}


/**
* 新規ニュース編集
*
* @param
* @return
*/
function newNews() {

// 記事日付
var today = new Date();
var yy = today.getFullYear();

var mmVal = today.getMonth() + 1;
var ddVal = today.getDate();

var mm = ( "00" + mmVal).substr(-2);
var dd = ( "00" + ddVal).substr(-2);
var dateStr = yy + '-' + mm + '-' + dd;

//定型文を取り出して本文の初期値にする
var phraseData;
var result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getFixPhrase.php" ,
		data : {
			branchNo : BRANCH_NO
		} ,

		cache : false
	});

	result.done(function(response) {
					//console.debug(response);
		phraseData = response;

		$("#title").val('');
		$("#newsDate").val(dateStr);
		$("#newsTerm").val('');

		$("#content").val(phraseData);

		$("#editNewsNo").val($('#newNewsRec').val());	// 新規の時の値を格納

		$("#dispBegDate").val('');
		$("#begDate").val('');
		$("#endDate").val('');
		$("input[name='newsCate']").val(["E"]);			// 記事種類

		$("#bgColorCode").val('');
		$("iframe").contents().find("body").css("background-color" ,'');

		$('#delNewsBtn').css('display' ,'none');		// 記事削除ボタンを非表示

		$("#enterNews").parsley().reset();

		CKEDITOR.instances.content.setData(phraseData);
	});

	result.fail(function(response, textStatus, errorThrown) {
					console.debug('error at newNews:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}


/**
* 既存ニュース編集
*
* @param
* @return
*/
function editNews(newsNo) {

var result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getNews1.php" ,
		data : {
			branchNo : BRANCH_NO ,
			newsNo   : newsNo
		} ,

		cache    : false  ,
		dataType : 'json'
	});

	result.done(function(response) {
					//console.debug(response);
		editNewsSet(response);	//編集表示
	});

	result.fail(function(response, textStatus, errorThrown) {
					console.debug('error at editNews:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}

/**
* ニュースの編集表示
*
* @param {String[]} 編集するニュース情報
* @return
*/
function editNewsSet(newsData) {

var dispBegDT = '';
var split;

	//表示開始日時があれば秒を削る
	if(newsData['dispBegDT']) {
		split = newsData['dispBegDT'].split(':');
		dispBegDT = split[0] + ':' + split[1];
	}

	$("#title").val(newsData['title']);
	$("#newsDate").val(newsData['newsDate']);
	$("#newsTerm").val(newsData['term']);

	$("#content").val(newsData['content']);

	$("#editNewsNo").val(newsData['addDT']);

	$("#dispBegDate").val(dispBegDT);

	$("#begDate").val(newsData['begDate']);
	$("#endDate").val(newsData['endDate']);


	$("input[name='newsCate']").val([newsData['category']]);

	$("#enterNews").parsley().reset();

	CKEDITOR.instances.content.setData(newsData['content']);


var bgColor = newsData['BGColor'];
var selectedColor = '';

	$("#bgColorCode").val('');
	$("iframe").contents().find("body").css("background-color" ,'');

	if(bgColor == COLOR_CODE['aqua'  ]) {
		$("#bgColorCode").val(bgColor);
		selectedColor = 'aqua';
	}
	if(bgColor == COLOR_CODE['lime'  ]) {
		$("#bgColorCode").val(bgColor);
		selectedColor = 'lime';
	}
	if(bgColor == COLOR_CODE['orange']) {
		$("#bgColorCode").val(bgColor);
		selectedColor = 'orange';
	}
	if(bgColor == COLOR_CODE['red'   ]) {
		$("#bgColorCode").val(bgColor);
		selectedColor = 'red';
	}

	if(selectedColor.length >= 1) {
		selectBGColor(selectedColor);
		// ロード直後はこの方法でないと背景色を触れない？
		CKEDITOR.addCss( 'body { background-color:' + selectedColor + '; }' );
	}

	$('#delNewsBtn').css('display' ,'inline');
}


/**
* ckEditorの設定
*
* @param
* @return
*/
function setCKEditNews() {

	CKEDITOR.replace('content' ,
		{
			allowedContent : true ,		// タグを消さない
			height  : 360 ,

			toolbar : 'SiteInfo' ,		// ツールバーセット名

			extraPlugins : 'womenlist,siteinfo,hyperlink' ,

			// 女性紹介ページへのリンク
			womenlist_defaultLabel : ""  ,
			womenlist_style : {
				element : "span"
			} ,
			womenlist_title : '紹介ページ' ,
			womenlist_value : profileLinkList	,		// see enterNews.php

			// サイト情報
			siteinfo_defaultLabel : ""  ,
			siteinfo_style : {
				element : "span"
			} ,
			siteinfo_title : 'サイト情報' ,
			siteinfo_value : siteVals     ,

			// 外部リンク
			hyperlink_title : '外部リンク' 		,
			hyperlink_command : '外部リンク'
		}
	);

	CKEDITOR.instances.content.on("blur", function(e) {
		CKEDITOR.instances.content.updateElement();
		var str = $("#content").val();
		var msg;

		if(str.length >= 1) {
			msg = '';
		} else {
			msg = ERR_MSG;
		}
		$("#warnContent").html(msg);
	});
}


/**
* ニュースの内容チェック
*
* @param
* @return
*/
function checkNewsEnter() {

var str;
var ret = $("#enterNews").parsley().validate();
var msg;

	CKEDITOR.instances.content.updateElement();
	str = $("#content").val();
	if(str.length >= 1) {
		msg = '';
	} else {
		msg = ERR_MSG;
		ret = false;
	}
	$("#warnContent").html(msg);

	return ret;
}


/**
* 出力時のCKEditorの内容の反映
*
* @param
* @return
*/
function updCkEditor() {

	CKEDITOR.instances.content.updateElement();

			// var content = $("#content").val();
			// console.debug(content);
}


/**
* 削除ダイアログの表示
*
* @param
* @return
*/
function showDelNews() {

	$('#DelNewsDlg').css('display' ,'block');
}

/**
* 削除ダイアログの非表示
*
* @param
* @return
*/
function hideDelNews() {

	$("#DelNewsDlg").css('display' ,'none');
}

/**
* ニュースの削除の本体
*
* @param
* @return
*/
function delNewsItem() {

var newsNo = $('#editNewsNo').val();

	window.parent.delNewsItem(newsNo);
}
