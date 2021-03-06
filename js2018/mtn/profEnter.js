/**
* プロファイル編集
*
* @version 1.0.1
* @date 2018.2.1
*/

var CURR_AREA_NO = 0;				// 編集領域の表示

$(document).ready(function(){
});


$(window).load(function(){

	if(TARGET_DIR.length <= 0) {
		newProf();
	} else {
		editProf(TARGET_DIR);
	}
});



/**
* 新規プロファイル編集
*
* @param
* @return
*/
function newProf() {

var result;

	$('#newProf').val('new');

	setShowProfDir('');

	$('#profDir').val('');

	$("input[name='newFace']").val(['N']);		// 新人 ON

	$('#profName'   ).val('');
	$('#profAge'    ).val('');
	$('#prof1Phrase').val('');
	$('#profHeight' ).val('');
	$('#profSize'   ).val('');

	$('#profZodiac').val('');
	$('#profBloodType').val('');

	$('input[name="seleWR"]').val(['']);

	$('#mastComment').val('');
	$('#appComment' ).val('');

	$('#yoasobiDiaryURI'  ).val('');
	$('#heavennetDiaryURI').val('');

	$('input[name="photoUSE"]').val(['N']);

	$('#profPCode').val('');


	$('#qa1' ).val('');
	$('#qa2' ).val('');
	$('#qa3' ).val('');
	$('#qa4' ).val('');
	$('#qa5' ).val('');
	$('#qa6' ).val('');
	$('#qa7' ).val('');
	$('#qa8' ).val('');
	$('#qa9' ).val('');
	$('#qa10').val('');
	$('#qa11').val('');
	$('#qa12').val('');
	$('#qa13').val('');
	$('#qa14').val('');

	$('.currPhoto').hide();

var chk = false;
	$("#useP1").prop("checked", chk);
	$("#useP2").prop("checked", chk);
	$("#useP3").prop("checked", chk);
	$("#useP4").prop("checked", chk);
	$("#useP5").prop("checked", chk);
	$("#useTN").prop("checked", chk);
	$("#useML").prop("checked", chk);

	$(".usePhoto").toggleSwitch();

	result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getProfile.php" ,
		data : {
			branchNo : BRANCH_NO ,
			dir      : ''
		} ,

		cache    : false ,
		dataType : 'json'
	});

	result.done(function(response) {
					//console.debug(response);

		$('#profWorkDiff').html(response["workTag"]["workDiff"]);
		$('#profWorkList').html(response["workTag"]["workList"]);

		setProfArea('NEW');

		$("#enterProfile").parsley().reset();

		setCKEditProf();
		CKEDITOR.instances.mastComment.setData('');
		CKEDITOR.instances.appComment.setData('');
	});

	result.fail(function(response, textStatus, errorThrown) {
			console.debug('error at newProf:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}

/**
* 既存プロファイル編集
*
* @param
* @return
*/
function editProf(dir) {

	$('#newProf').val('edit');
	setShowProfDir(dir);

var bVal;
var result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getProfile.php" ,
		data : {
			branchNo : BRANCH_NO ,
			dir      : dir
		} ,

		cache    : false ,
		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);

		if(response['newFace'] == 'N') {
			$("#newFace").prop("checked", true);
		} else {
			$("#newFace").prop("checked", false);
		}

		$('#profName'   ).val(response['name']);
		$('#profAge'    ).val(response['age']);
		$('#prof1Phrase').val(response['phrase1']);

		$('#profHeight').val(response['height']);
		$('#profSize'  ).val(response['sizes']);
		$('#profZodiac').val(response['zodiac']);
		$('#profBloodType').val(response['bloodType']);

		$('#mastComment').val(response['mastersComment']);
		$('#appComment' ).val(response['appealComment' ]);

		$('#qa1' ).val(response['QA1' ]);
		$('#qa2' ).val(response['QA2' ]);
		$('#qa3' ).val(response['QA3' ]);
		$('#qa4' ).val(response['QA4' ]);
		$('#qa5' ).val(response['QA5' ]);
		$('#qa6' ).val(response['QA6' ]);
		$('#qa7' ).val(response['QA7' ]);
		$('#qa8' ).val(response['QA8' ]);
		$('#qa9' ).val(response['QA9' ]);
		$('#qa10').val(response['QA10']);
		$('#qa11').val(response['QA11']);
		$('#qa12').val(response['QA12']);
		$('#qa13').val(response['QA13']);
		$('#qa14').val(response['QA14']);

		$('#profPCode').val(response['pcd']);

		$('#profWorkDiff').html(response["workTag"]["workDiff"]);
		$('#profWorkList').html(response["workTag"]["workList"]);

		bVal = response['B1'];
		if(bVal == null) {
			bVal = '';
		}
		$('input[name="profBA"]').val([bVal]);

		bVal = response['B2'];
		if(bVal == null) {
			bVal = '';
		}
		$('input[name="profBB"]').val([bVal]);

		bVal = response['B3'];
		if(bVal == null) {
			bVal = '';
		}
		$('input[name="profBC"]').val([bVal]);

			//$("#seleImgFile").prop('src' ,'fileSele.php?g=' + groupNo + '&b=' + BRANCH_NO + '&id=' + dir);

		setFileSeleVals(dir ,response['photo']);
		setProfArea('EDIT');
		$(".usePhoto").toggleSwitch();

		$("#enterProfile").parsley().reset();

		setCKEditProf();
		CKEDITOR.instances.mastComment.setData(response['mastersComment']);
		CKEDITOR.instances.appComment.setData(response['appealComment' ]);
	});

	result.fail(function(response, textStatus, errorThrown) {
			console.debug('error at editProf:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}


/**
* ckeditorの表示、動作の定義
*
* @param
* @return
*/
function setCKEditProf() {

	CKEDITOR.replace('mastComment' ,
		{
			height  : 120 ,
			toolbar : 'Full'
		});

	CKEDITOR.replace('appComment' ,
		{
			height  : 120 ,
			toolbar : 'Full'
		});

	CKEDITOR.instances.mastComment.on("blur", function(e) {
console.debug('updA');
		CKEDITOR.instances.mastComment.updateElement();
console.debug('updB');
	});

	CKEDITOR.instances.appComment.on("blur", function(e) {
		CKEDITOR.instances.appComment.updateElement();
	});
}

/**
* 入力域の初期表示
*
* @param
* @return
*/
function setProfArea(mode) {

var dispArea = ['none' ,'none' ,'none' ,'none'];
var btnDisp  = [false  ,false  ,false  ,false];

	CURR_AREA_NO = 0;
	dispArea[CURR_AREA_NO] = 'block';
	btnDisp[CURR_AREA_NO ] = true;

	// 初期状態で表示するエリアのボタンをdisable
	$('#showAreaBtn0').prop('disabled' ,btnDisp[0]);
	$('#showAreaBtn1').prop('disabled' ,btnDisp[1]);
//	$('#showAreaBtn2').prop('disabled' ,btnDisp[2]);
//	$('#showAreaBtn3').prop('disabled' ,btnDisp[3]);

	$('#profArea0').css('display' ,dispArea[0]);
	$('#profArea1').css('display' ,dispArea[1]);
//	$('#profArea2').css('display' ,dispArea[2]);
//	$('#profArea3').css('display' ,dispArea[3]);
}

/**
* 入力域表示
*
* @param
* @return
*/
function showArea(newAreaNo) {

var btnDisp = [false ,false ,false ,false];

	btnDisp[newAreaNo] = true;

var currID = '#profArea' + CURR_AREA_NO;
var newID  = '#profArea' + newAreaNo;

					//console.debug(currID + ' ' + newID);

	/* ボタン表示 */
	$('#showAreaBtn0').prop('disabled' ,btnDisp[0]);
	$('#showAreaBtn1').prop('disabled' ,btnDisp[1]);
//	$('#showAreaBtn2').prop('disabled' ,btnDisp[2]);
//	$('#showAreaBtn3').prop('disabled' ,btnDisp[3]);

	if(newAreaNo > CURR_AREA_NO) {
		// 今より下を表示
		$(newID).slideToggle("slow");		// 閉じる
		$(currID).slideToggle("slow");		// 開く
			console.debug('↓' + currID + ' ' + newID);
	} else {
		// 今より上を表示
		$(newID).slideToggle("slow");		// 閉じる
		$(currID).slideToggle("slow");		// 開く
			console.debug('↑' + currID + ' ' + newID);
	}
	CURR_AREA_NO = newAreaNo;
			console.debug('更新後:' + CURR_AREA_NO);
}



/**
* 写真表示の設定
*
* @param
* @return
*/
function setFileSeleVals(dir ,profData) {

var str;
var chk;
var show;

	show = profData['photoShow'];
	if(show == 'SHOW') {	//表示可
		str = 'O';
	}
	if(show == 'NG') {	//写真NG
		str = 'G';
	}
	if(show == 'NP') {	//準備中
		str = 'P';
	}
	if(show == 'NO') {	//写真なし
		str = 'N';
	}
	$('input[name="photoUSE"]').val([str]);

	// 写真の有無の設定
	$('.currPhoto').show();

	// 現在の写真ファイルの表示
	str = setCurrFileName(profData['fineNameF1']);
	$('#currImg1').html(str);

	str = setCurrFileName(profData['fineNameF2']);
	$('#currImg2').html(str);

	str = setCurrFileName(profData['fineNameF3']);
	$('#currImg3').html(str);

	str = setCurrFileName(profData['fineNameF4']);
	$('#currImg4').html(str);

	str = setCurrFileName(profData['fineNameF5']);
	$('#currImg5').html(str);

	str = setCurrFileName(profData['fineNameTN']);
	$('#currImgTN').html(str);


	// その写真を表示するか否かの設定
	chk = setUseVal(profData['useF1']);
	$("#useP1").prop("checked", chk);

	chk = setUseVal(profData['useF2']);
	$("#useP2").prop("checked", chk);

	chk = setUseVal(profData['useF3']);
	$("#useP3").prop("checked", chk);

	chk = setUseVal(profData['useF4']);
	$("#useP4").prop("checked", chk);

	chk = setUseVal(profData['useF5']);
	$("#useP5").prop("checked", chk);

	chk = setUseVal(profData['useTN']);
	$("#useTN").prop("checked", chk);

	chk = setUseVal(profData['useML']);
	$("#useML").prop("checked", chk);
}


function setCurrFileName(fileName) {

var str;

	if(fileName.length >= 1) {
		str = '<img src="' + fileName + '" class="showCurrPhoto">';
	} else {
		str = '-';
	}

	return str;
}


/**
* その写真を表示するか否かの設定
*
* @param
* @return
*/
function setUseVal(useVal) {

var chk;

	if(useVal == 'U') {
		chk = true;
	} else {
		chk = false;
	}

	return chk;
}

/**
* 識別子の入力/表示の設定
*
* @param
* @return
*/
function setShowProfDir(dir) {

	if(dir.length <= 0) {
		// 新規時は入力
		$('#enterProfN').css('display' ,'block');
		$('#enterProfE').css('display' ,'none');

		$('#delDirBtn').css('display' ,'none');
	} else {
		// 更新時は表示
		$('#enterProfN').css('display' ,'none');
		$('#enterProfE').css('display' ,'block');

		$('#delDirBtn').css('display' ,'inline');	//block
	}
	$('#profDir').val(dir);
	$('#profDirShow').html(dir);
}


function checkIDDir(profDir) {

var result;
var chkResult = '';

	result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/checkIDDir.php" ,
		data : {
			branchNo : BRANCH_NO ,
			profDir  : profDir
		} ,

		cache : false
	});

	result.done(function(response) {
					//console.debug(response);
		chkResult = response;
	});

	result.fail(function(response, textStatus, errorThrown) {
			console.debug('error at checkIDDir:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});

	return chkResult;
}


/**
* 識別子変更のダイアログの表示
*
* @param
* @return
*/
function showEditDir() {

var currDir = $("#profDirShow").html();

	$("#newDir").val(currDir);
	$("#editDirDlg").css('display' ,'block');

	$("#editDirDlg").draggable({ handle: $("#popup_titleDir") });
	$("#popup_titleDir").css({ cursor: 'move' });
}

/**
* 識別子変更のダイアログの終了
*
* @param
* @return
*/
function hideEditDir() {

	$("#editDirDlg").css('display' ,'none');
}

/**
* 識別子変更
*
* @param
* @return
*/
function updProfDir() {

var newDir  = $("#newDir").val();			//更新後
var currDir = $("#profDirShow").html();		//更新前

	if(newDir != currDir) {
		chkIDStr = checkID(newDir)
		if(chkIDStr.length >= 1) {
			alert(chkIDStr);
			return;
		}
	}

var result = $.ajax({
		type :"post" ,
		url  :"../cgi2018/ajax/mtn/updProfDir.php" ,
		data : {
			branchNo : BRANCH_NO ,
			old      : currDir   ,
			new      : newDir
		} ,

		cache    : false ,
		dataType : 'json'
	});

	result.done(function(response) {
					//console.debug(response);
		if(response['SESSCOND'] == SESS_OWN_INTIME) {
			$('#profDir').val(newDir);
			$('#profDirShow').html(newDir);

			getProfileList();
			writeProf();	//HTMLファイル再出力

			$("#editProfDlg").dialog("close");
			hideEditDir();
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
			console.debug('error at updProfDir:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}

/**
* 識別子の入力チェック
*
* @param
* @return
*/
function checkID(profDir) {

var chk = profDir.match(/[^0-9a-zA-Z_]+/);

var chkResult;
var ret = "";

	if(chk) {
		// 半角英数以外の文字があるとき
		ret = "識別子は半角英数字のみを入力してください";
	} else {
		// 半角英数のみのとき
		chkResult = checkIDDir(profDir);
		if(chkResult == 'ALREADY') {
			ret = "指定された識別子は既に使われています";
		}
	}

	return ret;
}

/**
* 削除
*
* @param
* @return
*/
function cfmDelDir() {

	$('#DelDirDlg').css('display' ,'block');
}

/**
* 削除処理の終了
*
* @param
* @return
*/
function hideDelDir() {

	$("#DelDirDlg").css('display' ,'none');
}

/**
* 削除の本体
*
* @param
* @return
*/
function delProfDir() {

var currDir = $("#profDirShow").html();

	window.parent.delProfDir(currDir);
}


/**
* 出力時のCKEditorの内容の反映
*
* @param
* @return
*/
function updCkEditor() {

	CKEDITOR.instances.mastComment.updateElement();
	CKEDITOR.instances.appComment.updateElement();
}
