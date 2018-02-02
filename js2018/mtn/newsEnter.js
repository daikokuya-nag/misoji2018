/**
* ニュース編集
*
* @version 1.0.1
* @date 2018.1.17
*/

$(document).ready(function(){
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

	$("#warnDigest").html('');
	$("#warnContent").html('');


	if(TARGET_NEWS.length <= 0) {
		newNews();
	} else {
		editNews(TARGET_NEWS);
	}
});


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

		$("#digest").val('');
		$("#content").val(phraseData);

		$("#editNewsNo").val($('#newNewsRec').val());	// 新規の時の値を格納

		$("#dispBegDate").val('');
		$("#begDate").val('');
		$("#endDate").val('');
		$("input[name='newsCate']").val(["E"]);			// 記事種類

		$('#delNewsBtn').css('display' ,'none');		// 記事削除ボタンを非表示

		$("#enterNews").parsley().reset();

		setCKEditNews();
		CKEDITOR.instances.digest.setData('');
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

	$("#digest").val(newsData['digest']);
	$("#content").val(newsData['content']);

	$("#editNewsNo").val(newsData['addDT']);

	$("#dispBegDate").val(dispBegDT);

	$("#begDate").val(newsData['begDate']);
	$("#endDate").val(newsData['endDate']);

	$("input[name='newsCate']").val([newsData['category']]);

	$('#delNewsBtn').css('display' ,'inline');

	$("#enterNews").parsley().reset();

	setCKEditNews();
	CKEDITOR.instances.digest.setData(newsData['digest']);
	CKEDITOR.instances.content.setData(newsData['content']);
}


/**
* ckEditorの設定
*
* @param
* @return
*/
function setCKEditNews() {

	CKEDITOR.replace('digest' ,
		{
			height : 120
		}
	);

	CKEDITOR.replace('content' ,
		{
			height : 120
		}
	);

	CKEDITOR.instances.digest.on("blur", function(e) {
		CKEDITOR.instances.digest.updateElement();
		var str = $("#digest").val();
		var msg;

		if(str.length >= 1) {
			msg = '';
		} else {
			msg = ERR_MSG;
		}
		$("#warnDigest").html(msg);
	});

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

	CKEDITOR.instances.digest.updateElement();
	str = $("#digest").val();
	if(str.length >= 1) {
		msg = '';
	} else {
		msg = ERR_MSG;
		ret = false;
	}
	$("#warnDigest").html(msg);

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

	CKEDITOR.instances.digest.updateElement();
	CKEDITOR.instances.content.updateElement();
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
