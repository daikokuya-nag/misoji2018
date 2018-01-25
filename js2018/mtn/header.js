/**
* ヘッダ編集
*
* @version 1.0.1
* @date 2018.1.23
*/

$(document).ready(function(){
});

$(document).on('sortstop' ,'#headerTopImgList' ,function(){		// 表示順のドロップ時の動作の定義

	enableWriteTopImgSeq();
});

function enableWriteTopImgSeq() {

	$("#bldHeaderImgDispSeq").prop('disabled' ,false);
}

$(window).load(function(){

	getHeaderImgList();
});

/**
* 現在のヘッダ画像の読み込み
*
* @param
* @return
*/
function getHeaderImgList() {

var result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getHeaderImg.php" ,
		data : {
			branchNo : BRANCH_NO
		} ,

		cache    : false ,
		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);

		setHeaderTop(response['top'] ,response['extList']);
		setHeaderOther(response['other'] ,response['extList']);
	});

	result.fail(function(response, textStatus, errorThrown) {
			console.debug('error at getHeaderImgList:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}

/**
* TOPページのヘッダ画像の表示
*
* @param
* @return
*/
function setHeaderTop(headerVals ,extList) {

var pageVal    = headerVals['pageVal'];
var seqList    = pageVal['value1'];
var useImgList = pageVal['value2'];
var imgNoList  = pageVal['value3'];
var existList  = headerVals['fileExist'];
var imgExt = [];
var split;

			//console.debug(seqList);
			//console.debug(useImgList);
			//console.debug(imgNoList);

var seq    = ['A' ,'B' ,'C' ,'D'];
var useImg = ['U' ,'U' ,'U' ,'U'];
var imgNo  = [''  ,''  ,''  ,''];
var fileExist = ['0' ,'0' ,'0' ,'0'];

	//画像の表示順
	if(seqList.length >= 1) {
		split = seqList.split(':');
		if(split.length >= 2) {
			seq = split;
		}
	}

	//その画像を表示するか
	if(useImgList.length >= 1) {
		split = useImgList.split(':')
		if(split.length >= 2) {
			useImg = split;
		}
	}

	//選択されている画像No
	if(imgNoList.length >= 1) {
		split = imgNoList.split(':');
		if(split.length >= 2) {
			imgNo = split;
		}
	}

	//画像ファイルの有無
	if(existList.length >= 1) {
		split = existList.split(':');
		if(split.length >= 2) {
			fileExist = split;
		}
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

	var trTag = setTRImgTag(seq ,imgNo ,fileExist ,imgExt);
	$('#headerTopImgList').html(trTag);

	//使用/非使用の指定
	var useChecked;
	if(useImg[0] == 'U') {
		useChecked = true;
	} else {
		useChecked = false;
	}
	$('#useHeaderTopImgA').prop('checked' ,useChecked);

	if(useImg[1] == 'U') {
		useChecked = true;
	} else {
		useChecked = false;
	}
	$('#useHeaderTopImgB').prop('checked' ,useChecked);

	if(useImg[2] == 'U') {
		useChecked = true;
	} else {
		useChecked = false;
	}
	$('#useHeaderTopImgC').prop('checked' ,useChecked);

	if(useImg[3] == 'U') {
		useChecked = true;
	} else {
		useChecked = false;
	}
	$('#useHeaderTopImgD').prop('checked' ,useChecked);

	$(".useHeaderTopImg").toggleSwitch();

	/***** 画像表示順 *****/
	$("#headerTopImgList").sortable();
}

/**
* TOPページのヘッダ画像の表示タグの定義
*
* @param
* @return
*/
function setTRImgTag(seqList ,imgNoList ,fileExist ,imgExtList) {

var ret = '';
var idx;
var idxMax = seqList.length;

var imgNo;
var seqID;
var paramIdx;
var imgTag;

	for(idx=0 ;idx<idxMax ;idx++) {
		seqID = seqList[idx];
		if(seqID == 'A') {
			paramIdx = 0;
		}
		if(seqID == 'B') {
			paramIdx = 1;
		}
		if(seqID == 'C') {
			paramIdx = 2;
		}
		if(seqID == 'D') {
			paramIdx = 3;
		}

		imgNo = imgNoList[paramIdx];

		//表示する画像の指定
		imgTag = '';
		if(fileExist[paramIdx] == 1) {
			if(imgNoList[paramIdx].length >= 1) {
				imgTag = '<img src="../img/1/HEADER/' + imgNo + '.' + imgExtList[imgNo] + '" class="img-responsive">';
			}
		}

		//画像Noの保持
		$("#headerTopImg" + seqID).val(imgNo);

		ret = ret +
		'<tr class="headerImg" id="headerTopImg-' + seqID + '">' +
			'<td class="headerImgTN" id="headerTopImgTN' + seqID + '">' + imgTag + '</td>' +
			'<td class="headerImgSele"><input type="button" value="画像選択" name="attHeaderTopImg' + seqID + '" id="attHeaderTopImg' + seqID + '" onclick="showSeleImg(\'HEADER\' ,\'TOP\' ,\'' + seqID + '\')"><br><div id="currTopImg' + seqID + '">&nbsp;</div></td>' +
			'<td class="headerImgDisp"><input type="checkbox" name="useHeaderTopImg' + seqID + '" id="useHeaderTopImg' + seqID + '" class="useHeaderTopImg" value="U" onchange="enableWriteTopImgSeq();"></td>' +
		'</tr>';
	}

	return ret;
}

/**
* TOPページ以外のヘッダ画像の表示
*
* @param
* @return
*/
function setHeaderOther(otherVals ,extList) {

var pageVal    = otherVals['pageVal'];
var imgNoList  = pageVal['value3'];
var existList  = otherVals['fileExist'];
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
			imgTag = '<img src="../img/1/HEADER/' + imgNo + '.' + imgExt[imgNo] + '" class="img-responsive">';
		}
	}

	trTag =
	'<tr class="headerImg" id="headerOtherImgT">' +
		'<td class="headerImgTN" id="headerOtherImgTN">' + imgTag + '</td>' +
		'<td class="headerImgSele"><input type="button" value="画像選択" name="attHeaderOtherImg" id="attHeaderOtherImg" onclick="showSeleImg(\'HEADER\' ,\'OTHER\')"><br><div id="currOtherImg">&nbsp;</div></td>' +
	'</tr>';
	$('#headerOtherImgList').html(trTag);

	//画像Noの保持
	$("#headerOtherImg").val(imgNo);
}

/**
* 表示順、表示/非表示更新時の出力
*
* @param
* @return
*/
function updHeaderImgSeq() {

var dispSW   = $(".useHeaderTopImg").serialize();
var imgOrder = $("#headerTopImgList").sortable('serialize');

var seleImgA = $('#headerTopImgA').val();
var seleImgB = $('#headerTopImgB').val();
var seleImgC = $('#headerTopImgC').val();
var seleImgD = $('#headerTopImgD').val();

var seleOther = $('#headerOtherImg').val();

var sendData = imgOrder + '&branchNo=' + BRANCH_NO + '&' + dispSW +
		'&imgNoA=' + seleImgA + '&imgNoB=' + seleImgB + '&imgNoC=' + seleImgC + '&imgNoD=' + seleImgD + '&imgOther=' + seleOther;

console.debug(sendData);

var result = $.ajax({
		type  : "post" ,
		url   : "../cgi2018/ajax/mtn/writeHeaderImgDispSeq.php" ,
		data  : sendData  ,
		cache : false     ,
		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);

		if(response['SESSCOND'] == SESS_OWN_INTIME) {
			selectWriteFile('PAGE_HEADER');		//出力対象ファイルの抽出→ファイル出力
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
