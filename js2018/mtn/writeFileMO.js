/**
* 携帯用ファイル出力
*
* @version 1.0.1
* @date 2018.1.17
*/

var ALL_OUT_FILES_MO;		// 出力するファイル数
var ALL_WROTE_FILES_MO;		// 出力したファイル数
var ALL_DONE_MO;			// PC用のファイルをすべて出力したか

/**
* 出力対象ファイルの抽出
*
* @param
* @return
*/
function selectWriteFileMO(outItem) {

var result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/selectWriteFileMO.php" ,
		data : {
			branchNo : BRANCH_NO ,
			outItem  : outItem
		} ,

		cache    : false ,
		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);
		writeHTMLFileMO(response);
	});

	result.fail(function(response, textStatus, errorThrown) {
					console.debug('error at selectWriteFileMO:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}

/**
* プロファイル編集時の出力対象ファイルの抽出
*
* @param
* @return
*/
function writeProfHTMLFileMO(profDir) {

console.debug(profDir);

var result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/selectWriteFileMO.php" ,
		data : {
			branchNo : BRANCH_NO ,
			outItem  : 'ALBUM'   ,
			profDir  : profDir
		} ,

		cache    : false ,
		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);
		writeHTMLFileMO(response);
	});

	result.fail(function(response, textStatus, errorThrown) {
					console.debug('error at selectWriteFileMO:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}

/**
* HTMLファイルの出力
*
* @param
* @return
*/
function writeHTMLFileMO(fileList) {

var fileID;
var fileName;
var idxMax = Object.keys(fileList).length;
var profList;
var profMax = 0;


	if(fileList['PROFILE']) {
				console.debug('紹介リストあり');
		idxMax--;
		profList = fileList['PROFILE'];
		profMax  = Object.keys(profList).length;
	}

	ALL_OUT_FILES_MO   = idxMax + profMax;		//出力する全ファイル数
	ALL_WROTE_FILES_MO = 0;
	ALL_DONE_MO = false;

				//console.debug('全ファイル数:' + ALL_OUT_FILES_MO);

	if(ALL_OUT_FILES_MO >= 1) {		//出力するファイルがあるとき
		//紹介ページ以外の出力
		for(fileID in fileList) {
			fileName = fileList[fileID]
					//console.debug(fileID + ' ' + fileName);
			writeHTMLFileMOMain(fileID ,'');
		}

		//紹介ページの出力
		for(fileID in profList) {
			fileName = profList[fileID]
					//console.debug(fileID + ' ' + fileName);
			writeHTMLFileMOMain('PROFILE' ,fileID);
		}
	} else {
		dispDoneMsg();
	}
}

/**
* HTMLファイルの出力の本体
*
* @param
* @return
*/
function writeHTMLFileMOMain(fileID ,profName) {

var result = $.ajax({
		type : "post" ,
		url  : "../cgi2018/ajax/mtn/writeHTMLFile.php" ,
		data : {
			branchNo : BRANCH_NO ,
			device   : 'MO'      ,
			fileID   : fileID    ,
			profName : profName
		} ,

		cache : false
	});

	result.done(function(response) {
					console.debug(response);
	});

	result.fail(function(response, textStatus, errorThrown) {
					console.debug('error at writeHTMLFileMOMain:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
		ALL_WROTE_FILES_MO++;
		if(ALL_WROTE_FILES_MO >= ALL_OUT_FILES_MO) {
			if(!ALL_DONE_MO) {
				ALL_DONE_MO = true;
				dispDoneMsg();
			}
		}
	});
}

/**
* 出力完了メッセージ表示
*
* @param
* @return
*/
function dispDoneMsg() {

	jAlert(
		'ファイル出力完了' ,
		'メンテナンス'
	);
}
