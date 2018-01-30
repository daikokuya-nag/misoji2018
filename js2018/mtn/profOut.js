/**
* プロファイル出力
*
* @version 1.0.1
* @date 2018.1.19
*/

/**
* 出力
*
* @param
* @return
*/
function writeProf() {

var vals = setVals();
var result = $.ajax({
		type : "post" ,
		url  : "../cgi2018/ajax/mtn/writeProfile.php" ,

		dataType    : "text" ,
		data        : vals   ,
		processData : false  ,
		contentType : false  ,

		cache    : false  ,
		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);

		if(response['SESSCOND'] == SESS_OWN_INTIME) {
			var profDir = $('#profDir').val();

			writeProfHTMLFile(profDir);
			$("#editProfDlg").dialog("close");
			getProfileList();	// プロファイルリスト再表示
		} else {	// セッションタイムアウトの時
			jAlert(
				TIMEOUT_MSG_STR ,
				TIMEOUT_MSG_TITLE ,
				function() {
					$("#editProfDlg").dialog("close");
					location.href = 'login.html';
				}
			);
		}
	});

	result.fail(function(response, textStatus, errorThrown) {
					console.debug('error at writeProf:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}

/**
* 出力データの編集
*
* @param
* @return
*/
function setVals() {

var fd = new FormData();

var mastComment = CKEDITOR.instances.mastComment.getData();
var appComment  = CKEDITOR.instances.appComment.getData();

	fd.append("branchNo" ,BRANCH_NO);
	fd.append("newProf"  ,$('#newProf').val());
	fd.append("profDir"  ,$('#profDir' ).val());
	fd.append("profName" ,$('#profName').val());

	fd.append("profBirthDate" ,$('#profBirthDate').val());
	fd.append("profZodiac"    ,$('#profZodiac'   ).val());
	fd.append("profBloodType" ,$('#profBloodType').val());

	fd.append("profAge"     ,$('#profAge'    ).val());
	fd.append("prof1Phrase" ,$('#prof1Phrase').val());
	fd.append("profHeight"  ,$('#profHeight' ).val());
	fd.append("profSize"    ,$('#profSize'   ).val());

	fd.append("mastComment" ,mastComment);
	fd.append("appComment"  ,appComment);

	fd.append("profPCode" ,$('#profPCode').val());

var newFace = $("#newFace:checked").val();
	if(newFace != 'N') {
		newFace = '';
	}
			console.debug('新人' + newFace);
	fd.append("newFace"  ,newFace);

var useP;	// 写真使用
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

var photoShow = $('input[name="photoUSE"]:checked').val();	// 写真表示
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

	// 週間出勤表

		// デフォルト分
	fd.append("sunF" ,$('#workDef0F').val());
	fd.append("sunT" ,$('#workDef0T').val());
	fd.append("sunM" ,$("input[name='workSele0']:checked").val());

	fd.append("monF" ,$('#workDef1F').val());
	fd.append("monT" ,$('#workDef1T').val());
	fd.append("monM" ,$("input[name='workSele1']:checked").val());

	fd.append("tueF" ,$('#workDef2F').val());
	fd.append("tueT" ,$('#workDef2T').val());
	fd.append("tueM" ,$("input[name='workSele2']:checked").val());

	fd.append("wedF" ,$('#workDef3F').val());
	fd.append("wedT" ,$('#workDef3T').val());
	fd.append("wedM" ,$("input[name='workSele3']:checked").val());

	fd.append("thuF" ,$('#workDef4F').val());
	fd.append("thuT" ,$('#workDef4T').val());
	fd.append("thuM" ,$("input[name='workSele4']:checked").val());

	fd.append("friF" ,$('#workDef5F').val());
	fd.append("friT" ,$('#workDef5T').val());
	fd.append("friM" ,$("input[name='workSele5']:checked").val());

	fd.append("satF" ,$('#workDef6F').val());
	fd.append("satT" ,$('#workDef6T').val());
	fd.append("satM" ,$("input[name='workSele6']:checked").val());

		// 予定外
	fd.append("diff1F" ,$('#workDiff0F').val());
	fd.append("diff1T" ,$('#workDiff0T').val());
	fd.append("diff1M" ,$("input[name='workDiff0']:checked").val());

	fd.append("diff2F" ,$('#workDiff1F').val());
	fd.append("diff2T" ,$('#workDiff1T').val());
	fd.append("diff2M" ,$("input[name='workDiff1']:checked").val());

	fd.append("diff3F" ,$('#workDiff2F').val());
	fd.append("diff3T" ,$('#workDiff2T').val());
	fd.append("diff3M" ,$("input[name='workDiff2']:checked").val());

	fd.append("diff4F" ,$('#workDiff3F').val());
	fd.append("diff4T" ,$('#workDiff3T').val());
	fd.append("diff4M" ,$("input[name='workDiff3']:checked").val());

	fd.append("diff5F" ,$('#workDiff4F').val());
	fd.append("diff5T" ,$('#workDiff4T').val());
	fd.append("diff5M" ,$("input[name='workDiff4']:checked").val());

	fd.append("diff6F" ,$('#workDiff5F').val());
	fd.append("diff6T" ,$('#workDiff5T').val());
	fd.append("diff6M" ,$("input[name='workDiff5']:checked").val());

	fd.append("diff7F" ,$('#workDiff6F').val());
	fd.append("diff7T" ,$('#workDiff6T').val());
	fd.append("diff7M" ,$("input[name='workDiff6']:checked").val());

		// 予定外の日付
	fd.append("diffDate1" ,$('#dateList0').val());
	fd.append("diffDate2" ,$('#dateList1').val());
	fd.append("diffDate3" ,$('#dateList2').val());
	fd.append("diffDate4" ,$('#dateList3').val());
	fd.append("diffDate5" ,$('#dateList4').val());
	fd.append("diffDate6" ,$('#dateList5').val());
	fd.append("diffDate7" ,$('#dateList6').val());

	// QA
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


/**
* 写真使用/非使用の設定
*
* @param
* @return
*/
function setPhotoUse(photoID) {

var ret = '';

	if($(photoID).prop('checked')) {
		ret = 'U';	// 写真使用
	} else {
		ret = 'N';	// 写真非使用
	}

	return ret;
}
