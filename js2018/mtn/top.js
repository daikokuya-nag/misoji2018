/*************************
TOPページ編集 Version 1.1
*************************/

/***** 初期化 *****/
$(document).ready(function(){

});

/***** 表示順のドロップ時の動作 *****/
$(document).on('sortstop' ,'#topImgList' ,function(){

	enableWriteTopImgSeq();
});

function enableWriteTopImgSeq() {

	$("#bldTopImgDispSeq").prop('disabled' ,false);
}



$(window).load(function(){

	/***** ヘッダ画像リストの読み込み *****/
	getTopImgList();
});


/********************
ヘッダ画像リストの読み込み
********************/
function getTopImgList() {

var branchNo = $('#branchNo').val();
var result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getTopPageImg.php" ,
		data : {
			branchNo : branchNo
		} ,

		cache    : false ,
		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);

		var pageVal    = response['pageVal'];
		var seqList    = pageVal['value1'];
		var useImgList = pageVal['value2'];
		var imgNoList  = pageVal['value3'];
		var extList    = response['extList'];
		var existList  = response['fileExist'];
		var imgExt = [];

					//console.debug(seqList);
					//console.debug(useImgList);
					//console.debug(imgNoList);

		var seq    = ['A' ,'B' ,'C' ,'D'];
		var useImg = ['U' ,'U' ,'U' ,'U'];
		var imgNo  = [''  ,''  ,''  ,''];
		var fileExist = ['0' ,'0' ,'0' ,'0'];

		//画像の表示順
		if(seqList.length >= 1) {
			seq = seqList.split(':')
		}

		//その画像を表示するか
		if(useImgList.length >= 1) {
			useImg = useImgList.split(':')
		}

		//選択されている画像No
		if(imgNoList.length >= 1) {
			imgNo = imgNoList.split(':');
		}

		//画像ファイルの有無
		if(existList.length >= 1) {
			fileExist = existList.split(':');
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
		$('#topImgList').html(trTag);

		//使用/非使用の指定
		var useChecked;
		if(useImg[0] == 'U') {
			useChecked = true;
		} else {
			useChecked = false;
		}
		$('#useTopImgA').prop('checked' ,useChecked);

		if(useImg[1] == 'U') {
			useChecked = true;
		} else {
			useChecked = false;
		}
		$('#useTopImgB').prop('checked' ,useChecked);

		if(useImg[2] == 'U') {
			useChecked = true;
		} else {
			useChecked = false;
		}
		$('#useTopImgC').prop('checked' ,useChecked);

		if(useImg[3] == 'U') {
			useChecked = true;
		} else {
			useChecked = false;
		}
		$('#useTopImgD').prop('checked' ,useChecked);

		$(".useTopImg").toggleSwitch();

		/***** 画像表示順 *****/
		$("#topImgList").sortable();
	});

	result.fail(function(result, textStatus, errorThrown) {
			console.debug('error at getTopImgList:' + result.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}


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
				imgTag = '<img src="../img/1/TOP_HEADER/' + imgNo + '.' + imgExtList[imgNo] + '">';
			}
		}

		//画像Noの保持
		$("#topImg" + seqID).val(imgNo);

		ret = ret +
		'<tr id="topImg-' + seqID + '">' +
			'<td class="topImgTN" id="topImgTN' + seqID + '">' + imgTag + '</td>' +
			'<td class="topImgSele"><input type="button" value="画像選択" name="attTopImg' + seqID + '" id="attTopImg' + seqID + '" onclick="showSeleImg(\'TOP_HEADER\' ,\'' + seqID + '\')"><br><div id="currTopImg' + seqID + '">&nbsp;</div></td>' +
			'<td class="topImgDisp"><input type="checkbox" name="useTopImg' + seqID + '" id="useTopImg' + seqID + '" class="useTopImg" value="U" onchange="enableWriteTopImgSeq();"></td>' +
		'</tr>';
	}

	return ret;
}


/********************
表示順、表示/非表示更新時の出力
********************/
function updTopImgSeq() {

var branchNo = $('#branchNo').val();
var dispSW   = $(".useTopImg").serialize();
var imgOrder = $("#topImgList").sortable('serialize');

var seleImgA = $('#topImgA').val();
var seleImgB = $('#topImgB').val();
var seleImgC = $('#topImgC').val();
var seleImgD = $('#topImgD').val();

var sendData = imgOrder + '&branchNo=' + branchNo + '&' + dispSW +
		'&imgNoA=' + seleImgA + '&imgNoB=' + seleImgB + '&imgNoC=' + seleImgC + '&imgNoD=' + seleImgD;

console.debug(sendData);

var result = $.ajax({
		type  : "post" ,
		url   : "../cgi2018/ajax/mtn/writeTopImgDispSeq.php" ,
		data  : sendData  ,
		cache : false     ,
		dataType : 'json' ,
	});

	result.done(function(response) {
					console.debug(response);

		if(response['SESSCOND'] == SESS_OWN_INTIME) {
//		showProfListAll();		//リスト再表示
//		bldProfListHTML(bld);	//アルバムページ再出力
//		bldProfSitemap();
		} else {
				alert('長時間操作がなかったため接続が切れました。ログインしなおしてください。');
//				location.href = 'login.html';
		}
	});

	result.fail(function(result, textStatus, errorThrown) {
			console.debug('error at writeTopImgSeqDisp:' + result.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}


/********************
リスト再表示
********************/
function showProfListAll() {

var groupNo  = $('#groupNo').val();
var profListTag;

	$.ajax({
		type : "get" ,
		url  : "cgi/ajax/bldProfList.php" ,
		data : {
			branchNo : branchNo
		} ,

		cache    : false  ,
		dataType : 'json' ,

		success : function(result) {
					console.debug(result);
			profListTag = result;
					//console.debug(ret['TITLE']);
		} ,

		error : function(result) {
					console.debug('error at showList:' + result);
		} ,

		complete : function(result) {
					//alert(newTag['data']);
			/*** ニュース埋め込み用 ***/
			$("#profListD").html(profListTag['news']['data']);

			/*** 定型文埋め込み用 ***/
			$("#profListFPD").html(profListTag['news']['data']);

			/*** プロファイルリスト ***/
			$("#profSeqListD").html(profListTag['prof']['data']);

			$("#profSeqList").tableDnD({
				onDrop: function(table, row) {
									//profOrder = $.tableDnD.serialize();
									//		console.debug(profOrder);
									//enableWriteProfSeq();
				}
			});

			$(".dispProfSW").toggleSwitch();
		}
	});

}

/********************
アルバムHTML、JSのファイル出力
********************/
function bldProfListHTML(bld) {

var groupNo  = $('#groupNo').val();
var branchNo = $('#branchNo').val();

	$.ajax({
		type :"post" ,
		url  :"cgi/ajax/bldProfHTML.php" ,
		data : {
			groupNo  : groupNo  ,
			branchNo : branchNo ,
			bld      : bld      ,
			profDir  : ''
		} ,

		cache    :false ,
//		dataType :'json' ,

		success :function(result) {
					console.debug(result);
			ret = result;
					//console.debug(ret['TITLE']);
		} ,

		error :function(result) {
					console.debug('error at bldProfListHTML:' + result);
		}
	});
}




/*******************************************************************************/
/*******************************************************************************/
/*******************************************************************************/
/*******************************************************************************/
/*******************************************************************************/
/*******************************************************************************/











/********************
HTMLファイル出力
********************/
function bldProfHTML(profDir) {

var groupNo  = $('#groupNo' ).val();
var branchNo = $('#branchNo').val();

	$.ajax({
		type :"post" ,
		url  :"cgi/ajax/bldProfHTML.php" ,
		data : {
			groupNo  :groupNo  ,
			branchNo :branchNo ,
			profDir  :profDir
		} ,

		cache    :false ,
//		dataType :'json' ,

		success :function(result) {
					console.debug(result);
			ret = result;
					//console.debug(ret['TITLE']);
		} ,

		error :function(result) {
					console.debug('error at bldProfHTML:' + result);
		}
	});
}









/********************
サイトマップ出力
********************/
function bldProfSitemap() {

var groupNo  = $('#groupNo').val();
var branchNo = $('#branchNo').val();

	$.ajax({
		type :"post" ,
		url  :"cgiA/ajax/bldProfSitemap.php" ,
		data : {
			groupNo  :groupNo  ,
			branchNo :branchNo
		} ,

		cache    :false ,
//		dataType :'json' ,

		success :function(result) {
					console.debug(result);
			//ret = result;
					//console.debug(ret['TITLE']);
		} ,

		error :function(result) {
					console.debug('error at bldProfSiteMap:' + result);
		}
	});
}
