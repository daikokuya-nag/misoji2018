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

//var vals = setVals();
var vals = setValsFromIFrame();

console.debug(vals);

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
						//var profDir = $('#profDir').val();
			var docForm = editProfile.document.enterProfile;
			var profDir = docForm.profDir.value;

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

	// 写真ファイル
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
	fd.append("diff1M" ,$("input[name='workDiff0M']:checked").val());

	fd.append("diff2F" ,$('#workDiff1F').val());
	fd.append("diff2T" ,$('#workDiff1T').val());
	fd.append("diff2M" ,$("input[name='workDiff1M']:checked").val());

	fd.append("diff3F" ,$('#workDiff2F').val());
	fd.append("diff3T" ,$('#workDiff2T').val());
	fd.append("diff3M" ,$("input[name='workDiff2M']:checked").val());

	fd.append("diff4F" ,$('#workDiff3F').val());
	fd.append("diff4T" ,$('#workDiff3T').val());
	fd.append("diff4M" ,$("input[name='workDiff3M']:checked").val());

	fd.append("diff5F" ,$('#workDiff4F').val());
	fd.append("diff5T" ,$('#workDiff4T').val());
	fd.append("diff5M" ,$("input[name='workDiff4M']:checked").val());

	fd.append("diff6F" ,$('#workDiff5F').val());
	fd.append("diff6T" ,$('#workDiff5T').val());
	fd.append("diff6M" ,$("input[name='workDiff5M']:checked").val());

	fd.append("diff7F" ,$('#workDiff6F').val());
	fd.append("diff7T" ,$('#workDiff6T').val());
	fd.append("diff7M" ,$("input[name='workDiff6M']:checked").val());

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


function setValsFromIFrame() {

var docForm = editProfile.document.enterProfile;

document.getElementById('editProfile').contentWindow.updCkEditor();

var fd = new FormData();

	fd.append("branchNo" ,BRANCH_NO);
	fd.append("newProf"  ,docForm.newProf.value);
	fd.append("profDir"  ,docForm.profDir.value);
	fd.append("profName" ,docForm.profName.value);

	fd.append("profBirthDate" ,docForm.profBirthDate.value);
	fd.append("profZodiac"    ,docForm.profZodiac.value);
	fd.append("profBloodType" ,docForm.profBloodType.value);

	fd.append("profAge"     ,docForm.profAge.value);
	fd.append("prof1Phrase" ,docForm.prof1Phrase.value);
	fd.append("profHeight"  ,docForm.profHeight.value);
	fd.append("profSize"    ,docForm.profSize.value);

	fd.append("mastComment" ,docForm.mastComment.value);
	fd.append("appComment"  ,docForm.appComment.value);

	fd.append("profPCode" ,docForm.profPCode.value);

	if(docForm.newFace.checked) {
		newFace = 'N';
	} else {
		newFace = '';
	}
			console.debug('新人' + newFace);
	fd.append("newFace"  ,newFace);

var useP;	// 写真使用
	useP = setPhotoUseIFrame(docForm.useP1);
	fd.append("useP1" ,useP);
	useP = setPhotoUseIFrame(docForm.useP2);
	fd.append("useP2" ,useP);
	useP = setPhotoUseIFrame(docForm.useP3);
	fd.append("useP3" ,useP);
	useP = setPhotoUseIFrame(docForm.useP4);
	fd.append("useP4" ,useP);
	useP = setPhotoUseIFrame(docForm.useP5);
	fd.append("useP5" ,useP);

	useP = setPhotoUseIFrame(docForm.useTN);
	fd.append("useTN" ,useP);
	useP = setPhotoUseIFrame(docForm.useML);
	fd.append("useML" ,useP);

	// 写真表示
	fd.append("photoShow" ,docForm.photoUSE.value);

	// 写真ファイル
	if(docForm.attF1.value !== '') {
		fd.append("attF1", docForm.attF1.files[0]);
	}
	if(docForm.attF2.value !== '') {
		fd.append("attF2", docForm.attF2.files[0]);
	}
	if(docForm.attF3.value !== '') {
		fd.append("attF3", docForm.attF3.files[0]);
	}
	if(docForm.attF4.value !== '') {
		fd.append("attF4", docForm.attF4.files[0]);
	}
	if(docForm.attF5.value !== '') {
		fd.append("attF5", docForm.attF5.files[0]);
	}

	if(docForm.attTN.value !== '') {
		fd.append("attTN", docForm.attTN.files[0]);
	}
//	if(docForm.attML.value !== '') {
//		fd.append("attML", docForm.attML.files[0]);
//	}

	// 週間出勤表
var workSele;
		// デフォルト分
	fd.append("sunF" ,docForm.workDef0F.value);
	fd.append("sunT" ,docForm.workDef0T.value);
	workSele = setRadioVal(docForm.workSele0);
	fd.append("sunM" ,workSele);

	fd.append("monF" ,docForm.workDef1F.value);
	fd.append("monT" ,docForm.workDef1T.value);
	workSele = setRadioVal(docForm.workSele1);
	fd.append("monM" ,workSele);

	fd.append("tueF" ,docForm.workDef2F.value);
	fd.append("tueT" ,docForm.workDef2T.value);
	workSele = setRadioVal(docForm.workSele2);
	fd.append("tueM" ,workSele);

	fd.append("wedF" ,docForm.workDef3F.value);
	fd.append("wedT" ,docForm.workDef3T.value);
	workSele = setRadioVal(docForm.workSele3);
	fd.append("wedM" ,workSele);

	fd.append("thuF" ,docForm.workDef4F.value);
	fd.append("thuT" ,docForm.workDef4T.value);
	workSele = setRadioVal(docForm.workSele4);
	fd.append("thuM" ,workSele);

	fd.append("friF" ,docForm.workDef5F.value);
	fd.append("friT" ,docForm.workDef5T.value);
	workSele = setRadioVal(docForm.workSele5);
	fd.append("friM" ,workSele);

	fd.append("satF" ,docForm.workDef6F.value);
	fd.append("satT" ,docForm.workDef6T.value);
	workSele = setRadioVal(docForm.workSele6);
	fd.append("satM" ,workSele);

		// 予定外
	fd.append("diff1F" ,docForm.workDiff0F.value);
	fd.append("diff1T" ,docForm.workDiff0T.value);
	workSele = setRadioVal(docForm.workDiff0M);
	fd.append("diff1M" ,workSele);

	fd.append("diff2F" ,docForm.workDiff1F.value);
	fd.append("diff2T" ,docForm.workDiff1T.value);
	workSele = setRadioVal(docForm.workDiff1M);
	fd.append("diff2M" ,workSele);

	fd.append("diff3F" ,docForm.workDiff2F.value);
	fd.append("diff3T" ,docForm.workDiff2T.value);
	workSele = setRadioVal(docForm.workDiff2M);
	fd.append("diff3M" ,workSele);

	fd.append("diff4F" ,docForm.workDiff3F.value);
	fd.append("diff4T" ,docForm.workDiff3T.value);
	workSele = setRadioVal(docForm.workDiff3M);
	fd.append("diff4M" ,workSele);

	fd.append("diff5F" ,docForm.workDiff4F.value);
	fd.append("diff5T" ,docForm.workDiff4T.value);
	workSele = setRadioVal(docForm.workDiff4M);
	fd.append("diff5M" ,workSele);

	fd.append("diff6F" ,docForm.workDiff5F.value);
	fd.append("diff6T" ,docForm.workDiff5T.value);
	workSele = setRadioVal(docForm.workDiff5M);
	fd.append("diff6M" ,workSele);

	fd.append("diff7F" ,docForm.workDiff6F.value);
	fd.append("diff7T" ,docForm.workDiff6T.value);
	workSele = setRadioVal(docForm.workDiff6M);
	fd.append("diff7M" ,workSele);

		// 予定外の日付
	fd.append("diffDate1" ,docForm.dateList0.value);
	fd.append("diffDate2" ,docForm.dateList1.value);
	fd.append("diffDate3" ,docForm.dateList2.value);
	fd.append("diffDate4" ,docForm.dateList3.value);
	fd.append("diffDate5" ,docForm.dateList4.value);
	fd.append("diffDate6" ,docForm.dateList5.value);
	fd.append("diffDate7" ,docForm.dateList6.value);

	// QA
	fd.append("qa1"  ,docForm.qa1.value);
	fd.append("qa2"  ,docForm.qa2.value);
	fd.append("qa3"  ,docForm.qa3.value);
	fd.append("qa4"  ,docForm.qa4.value);
	fd.append("qa5"  ,docForm.qa5.value);
	fd.append("qa6"  ,docForm.qa6.value);
	fd.append("qa7"  ,docForm.qa7.value);
	fd.append("qa8"  ,docForm.qa8.value);
	fd.append("qa9"  ,docForm.qa9.value);
	fd.append("qa10" ,docForm.qa10.value);
	fd.append("qa11" ,docForm.qa11.value);
	fd.append("qa12" ,docForm.qa12.value);
	fd.append("qa13" ,docForm.qa13.value);
	fd.append("qa14" ,docForm.qa14.value);

/*
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
*/
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


/**
* 写真使用/非使用の設定
*
* @param
* @return
*/
function setPhotoUseIFrame(photoUse) {

var ret = '';

	if(photoUse.checked) {
		ret = 'U';	// 写真使用
	} else {
		ret = 'N';	// 写真非使用
	}

	return ret;
}



function setRadioVal(radioNode) {

var sele = radioNode.value;
var ret = '';

	if(sele === '') {
	} else {
		ret = sele;
	}

	return ret;
}
