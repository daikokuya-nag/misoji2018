/**
* メンテ共通関数
*
* @version 1.0.1
* @date 2018.1.17
*/

var EXT_LIST = [];		// 画像の拡張子のリスト
var USE_PAGE = {};		// 自サイト、外部サイトのいずれを使うか
var RADIO_NAME     = {NEWS:'useNews'       ,ALBUM:'useProfile'      ,RECRUIT:'useRecruitPage'   ,SYSTEM:'useSystemPage'};	// ラジオボタン名
var ID_PREFIX      = {NEWS:'tabsNews'      ,ALBUM:'tabsProfile'     ,RECRUIT:'tabsRecruit'      ,SYSTEM:'tabsSystem'};		// tabのプレフィクス
var OUTER_URL_FORM = {NEWS:'newsOuterURL'  ,ALBUM:'profileOuterURL' ,RECRUIT:'recruitOuterURL'  ,SYSTEM:'systemOuterURL'};	// 外部サイトの入力域
var GRAY_PANEL_ID  = {NEWS:'grayPanelNews' ,ALBUM:'grayPanelProf'   ,RECRUIT:'grayPanelRecruit' ,SYSTEM:'grayPanelSystem'};	// 外部サイト使用時の、入力不可領域のマスク

var EDIT_AREA        = {NEWS:'tabNewsMain' ,ALBUM:'tabProfMain'     ,RECRUIT:'tabRecruitMain'   ,SYSTEM:'tabSystemMain'};	// 入力項目範囲
var EDIT_AREA_HEIGHT = {NEWS:0 ,ALBUM:0 ,RECRUIT:0 ,SYSTEM:0};		// 入力域の高さ
var TAB_HEIGHT       = {NEWS:0 ,ALBUM:0 ,RECRUIT:0 ,SYSTEM:0};		// tabの高さ

var DISP_SYSTEM_TAB  = false;	// システムタブを表示したか
var DISP_RECRUIT_TAB = false;	// 求人タブを表示したか

var ERR_MSG = 'この値は必須です';	// 

var TIMEOUT_MSG_STR   = '長時間操作がなかったため接続が切れました。ログインしなおしてください。';
var TIMEOUT_MSG_TITLE = 'メンテナンス';
var SELECTABLE_IMG_FILE = '画像はjpgまたはgifファイルを選択してください';


$(document).ready(function(){

var width = $(".tabArea").width();

	// タブシステムの定義
	$("#tabA").tabs(
		{
					//heightStyle : "fill"
			activate : function(event, ui) {
					//console.debug(ui.newPanel.selector);

				// 選択されたタブの初回表示時に高さを定義する
				var selectedPanel = ui.newPanel.selector;
				if(selectedPanel == "#tabsNews") {
					if(TAB_HEIGHT['NEWS'] == 0) {
						setNewsTabHeight();
					}
				}

				if(selectedPanel == "#tabsProfile") {
					if(TAB_HEIGHT['ALBUM'] == 0) {
						setProfTabHeight();
					}
				}

				if(selectedPanel == "#tabsRecruit") {
					$("#warnRecruitStr").html('');
					setCKEditRecruit();
					if(TAB_HEIGHT['RECRUIT'] == 0) {
						setRecruitTabHeight();
					}
				}

				if(selectedPanel == "#tabsSystem") {
					$("#warnSystemStr").html('');
					setCKEditSystem();
					if(TAB_HEIGHT['SYSTEM'] == 0) {
						setSystemTabHeight();
					}
				}
			}
		}
	);

	// タブの中身調整
	// タブの高さ
	$(".tabArea").height(700);
	$(".tabArea").css('overflow' ,'auto');

	// 下ボタンの調整
	$(".tabBottomBtn").width(width + 'px');

	setNewsTabHeight();

	// 画像選択

	// 選択された画像の正当性のチェックをどこで行うか、要再検討
	$("#imgFileSele").change(function () {
		fileSele(this);
	});

	// 外部/内部いずれのサイトを使うか
	$("input[name='useNews']").change(function() {
		$("#sendSeleNewsPage").prop('disabled' ,false);
		USE_PAGE['NEWS']['USE'] = $(this).val();
		setUsePage('NEWS');
	});

	$("input[name='useProfile']").change(function() {
		$("#sendSeleProfPage").prop('disabled' ,false);
		USE_PAGE['ALBUM']['USE'] = $(this).val();
		setUsePage('ALBUM');
	});

	$("input[name='useRecruitPage']").change(function() {
		$("#sendSeleRecruitPage").prop('disabled' ,false);
		USE_PAGE['RECRUIT']['USE'] = $(this).val();
		setUsePage('RECRUIT');
	});

	$("input[name='useSystemPage']").change(function() {
		$("#sendSeleSystemPage").prop('disabled' ,false);
		USE_PAGE['SYSTEM']['USE'] = $(this).val();
		setUsePage('SYSTEM');
	});

	// 画像リストの読み込み
	readImgList();
});


$(window).load(function(){

	initSelectImgFileDlg();		// 画像選択ダイアログの定義
	readUsePage();				// 使用するページの読み込み
});


/**
* 新着情報パネルの高さ調整
*
* @param
* @return
*/
function setNewsTabHeight() {

							//console.debug('set news tab height');
var areaH     = $("#tabsNews").height();		// 領域の高さ
var areaSeleH = $("#tabNewsUsePage").height();	// 使用ページ選択の高さ
var areaTopH  = $("#tabNewsTop").height();		// 上ボタンの高さ
var areaBtmH  = $("#tabNewsBottom").height();	// 下ボタンの高さ

var height = areaH - (areaSeleH + areaTopH + areaBtmH);
var grayPanelID = GRAY_PANEL_ID['news'];

				//console.debug(areaH + ' ' + areaTopH + ' ' + areaBtmH + ' ' + height);
	$("#tabNewsMid").height(height + 'px');
	TAB_HEIGHT['NEWS'] = height;

	height = $("#" + EDIT_AREA['news']).height();
	EDIT_AREA_HEIGHT['news'] = height;
	$("#" + grayPanelID).height(height);
}

/**
* プロファイルパネルの高さ調整
*
* @param
* @return
*/
function setProfTabHeight() {

							//console.debug('set prof tab height');
var areaH     = $("#tabsProfile").height();			//領域の高さ
var areaSeleH = $("#tabProfileUsePage").height();	//使用ページ選択の高さ
var areaTopH  = $("#tabProfTop").height();			//上ボタンの高さ
var areaBtmH  = $("#tabProfBottom").height();		//下ボタンの高さ

var height = areaH - (areaSeleH + areaTopH + areaBtmH);
var grayPanelID = GRAY_PANEL_ID['profile'];

				//console.debug(areaH + ' ' + areaTopH + ' ' + areaBtmH + ' ' + height);
	$("#tabProfMid").height(height + 'px');
	TAB_HEIGHT['ALBUM'] = height;

	height = $("#" + EDIT_AREA['profile']).height();
	EDIT_AREA_HEIGHT['profile'] = height;
	$("#" + grayPanelID).height(height);
}

/**
* 求人パネルの高さ調整
*
* @param
* @return
*/
function setRecruitTabHeight() {

							//console.debug('set recruit tab height');
var areaH     = $("#tabsRecruit").height();			//領域の高さ
var areaSeleH = $("#tabRecruitUsePage").height();	//使用ページ選択の高さ
var areaTopH  = $("#tabRecruitTop").height();		//上ボタンの高さ
var areaBtmH  = $("#tabRecruitBottom").height();	//下ボタンの高さ

var height = areaH - (areaSeleH + areaTopH + areaBtmH);
var grayPanelID = GRAY_PANEL_ID['recruit'];

				//console.debug(areaH + ' ' + areaTopH + ' ' + areaBtmH + ' ' + height);
	$("#tabRecruitMid").height(height + 'px');
	TAB_HEIGHT['RECRUIT'] = height;

	height = $("#" + EDIT_AREA['recruit']).height();
	EDIT_AREA_HEIGHT['recruit'] = height;
	$("#" + grayPanelID).height(height);

	//ckEditorの編集領域の高さの調整
var editTopH = $("#cke_recruitStr .cke_top").height();
var topPH    = $("#cke_recruitStr .cke_top").css("padding-top");
var topPB    = $("#cke_recruitStr .cke_top").css("padding-bottom");

var editBtmH = $("#cke_recruitStr .cke_bottom").height();
var btmPH    = $("#cke_recruitStr .cke_bottom").css("padding-top");
var btmPB    = $("#cke_recruitStr .cke_bottom").css("padding-bottom");

	topPH = parseFloat(topPH);
	topPB = parseFloat(topPB);

	btmPH = parseFloat(btmPH);
	btmPB = parseFloat(btmPB);

	height -= (editTopH + editBtmH + topPH + topPB + btmPH + btmPB + 7);

				//console.debug(editTopH + ' ' + editBtmH + ' ' + height);
	$("#cke_recruitStr .cke_contents").height(height + 'px');
}


/**
* システムパネルの高さ調整
*
* @param
* @return
*/
function setSystemTabHeight() {

							//console.debug('set system tab height');
var areaH     = $("#tabsSystem").height();			//領域の高さ
var areaSeleH = $("#tabSystemUsePage").height();	//使用ページ選択の高さ
var areaTopH  = $("#tabSystemTop").height();		//上ボタンの高さ
var areaBtmH  = $("#tabSystemBottom").height();		//下ボタンの高さ

var height = areaH - (areaSeleH + areaTopH + areaBtmH);
var grayPanelID = GRAY_PANEL_ID['system'];

				//console.debug(areaH + ' ' + areaTopH + ' ' + areaBtmH + ' ' + height);
	$("#tabSystemMid").height(height + 'px');
	TAB_HEIGHT['SYSTEM'] = height;

	height = $("#" + EDIT_AREA['system']).height();
	EDIT_AREA_HEIGHT['system'] = height;
	$("#" + grayPanelID).height(height);

	//ckEditorの編集領域の高さの調整
var editTopH = $("#cke_systemStr .cke_top").height();
var topPH    = $("#cke_systemStr .cke_top").css("padding-top");
var topPB    = $("#cke_systemStr .cke_top").css("padding-bottom");

var editBtmH = $("#cke_systemStr .cke_bottom").height();
var btmPH    = $("#cke_systemStr .cke_bottom").css("padding-top");
var btmPB    = $("#cke_systemStr .cke_bottom").css("padding-bottom");

	topPH = parseFloat(topPH);
	topPB = parseFloat(topPB);

	btmPH = parseFloat(btmPH);
	btmPB = parseFloat(btmPB);

	height -= (editTopH + editBtmH + topPH + topPB + btmPH + btmPB + 7);

				//console.debug(editTopH + ' ' + editBtmH + ' ' + height);
	$("#cke_systemStr .cke_contents").height(height + 'px');
}

/**
* 求人パネルのckEditorの調整
*
* @param
* @return
*/
function setCKEditRecruit() {

	if(!DISP_RECRUIT_TAB) {
		CKEDITOR.replace('recruitStr' ,
			{
				height : 120
			});

		CKEDITOR.instances.recruitStr.on("blur" ,function(e) {
			CKEDITOR.instances.recruitStr.updateElement();
			var str = $("#recruitStr").val();
			var msg;

			if(str.length >= 1) {
				msg = '';
			} else {
				msg = ERR_MSG;
			}
			$("#warnRecruitStr").html(msg);
		});

		DISP_RECRUIT_TAB = true;
	}
}

/**
* システムパネルのckEditorの調整
*
* @param
* @return
*/
function setCKEditSystem() {

	if(!DISP_SYSTEM_TAB) {
		CKEDITOR.replace('systemStr' ,
			{
				height : 120
			});

		CKEDITOR.instances.systemStr.on("blur" ,function(e) {
			CKEDITOR.instances.systemStr.updateElement();
			var str = $("#systemStr").val();
			var msg;

			if(str.length >= 1) {
				msg = '';
			} else {
				msg = ERR_MSG;
			}
			$("#warnSystemStr").html(msg);
		});

		DISP_SYSTEM_TAB = true;
	}
}



/**
* 画像選択時の妥当性チェック
*
* @param {Object} 選択したファイルオブジェクト
* @return
*/
function fileSele(obj) {

var fileAttr = obj.files[0];

var name = fileAttr.name;
				//var size = fileAttr.size;
var type = fileAttr.type;
var msgImgFile;

	if(type == 'image/jpeg'
	|| type == 'image/png'
	|| type == 'image/gif') {
		msgImgFile = '';
		$("#imgTitle").val(name);
		$("#addNewImgBtn").prop('disabled' ,false);
	} else {
		msgImgFile = SELECTABLE_IMG_FILE;
		$("#addNewImgBtn").prop('disabled' ,true);
	}

	$("#warnImgFile").html(msgImgFile);
}

/**
* ファイル選択ボタンの再定義
*
* @param
* @return
*/
function resetFileSelector() {

	/*****
	1. 仮のファイル選択ボタンを生成し、
	2. 元のファイル選択ボタンを削除し、
	3. 仮のファイル選択ボタンを新しいファイル選択ボタンにする
	*****/
	$('#imgFileSele').after('<input type="file" name="imgFileSele" id="tempImgFileSele" data-parsley-required="true" data-parsley-trigger="focusout submit change">');
	$('#imgFileSele').remove();
	$('#tempImgFileSele').attr('id','imgFileSele');

	$('#imgFileSele').on("change", function () {
		fileSele(this);
	});
}


/**
* 画像選択ダイアログの定義
*
* @param
* @return
*/
function initSelectImgFileDlg() {

	$("#selectImgFile").dialog({
		autoOpen : false ,	//true ,
		modal : true ,
		width : 1220 ,		//1020
		buttons : [
			{
				text  : "出力",
				click : function() {
					checkSeleImg();
				}
			} ,
			{
				text  : "キャンセル",
				click : function() {
					$(this).dialog("close");
				}
			}
		]
	});
}


/**
* 画像リスト取得
*
* @param
* @return
*/
function readImgList() {

var result;
var dispList;
var extList;
var idx;
var idxMax;
var extS1;
var extS2;

	result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getImgFiles.php" ,
		data : {
			branchNo : BRANCH_NO
		} ,

		cache    : false ,
		dataType : 'json'
	});

	result.done(function(response) {
				console.debug(response);
		dispList = response['SEQ']['data'];
		$("#imgList").html(dispList);

		extList = response['SEQ']['extList'];
		extS1 = extList.split(',');
		idxMax = extS1.length - 1;
		for(idx=0 ;idx<idxMax ;idx++) {
			extS2 = extS1[idx].split(':');
			EXT_LIST[extS2[0]] = extS2[1];
		}
	});

	result.fail(function(response, textStatus, errorThrown) {
			console.debug('error at readImgList:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}



/**
* 新規画像選択領域表示
*
* @param
* @return
*/
function seleNewImg() {

	$("#seleNewImg").show();
}

/**
* 画像追加
*
* @param
* @return
*/
function addNewImg() {

var str;
var enterTitle = $("#enterNewImgFile").parsley().validate();
var enterFile;

var msgTitle;
var msgImgFile;

var imgVal;
var fileType;

var fd;
var result;

	if($("#imgFileSele").val() !== '') {
		imgVal = $("#imgFileSele").prop("files")[0];
					//console.debug(imgVal);
		fileType = imgVal.type;
					//console.debug(fileType);

		if(fileType == 'image/jpeg'
		|| fileType == 'image/png'
		|| fileType == 'image/gif') {
			msgImgFile = '';
			enterFile  = true;
		} else {
			msgImgFile = SELECTABLE_IMG_FILE;
			enterFile  = false;
		}
		$("#warnImgFile").html(msgImgFile);
	}

	if(enterFile && enterTitle) {
		fd = new FormData();

		fd.append("newFile"  ,imgVal);
		fd.append("branchNo" ,BRANCH_NO);
		fd.append("title"    ,$('#imgTitle').val());
		fd.append("class"    ,$("#imgClass").val());

				//console.debug('file upload beg');
		result = ajaxUploadNewImg(fd);
				//console.debug('file upload end');

		result.done(function(response) {
					console.debug(response);
			$("#seleNewImg").hide();
			readImgList();		//画像リスト再表示
		});

		result.fail(function(result, textStatus, errorThrown) {
				console.log("error for addNewImg:" + result.status + ' ' + textStatus);
		});

		result.always(function() {
		});
	}
}


/**
* 画像のアップロード
*
* @param {Object} 画像ファイル情報
* @return
*/
function ajaxUploadNewImg(fd) {

var jqXHR;

	jqXHR = $.ajax({
		type : "post" ,
		url  : "../cgi2018/ajax/mtn/uploadImg.php" ,

		dataType    : "text",
		data        : fd    ,
		processData : false ,
		contentType : false ,

		cache : false
	});

	return jqXHR;
}


function showSeleImg(imgClass ,place ,param1) {

var imgNo;

	$("input[name='seleImg']").prop("checked", false);
	if(place == 'TOP') {
		$("#imgClass").val(imgClass);
		$("#place").val(place);
		$("#imgParam1").val(param1);

		imgNo = $('#headerTopImg' + param1).val();
		if(imgNo) {
			if(imgNo.length >= 1) {
				$("#seleImg" + imgNo).prop("checked", true);
			}
		}
	}

	if(place == 'OTHER') {
		$("#imgClass").val(imgClass);
		$("#place").val(place);

		imgNo = $('#headerOtherImg').val();
		if(imgNo) {
			if(imgNo.length >= 1) {
				$("#seleImg" + imgNo).prop("checked", true);
			}
		}
	}


	$("#enterNewImgFile").parsley().reset();	// validateリセット

	//ファイル選択ボタンのリセット
	resetFileSelector();
	$("#warnImgFile").html('');

	$("#selectImgFile").dialog("open");
}


function checkSeleImg() {

/***** 選択されいてる画像 *****/
var selectedImg = $("input[name='seleImg']:checked").val();
var place       = $("#place").val();

var param1;
var tagStr;
var ext;

	console.debug(selectedImg);

console.debug(place);

	if(place == 'TOP') {
		param1 = $("#imgParam1").val();
		ext  = EXT_LIST[selectedImg];
			console.debug('top');
			console.debug(ext);

		tagStr = '<img src="../img/' + BRANCH_NO +  '/HEADER/' + selectedImg + '.' + ext + '">';
		$('#headerTopImgTN' + param1).html(tagStr);
		$('#headerTopImg' + param1).val(selectedImg);
		$("#bldHeaderImgDispSeq").prop('disabled' ,false);
	}

	if(place == 'OTHER') {
		param1 = $("#imgParam1").val();
		ext  = EXT_LIST[selectedImg];
			console.debug('other');
			console.debug(ext);

		tagStr = '<img src="../img/' + BRANCH_NO +  '/HEADER/' + selectedImg + '.' + ext + '">';
		$('#headerOtherImgTN').html(tagStr);
		$('#headerOtherImg').val(selectedImg);
		$("#bldHeaderImgDispSeq").prop('disabled' ,false);
	}
}


function readUsePage() {

var branchNo = $('#branchNo').val();

var result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getUsePage.php" ,
		data : {
			branchNo : branchNo
		} ,

		cache    : false ,
		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);
		USE_PAGE['NEWS'   ] = response['NEWS'   ];
		USE_PAGE['ALBUM'  ] = response['PROFILE'];	//PROFIEのみALBUMと読み替える
		USE_PAGE['RECRUIT'] = response['RECRUIT'];
		USE_PAGE['SYSTEM' ] = response['SYSTEM' ];

		setUsePage('NEWS');
		setUsePage('ALBUM');
		setUsePage('RECRUIT');
		setUsePage('SYSTEM');

		$("input[name='newsOuterURL']").change(function() {
			$("#sendSeleNewsPage").prop('disabled' ,false);
		});

		$("input[name='profileOuterURL']").change(function() {
			$('#sendSeleProfPage').prop('disabled' ,false);
		});

		$("input[name='systemOuterURL']").change(function() {
			$('#sendSeleSystemPage').prop('disabled' ,false);
		});

		$("input[name='recruitOuterURL']").change(function() {
			$('#sendSeleRecruitPage').prop('disabled' ,false);
		});
	});

	result.fail(function(response, textStatus, errorThrown) {
			console.debug('error at readUsePage:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}


function setUsePage(pageID) {

var usePage  = USE_PAGE[pageID]['USE'];
var idPrefix = ID_PREFIX[pageID];

var outerURLForm = OUTER_URL_FORM[pageID];
var grayPanelID  = GRAY_PANEL_ID[pageID];
var editArea     = EDIT_AREA[pageID];

	$("#" + outerURLForm).val(USE_PAGE[pageID]['USEPAGE']);
	$("input[name='" + RADIO_NAME[pageID] + "']").val([usePage]);

console.debug(usePage);

	if(usePage == 'OWN_SITE') {
		//内部サイトの時
		$("#" + outerURLForm).prop('disabled' ,true);

		$("#" + grayPanelID).css("top" ,'0px');
		$("#" + grayPanelID).hide();	//.fadeIn("slow");
		$("#" + idPrefix).css("overflow" ,"auto");
	} else {
		//外部サイトの時
		$("#" + outerURLForm).prop('disabled' ,false);

		if(grayPanelID.length >= 1) {
			var topPos = $("#" + editArea).height();
							//console.debug('id prefix:' + idPrefix);
				$("#" + idPrefix).css("overflow" ,"hidden");
			if(topPos >= 1) {
				$("#" + grayPanelID).css("top" ,'-' + topPos + 'px');
				$("#" + grayPanelID).show();	//.fadeIn("slow");
			}
		}
	}
}


function updUsePage(pageID) {

var usePage  = $("input[name='" + RADIO_NAME[pageID] + "']:checked").val();
var outerURL = $("#" + OUTER_URL_FORM[pageID]).val();

console.debug(usePage);
console.debug(outerURL);

var result = $.ajax({
		type : "post" ,
		url  : "../cgi2018/ajax/mtn/writeUsePage.php" ,
		data : {
			branchNo : BRANCH_NO ,
			pageID   : pageID    ,
			usePage  : usePage   ,
			outerURL : outerURL
		} ,

		cache    : false ,
		dataType :'json'
	});

	result.done(function(response) {
					console.debug(response);
		if(response['SESSCOND'] == SESS_OWN_INTIME) {
			selectWriteFile('PAGE_MENU');		//出力対象ファイルの抽出→ファイル出力			//pageID
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
			console.debug('error at updUsePage:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}
