/*************************
ファイル出力 Version 1.0
2016 Jan. 25 ver 1.0
*************************/

var ALL_OUT_FILES;
var ALL_WROTE_FILES;
var ALL_DONE;

/********************
出力対象ファイルの抽出
********************/
function selectWriteFile(outItem) {

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
出力対象ファイルの抽出
********************/
function writeProfHTMLFile(profDir) {

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

	ALL_OUT_FILES   = idxMax + profMax;		//出力する全ファイル数
	ALL_WROTE_FILES = 0;
	ALL_DONE = false;

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
		ALL_WROTE_FILES++;
		if(ALL_WROTE_FILES >= ALL_OUT_FILES) {
			if(!ALL_DONE) {
				ALL_DONE = true;
				jAlert(
					'ファイル出力完了' ,
					'メンテナンス'
				);
			}
		}
	});
}
