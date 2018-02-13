/**
* 求人
*
* @version 1.0.1
* @date 2018.1.17
*/

$(document).ready(function(){

});

$(window).load(function(){

	setCKEditRecruit();
	getRecruitVal();
});

/**
* 求人内容の読み込み
*
* @param
* @return
*/
function getRecruitVal() {

var result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getRecruitVal.php" ,
		data : {
			branchNo : BRANCH_NO
		} ,

		cache    : false ,
		dataType : 'json'
	});

	result.done(function(response) {
					//console.debug(response);

		setRecruitImg(response['img'] ,response['extList']);

		$('#recruitStr').val(response['str1']);
		CKEDITOR.instances.recruitStr.setData(response['str']);
	});

	result.fail(function(response, textStatus, errorThrown) {
					console.debug('error at getRecruitVal:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}

/**
* ckEditorの設定
*
* @param
* @return
*/
function setCKEditorRecruit() {

	CKEDITOR.replace('sideBarStr' ,
		{
			height : 120
		}
	);


	CKEDITOR.instances.recruitStr.on("blur", function(e) {
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
}

/**
* サイドバーの画像の表示
*
* @param
* @return
*/
function setRecruitImg(imgVals ,extList) {

var pageVal   = imgVals['pageVal'];
var useImg    = pageVal['value2'];
var imgNoList = pageVal['value3'];
var existList = imgVals['fileExist'];
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
			imgTag = '<img src="../img/1/RECRUIT/' + imgNo + '.' + imgExt[imgNo] + '" class="img-responsive">';
		}
	}

	$('#recruitImgTN').html(imgTag);
	$("#recruitImg").val(imgNo);			//画像Noの保持
}

/**
* 求人内容の出力
*
* @param
* @return
*/
function writeRecruitVal() {

	CKEDITOR.instances.recruitStr.updateElement();

var result;
var str     = $('#recruitStr').val();
var seleImg = $('#recruitImg').val();

	if(str.length >= 1) {
		result = $.ajax({
			type : "post" ,
			url  : "../cgi2018/ajax/mtn/writeRecruitVal.php" ,
			data : {
				branchNo : BRANCH_NO ,
				str      : str       ,
				img      : seleImg
			} ,

			cache    : false ,
			dataType : 'json'
		});

		result.done(function(response) {
						console.debug(response);
			if(response['SESSCOND'] == SESS_OWN_INTIME) {
				selectWriteFile('RECRUIT');		//出力対象ファイルの抽出→ファイル出力
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
						console.debug('error at writeRecruitVal:' + response.status + ' ' + textStatus);
		});

		result.always(function() {
		});
	}
}
