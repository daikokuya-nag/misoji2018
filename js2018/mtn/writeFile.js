/*************************
ファイル出力 Version 1.0
2016 Jan. 25 ver 1.0
*************************/

var ALL_OUT_FILES_PC;
var ALL_WROTE_FILES_PC;
var ALL_DONE_PC;
var FROM;
var PROF_DIR;
var OUT_ITEM;

/********************
出力対象ファイルの抽出
********************/
function selectWriteFile(outItem) {

	FROM     = 'other';
	OUT_ITEM = outItem;

var result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/selectWriteFile.php" ,
		data : {
			branchNo : BRANCH_NO ,
			outItem  : outItem
		} ,

		cache    : false ,
		dataType : 'json'
	});

	result.done(function(response) {
					console.debug(response);
		writeHTMLFile(response);
	});

	result.fail(function(response, textStatus, errorThrown) {
					console.debug('error at selectWriteFile:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}

/********************
プロファイル編集時の出力対象ファイルの抽出
********************/
function writeProfHTMLFile(profDir) {

	FROM     = 'profile';
	PROF_DIR = profDir;

var result = $.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/selectWriteFile.php" ,
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
		writeHTMLFile(response);
	});

	result.fail(function(response, textStatus, errorThrown) {
					console.debug('error at selectWriteFile:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}


/********************
HTMLファイルの出力
********************/
function writeHTMLFile(fileList) {

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

	ALL_OUT_FILES_PC   = idxMax + profMax;		//出力する全ファイル数
	ALL_WROTE_FILES_PC = 0;
	ALL_DONE_PC = false;

	if(ALL_OUT_FILES_PC >= 1) {		//出力するファイルがあるとき
		//紹介ページ以外の出力
		for(fileID in fileList) {
			fileName = fileList[fileID]
					//console.debug(fileID + ' ' + fileName);
			writeHTMLFileMain(fileID ,'');
		}

		//紹介ページの出力
		for(fileID in profList) {
			fileName = profList[fileID]
					//console.debug(fileID + ' ' + fileName);
			writeHTMLFileMain('PROFILE' ,fileID);
		}
	} else {						//出力するファイルがないときは何もせずMOへ
		if(FROM == 'profile') {
			writeProfHTMLFileMO(PROF_DIR);
		} else {
			selectWriteFileMO(OUT_ITEM);
		}
	}
}



/********************
HTMLファイルの出力の本体
********************/
function writeHTMLFileMain(fileID ,profName) {

var result = $.ajax({
		type : "post" ,
		url  : "../cgi2018/ajax/mtn/writeHTMLFile.php" ,
		data : {
			branchNo : BRANCH_NO ,
			device   : 'PC'      ,
			fileID   : fileID    ,
			profName : profName
		} ,

		cache : false
	});

	result.done(function(response) {
					console.debug(response);
	});

	result.fail(function(response, textStatus, errorThrown) {
					console.debug('error at writeHTMLFileMain:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
		ALL_WROTE_FILES_PC++;
		if(ALL_WROTE_FILES_PC >= ALL_OUT_FILES_PC) {
			if(!ALL_DONE_PC) {
				ALL_DONE_PC = true;

				if(FROM == 'profile') {
					writeProfHTMLFileMO(PROF_DIR);
				} else {
					selectWriteFileMO(OUT_ITEM);
				}
			}
		}
	});
}
