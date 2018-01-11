/*************************
ニュース編集 Version 1.0
2016 Jan. 25 ver 1.0
*************************/

var DISP_UPDATED   = false;
var RESP_NEWS_DISP = false;	//「表示可否反映」ボタンの有効/無効
var NEWS_NO_LIST;			//ニュースNoのリスト
var DISP_NEWS_EDIT_DIALOG = false;	//ニュース編集のダイアログを表示したか

/***** 初期化 *****/
$(document).ready(function(){

});


$(window).load(function(){

	$("#warnDigest").html('');

	/***** 編集ダイアログの定義 *****/
	$("#editNews").dialog({
		autoOpen : false ,
		modal    : true  ,
		width    : 850   ,
		buttons: [
			{
				text :"出力",
				click:function() {
					var chkEnter = checkNewsEnter();
					if(chkEnter) {
								//alert('ok');
						writeNewsPre();
					} else {
						alert('any error');
					}
				}
			} ,
			{
				text :"キャンセル",
				click:function() {
					$(this).dialog("close");
				}
			}
		]
	});

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

	$('#dispBegDate').datetimepicker(dtop);

	RESP_NEWS_DISP = false;

	/***** ニュースの読み込み *****/
	getNewsList();
});


/********************
ニュースリストの読み込み
********************/
function getNewsList() {

var result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getNewsList.php" ,
		data : {
			branchNo : BRANCH_NO
		} ,

		cache    : false ,
		dataType : 'json'
	});

	result.done(function(response) {
					//console.debug(response);
		//一覧の表示
		$("#newsListH").html(response['title']);
		$("#newsListD").html(response['data']);
		$(".dispNewsSW").toggleSwitch();

		NEWS_NO_LIST = response['newsNoList'];	//ニュースNoリストの保持
		dispWriteNewsBtn();					//表示可否反映ボタンの初期化
	});

	result.fail(function(response, textStatus, errorThrown) {
					console.debug('error at getNewsList:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}


/********************
新規ニュース編集
********************/
function newNews() {

/***** 記事日付 *****/
var today = new Date();
var yy = today.getFullYear();

var mmVal = today.getMonth() + 1;
var ddVal = today.getDate();

var mm = ( "00" + mmVal).substr(-2);
var dd = ( "00" + ddVal).substr(-2);
var dateStr = yy + '-' + mm + '-' + dd;

var phraseData;

	//定型文を取り出して本文の初期値にする
var result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getFixPhrase.php" ,
		data : {
			branchNo : BRANCH_NO
		} ,

		cache : false
	});

	result.done(function(response) {
					console.debug(response);
		phraseData = response;

		$("#title").val('');
		$("#newsDate").val(dateStr);
		$("#newsTerm").val('');

		$("#digest").val('');
		$("#content").val(phraseData);

		$("#editNewsNo").val($('#newNewsRec').val());	/* 新規 */

		$("#dispBegDate").val('');

		$("#begDate").val('');
		$("#endDate").val('');

		$("input[name='newsCate']").val(["E"]);

		$('#delNewsBtn').css('display' ,'none');

		$("#editNews").dialog( {
			title: '新規'
		});
		$("#editNews").dialog("open");

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


/********************
既存ニュース編集
********************/
function editNews(newsNo) {

	getNewsData(BRANCH_NO ,newsNo ,'E');
}

/********************
1件のニュース情報取出し
********************/
function getNewsData(branchNo ,newsNo ,mode) {

var result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getNews1.php" ,
		data : {
			branchNo : branchNo ,
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
					console.debug('error at getNewsData:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}


/********************
ニュースの編集表示
********************/
function editNewsSet(newsData) {

var dispBegDT = '';
var split;

	//表示開始日時があれば、秒を削る
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

	$("#editNews").dialog( {
		title: '編集 ' + newsData['addDT']
	});
	$("#editNews").dialog("open");

	setCKEditNews();
	CKEDITOR.instances.digest.setData(newsData['digest']);
	CKEDITOR.instances.content.setData(newsData['content']);
}

function setCKEditNews() {

	if(!DISP_NEWS_EDIT_DIALOG) {

		CKEDITOR.replace('digest' ,
			{
				height : 120
			});

		CKEDITOR.replace('content' ,
			{
				height : 120
			});


		CKEDITOR.instances.digest.on("blur", function(e) {
			CKEDITOR.instances.digest.updateElement();
			var str = $("#digest").val();
			var msg;

			if(str.length >= 1) {
				msg = '';
			} else {
				msg = 'any error';
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
				msg = 'any error';
			}
			$("#warnContent").html(msg);
		});


		DISP_NEWS_EDIT_DIALOG = true;
	}
}


/********************
ニュースの内容チェック
********************/
function checkNewsEnter() {

var str;
var ret = $("#enterNews").parsley().validate();

	CKEDITOR.instances.digest.updateElement();
	str = $("#digest").val();
	if(str.length <= 0) {
		ret = false;
	}

	CKEDITOR.instances.content.updateElement();
	str = $("#content").val();
	if(str.length <= 0) {
		ret = false;
	}

	return ret;
}


/***********************************************************************************************************************/
/*************************
表示可否反映
*************************/

/********************
「表示可否反映」ボタンの有効化
********************/
function enableWriteNewsDisp() {

	RESP_NEWS_DISP = true;
	dispWriteNewsBtn();
}

/********************
「表示可否反映」ボタンの表示
********************/
function dispWriteNewsBtn() {

	if(RESP_NEWS_DISP) {
		$("#bldNewsList").prop('disabled' ,false);
	}
}

/********************
表示可否反映「ボタン」クリック時
********************/
function updNewsDisp() {

var dispCnt = 0;	//表示がONの件数
var listMax = NEWS_NO_LIST.length;

var dataList;
var idPrefix = '#news';
var idx;
var swList = '';	//ON,OFFのリスト
var result;

	for(idx=0 ;idx<listMax ;idx++) {
		newsNo = NEWS_NO_LIST[idx];

		currCond = $(idPrefix + newsNo).prop('checked');
		if(currCond) {
			/* ON */
			swList = swList + 'news' + newsNo + '=U&';
			dispCnt++;
		} else {
			/* OFF */
			swList = swList + 'news' + newsNo + '=N&';
		}
	}
	dataList = swList + $('input[name=branchNo]').serialize();

	result = $.ajax({
		type  : "post" ,
		url   : "../cgi2018/ajax/mtn/writeNewsDisp.php" ,
		data  : dataList ,
		cache : false    ,
		dataType :'json' ,
	});

	result.done(function(response) {
					//console.debug(response);

		if(response['SESSCOND'] == SESS_OWN_INTIME) {
			selectWriteFile('NEWS');		//出力対象ファイルの抽出→ファイル出力
			dispCnt = response['DISPCOUNT'];
			if(dispCnt >= 1) {

				alert('出力完了 (' + dispCnt + '件)');
			} else {
				alert('表示するニュースが0件でした');
			}
		} else {
			alert('長時間操作がなかったため接続が切れました。ログインしなおしてください。');
			$("#editNews").dialog("close");
			location.href = 'login.html';
		}
	});

	result.fail(function(response, textStatus, errorThrown) {
					console.debug('error at updNewsDisp:' + response.status + ' ' + textStatus);
	});

	result.always(function(result) {
	});
}


/***********************************************************************************************************************/
/*************************
ニュース出力
*************************/
/********************
セッション状態の取得
********************/
function writeNewsPre() {

	/* ニュース書き出し */
var newsNo   = $('#editNewsNo').val();

var title    = $('#title').val();
var newsDate = $('#newsDate').val();
var newsTerm = $('#newsTerm').val();
var digest   = CKEDITOR.instances.digest.getData();
var content  = CKEDITOR.instances.content.getData();

var dispBeg = $('#dispBegDate').val();
var begDate = $('#begDate').val();
var endDate = $('#endDate').val();

var cate = $("input[name='newsCate']:checked").val();

var reshowTag;
var newsIDTag;

					//console.debug('書き出すニュースNo:' + newsNo);
var result = $.ajax({
		type  : "post" ,
		url   : "../cgi2018/ajax/mtn/writeNews.php" ,
		data  : {
			branchNo : BRANCH_NO ,
			newsNo   : newsNo   ,

			title    : title    ,
			newsDate : newsDate ,
			newsTerm : newsTerm ,

			begDate  : begDate  ,
			endDate  : endDate  ,

			digest   : digest   ,
			content  : content  ,
			cate     : cate     ,
			dispBeg  : dispBeg
		} ,

		cache    : false  ,
		dataType : 'json'
	});

	result.done(function(response) {
					//console.debug(response);

///		if(response == SESS_OWN_INTIME) {
///				writeNewsPreA(response);
///		} else {
///				alert('長時間操作がなかったため接続が切れました。ログインしなおしてください。');
/////				$("#editNews").dialog("close");
/////				location.href = 'login.html';
///		}


		reshowTag = response['tag'];
		newsIDTag = response['id'];

		alert('ニュース出力完了');
		$("#editNews").dialog("close");
//		reshowList(newsNo ,tdTag ,newsIDTag);
	});

	result.fail(function(response, textStatus, errorThrown) {
			console.debug('error at writeNewsPre:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}


/********************
ニュースの再表示
********************/
function reshowList(newsNo ,tdTag ,newsIDTag) {

var newRec  = $('#newNewsRec').val();
var tdClass = 'td'   + newsIDTag;
var dispID  = 'news' + newsIDTag;

var listMax;

	if(newsNo == newRec) {
		/*** 新規ニュース ***/
			console.debug('新規ニュース追加表示');
		$('#newsListD').prepend('<tr id="' + newsIDTag + '">' + tdTag + '</tr>');	//先頭に追加
		$('.' + tdClass).slideDown("fast");											//行が下がる視覚効果
		$('#' + dispID).toggleSwitch();

		listMax = NEWS_NO_LIST.length;
		NEWS_NO_LIST[listMax] = newsIDTag;
	} else {
		/*** 既存ニュース ***/
					//console.debug('既存ニュース更新表示' + newsIDTag);
					//console.debug(tdTag);
		$('#' + newsIDTag).html(tdTag);

		$('.' + tdClass).slideDown("fast");
		$('#' + dispID).toggleSwitch();
	}
}


/***********************************************************************************************************************/
/********************
削除
********************/
function cfmDelNews() {

/*
	jConfirm('消していいの？' ,'プロファイルの削除' ,function(r) {
		if(r==true){jAlert('OKをクリックしました。', '確認ダイアログ分岐後');}
		else{jAlert('Cancelをクリックしました。', '確認ダイアログ分岐後');}
	});
*/

	/*	$('.cfmDelPrompt').css('display' ,'block');	*/

	$('#DelNewsDlg').css('display' ,'block');
}

/*******************
削除のpopupを非表示
*******************/
function hideDelNews() {

	$("#DelNewsDlg").css('display' ,'none');
}

/*******************
削除の本体
*******************/
function delNewsItem() {

var newsNo   = $('#editNewsNo').val();
var result;

	result = $.ajax({
		type : "post" ,
		url  : "cgi/ajax/delNewsItem.php" ,
		data : {
			branchNo : BRANCH_NO ,
			newsNo   : newsNo
		} ,

		cache : false
	});

	result.done(function(response) {
					//console.debug(response);

			showNewsList();		/* リスト表示 */

			hideDelNews();
			$("#editNews").dialog("close");
	});

	result.fail(function(response, textStatus, errorThrown) {
			console.debug('error at delNewsItem:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}

/********************
リスト表示
********************/
function showNewsList() {

	$("#bldNewsList").prop('disabled' ,true);
	getNewsList();
}
