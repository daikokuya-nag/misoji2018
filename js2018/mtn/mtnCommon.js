
var EXT_LIST = [];

var USE_PAGE = {};
var RADIO_NAME     = {NEWS:'useNews'       ,ALBUM:'useProfile'      ,RECRUIT:'useRecruitPage'   ,SYSTEM:'useSystemPage'};
var ID_PREFIX      = {NEWS:'tabsNews'      ,ALBUM:'tabsProfile'     ,RECRUIT:'tabsRecruit'      ,SYSTEM:'tabsSystem'};
var OUTER_URL_FORM = {NEWS:'newsOuterURL'  ,ALBUM:'profileOuterURL' ,RECRUIT:'recruitOuterURL'  ,SYSTEM:'systemOuterURL'};
var GRAY_PANEL_ID  = {NEWS:'grayPanelNews' ,ALBUM:'grayPanelProf'   ,RECRUIT:'grayPanelRecruit' ,SYSTEM:'grayPanelSystem'};

var EDIT_AREA        = {NEWS:'tabNewsMain' ,ALBUM:'tabProfMain'     ,RECRUIT:'tabRecruitMain'   ,SYSTEM:'tabSystemMain'};
var EDIT_AREA_HEIGHT = {NEWS:0 ,ALBUM:0 ,RECRUIT:0 ,SYSTEM:0};

var TAB_HEIGHT = {NEWS:0 ,ALBUM:0 ,RECRUIT:0 ,SYSTEM:0};


var DISP_SYSTEM_TAB  = false;	//システムタブを表示したか
var DISP_RECRUIT_TAB = false;	//求人タブを表示したか

$(document).ready(function(){

					/*
						CKEDITOR.on('instanceReady', function(){
							$.each( CKEDITOR.instances, function(instance) {
								CKEDITOR.instances[instance].on("change", function(e) {
									for(instance in CKEDITOR.instances)
										CKEDITOR.instances[instance].updateElement();
								});

								CKEDITOR.instances[instance].on("blur", function(e) {
									for(instance in CKEDITOR.instances)
										CKEDITOR.instances[instance].updateElement();
								});
							});
						});
					*/

	$("#tabA").tabs(
		{
			//heightStyle : "fill"
			activate : function(event, ui) {
				console.debug(ui.newPanel.selector);
				var selectedPanel = ui.newPanel.selector;
				if(selectedPanel == "#tabsNews") {
					if(TAB_HEIGHT['news'] == 0) {
						setNewsTabHeight();
					}
				}
				if(selectedPanel == "#tabsProfile") {
					if(TAB_HEIGHT['profile'] == 0) {
						setProfTabHeight();
					}
				}

				if(selectedPanel == "#tabsRecruit") {
					setCKEditRecruit();
					if(TAB_HEIGHT['recruit'] == 0) {
						setRecruitTabHeight();
					}
				}
				if(selectedPanel == "#tabsSystem") {
					setCKEditSystem();
					if(TAB_HEIGHT['system'] == 0) {
						setSystemTabHeight();
					}
				}
			}
		}
	);


	/***** タブの中身調整 *****/
	setTabHeight();
	setTabBottom();

	setNewsTabHeight();

	/***** ファイル選択時 *****/
	$("#imgFileSele").change(function () {
		fileSele(this);
	});


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

	readImgList();
});


$(window).load(function(){

	/***** 画像選択ダイアログの定義 *****/
	initSelectImgFileDlg();

	readUsePage();

});


/***** 高さ調整 *****/
function setTabHeight() {
	$(".tabArea").height(700);
	$(".tabArea").css('overflow' ,'auto');
}

/***** 下ボタンの調整 *****/
function setTabBottom() {

var width = $(".tabArea").width();

	$(".tabBottomBtn").width(width + 'px');
}



/***** 新着情報パネルの高さ調整 *****/
function setNewsTabHeight() {

							//console.debug('set news tab height');
var areaH     = $("#tabsNews").height();			//領域の高さ
var areaSeleH = $("#tabNewsUsePage").height();	//使用ページ選択の高さ
var areaTopH  = $("#tabNewsTop").height();		//上ボタンの高さ
var areaBtmH  = $("#tabNewsBottom").height();	//下ボタンの高さ

var height = areaH - (areaSeleH + areaTopH + areaBtmH);
var grayPanelID = GRAY_PANEL_ID['news'];

				//console.debug(areaH + ' ' + areaTopH + ' ' + areaBtmH + ' ' + height);
	$("#tabNewsMid").height(height + 'px');
	TAB_HEIGHT['news'] = height;

	height = $("#" + EDIT_AREA['news']).height();
	EDIT_AREA_HEIGHT['news'] = height;
	$("#" + grayPanelID).height(height);
}


/***** プロファイルパネルの高さ調整 *****/
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
	TAB_HEIGHT['profile'] = height;

	height = $("#" + EDIT_AREA['profile']).height();
	EDIT_AREA_HEIGHT['profile'] = height;
	$("#" + grayPanelID).height(height);
}


/***** 求人パネルの高さ調整 *****/
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
	TAB_HEIGHT['recruit'] = height;

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


/***** システムパネルの高さ調整 *****/
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
	TAB_HEIGHT['system'] = height;

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


function setCKEditRecruit() {

	if(!DISP_RECRUIT_TAB) {

		CKEDITOR.replace('recruitStr' ,
			{
				height : 120
			});


		CKEDITOR.instances.recruitStr.on("blur", function(e) {
			CKEDITOR.instances.recruitStr.updateElement();
			var str = $("#recruitStr").val();
			var msg;

			if(str.length >= 1) {
				msg = '';
			} else {
				msg = 'any error';
			}
			$("#warnRecruitStr").html(msg);
		});


		DISP_RECRUIT_TAB = true;
	}
}

function setCKEditSystem() {


	if(!DISP_SYSTEM_TAB) {
		CKEDITOR.replace('systemStr' ,
			{
				height : 120
			});


		CKEDITOR.instances.systemStr.on("blur", function(e) {
			CKEDITOR.instances.systemStr.updateElement();
			var str = $("#systemStr").val();
			var msg;

			if(str.length >= 1) {
				msg = '';
			} else {
				msg = 'any error';
			}
			$("#warnSystemStr").html(msg);
		});


		DISP_SYSTEM_TAB = true;
	}
}



/************************************** 画像選択 **************************************/

/***** 画像選択時の妥当性チェック *****/
function fileSele(obj) {

var fileAttr = obj.files[0];

var name = fileAttr.name;
				//var size = fileAttr.size;
var type = fileAttr.type;
var str = '';

	if(type == 'image/jpeg'
	|| type == 'image/png'
	|| type == 'image/gif') {
//		$("#strImgFile").html('');	//選択していないときのエラーメッセージを非表示
//		$('#newFile').parsley().reset();
//		$('.imgTypeCaution').html(str);
		$("#imgTitle").val(name);

		$("#addNewImgBtn").prop('disabled' ,false);
	} else {
		/* ファイル形式が指定以外だったとき */
		//選択したファイル名をリセット

//		$('#imgFileSele').parsley().reset();

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


//		$('#newFile').parsley({
//			successClass : "has-success",
//			errorClass   : "has-error"  ,
//
//			errorsWrapper : '<div class="invalid-message"></div>',
//			errorTemplate : '<span></span>'
//		});
//		$("#newFile").parsley().isValid();

		str = 'jpg、png、gifのいずれかの形式のファイルを選択してください';
//		$('.imgTypeCaution').html(str);

		$("#addNewImgBtn").prop('disabled' ,true);
	}

console.debug('str:' + str);
}



/***** 画像選択ダイアログの定義 *****/
function initSelectImgFileDlg() {

	$("#selectImgFile").dialog({
		autoOpen : false ,	//true ,
		modal : true ,
		width : 1220 ,		//1020
		buttons : [
			{
				text  : "出力",
				click : function() {
					var chkEnter = checkSeleImg();
					if(chkEnter) {
							//alert('OK');
					} else {
							//alert('any error');
						//alert(chkEnter);
					}
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



/***** 画像リスト取得 *****/
function readImgList() {

var branchNo = $('#branchNo').val();
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
			branchNo : branchNo
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



/***** 新規画像選択 *****/
function seleNewImg() {

	$("#seleNewImg").show();
}

/***** 画像追加 *****/
function addNewImg() {

var fd = new FormData();
var result

	if($("#imgFileSele").val() !== '') {
		fd.append("newFile"  ,$("#imgFileSele").prop("files")[0]);
		fd.append("branchNo" ,$('#branchNo').val());
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


/***** 画像のアップロード *****/
function ajaxUploadNewImg(fd) {

var jqXHR;

	jqXHR = $.ajax({
		type : "post" ,
		url  : "../cgi2018/ajax/mtn/uploadImg.php" ,

		dataType    : "text",
		data        : fd ,
		processData : false ,
		contentType : false ,

		cache : false
	});

	return jqXHR;
}


function showSeleImg(imgClass ,param1) {

var imgNo;

	$("#imgClass").val(imgClass);
	$("#imgParam1").val(param1);

	$("input[name='seleImg']").prop("checked", false);

	if(imgClass == 'TOP_HEADER') {
		imgNo = $('#topImg' + param1).val();
		if(imgNo.length >= 1) {
			$("#seleImg" + imgNo).prop("checked", true);
		}
	}

	$("#selectImgFile").dialog("open");
}


function checkSeleImg() {

var branchNo = $('#branchNo').val();

/***** 選択されいてる画像 *****/
var selectedImg = $("input[name='seleImg']:checked").val();
var imgClass    = $("#imgClass").val();

var param1;
var tagStr;
var ext;

	console.debug(selectedImg);

	if(imgClass = 'TOP_HEADER') {
		param1 = $("#imgParam1").val();
		ext  = EXT_LIST[selectedImg];
			console.debug(ext);

		tagStr = '<img src="../img/' + branchNo +  '/' + imgClass + '/' + selectedImg + '.' + ext + '">';
		$('#topImgTN' + param1).html(tagStr);

		$('#topImg' + param1).val(selectedImg);

		$("#bldTopImgDispSeq").prop('disabled' ,false);
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

		var pageID;
		var radioName;
		var usePage;

		pageID    = 'NEWS';
		radioName = RADIO_NAME[pageID];
		usePage   = USE_PAGE[pageID]['USE'];
		$("input[name='" + radioName + "']").val([usePage]);
		setUsePage(pageID);

		pageID    = 'ALBUM';
		radioName = RADIO_NAME[pageID];
		usePage   = USE_PAGE[pageID]['USE'];
		$("input[name='" + radioName + "']").val([usePage]);
		setUsePage(pageID);

		pageID    = 'RECRUIT';
		radioName = RADIO_NAME[pageID];
		usePage   = USE_PAGE[pageID]['USE'];
		$("input[name='" + radioName + "']").val([usePage]);
		setUsePage(pageID);

		pageID    = 'SYSTEM';
		radioName = RADIO_NAME[pageID];
		usePage   = USE_PAGE[pageID]['USE'];
		$("input[name='" + radioName + "']").val([usePage]);
		setUsePage(pageID);


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

var usePage      = USE_PAGE[pageID]['USE'];
var otherURL     = USE_PAGE[pageID]['USEPAGE'];
var idPrefix     = ID_PREFIX[pageID];
var outerURLForm = OUTER_URL_FORM[pageID];
var grayPanelID  = GRAY_PANEL_ID[pageID];
var editArea       = EDIT_AREA[pageID];
var editAreaHeight = EDIT_AREA_HEIGHT[pageID];

	$("#" + outerURLForm).val(otherURL);

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

var branchNo = $('#branchNo').val();

var radioName    = RADIO_NAME[pageID];
var outerURLForm = OUTER_URL_FORM[pageID];

var usePage  = $("input[name='" + radioName + "']:checked").val();
var outerURL = $("#" + outerURLForm).val();

console.debug(usePage);
console.debug(outerURL);

var result = $.ajax({
		type : "post" ,
		url  : "../cgi2018/ajax/mtn/writeUsePage.php" ,
		data : {
			branchNo : branchNo ,
			pageID   : pageID   ,
			usePage  : usePage  ,
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
				'長時間操作がなかったため接続が切れました。ログインしなおしてください。' ,
				'メンテナンス' ,
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
