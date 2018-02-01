/**
* プロファイル一覧
*
* @version 1.0.1
* @date 2018.1.23
*/

var CURR_AREA_NO = 0;				// 編集領域の表示
var DISP_PROF_EDIT_DIALOG = false;	// 女性情報編集のダイアログを表示したか

$(document).ready(function(){
});

$(document).on('sortstop' ,'#profSeqListD' ,function(){		// 表示順のドロップ時の動作

	enableWriteProfSeq();
});

$(window).load(function(){

	$("#editProfDlg").dialog({		// 編集ダイアログの定義
		autoOpen: false ,
		modal : true ,
		width : 1220 ,		//1020

		open : function() {
			adjustEditIFrame();
		} ,

		buttons: [
			{
				text :"出力",
				click:function() {
//					var chkEnter = checkProfEnter();
//					if(chkEnter) {
						writeProf();
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

	getProfileList();

//	adjustEditIFrame();
});

/**
* プロファイルの内容チェック
*
* @param
* @return
*/
function checkProfEnter() {

var ret = $("#enterProfile").parsley().validate();

	return ret;
}


/**
* プロファイルリストの読み込み
*
* @param
* @return
*/
function getProfileList() {

var result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getProfileList.php" ,
		data : {
			branchNo : BRANCH_NO
		} ,

		cache    : false ,
		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);

				//$("#profSeqListH").html(response['SEQ']['title']);
		$("#profSeqListD").html(response['SEQ']['data']);
		$(".dispProfSW").toggleSwitch();

		/***** プロファイル表示順 *****/
		$("#profSeqListD").sortable();
	});

	result.fail(function(response, textStatus, errorThrown) {
			console.debug('error at getProfileList:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}

/**
* 出力ボタンの有効化
*
* @param
* @return
*/
function enableWriteProfSeq() {

	$("#bldProfList").prop('disabled' ,false);
}

/**
* 新規プロファイル編集
*
* @param
* @return
*/
function newProf() {

	console.debug('new Profile');
//	$("#editProfile").src('enterProfile.php?id=');
}

/**
* 既存プロファイル編集
*
* @param
* @return
*/
function editProf(dir) {

	console.debug('edit Profile ' + dir);
	$("#editProfile").prop('src' ,'enterProfile.php?id=' + dir);

	$("#editProfDlg").dialog( {
		title : '編集 '
	});
	$("#editProfDlg").dialog("open");
}



/**
* iFrameの高さの調整
*
* @param
* @return
*/
function adjustEditIFrame() {

	// iframeの幅と高さを特定
var $frame = $('#editProfile');
var innerH = $frame.get(0).contentWindow.document.body.scrollHeight;
var innerW = $frame.get(0).contentWindow.document.body.scrollWidth;

	$frame.attr('height', innerH + 'px');
	$frame.attr('width', innerW + 'px');

	// ブラウザの高さとiframの高さを比較して低い方をダイアログの高さにする
var outerH = $(window).height();

var dispH;
	if(innerH > outerH) {
		dispH = outerH
	} else {
		dispH = innerH
	}
	dispH-=2;

	$("#editProfDlg").dialog({
		height: dispH
	});
}



/**
* 表示順、表示/非表示更新時の出力
*
* @param
* @return
*/
function updProfSeq() {

var dispSW    = $(".dispProfSW").serialize();
var profOrder = $("#profSeqListD").sortable('serialize');
var dataVal   = profOrder + '&branchNo=' + BRANCH_NO + '&' + dispSW;
			//console.debug(dataVal);

var result = $.ajax({
		type : "post" ,
		url  : "../cgi2018/ajax/mtn/writeProfSeqDisp.php" ,
		data : dataVal ,

		cache    : false  ,
		dataType : 'json' ,
	});

	result.done(function(response) {
					console.debug(response);

		if(response['SESSCOND'] == SESS_OWN_INTIME) {
			selectWriteFile('ALBUM');		//出力対象ファイルの抽出→ファイル出力
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
			console.debug('error at updProfSeq:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}


/*
ここから下は未実装
*/
/********************
全プロファイル一括更新
********************/
function updAllProf() {

var profMax = 0;
var profDir = new Array();
var i;

	$.ajax({
		type :"get" ,
		url  : "cgi/ajax/getProfileList.php" ,
		data : {
			branchNo : BRANCH_NO
		} ,

		cache    :false ,
		dataType :'json' ,

		success :function(result) {
					console.debug(result);
							/*
							profMax = result['profMax'];
							profDir = result['dirList'];
							*/
			profDir = result;
			profMax = profDir.length;
		} ,

		error :function(result) {
					console.debug('error at updAllProf:' + result);
		} ,

		complete : function(result) {

			if(profMax >= 1) {
				for(i=0 ;i<profMax ;i++) {
							/*updProf(groupNo ,branchNo ,profDir[i]);*/
					//bldProfHTML(profDir[i]);
				}
				alert('全件出力完了');
			}

		}
	});

}
