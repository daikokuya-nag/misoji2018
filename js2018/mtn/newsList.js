/**
* ニュースリスト
*
* @version 1.0.1
* @date 2018.1.17
*/


var RESP_NEWS_DISP = false;			//「表示可否反映」ボタンの有効/無効
var NEWS_NO_LIST;					//ニュースNoのリスト

$(document).ready(function(){

	setNewsColorPicker();
});


function setNewsColorPicker() {

	$('#newsBGColorPicker').farbtastic('#newsBGColor');

}


$(window).load(function(){

	// 編集ダイアログの定義
	$("#editNewsDlg").dialog({
		autoOpen : false ,
		modal    : true  ,
		width    : 950   ,

		open : function() {
			adjustEditNewsIFrame();
		} ,

		buttons: [
			{
				text :"出力",
				click:function() {
//					var chkEnter = checkNewsEnter();
//					if(chkEnter) {
						writeNews();
//					}
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

	getNewsList();
});


/**
* ニュースリストの読み込み
*
* @param
* @return
*/
function getNewsList() {

var result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getNewsList.php" ,
		data : {
			branchNo : BRANCH_NO
		} ,

		cache    : false  ,
		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);
		//一覧の表示
		$("#newsListH").html(response['title']);
		$("#newsListD").html(response['data']);
		$(".dispNewsSW").toggleSwitch();

		NEWS_NO_LIST = response['newsNoList'];	//ニュースNoリストの保持
		dispWriteNewsBtn();						//表示可否反映ボタンの初期化

		//色指定の表示
		var colorCD = "#000000";
		if(response['bgColor'].length >= 1) {
			colorCD = response['bgColor'];
		}
		$("#newsBGColor").val(colorCD);
		$.farbtastic("#newsBGColorPicker").setColor(colorCD);
	});

	result.fail(function(response, textStatus, errorThrown) {
					console.debug('error at getNewsList:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}


/**
* iFrameの高さの調整
*
* @param
* @return
*/
function adjustEditNewsIFrame() {

	// iframeの幅と高さを特定
var frame  = $('#editNewsForm');
var innerH = frame.get(0).contentWindow.document.body.scrollHeight;
var innerW = frame.get(0).contentWindow.document.body.scrollWidth;

	frame.attr('height', innerH + 'px');
	frame.attr('width', innerW + 'px');

	// ブラウザの高さとiframの高さを比較して低い方をダイアログの高さにする
var outerH = $(window).height();

var dispH;
	if(innerH > outerH) {
		dispH = outerH;
	} else {
		dispH = innerH;
	}
	dispH-=2;

			/*
			console.debug(innerH);
			console.debug(outerH);
			console.debug(dispH);
			*/

	$("#editNewsDlg").dialog({
		height: outerH		//dispH
	});
}


/**
* 新規ニュース編集
*
* @param
* @return
*/
function newNews() {

	console.debug('new News');
	$("#editNewsForm").prop('src' ,'enterNews.php?id=');

	$("#editNewsDlg").dialog( {
		title : '新規 '
	});
	$("#editNewsDlg").dialog("open");
}


/**
* 既存ニュース編集
*
* @param
* @return
*/
function editNews(newsNo) {

	console.debug('edit News ' + newsNo);
	$("#editNewsForm").prop('src' ,'enterNews.php?id=' + newsNo);

	$("#editNewsDlg").dialog( {
		title : '編集 ' + newsNo
	});
	$("#editNewsDlg").dialog("open");
}


/**
* 「表示可否反映」ボタンの有効化
*
* @param
* @return
*/
function enableWriteNewsDisp() {

	RESP_NEWS_DISP = true;
	dispWriteNewsBtn();
}

/**
* 「表示可否反映」ボタンの表示
*
* @param
* @return
*/
function dispWriteNewsBtn() {

	if(RESP_NEWS_DISP) {
		$("#bldNewsList").prop('disabled' ,false);
	}
}

/**
* 表示可否反映「ボタン」クリック時
*
* 表示可否をサーバへ送信
*
* @param
* @return
*/
function updNewsDisp() {

var dispCnt = 0;	//表示がONの件数
var listMax = NEWS_NO_LIST.length;

var dataList;
var idPrefix = '#news';
var idx;
var swList = '';	//ON,OFFのリスト
var result;

	// 各ニュースの表示/非表示の抽出
	for(idx=0 ;idx<listMax ;idx++) {
		newsNo = NEWS_NO_LIST[idx];
		currCond = $(idPrefix + newsNo).prop('checked');
		if(currCond) {
			swList = swList + 'news' + newsNo + '=U&';	// ONのとき
			dispCnt++;
		} else {
			swList = swList + 'news' + newsNo + '=N&';	// OFFのとき
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
		} else {
			// セッションタイムアウト
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
					console.debug('error at updNewsDisp:' + response.status + ' ' + textStatus);
	});

	result.always(function(result) {
	});
}

/**
* ニュース出力
*
* @param
* @return
*/
function writeNews() {

var docForm = editNewsForm.document.enterNews;

document.getElementById('editNewsForm').contentWindow.updCkEditor();

var newsNo   = docForm.editNewsNo.value;

var title    = docForm.title.value;
var newsDate = docForm.newsDate.value;
var newsTerm = docForm.newsTerm.value;
var content  = docForm.content.value;
console.debug(content);
var dispBeg = docForm.dispBegDate.value;
var begDate = docForm.begDate.value;
var endDate = docForm.endDate.value;

var cateSele = docForm.newsCate.value;
var cate = '';
	if(cateSele === '') {
	} else {
		cate = cateSele;
	}

					//console.debug('書き出すニュースNo:' + newsNo);
var result = $.ajax({
		type  : "post" ,
		url   : "../cgi2018/ajax/mtn/writeNews.php" ,
		data  : {
			branchNo : BRANCH_NO ,
			newsNo   : newsNo    ,

			title    : title     ,
			newsDate : newsDate  ,
			newsTerm : newsTerm  ,

			begDate  : begDate   ,
			endDate  : endDate   ,

			content  : content   ,
			cate     : cate      ,
			dispBeg  : dispBeg
		} ,

		cache    : false  ,
		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);

		if(response['SESSCOND'] == SESS_OWN_INTIME) {
			$("#editNewsDlg").dialog("close");
			getNewsList();					//ニュースの再表示
			selectWriteFile('NEWS');		//出力対象ファイルの抽出→ファイル出力
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
			console.debug('error at writeNews:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}


/**
* ニュースの再表示
*
* @param {String} ニュースNo
* @param {String} 表示する内容のタグ
* @param {String} 表示するニュースNo
* @return
*/
function reshowList(newsNo ,tdTag ,newsIDTag) {

var newRec  = $('#newNewsRec').val();
var tdClass = 'td'   + newsIDTag;
var dispID  = 'news' + newsIDTag;
var listMax;

	if(newsNo == newRec) {
		// 新規ニュース
					//console.debug('新規ニュース追加表示');
		$('#newsListD').prepend('<tr id="' + newsIDTag + '">' + tdTag + '</tr>');	//先頭に追加
		$('.' + tdClass).slideDown("fast");											//行が下がる視覚効果
		$('#' + dispID).toggleSwitch();

		listMax = NEWS_NO_LIST.length;
		NEWS_NO_LIST[listMax] = newsIDTag;
	} else {
		// 既存ニュース
					//console.debug('既存ニュース更新表示' + newsIDTag);
					//console.debug(tdTag);
		$('#' + newsIDTag).html(tdTag);
		$('.' + tdClass).slideDown("fast");
		$('#' + dispID).toggleSwitch();
	}
}


/**
* 装飾指定の出力
*
* @param
* @return
*/
function writeNewsBGColor() {

var color = $("#newsBGColor").val();	//背景色

var result = $.ajax({
		type  : "post" ,
		url   : "../cgi2018/ajax/mtn/writeNewsBGColor.php" ,
		data : {
			branchNo : BRANCH_NO ,
			color    : color
		} ,

		cache    : false  ,
		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);

		if(response['SESSCOND'] == SESS_OWN_INTIME) {
			selectWriteFile('DECORATION');		//出力対象ファイルの抽出→ファイル出力
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
			console.debug('error at updHeaderImgSeq:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}


/**
* ニュースの削除の本体
*
* @param
* @return
*/
function delNewsItem(newsNo) {

var result = $.ajax({
		type : "post" ,
		url  : "../cgi2018/ajax/mtn/delNewsItem.php" ,
		data : {
			branchNo : BRANCH_NO ,
			newsNo   : newsNo
		} ,

		cache    : false  ,
		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);
		if(response['SESSCOND'] == SESS_OWN_INTIME) {
					//hideDelNews();
			$("#editNewsDlg").dialog("close");
			getNewsList();					// 最新のニュースリストを再読み込み
			selectWriteFile('NEWS');		// HTMLファイル再出力
		} else {
			//タイムアウト
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
			console.debug('error at delNewsItem:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}
