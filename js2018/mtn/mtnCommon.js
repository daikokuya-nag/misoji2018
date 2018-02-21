/**
* メンテ共通関数
*
* @version 1.0.1
* @date 2018.1.17
*/

var EXT_LIST = [];		// 画像の拡張子のリスト
var USE_PAGE = {};		// 自サイト、外部サイトのいずれを使うか
var RADIO_NAME     = {NEWS:'useNews'       ,ALBUM:'useProfile'      ,WORK:'useWork'         ,RECRUIT:'useRecruitPage'   ,SYSTEM:'useSystemPage'   ,PHOTODIARY:'usePhotoDiaryPage'};		// ラジオボタン名
var ID_PREFIX      = {NEWS:'tabsNews'      ,ALBUM:'tabsProfile'     ,WORK:''                ,RECRUIT:'tabsRecruit'      ,SYSTEM:'tabsSystem'      ,PHOTODIARY:'tabsPhotoDiary'};		// tabのプレフィクス
var OUTER_URL_FORM = {NEWS:'newsOuterURL'  ,ALBUM:'profileOuterURL' ,WORK:'workOuterURL'    ,RECRUIT:'recruitOuterURL'  ,SYSTEM:'systemOuterURL'  ,PHOTODIARY:'photoDiaryOuterURL'};	// 外部サイトの入力域
var GRAY_PANEL_ID  = {NEWS:'grayPanelNews' ,ALBUM:'grayPanelProf'   ,WORK:''                ,RECRUIT:'grayPanelRecruit' ,SYSTEM:'grayPanelSystem' ,PHOTODIARY:'grayPanelPhotoDiary'};	// 外部サイト使用時の、入力不可領域のマスク

var EDIT_AREA        = {NEWS:'tabNewsMain' ,ALBUM:'tabProfMain' ,RECRUIT:'tabRecruitMain' ,SYSTEM:'tabSystemMain' ,PHOTODIARY:'tabSystemMain' ,TOP:'tabTopMain' ,AGE_AUTH:'tabAgeAuthMid'};		// 入力項目範囲
var EDIT_AREA_HEIGHT = {NEWS:0 ,ALBUM:0 ,RECRUIT:0 ,SYSTEM:0 ,PHOTODIARY:0 ,TOP:0 ,AGE_AUTH:0};		// 入力域の高さ
var TAB_HEIGHT       = {NEWS:0 ,ALBUM:0 ,RECRUIT:0 ,SYSTEM:0 ,PHOTODIARY:0 ,TOP:0 ,AGE_AUTH:0};		// tabの高さ

var DISP_SYSTEM_TAB  = false;	// システムタブを表示したか
var DISP_RECRUIT_TAB = false;	// 求人タブを表示したか

var TAB_A_HEIGHT   = 0;
var TAB_TOP_HEIGHT = 0;
var TAB_BTM_HEIGHT = 0;


$(document).ready(function(){

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

				if(selectedPanel == "#tabsTop") {
					if(TAB_HEIGHT['TOP'] == 0) {
						setTopTabHeight();
					}
				}

				if(selectedPanel == "#tabsProfile") {
					if(TAB_HEIGHT['ALBUM'] == 0) {
						setProfTabHeight();
					}
				}

				if(selectedPanel == "#tabsRecruit") {
					$("#warnRecruitStr").html('');
					if(TAB_HEIGHT['RECRUIT'] == 0) {
						setRecruitTabHeight();
					}
				}

				if(selectedPanel == "#tabsSystem") {
					$("#warnSystemStr").html('');
					if(TAB_HEIGHT['SYSTEM'] == 0) {
						setSystemTabHeight();
					}
				}

				if(selectedPanel == "#tabsAgeAuth") {
					if(TAB_HEIGHT['AGE_AUTH'] == 0) {
						setAgeAuthTabHeight();
					}
				}
			}
		}
	);

	//最初に表示されるパネル
//	setTopTabHeight();

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

	$("input[name='useWork']").change(function() {
		$("#sendSeleWorkPage").prop('disabled' ,false);
		USE_PAGE['WORK']['USE'] = $(this).val();
		setUsePage('WORK');
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

				//現在、写メ日記は外部のみのためボタン制御は入れない
				/*
				$("input[name='usePhotoDiaryPage']").change(function() {
					$("#sendSelePhotoDiaryPage").prop('disabled' ,false);
					USE_PAGE['PHOTODIARY']['USE'] = $(this).val();
					setUsePage('PHOTODIARY');
				});
				*/
});


$(window).load(function(){

	// タブの中身調整
	adjustTabHeight();


	readUsePage();				// 使用するページの読み込み


	footerFixed();
	checkFontSize(footerFixed);


	//最初に表示されるパネル
	setTopTabHeight();
});


function adjustTabHeight() {

var tempH;

var footerH;
var windowH;

	footerH = $("#footer").height();
			// console.debug("heigt of footer:" + footerH);

	windowH = $(window).height();
			// console.debug("heigt of window:" + windowH);

	tempH = windowH - footerH * 3;
			// console.debug("heigt of calced:" + tempH);

	// タブオブジェクトの高さ
	$("#tabA").height(tempH);		//750
	$(".tabArea").css('overflow' ,'auto');

	// 下ボタンの調整
var width = $(".tabArea").width();
	$(".tabBottomBtn").width(width + 'px');

	TAB_A_HEIGHT   = tempH;
	TAB_TOP_HEIGHT = $("#tabA ul").height();	// タブの並びの高さ	//41;
			// console.debug('TAB_TOP_HEIGHT ' + TAB_TOP_HEIGHT);
	TAB_BTM_HEIGHT = 40;
}

/**
* TOPパネルの高さ調整
*
* @param
* @return
*/
function setTopTabHeight() {

var areaBtmH = $("#tabTopBottom").height();		// 下ボタンの高さ
var height   = TAB_A_HEIGHT - (TAB_TOP_HEIGHT + areaBtmH + TAB_BTM_HEIGHT);

							//console.debug(areaH + ' ' + areaBtmH + ' ' + height);
	$("#tabTopSystemMid").height(height + 'px');
	TAB_HEIGHT['TOP'] = height;

	height = $("#" + EDIT_AREA['TOP']).height();
	EDIT_AREA_HEIGHT['TOP'] = height;
}

/**
* 新着情報パネルの高さ調整
*
* @param
* @return
*/
function setNewsTabHeight() {

var areaSeleH  = $("#tabNewsUsePage").height();		// 使用ページ選択の高さ
var areaUpperH = $("#tabNewsUpper").height();		// 上ボタンとタイトルの高さ
var areaBtmH   = $("#tabNewsBottom").height();		// 下ボタンの高さ

var height = TAB_A_HEIGHT - (TAB_TOP_HEIGHT + areaSeleH + areaUpperH + areaBtmH + TAB_BTM_HEIGHT);
var grayPanelID = GRAY_PANEL_ID['NEWS'];

							//console.debug(TAB_A_HEIGHT + ' ' + areaUpperH + ' ' + areaBtmH + ' ' + height);
	$("#tabNewsMid").height(height + 'px');
	TAB_HEIGHT['NEWS'] = height;

	height = $("#" + EDIT_AREA['NEWS']).height();
	EDIT_AREA_HEIGHT['NEWS'] = height;
	$("#" + grayPanelID).height(height);
}

/**
* プロファイルパネルの高さ調整
*
* @param
* @return
*/
function setProfTabHeight() {

var areaSeleH  = $("#tabProfileUsePage").height();	// 使用ページ選択の高さ
var areaUpperH = $("#tabProfUpper").height();		// 上ボタンとタイトルの高さ
var areaBtmH   = $("#tabProfBottom").height();		// 下ボタンの高さ

var height = TAB_A_HEIGHT - (TAB_TOP_HEIGHT + areaSeleH + areaUpperH + areaBtmH + TAB_BTM_HEIGHT);
var grayPanelID = GRAY_PANEL_ID['ALBUM'];

							//console.debug('profile tab ' + areaH + ' ' + areaSeleH + ' ' + areaUpperH + ' ' + areaBtmH + ' ' + height);
	$("#tabProfMid").height(height + 'px');
	TAB_HEIGHT['ALBUM'] = height;

	height = $("#" + EDIT_AREA['ALBUM']).height();
	EDIT_AREA_HEIGHT['ALBUM'] = height;
	$("#" + grayPanelID).height(height);
}

/**
* 求人パネルの高さ調整
*
* @param
* @return
*/
function setRecruitTabHeight() {

var areaSeleH = $("#tabRecruitUsePage").height();	//使用ページ選択の高さ
var areaTopH  = $("#tabRecruitTop").height();		//上ボタンの高さ
var areaBtmH  = $("#tabRecruitBottom").height();	//下ボタンの高さ

var height = TAB_A_HEIGHT - (TAB_TOP_HEIGHT + areaSeleH + areaTopH + areaBtmH + TAB_BTM_HEIGHT);
var grayPanelID = GRAY_PANEL_ID['RECRUIT'];

							//console.debug(areaH + ' ' + areaTopH + ' ' + areaBtmH + ' ' + height);
	$("#tabRecruitMid").height(height + 'px');
	TAB_HEIGHT['RECRUIT'] = height;

	height = $("#" + EDIT_AREA['RECRUIT']).height();
	EDIT_AREA_HEIGHT['RECRUIT'] = height;
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

var areaSeleH = $("#tabSystemUsePage").height();	//使用ページ選択の高さ
var areaTopH  = $("#tabSystemTop").height();		//上ボタンの高さ
var areaBtmH  = $("#tabSystemBottom").height();		//下ボタンの高さ

var height = TAB_A_HEIGHT - (TAB_TOP_HEIGHT + areaSeleH + areaTopH + areaBtmH + TAB_BTM_HEIGHT);
var grayPanelID = GRAY_PANEL_ID['SYSTEM'];

						//console.debug(areaH + ' ' + areaTopH + ' ' + areaBtmH + ' ' + height);
	$("#tabSystemMid").height(height + 'px');
	TAB_HEIGHT['SYSTEM'] = height;

	height = $("#" + EDIT_AREA['SYSTEM']).height();
	EDIT_AREA_HEIGHT['SYSTEM'] = height;
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
			height  : 120 ,
			toolbar : 'Full'
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
			height  : 120 ,
			toolbar : 'Full'
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
* 年齢認証パネルの高さ調整
*
* @param
* @return
*/
function setAgeAuthTabHeight() {

var areaBtmH = $("#tabAgeAuthBottom").height();		// 下ボタンの高さ
var height   = TAB_A_HEIGHT - (TAB_TOP_HEIGHT + areaBtmH + TAB_BTM_HEIGHT);

							//console.debug(areaH + ' ' + areaUpperH + ' ' + areaBtmH + ' ' + height);
	$("#tabAgeAuthMid").height(height + 'px');
	TAB_HEIGHT['AGE_AUTH'] = height;

	height = $("#" + EDIT_AREA['AGE_AUTH']).height();
	EDIT_AREA_HEIGHT['AGE_AUTH'] = height;
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
					// console.debug(response);
		USE_PAGE['NEWS'   ] = response['NEWS'   ];
		USE_PAGE['ALBUM'  ] = response['ALBUM'  ];	//PROFIEのみALBUMと読み替える
		USE_PAGE['WORK'   ] = response['WORK'   ];
		USE_PAGE['RECRUIT'] = response['RECRUIT'];
		USE_PAGE['SYSTEM' ] = response['SYSTEM' ];
		USE_PAGE['PHOTODIARY'] = response['PHOTODIARY'];

		setUsePage('NEWS');
		setUsePage('ALBUM');
		setUsePage('WORK');

		setUsePage('RECRUIT');
		setUsePage('SYSTEM');
		setUsePage('PHOTODIARY');

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

				//現在、写メ日記は外部のみのためボタン制御は入れない
				/*
				$("input[name='photoDiaryOuterURL']").change(function() {
					$('#sendSelePhotoDiaryPage').prop('disabled' ,false);
				});
				*/
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

					//console.debug(pageID + ' ' + usePage);
	if(usePage == 'OWN_SITE') {
		//内部サイトの時
		$("#" + outerURLForm).prop('disabled' ,true);

		$("#" + grayPanelID).css("top" ,'0px');
		$("#" + grayPanelID).hide();	//.fadeIn("slow");
		if(idPrefix.length >= 1) {
			$("#" + idPrefix).css("overflow" ,"auto");
		}
	} else {
		//外部サイトの時
		$("#" + outerURLForm).prop('disabled' ,false);

		if(grayPanelID.length >= 1) {
			var topPos = $("#" + editArea).height();
						//console.debug('id prefix:' + idPrefix);

			if(idPrefix.length >= 1) {
				$("#" + idPrefix).css("overflow" ,"hidden");
			}
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
var updID;

				/*
				console.debug(usePage);
				console.debug(outerURL);
				*/

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
					// console.debug(response);
		if(response['SESSCOND'] == SESS_OWN_INTIME) {

			if(pageID == 'WORK') {
				updID = 'WORK';
			} else {
				updID = 'PAGE_MENU';
			}

			selectWriteFile(updID);		//出力対象ファイルの抽出→ファイル出力
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
