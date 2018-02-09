/**
* サイドバー編集
*
* @version 1.0.1
* @date 2018.1.24
*/

$(document).ready(function(){
});


$(window).load(function(){

	setCKEditSideBar();
	getSideBarValList();	// 現在のサイドバーで使用する画像の読み込み
});

/**
* 現在のサイドバーの画像の読み込み
*
* @param
* @return
*/
function getSideBarValList() {

var result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/getSideBarVal.php" ,
		data : {
			branchNo : BRANCH_NO
		} ,

		cache    : false ,
		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);

		setSideBarImg(response['img1'] ,response['extList'] ,1);
		setSideBarImg(response['img2'] ,response['extList'] ,2);

		$('#sideBarStr1').val(response['str1']);
		$('#sideBarStr2').val(response['str2']);

		CKEDITOR.instances.sideBarStr1.setData(response['str1']);
		CKEDITOR.instances.sideBarStr2.setData(response['str2']);

		$(".useSideBarImg").toggleSwitch();
	});

	result.fail(function(response, textStatus, errorThrown) {
			console.debug('error at getSideBarValList:' + response.status + ' ' + textStatus);
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
function setCKEditSideBar() {

	CKEDITOR.replace('sideBarStr1' ,
		{
			height : 120
		}
	);

	CKEDITOR.replace('sideBarStr2' ,
		{
			height : 120
		}
	);


	CKEDITOR.instances.sideBarStr1.on("blur", function(e) {
		CKEDITOR.instances.sideBarStr1.updateElement();
		var str = $("#sideBarStr1").val();
		var msg;

		if(str.length >= 1) {
			msg = '';
		} else {
			msg = ERR_MSG;
		}
		$("#warnSideBarStr1").html(msg);
	});

	CKEDITOR.instances.sideBarStr2.on("blur", function(e) {
		CKEDITOR.instances.sideBarStr2.updateElement();
		var str = $("#sideBarStr2").val();
		var msg;

		if(str.length >= 1) {
			msg = '';
		} else {
			msg = ERR_MSG;
		}
		$("#warnSideBarStr2").html(msg);
	});

}

/**
* サイドバーの画像の表示
*
* @param
* @return
*/
function setSideBarImg(imgVals ,extList ,imgID) {

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
			imgTag = '<img src="../img/1/SIDEBAR/' + imgNo + '.' + imgExt[imgNo] + '" class="img-responsive">';
		}
	}

	if(useImg == 'U') {
		useChecked = true;
	} else {
		useChecked = false;
	}

	if(imgID == 1) {
		$('#sideBarImgTN1').html(imgTag);
		$("#sideBarImg1").val(imgNo);			//画像Noの保持
		$('#useSideBarImg1').prop('checked' ,useChecked);
	}
	if(imgID == 2) {
		$('#sideBarImgTN2').html(imgTag);
		$("#sideBarImg2").val(imgNo);			//画像Noの保持
		$('#useSideBarImg2').prop('checked' ,useChecked);
	}
}

/**
* 表示画像出力
*
* @param
* @return
*/
function updSideBarImg() {

	CKEDITOR.instances.sideBarStr1.updateElement();
	CKEDITOR.instances.sideBarStr2.updateElement();

var seleImg1 = $('#sideBarImg1').val();
var seleImg2 = $('#sideBarImg2').val();

var str1 = $('#sideBarStr1').val();
var str2 = $('#sideBarStr2').val();

var dispSW   = $(".useSideBarImg").serialize();
var sendData = dispSW + '&branchNo=' + BRANCH_NO + '&img1=' + seleImg1 + '&img2=' + seleImg2 + '&str1=' + str1 + '&str2=' + str2;

console.debug(sendData);

var result = $.ajax({
		type : "post" ,
		url  : "../cgi2018/ajax/mtn/writeSideBarVal.php" ,
		data : sendData ,
		cache    : false ,
		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);

		if(response['SESSCOND'] == SESS_OWN_INTIME) {
			selectWriteFile('TOP');		//出力対象ファイルの抽出→ファイル出力
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
			console.debug('error at updTopImg:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}
