/*************************
プロファイル出力 Version 1.1
2016 Mar. 3  ver 1.0
*************************/

/********************
セッション状態の取得
********************/
function writeProf() {

var vals = setVals();
var result = $.ajax({
		type : "post" ,
		url  : "../cgi2018/ajax/mtn/writeProfile.php" ,

		dataType    : "text",
		data        : vals  ,
		processData : false ,
		contentType : false ,

		cache    : false  ,
		dataType : 'json' ,
	});

	result.done(function(response) {
					console.debug(response);

		if(response['SESSCOND'] == SESS_OWN_INTIME) {
//			bldProfHTML(response);			/* HTMLファイル出力 */
//			writeProfileNext();
		} else {
			alert('長時間操作がなかったため接続が切れました。ログインしなおしてください。');
			$("#editProfDlg").dialog("close");
			location.href = 'login.html';
		}
	});

	result.fail(function(result, textStatus, errorThrown) {
					console.debug('error at writeProfile:' + result.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}


function writeProfileNext() {

//var branchNo = $('#branchNo').val();
//var profListTag;
//
//	$.ajax({
//		type : "get" ,
//		url  : "cgi/ajax/bldProfList.php" ,
//		data : {
//			branchNo : branchNo
//		} ,
//
//		cache    : false ,
//		dataType : 'json' ,
//
//		success : function(result) {
//					console.debug(result);
//			profListTag = result;
//					//console.debug(ret['TITLE']);
//		} ,
//
//		error : function(result) {
//					console.debug('error at writeProfileNext:' + result);
//		} ,
//
//		complete  : function(result) {
//			writeProfileNext2(profListTag);
//		}
//	});
}


function writeProfileNext2(profListTag) {

				//alert(newTag['data']);
	/*** ニュース埋め込み用 ***/
	$("#profListD").html(profListTag['news']['data']);

	/*** 定型文埋め込み用 ***/
	$("#profListFPD").html(profListTag['news']['data']);

	/*** プロファイルリスト ***/
	$("#profSeqListD").html(profListTag['prof']['data']);

	$("#profSeqList").tableDnD({
		onDrop: function(table, row) {
							//profOrder = $.tableDnD.serialize();
							//		console.debug(profOrder);
							//enableWriteProfSeq();
		}
	});

	$(".dispProfSW").toggleSwitch();

	$("#profSeqList").tableDnD({
		onDrop: function(table, row) {
			profOrder = $.tableDnD.serialize();
					console.debug(profOrder);
			enableWriteProfSeq();
		}
	});

	var newProf  = $('#newProf').val();
			//imgFileUpload();
	if(newProf == 'edit') {
		dlgStr = 'プロファイルの更新完了';
	} else {
		dlgStr = 'プロファイルの新規登録完了';
	}
	alert(dlgStr);


	$("#attF1").replaceWith('<input type="file" name="attF1" id="attF1">');
	$("#attF2").replaceWith('<input type="file" name="attF2" id="attF2">');
	$("#attF3").replaceWith('<input type="file" name="attF3" id="attF3">');
	$("#attF4").replaceWith('<input type="file" name="attF4" id="attF4">');
	$("#attF5").replaceWith('<input type="file" name="attF5" id="attF5">');

	$("#attTN").replaceWith('<input type="file" name="attTN" id="attTN">');
	$("#attML").replaceWith('<input type="file" name="attML" id="attML">');


	$("#editProfDlg").dialog("close");
}


/********************************************/
function setVals() {

// FormData オブジェクトを作成
var fd = new FormData();

var mastComment = CKEDITOR.instances.mastComment.getData();
var appComment  = CKEDITOR.instances.appComment.getData();

	fd.append("branchNo" ,$('#branchNo').val());
	fd.append("newProf"  ,$('#newProf').val());
	fd.append("profDir"  ,$('#profDir' ).val());
	fd.append("profName" ,$('#profName').val());

	fd.append("profBirthDate" ,$('#profBirthDate').val());
	fd.append("profZodiac"    ,$('#profZodiac'   ).val());
	fd.append("profBloodType" ,$('#profBloodType').val());

	fd.append("profAge"    ,$('#profAge'   ).val());
	fd.append("profHeight" ,$('#profHeight').val());
	fd.append("profSize"   ,$('#profSize'  ).val());

	fd.append("mastComment" ,mastComment);
	fd.append("appComment"  ,appComment);

	fd.append("profPCode" ,$('#profPCode').val());

var newFace = $("#newFace:checked").val();
	if(newFace != 'N') {
		newFace = '';
	}
			console.debug('新人' + newFace);
	fd.append("newFace"  ,newFace);


/***** 写真使用 *****/
var useP;

	useP = setPhotoUse('#useP1');
	fd.append("useP1" ,useP);
	useP = setPhotoUse('#useP2');
	fd.append("useP2" ,useP);
	useP = setPhotoUse('#useP3');
	fd.append("useP3" ,useP);
	useP = setPhotoUse('#useP4');
	fd.append("useP4" ,useP);
	useP = setPhotoUse('#useP5');
	fd.append("useP5" ,useP);

	useP = setPhotoUse('#useTN');
	fd.append("useTN" ,useP);
	useP = setPhotoUse('#useML');
	fd.append("useML" ,useP);


	/*** 写真表示 ***/
var photoShow = $('input[name="photoUSE"]:checked').val();
				//console.debug('photoShow:' + photoShow);
	fd.append("photoShow" ,photoShow);

	/***** 写真ファイル *****/
	if($("#attF1").val() !== '') {
		fd.append("attF1", $("#attF1").prop("files")[0]);
	}
	if($("#attF2").val() !== '') {
		fd.append("attF2", $("#attF2").prop("files")[0]);
	}
	if($("#attF3").val() !== '') {
		fd.append("attF3", $("#attF3").prop("files")[0]);
	}
	if($("#attF4").val() !== '') {
		fd.append("attF4", $("#attF4").prop("files")[0]);
	}
	if($("#attF5").val() !== '') {
		fd.append("attF5", $("#attF5").prop("files")[0]);
	}

	if($("#attTN").val() !== '') {
		fd.append("attTN", $("#attTN").prop("files")[0]);
	}
//	if($("#attML").val() !== '') {
//		fd.append("attML", $("#attML").prop("files")[0]);
//	}

	/***** 週間出勤表 *****/
var idF;

			/***** デフォルト分 *****/
	idF = '#workDef';

	fd.append("sunF" ,$(idF + '0F').val());
	fd.append("sunT" ,$(idF + '0T').val());
	fd.append("sunM" ,$("input[name='workSele0']:checked").val());

	fd.append("monF" ,$(idF + '1F').val());
	fd.append("monT" ,$(idF + '1T').val());
	fd.append("monM" ,$("input[name='workSele1']:checked").val());

	fd.append("tueF" ,$(idF + '2F').val());
	fd.append("tueT" ,$(idF + '2T').val());
	fd.append("tueM" ,$("input[name='workSele2']:checked").val());

	fd.append("wedF" ,$(idF + '3F').val());
	fd.append("wedT" ,$(idF + '3T').val());
	fd.append("wedM" ,$("input[name='workSele3']:checked").val());

	fd.append("thuF" ,$(idF + '4F').val());
	fd.append("thuT" ,$(idF + '4T').val());
	fd.append("thuM" ,$("input[name='workSele4']:checked").val());

	fd.append("friF" ,$(idF + '5F').val());
	fd.append("friT" ,$(idF + '5T').val());
	fd.append("friM" ,$("input[name='workSele5']:checked").val());

	fd.append("satF" ,$(idF + '6F').val());
	fd.append("satT" ,$(idF + '6T').val());
	fd.append("satM" ,$("input[name='workSele6']:checked").val());

			/***** 予定外 *****/
	idF = '#workDiff';

	fd.append("diff1F" ,$(idF + '0F').val());
	fd.append("diff1T" ,$(idF + '0T').val());
	fd.append("diff1M" ,$("input[name='workDiff0']:checked").val());

	fd.append("diff2F" ,$(idF + '1F').val());
	fd.append("diff2T" ,$(idF + '1T').val());
	fd.append("diff2M" ,$("input[name='workDiff1']:checked").val());

	fd.append("diff3F" ,$(idF + '2F').val());
	fd.append("diff3T" ,$(idF + '2T').val());
	fd.append("diff3M" ,$("input[name='workDiff2']:checked").val());

	fd.append("diff4F" ,$(idF + '3F').val());
	fd.append("diff4T" ,$(idF + '3T').val());
	fd.append("diff4M" ,$("input[name='workDiff3']:checked").val());

	fd.append("diff5F" ,$(idF + '4F').val());
	fd.append("diff5T" ,$(idF + '4T').val());
	fd.append("diff5M" ,$("input[name='workDiff4']:checked").val());

	fd.append("diff6F" ,$(idF + '5F').val());
	fd.append("diff6T" ,$(idF + '5T').val());
	fd.append("diff6M" ,$("input[name='workDiff5']:checked").val());

	fd.append("diff7F" ,$(idF + '6F').val());
	fd.append("diff7T" ,$(idF + '6T').val());
	fd.append("diff7M" ,$("input[name='workDiff6']:checked").val());


/*** 予定外の日付 ***/
	idF = '#dateList';

			/***** 差異分 *****/
	fd.append("diffDate1" ,$(idF + '0').val());
	fd.append("diffDate2" ,$(idF + '1').val());
	fd.append("diffDate3" ,$(idF + '2').val());
	fd.append("diffDate4" ,$(idF + '3').val());
	fd.append("diffDate5" ,$(idF + '4').val());
	fd.append("diffDate6" ,$(idF + '5').val());
	fd.append("diffDate7" ,$(idF + '6').val());


	/*** QA ***/
	fd.append("qa1"  ,$('#qa1').val());
	fd.append("qa2"  ,$('#qa2').val());
	fd.append("qa3"  ,$('#qa3').val());
	fd.append("qa4"  ,$('#qa4').val());
	fd.append("qa5"  ,$('#qa5').val());
	fd.append("qa6"  ,$('#qa6').val());
	fd.append("qa7"  ,$('#qa7').val());
	fd.append("qa8"  ,$('#qa8').val());
	fd.append("qa9"  ,$('#qa9').val());
	fd.append("qa10" ,$('#qa10').val());
	fd.append("qa11" ,$('#qa11').val());
	fd.append("qa12" ,$('#qa12').val());
	fd.append("qa13" ,$('#qa13').val());
	fd.append("qa14" ,$('#qa14').val());


var bDataVal;

	bDataVal = $('input[name="profBA"]:checked').val();
	if(bDataVal != null) {
		fd.append("ba" ,bDataVal);
	}

	bDataVal = $('input[name="profBB"]:checked').val();
	if(bDataVal != null) {
		fd.append("bb" ,bDataVal);
	}

	bDataVal = $('input[name="profBC"]:checked').val();
	if(bDataVal != null) {
		fd.append("bc" ,bDataVal);
	}

	return fd;
}


function setPhotoUse(photoID) {

var ret = '';

	if($(photoID).prop('checked')) {
		ret = 'U';	/* 写真使用 */
	} else {
		ret = 'N';	/* 写真非使用 */
	}

	return ret;
}
