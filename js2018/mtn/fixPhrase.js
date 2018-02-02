/**
* 定型文編集
*
* @version 1.0.1
* @date 2018.1.4
*/

$(document).ready(function(){
});


$(window).load(function(){

	$("#editFixPhraseDlg").dialog({
		autoOpen : false ,
		modal    : true  ,
		width    : 850   ,

		open : function() {
			adjustEditFixPhraseIFrame();
		} ,

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


/**
* 定型文編集ダイアログ表示
*
* @param
* @return
*/
function editFixPhrase() {

	$("#editFixPhraseForm").prop('src' ,'enterFixPhrase.php');
	$("#editFixPhraseDlg").dialog("open");
}


/**
* iFrameの高さの調整
*
* @param
* @return
*/
function adjustEditFixPhraseIFrame() {

	// iframeの幅と高さを特定
var frame  = $('#editFixPhraseForm');
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

			
			console.debug(innerH);
			console.debug(outerH);
			console.debug(dispH);
			

	$("#editFixPhraseDlg").dialog({
		height: outerH		//dispH
	});
}


/**
* 定型文書き出し
*
* @param
* @return
*/
function writeFixPhrase() {

			//var phraseStr = CKEDITOR.instances.fixPhraseStr.getData();

var docForm = editFixPhraseForm.document.enterFixPhrase;

document.getElementById('editFixPhraseForm').contentWindow.updCkEditor();

var phraseStr = docForm.fixPhraseStr.value;
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
			$("#editFixPhraseDlg").dialog("close");
		});

		result.fail(function(response, textStatus, errorThrown) {
				console.debug('error at writeFixPhrase:' + response.status + ' ' + textStatus);
		});

		result.always(function() {
		});
	}
}
