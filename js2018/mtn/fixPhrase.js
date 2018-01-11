/*************************
定型文編集 Version 1.0
2013 July 04 ver 1.0
*************************/

var DISP_FIX_PHRASE_EDIT_DIALOG = false;	//定型文編集のダイアログを表示したか

/***** 初期化 *****/
$(document).ready(function(){

});


$(window).load(function(){

	$("#editFixPhrase").dialog({
		autoOpen : false ,
		modal    : true  ,
		width    : 850   ,
		buttons: [
			{
				text :"出力",
				click:function() {
					writeFixPhrase();		/* 定型文書き出し */
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
});


/********************
定型文編集ダイアログ表示
********************/
function editFixPhrase() {

var phraseData;
var result;

	result = $.ajax({
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

					console.debug('本文:' + phraseData);
		$("#fixPhraseStr").val(phraseData);
		$("#warnFixPhraseStr").html('');

		$("#editFixPhrase").dialog("open");

		setCKEditFixPhrase();
		CKEDITOR.instances.fixPhraseStr.setData(phraseData);
	});

	result.fail(function(response, textStatus, errorThrown) {
			console.debug('error at editFixPhrase:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}

function setCKEditFixPhrase() {

	if(!DISP_FIX_PHRASE_EDIT_DIALOG) {

		CKEDITOR.replace('fixPhraseStr',
			{
				height : 120
			});


		CKEDITOR.instances.fixPhraseStr.on("blur", function(e) {
			CKEDITOR.instances.fixPhraseStr.updateElement();
			var str = $("#fixPhraseStr").val();
			var msg;

			if(str.length >= 1) {
				msg = '';
			} else {
				msg = 'any error';
			}
			$("#warnFixPhraseStr").html(msg);
		});


		DISP_FIX_PHRASE_EDIT_DIALOG = true;
	}
}


/********************
定型文書き出し
********************/
function writeFixPhrase() {

var phraseStr = CKEDITOR.instances.fixPhraseStr.getData();
var result;

	if(phraseStr.length >= 1) {
		result = $.ajax({
			type : "post" ,
			url  : "../cgi2018/ajax/mtn/writeFixPhrase.php" ,
			data : {
				branchNo  : BRANCH_NO ,
				phraseStr : phraseStr
			} ,

			cache : false
		});

		result.done(function(response) {
						//console.debug(response);
			alert('定型文出力完了');
			$("#editFixPhrase").dialog("close");
		});

		result.fail(function(response, textStatus, errorThrown) {
				console.debug('error at writeFixPhrase:' + response.status + ' ' + textStatus);
		});

		result.always(function() {
		});
	}
}
