/**
* TOPページ編集
*
* @version 1.0.1
* @date 2018.1.24
*/

$(document).ready(function(){
});


$(window).load(function(){

	getTopValList();	// 現在のTOPページで使用する画像の読み込み
});

/**
* 現在のTOPページの画像の読み込み
*
* @param
* @return
*/
function getTopValList() {

var result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getTopVal.php" ,
		data : {
			branchNo : BRANCH_NO
		} ,

		cache    : false ,
		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);

		setTopImg(response['system' ] ,response['extList'] ,'SYSTEM');
		setTopImg(response['recruit'] ,response['extList'] ,'RECRUIT');

		setTopArea(response['area']);

		$(".useTopImg").toggleSwitch();
	});

	result.fail(function(response, textStatus, errorThrown) {
			console.debug('error at getTopValList:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}

/**
* TOPページの画像の表示
*
* @param
* @return
*/
function setTopImg(imgVals ,extList ,imgID) {

var pageVal   = imgVals['pageVal'];
var useImg    = pageVal['value2'];
var imgNoList = pageVal['value3'];
var existList = imgVals['fileExist'];
var imgExt = [];

			//console.debug(seqList);
			//console.debug(useImgList);
			//console.debug(imgNoList);

var imgNo     = '';
var fileExist = '0';

	//選択されている画像No
	if(imgNoList.length >= 1) {
		imgNo = imgNoList;
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
			imgTag = '<img src="../img/1/TOP/' + imgNo + '.' + imgExt[imgNo] + '" class="img-responsive">';
		}
	}

	if(useImg == 'U') {
		useChecked = true;
	} else {
		useChecked = false;
	}

	if(imgID == 'SYSTEM') {
		$('#topSystemImgTN').html(imgTag);
		$("#topSystemImg").val(imgNo);			//画像Noの保持
		$('#useTopSystemImg').prop('checked' ,useChecked);
	}
	if(imgID == 'RECRUIT') {
		$('#topRecruitImgTN').html(imgTag);
		$("#topRecruitImg").val(imgNo);			//画像Noの保持
		$('#useTopRecruitImg').prop('checked' ,useChecked);
	}
}

/**
* TOPページの区画の色
*
* @param
* @return
*/
function setTopArea(areaVals) {

	$("#areaTitleStr").val(areaVals['titleColor']);
	$("#areaTitleBG" ).val(areaVals['titleBGColor']);
	$("#areaBG"      ).val(areaVals['areaBGColor']);
}

/**
* 表示画像出力
*
* @param
* @return
*/
function updTopImg() {

var seleSystem  = $('#topSystemImg' ).val();
var seleRecruit = $('#topRecruitImg').val();

var titleColor   = $("#areaTitleStr").val();
var titleBGColor = $("#areaTitleBG" ).val();
var areaBGColor  = $("#areaBG"      ).val();

var dispSW   = $(".useTopImg").serialize();
var sendData = dispSW + '&branchNo=' + BRANCH_NO + '&system=' + seleSystem + '&recruit=' + seleRecruit +
				'&titleColor=' +  titleColor + '&titleBGColor=' + titleBGColor + '&areaBGColor=' +  areaBGColor;

console.debug(sendData);

var result = $.ajax({
		type : "post" ,
		url  : "../cgi2018/ajax/mtn/writeTopVal.php" ,
		data : sendData ,
		cache    : false ,
		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);

		if(response['SESSCOND'] == SESS_OWN_INTIME) {
			selectWriteFile('TOP');		//出力対象ファイルの抽出→ファイル出力
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
			console.debug('error at updTopImg:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}
