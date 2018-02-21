/**
* 定型文編集
*
* @version 1.0.1
* @date 2018.2.2
*/

$(document).ready(function(){
});


$(window).load(function(){

	editFixPhrase();
});


/**
* 定型文編集ダイアログ表示
*
* @param
* @return
*/
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
					//console.debug('本文:' + phraseData);
		$("#fixPhraseStr").val(phraseData);
		$("#warnFixPhraseStr").html('');

		setCKEditFixPhrase();
		CKEDITOR.instances.fixPhraseStr.setData(phraseData);
	});

	result.fail(function(response, textStatus, errorThrown) {
			console.debug('error at editFixPhrase:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}

/**
* ckEditorの定義
*
* @param
* @return
*/
function setCKEditFixPhrase() {

	CKEDITOR.replace('fixPhraseStr',
		{
			height  : 120 ,
			toolbar : 'Full'
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
}

/**
* 出力時のCKEditorの内容の反映
*
* @param
* @return
*/
function updCkEditor() {

	CKEDITOR.instances.fixPhraseStr.updateElement();
}
