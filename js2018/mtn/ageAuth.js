/**
* 年齢認証ページ
*
* @version 1.0.1
* @date 2018.2.5
*/

// リンク用
var NEW_AA_LINK = 'NEW_AA_LINK';	// 新規分のサフィックス
var NEW_AA_LINK_SEQ = 1;			// 新規分の連番
var EDIT_AA_LINK;

var EXIST_AA_LINK_SEQ = 1;			// 既存がいくつあるか
// リンク用ココマデ

var EXT_LIST;

$(document).ready(function(){

	$('input[name="ageAuthLinkImg"]:radio').change( function() {
		var val = $( this ).val();

			console.debug(val);
		if(val == 'URL') {
			$("#attAALink").prop('disabled' ,true);
			$("#imgURLAA").prop('disabled' ,false);
		} else {
			if(val == 'FILE') {
			$("#attAALink").prop('disabled' ,false);
			$("#imgURLAA").prop('disabled' ,true);
			} else {	// 画像ナシのとき
				$("#attAALink").prop('disabled' ,true);
				$("#imgURLAA").prop('disabled' ,true);
			}
		}
	});
});


$(window).load(function(){

	// リンク編集ダイアログの定義
	$("#editAgeAuthLinkDlg").dialog({
		autoOpen : false ,
		modal    : true  ,
		width    : 850   ,

		buttons: [
			{
				text :"出力",
				click:function() {
//					var chkEnter = checkNewsEnter();
//					if(chkEnter) {
						keepAgeAuthLink();
						$(this).dialog("close");
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

	setCKEditAgeAuth();
	getAgeAuthValList('INIT');	// 現在の年齢認証ページで使用する画像の読み込み
});


/**
* 現在の年齢認証ページの画像の読み込み
*
* @param
* @return
*/
function getAgeAuthValList(readMode) {

var result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getAgeAuthVal.php" ,
		data : {
			branchNo : BRANCH_NO
		} ,

		cache    : false ,
		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);
		EXT_LIST = setExtList(response['extList']);

		if(readMode == 'INIT') {
			//画像
			setAgeAuthImg(response['img']);
			$('#ageAuthStr').val(response['str']);
			CKEDITOR.instances.ageAuthStr.setData(response['str']);
		}
		//リンク
		setLink(response['link']);
	});

	result.fail(function(response, textStatus, errorThrown) {
			console.debug('error at getAgeAuthValList:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}

function setExtList(extList) {

var imgExt = [];

	if(extList.length >= 1) {
		extS1 = extList.split(',');
		idxMax = extS1.length - 1;
		for(idx=0 ;idx<idxMax ;idx++) {
			extS2 = extS1[idx].split(':');
			imgExt[extS2[0]] = extS2[1];
		}
	}

	return imgExt;
}


/**
* ckEditorの設定
*
* @param
* @return
*/
function setCKEditAgeAuth() {

	CKEDITOR.replace('ageAuthStr' ,
		{
			height : 120
		}
	);


	CKEDITOR.instances.ageAuthStr.on("blur", function(e) {
		CKEDITOR.instances.ageAuthStr.updateElement();
		var str = $("#ageAuthStr").val();
		var msg;

		if(str.length >= 1) {
			msg = '';
		} else {
			msg = ERR_MSG;
		}
		$("#warnAgeAuthStr").html(msg);
	});
}


/**
* 年齢認証の画像の表示
*
* @param
* @return
*/
function setAgeAuthImg(imgVals) {

var pageVal   = imgVals['imgVal'];
var imgNoList = pageVal['value3'];
var existList = imgVals['fileExist'];

var imgNo     = '';
var fileExist = '0';

	//選択されている画像No
	if(imgNoList.length >= 1) {
		imgNo = imgNoList;
	}

	//画像ファイルの有無
	if(existList) {
		if(existList.length >= 1) {
			fileExist = existList;
		}
	}

	imgTag = '';
	if(fileExist == 1) {
		if(imgNo.length >= 1) {
			imgTag = '<img src="../img/1/AGE_AUTH/' + imgNo + '.' + EXT_LIST[imgNo] + '" class="img-responsive">';
		}
	}

	$('#ageAuthImgTN').html(imgTag);
	$("#ageAuthImg").val(imgNo);			//画像Noの保持
}


function setLink(linkVals) {

var linkMax = linkVals.length;
var idx;
var link1;

var no;
var siteName;
var url;
var imgUrl;

var tagStr;

	for(idx=0 ;idx<linkMax ;idx++) {
		link1 = linkVals[idx];
		siteName = link1['siteName'];
		if(siteName.length >= 1) {
			url    = link1['url'];
			imgUrl = link1['imgFile'];

			tagStr = setTDTagStr(EXIST_AA_LINK_SEQ ,siteName ,url ,imgUrl);
			tagStr = '<tr id="AALink' + EXIST_AA_LINK_SEQ + '">' + tagStr + '</tr>';
			$("#ageAuthLink").append(tagStr);
			EXIST_AA_LINK_SEQ++;
		}
	}
}


/**
* 新規リンク
*
* @param
* @return
*/
function newAgeAuthLink() {

var newLinkSeq = NEW_AA_LINK + NEW_AA_LINK_SEQ;

	EDIT_AA_LINK = 'NEW';

	// 新規の時はファイル選択
	$("input[name='ageAuthLinkImg']").val(["FILE"]);
	$("#attAALink").prop('disabled' ,false);
	$("#imgURLAA").prop('disabled' ,true);

	$("#siteNameAA").val('');
	$("#urlAA").val('');
	$("#imgURLAA").val('');

	$("#delAALinkBtn").hide();		// 削除ボタン非表示

	$("#editAgeAuthLink").val(newLinkSeq);
	$("#editAgeAuthLinkDlg").dialog( {
		title : '新規 '
	});
	$("#editAgeAuthLinkDlg").dialog("open");
}

/**
* リンク編集
*
* @param
* @return
*/
function editAgeAuthLink(editNo) {

	EDIT_AA_LINK = 'EDIT';

var siteNameAA = $("#siteNameAA" + editNo).html();
var urlAA      = $("#urlAA"      + editNo).html();
var imgURLAA   = $("#imgURLAA"   + editNo).val();
var imgNOAA    = $("#imgNOAA"    + editNo).val();

var imgURL4;
var anyFile = false;

console.debug('imgURLAA:' + imgURLAA);
console.debug('editNo:' + editNo);

	if(imgURLAA.length >= 1) {
		$("input[name='ageAuthLinkImg']").val(["URL"]);
		$("#attAALink").prop('disabled' ,true);
		$("#imgURLAA").prop('disabled' ,false);
		anyFile = true;
	}

	if(imgNOAA.length >= 1) {
		$("input[name='ageAuthLinkImg']").val(["FILE"]);
		$("#attAALink").prop('disabled' ,false);
		$("#imgURLAA").prop('disabled' ,true);
		anyFile = true;
	}

	// 画像ナシの時
	if(!anyFile) {
		$("input[name='ageAuthLinkImg']").val(["NOBANNER"]);
		$("#attAALink").prop('disabled' ,true);
		$("#imgURLAA").prop('disabled' ,true);
	}


	$("#siteNameAA").val(siteNameAA);
	$("#urlAA"     ).val(urlAA);
	$("#imgURLAA"  ).val(imgURLAA);

	$("#delAALinkBtn").show();		// 削除ボタン表示

	$("#editAgeAuthLink").val(editNo);
	$("#editAgeAuthLinkDlg").dialog( {
		title : '編集 '
	});
	$("#editAgeAuthLinkDlg").dialog("open");
}


function keepAgeAuthLink() {

var no       = $("#editAgeAuthLink").val();
var siteName = $("#siteNameAA").val();
var url      = $("#urlAA").val();

var imgURL;
var tagStr;
var targetTR = 'AALink' + no;

var sele = $("input[name='ageAuthLinkImg']:checked").val();

	if(sele == 'URL') {
		imgURL = $("#imgURLAA").val();
	} else {
		if(sele == 'FILE') {
			imgURL = $("#imgNOAA").val();
		} else {
			imgURL = '';
		}
	}
				//console.debug(no + ' ' + sele + ' ' + imgURL);
	tagStr = setTDTagStr(no ,siteName ,url ,imgURL);

	if(EDIT_AA_LINK == 'NEW') {
		tagStr = '<tr id="AALink' + no + '">' + tagStr + '</tr>';
		$("#ageAuthLink").append(tagStr);

		NEW_AA_LINK_SEQ++;
	} else {
		$("#" + targetTR).html(tagStr);
	}
}


function setTDTagStr(no ,siteName ,url ,imgURL) {

var ret;

var imgURL4;
var editBtn;

	if(imgURL.length >= 1) {
		imgURL4 = imgURL.substr(0 ,4);
		if(imgURL4 == 'http'
		|| imgURL4 == 'HTTP') {
			imgTag = '<img src="' + imgURL + '" class="img-responsive">' +
					'<input type="hidden" id="imgURLAA' + no + '" value="' + imgURL +  '">' +
					'<input type="hidden" id="imgNOAA' + no + '" value="">';
		} else {
			imgTag = '<img src="../img/1/AGE_AUTH/' + imgURL + '.' + EXT_LIST[imgURL] + '" class="img-responsive">' +
					'<input type="hidden" id="imgURLAA' + no + '" value="">' +
					'<input type="hidden" id="imgNOAA' + no + '" value="' + imgURL +  '">';
		}
	} else {
		imgTag = '画像ナシ' +
				'<input type="hidden" id="imgURLAA' + no + '" value="">' +
				'<input type="hidden" id="imgNOAA' + no + '" value="">';
	}

	editBtn = '<input type="button" value="編集" onclick="editAgeAuthLink(\'' + no + '\')">';

	ret = '<td id="siteNameAA' + no + '">' + siteName + '</td><td id="urlAA' + no + '">' + url + '</td><td id="imgTagAA' + no + '">' + imgTag + '</td><td>' + editBtn + '</td>';

	return ret;
}


function cfmDelAgeAuthLink() {

	$("#DelAALinkDlg").show();
}

function delAgeAuthLink() {

var editNo = $("#editAgeAuthLink").val();

var result = $.ajax({
		type : "post" ,
		url  : "../cgi2018/ajax/mtn/delAgeAuthLink.php" ,
		data : {
			branchNo : BRANCH_NO ,
			editNo   : editNo
		} ,
		cache    : false ,
//		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);

		if(response['SESSCOND'] == SESS_OWN_INTIME) {
			hideDelAgeAuthLink();
			selectWriteFile('AGE_AUTH');		//出力対象ファイルの抽出→ファイル出力
			$("#editAgeAuthLinkDlg").dialog("close");

			$("#ageAuthLink").empty();
			getAgeAuthValList('RESHOW');	// リンク領域の再表示
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
			console.debug('error at delAgeAuthLink:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}

function hideDelAgeAuthLink() {

	$("#DelAALinkDlg").hide();
}


/**
* 表示画像出力
*
* @param
* @return
*/
function updAgeAuthImg() {

var seleImg = $('#ageAuthImg').val();
var linkImg = getLinkImg();

	CKEDITOR.instances.ageAuthStr.updateElement();
var str = $('#ageAuthStr').val();

var sendData = '&branchNo=' + BRANCH_NO + '&img=' + seleImg + linkImg + '&str=' + str;

console.debug(sendData);


var result = $.ajax({
		type : "post" ,
		url  : "../cgi2018/ajax/mtn/writeAgeAuthVal.php" ,
		data : sendData ,
		cache    : false ,
		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);

		if(response['SESSCOND'] == SESS_OWN_INTIME) {
			selectWriteFile('AGE_AUTH');		//出力対象ファイルの抽出→ファイル出力
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
			console.debug('error at updAgeAuthImg:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}



function getLinkImg() {

var seq;

var seqSuffix;
var siteName;
var url;

var imgUrlVal;
var imgImgVal;
var img;

var siteNameParam;
var urlParam;
var imgParam;

var linkSeq = 0;
var ret = '';

	// 既存分の組み立て
	for(seq=1 ;seq<EXIST_AA_LINK_SEQ ;seq++) {
		siteName = $('#siteNameAA' + seq).html();
		url      = $('#urlAA'      + seq).html();

		imgImgVal = $('#imgURLAA' + seq).val();
		imgUrlVal = $('#imgNOAA'  + seq).val();

		if(imgImgVal.length >= 1) {
			img = imgImgVal;
		} else {
			img = imgUrlVal;
		}

		linkSeq++;
		siteNameParam = '&name' + linkSeq + '=' + siteName;
		urlParam      = '&url'  + linkSeq + '=' + url;
		imgParam      = '&img'  + linkSeq + '=' + img;

		ret = ret + siteNameParam + urlParam + imgParam;
	}

	// 新規分の組み立て
	for(seq=1 ;seq<NEW_AA_LINK_SEQ ;seq++) {
		seqSuffix = NEW_AA_LINK + seq;
		siteName = $('#siteNameAA' + seqSuffix).html();
		url      = $('#urlAA'      + seqSuffix).html();

		imgImgVal = $('#imgURLAA' + seqSuffix).val();
		imgUrlVal = $('#imgNOAA'  + seqSuffix).val();

		if(imgImgVal.length >= 1) {
			img = imgImgVal;
		} else {
			img = imgUrlVal;
		}

		linkSeq++;
		siteNameParam = '&name' + linkSeq + '=' + siteName;
		urlParam      = '&url'  + linkSeq + '=' + url;
		imgParam      = '&img'  + linkSeq + '=' + img;

		ret = ret + siteNameParam + urlParam + imgParam;
	}

	ret = ret + '&linkSeq=' + linkSeq;

	return ret;
}
