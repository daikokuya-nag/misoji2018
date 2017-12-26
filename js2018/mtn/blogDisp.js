/*************************
ブログ出力可否 Version 1.0
2016 June 07 ver 1.0
*************************/

/********************
セッション状態の取得
********************/
function updBlogDisp() {

var sess;
var goLogin = 0;

	$.ajax({
		type  :"get" ,
		url   :"cgi/ajax/getSess.php" ,
		cache :false ,

		complete :function(result) {
			writeBlogDispPreA(sess);
		} ,

		success :function(result) {
					console.debug(result);
			sess = result;
		} ,

		error :function(result) {
					console.debug('error at writeNewsPre:' + result);
		}
	});
}


/********************
セッション情報の更新
********************/
function writeBlogDispPreA(sess) {

var ret;

	if(sess == SESS_OWN_INTIME) {
		$.ajax({
			type  :"get" ,
			url   :"cgi/ajax/updSess.php" ,
			cache :false ,

			complete :function(result) {
				updBlogDispMain();
			} ,

			success :function(result) {
						console.debug(result);
				ret = result;
			} ,

			error :function(result) {
						console.debug('error at writeNewsPreA:' + result);
			}
		});
	} else {
		alert('長時間操作がなかったため接続が切れました。ログインしなおしてください。');
		$("#editBlog").dialog("close");
		location.href = 'login.html';
	}
}



function updBlogDispMain() {

var dispCnt = 0;

var dataList;
var listMax;
var idx;
var listVar;
var idPrefix;
var idxList;
var swList = '';

	listMax  = blogNoList30.length;
	listVar  = blogNoList30;
	idPrefix = '#blog';	//'#newsAll';


	for(idx=0 ;idx<listMax ;idx++) {
		blogNo = listVar[idx];

		currCond = $(idPrefix + blogNo).prop('checked');
		if(currCond) {
			/* ON */
			swList = swList + blogNo + '=U&';
		} else {
			/* OFF */
			swList = swList + blogNo + '=N&';
		}
	}

	dataList = swList + $('input[name=groupNo] ,input[name=branchNo]').serialize();

console.debug(dataList);

var ret;

	$.ajax({
		type  :"post" ,
		url   :"cgi/ajax/writeBlogDisp.php" ,
		data  : dataList ,

		cache :false ,
									//		dataType :'json' ,

		complete :function(result) {
			if(dispCnt >= 1) {
				bldBlogHTMLAll();
				//bldNewsSitemap();	/* サイトマップ出力 */
				alert('出力完了 (' + dispCnt + '件)');
			} else {
				alert('表示するニュースが0件でした');
			}
		} ,

		success :function(result) {
					console.debug(result);
			dispCnt = result;
					//console.debug(ret['TITLE']);
		} ,

		error :function(result) {
					console.debug('error at writeNewsDisp:' + result);
		} ,

	});


	return ret;
}
