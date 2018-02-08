/**
* 画像選択ダイアログ
*
* @version 1.0.1
* @date 2018.1.17
*/

var IMG_SELECTOR  = '';	// どの画像種類を選択しているか
var IMG_CLASS     = '';
var IMG_PLACE     = '';
var IMG_PARAM_1   = '';

$(window).load(function(){

	initSelectImgFileDlg();		// 画像選択ダイアログの定義
});

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
				text  : "選択",
				click : function() {
					checkSeleImg();
					$(this).dialog("close");
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


function showSeleImg(imgClass ,place ,param1) {

var imgNo;

console.debug(imgClass);

	IMG_SELECTOR = '';
	if(imgClass == 'HEADER') {
		IMG_SELECTOR = 'HEADER';
	}

	if(imgClass == 'ALL_BG') {
		IMG_SELECTOR = 'DECO';
	}

	if(imgClass == 'TOP') {
		IMG_SELECTOR = 'TOP';
	}

	if(imgClass == 'SIDEBAR') {
		IMG_SELECTOR = 'SIDEBAR';
	}

	if(imgClass == 'AGE_AUTH') {
		IMG_SELECTOR = 'AGE_AUTH';
	}

	IMG_CLASS = imgClass;
	if(place) {
		IMG_PLACE = place;
	} else {
		IMG_PLACE = '';
	}

	if(param1) {
		IMG_PARAM_1 = param1;
	} else {
		IMG_PARAM_1 = '';
	}

	readImgList();
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
			branchNo : BRANCH_NO ,
			img      : IMG_SELECTOR
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

		showImgDlg();
	});

	result.fail(function(response, textStatus, errorThrown) {
			console.debug('error at readImgList:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}


function showImgDlg() {

	$("input[name='seleImg']").prop("checked", false);
	if(IMG_CLASS == 'TOP_HEADER') {
		imgNo = $('#headerTopImg' + param1).val();
		if(imgNo) {
			if(imgNo.length >= 1) {
				$("#seleImg" + imgNo).prop("checked", true);
			}
		}
	}

	if(IMG_CLASS == 'OTHER_HEADER') {
		imgNo = $('#headerOtherImg').val();
		if(imgNo) {
			if(imgNo.length >= 1) {
				$("#seleImg" + imgNo).prop("checked", true);
			}
		}
	}

	if(IMG_CLASS == 'ALL_BG') {
		imgNo = $('#decoBGImg').val();
		if(imgNo) {
			if(imgNo.length >= 1) {
				$("#seleImg" + imgNo).prop("checked", true);
			}
		}
	}

	if(IMG_CLASS == 'TOP') {
		if(IMG_PLACE == 'SYSTEM') {
			imgNo = $('#topSystemImg').val();
		}
		if(IMG_PLACE == 'RECRUIT') {
			imgNo = $('#topRecruitImg').val();
		}

		if(imgNo) {
			if(imgNo.length >= 1) {
				$("#seleImg" + imgNo).prop("checked", true);
			}
		}
	}

	if(IMG_CLASS == 'SIDEBAR') {
		imgNo = $('#sideBarImg' + IMG_PLACE).val();

		if(imgNo) {
			if(imgNo.length >= 1) {
				$("#seleImg" + imgNo).prop("checked", true);
			}
		}
	}

	if(IMG_CLASS == 'AGE_AUTH') {
		if(IMG_PLACE == 'TOP') {
			imgNo = $('#ageAuthImg').val();
		}
		if(IMG_PLACE == 'LINK_EXCHANGE') {
			var linkNo = $("#editAgeAuthLink").val();
			imgNo = $('#imgNOAA' + linkNo).val();
		}

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


function checkSeleImg() {

/***** 選択されいてる画像 *****/
var selectedImg = $("input[name='seleImg']:checked").val();

var tagStr;
var ext;

	if(IMG_CLASS == 'HEADER') {
		if(IMG_PLACE == 'TOP') {
			ext = EXT_LIST[selectedImg];
				console.debug('top');
				console.debug(ext);

			tagStr = '<img src="../img/' + BRANCH_NO +  '/HEADER/' + selectedImg + '.' + ext + '">';
			$('#headerTopImgTN' + IMG_PARAM_1).html(tagStr);
			$('#headerTopImg' + IMG_PARAM_1).val(selectedImg);
			$("#bldHeaderImgDispSeq").prop('disabled' ,false);
		}

		if(IMG_PLACE == 'OTHER') {
			ext = EXT_LIST[selectedImg];
				console.debug('other');
				console.debug(ext);

			tagStr = '<img src="../img/' + BRANCH_NO +  '/HEADER/' + selectedImg + '.' + ext + '">';
			$('#headerOtherImgTN').html(tagStr);
			$('#headerOtherImg').val(selectedImg);
			$("#bldHeaderImgDispSeq").prop('disabled' ,false);
		}
	}

	if(IMG_CLASS == 'ALL_BG') {
		ext = EXT_LIST[selectedImg];
			console.debug('other');
			console.debug(ext);

		tagStr = '<img src="../img/' + BRANCH_NO +  '/DECO/' + selectedImg + '.' + ext + '">';
		$('#decoBGImgTN').html(tagStr);
		$('#decoBGImg').val(selectedImg);
	}

	if(IMG_CLASS == 'TOP') {
		if(IMG_PLACE == 'SYSTEM') {
			ext = EXT_LIST[selectedImg];
				console.debug('top system');
				console.debug(ext);

			tagStr = '<img src="../img/' + BRANCH_NO +  '/TOP/' + selectedImg + '.' + ext + '">';
			$('#topSystemImgTN').html(tagStr);
			$('#topSystemImg').val(selectedImg);
		}

		if(IMG_PLACE == 'RECRUIT') {
			ext = EXT_LIST[selectedImg];
				console.debug('top recruit');
				console.debug(ext);

			tagStr = '<img src="../img/' + BRANCH_NO +  '/TOP/' + selectedImg + '.' + ext + '">';
			$('#topRecruitImgTN').html(tagStr);
			$('#topRecruitImg').val(selectedImg);
		}

		$("#bldTopImg").prop('disabled' ,false);
	}

	if(IMG_CLASS == 'SIDEBAR') {
		ext = EXT_LIST[selectedImg];
			console.debug('side bar');
			console.debug(ext);

		tagStr = '<img src="../img/' + BRANCH_NO +  '/SIDEBAR/' + selectedImg + '.' + ext + '">';
		$('#sideBarImgTN' + IMG_PLACE).html(tagStr);
		$('#sideBarImg' + IMG_PLACE).val(selectedImg);
		$("#bldSideBarImg").prop('disabled' ,false);
	}

	if(IMG_CLASS == 'AGE_AUTH') {
		if(IMG_PLACE == 'TOP') {
			ext = EXT_LIST[selectedImg];
				console.debug('age Auth system');
				console.debug(ext);

			tagStr = '<img src="../img/' + BRANCH_NO +  '/AGE_AUTH/' + selectedImg + '.' + ext + '">';
			$('#ageAuthImgTN').html(tagStr);
			$('#ageAuthImg').val(selectedImg);
		}

		if(IMG_PLACE == 'LINK_EXCHANGE') {
			ext = EXT_LIST[selectedImg];
				//console.debug('age Auth link exchange');
				//console.debug(ext);

			var linkNo = $("#editAgeAuthLink").val();
							//console.debug('link No:' + linkNo);
							//			$('#imgNOAA'  + linkNo).val(selectedImg);
			$('#imgNOAA').val(selectedImg);
		}
	}
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
		fd.append("class"    ,IMG_CLASS);		//$("#imgClass").val()

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


/**
* 新規画像選択領域表示
*
* @param
* @return
*/
function seleNewImg() {

	$("#seleNewImg").show();
}
