
	/***** 新着情報 *****/
var TAB_NEWS_HEIGHT;

	/***** プロファイル *****/
var TAB_PROF_HEIGHT;

	/***** 求人 *****/
var TAB_RECRUIT_HEIGHT;

	/***** システム *****/
var TAB_SYSTEM_HEIGHT;

var EXT_LIST = [];

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
					if(TAB_NEWS_HEIGHT == 0) {
						setNewsTabHeight();
					}
				}
				if(selectedPanel == "#tabsProfile") {
					if(TAB_PROF_HEIGHT == 0) {
						setProfTabHeight();
					}
				}

				if(selectedPanel == "#tabsRecruit") {
					if(TAB_RECRUIT_HEIGHT == 0) {
						setRecruitTabHeight();
					}
				}
				if(selectedPanel == "#tabsSystem") {
					if(TAB_SYSTEM_HEIGHT == 0) {
						setSystemTabHeight();
					}
				}
			}
		}
	);

	/***** タブの中身調整 *****/
	/***** 高さの初期化 *****/
	/***** 新着情報 *****/
	TAB_NEWS_HEIGHT = 0;

	/***** プロファイル *****/
	TAB_PROF_HEIGHT = 0;

	/***** 求人 *****/
	TAB_RECRUIT_HEIGHT = 0;

	/***** システム *****/
	TAB_SYSTEM_HEIGHT = 0;

	setTabHeight();
	setTabBottom();

	setNewsTabHeight();

	/***** ファイル選択時 *****/
	$("#imgFileSele").change(function () {
		fileSele(this);
	});

	showImgList();
});


$(window).load(function(){

	/***** 画像選択ダイアログの定義 *****/
	initSelectImgFileDlg();
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
var areaH    = $("#tabsNews").height();			//領域の高さ
var areaTopH = $("#tabNewsTop").height();		//上ボタンの高さ
var areaBtmH = $("#tabNewsBottom").height();	//下ボタンの高さ

var height = areaH - (areaTopH + areaBtmH);

				//console.debug(areaH + ' ' + areaTopH + ' ' + areaBtmH + ' ' + height);
	$("#tabNewsMid").height(height + 'px');

	TAB_NEWS_HEIGHT = height;
}


/***** プロファイルパネルの高さ調整 *****/
function setProfTabHeight() {

							//console.debug('set prof tab height');
var areaH    = $("#tabsProfile").height();		//領域の高さ
var areaTopH = $("#tabProfTop").height();		//上ボタンの高さ
var areaBtmH = $("#tabProfBottom").height();	//下ボタンの高さ

var height = areaH - (areaTopH + areaBtmH);

				//console.debug(areaH + ' ' + areaTopH + ' ' + areaBtmH + ' ' + height);
	$("#tabProfMid").height(height + 'px');

	TAB_PROF_HEIGHT = height;
}


/***** 求人パネルの高さ調整 *****/
function setRecruitTabHeight() {

							//console.debug('set recruit tab height');
var areaH    = $("#tabsRecruit").height();		//領域の高さ
var areaTopH = $("#tabRecruitTop").height();	//上ボタンの高さ
var areaBtmH = $("#tabRecruitBottom").height();	//下ボタンの高さ

var height = areaH - (areaTopH + areaBtmH);

				//console.debug(areaH + ' ' + areaTopH + ' ' + areaBtmH + ' ' + height);
	$("#tabRecruitMid").height(height + 'px');

	TAB_RECRUIT_HEIGHT = height;

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
var areaH    = $("#tabsSystem").height();		//領域の高さ
var areaTopH = $("#tabSystemTop").height();		//上ボタンの高さ
var areaBtmH = $("#tabSystemBottom").height();		//下ボタンの高さ

var height = areaH - (areaTopH + areaBtmH);

				//console.debug(areaH + ' ' + areaTopH + ' ' + areaBtmH + ' ' + height);
	$("#tabSystemMid").height(height + 'px');

	TAB_SYSTEM_HEIGHT = height;

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
function showImgList() {

var branchNo = $('#branchNo').val();
var result;
var list;
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
					//console.debug(response);
		list = response['SEQ']['data'];
		$("#imgList").html(list);

		extList = response['SEQ']['extList'];
		extS1 = extList.split(',');
		idxMax = extS1.length - 1;
		for(idx=0 ;idx<idxMax ;idx++) {
			extS2 = extS1[idx].split(':');
			EXT_LIST[extS2[0]] = extS2[1];
		}
	});

	result.fail(function(result, textStatus, errorThrown) {
			console.debug('error at getImgFiles(:' + result.status + ' ' + textStatus);
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


console.debug('file upload beg');
		result = ajaxUploadNewImg(fd);
console.debug('file upload end');

		result.done(function(response) {
					console.debug(response);
			$("#seleNewImg").hide();
			showImgList();		//画像リスト再表示
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
