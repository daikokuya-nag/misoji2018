/**
* 画面装飾編集
*
* @version 1.0.1
* @date 2018.1.23
*/

$(document).ready(function(){

	setDecoColorPicker();
});


$(window).load(function(){

	getDecoVals();
});


function setDecoColorPicker() {

//	$('#demo').hide();
/*
	var f = $.farbtastic('#decoColorPicker');
	var p = $('#decoColorPicker').css('opacity', 0.25);
	var selected;

	$('.decoColorwell')
		.each(function () { f.linkTo(this); $(this).css('opacity', 0.75); })
		.focus(function() {
			if (selected) {
				$(selected).css('opacity', 0.75).removeClass('colorwell-selected');
			}

			f.linkTo(this);
			p.css('opacity', 1);
			$(selected = this).css('opacity', 1).addClass('colorwell-selected');
		});
*/

	$('#decoColorPicker').farbtastic('#pageBGColor');

}

/**
* 装飾データの読み込み
*
* @param
* @return
*/
function getDecoVals() {

var result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getBGDecoration.php" ,
		data : {
			branchNo : BRANCH_NO
		} ,

		cache    : false ,
		dataType : 'json'
	});

	result.done(function(response) {
					//console.debug(response);

		setAllBGImg(response['vals'] ,response['extList']);
	});

	result.fail(function(response, textStatus, errorThrown) {
			console.debug('error at getHeaderImgList:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}


/**
* 現在の装飾データの表示
*
* @param
* @return
*/
function setAllBGImg(otherVals ,extList) {

var pageVal = otherVals['pageVal'];
var useBG   = "N";
var colorCD = "#000000";
var imgNo;

var existList  = otherVals['fileExist'];
var imgExt = [];

var fileExist = '0';

	//背景使用の指定
	if(pageVal['value1'].length >= 1) {
		useBG = pageVal['value1'];

		if(useBG != "N"
		&& useBG != "C"
		&& useBG != "I") {
			useBG = "N";
		}
	}
	$("input[name='usePageBG']").val([useBG]);

	//背景色
	if(pageVal['value2'].length >= 1) {
		colorCD = pageVal['value2'];
	}
	$("#pageBGColor").val(colorCD);
	$.farbtastic("#decoColorPicker").setColor(colorCD);

	//背景画像No
	if(pageVal['value3'].length >= 1) {
		imgNo = pageVal['value3'];
	}
	//画像ファイルの有無
	if(existList.length >= 1) {
		fileExist = existList;
	}

	//拡張子リスト
	if(extList.length >= 1) {
		extS1 = extList.split(',');
		idxMax = extS1.length - 1;
		for(idx=0 ;idx<idxMax ;idx++) {
			extS2 = extS1[idx].split(':');
			imgExt[extS2[0]] = extS2[1];
		}
	}

	imgTag = '';
	if(fileExist == 1) {
		if(imgNo.length >= 1) {
			imgTag = '<img src="../img/1/DECO/' + imgNo + '.' + imgExt[imgNo] + '" class="img-responsive">';
		}
	}

	$('#decoBGImgTN').html(imgTag);

	//画像Noの保持
	$("#decoBGImg").val(imgNo);
}


/**
* 装飾指定の出力
*
* @param
* @return
*/
function writeDecoVal() {

var useBG = $("input[name='usePageBG']:checked").val();	//背景の使用
var color = $("#pageBGColor").val();	//背景色
var imgNo = $("#decoBGImg").val();		//背景画像No

var result = $.ajax({
		type  : "post" ,
		url   : "../cgi2018/ajax/mtn/writeBGDecoration.php" ,
		data : {
			branchNo : BRANCH_NO ,
			useBG    : useBG     ,
			color    : color     ,
			imgNo    : imgNo
		} ,

		cache    : false  ,
		dataType : 'json'
	});

	result.done(function(response) {
					// console.debug(response);

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
