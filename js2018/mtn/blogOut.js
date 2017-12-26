/*************************
ブログ出力 Version 1.0
2016 June 07 ver 1.0
*************************/

/********************
セッション状態の取得
********************/
function writeBlogPre() {

var sess;

	$.ajax({
		type  : "get" ,
		url   : "cgi/ajax/getSess.php" ,
		cache : false ,

		success : function(result) {
					console.debug(result);
			sess = result;
		} ,

		error : function(result) {
					console.debug('error at writeBlogPre:' + result);
		} ,

		complete : function(result) {
			if(sess == SESS_OWN_INTIME) {
				writeBlogPreA(sess);
			} else {
				alert('長時間操作がなかったため接続が切れました。ログインしなおしてください。');
				$("#editNews").dialog("close");
				location.href = 'login.html';
			}
		}
	});
}

/********************
セッション情報の更新
********************/
function writeBlogPreA(sess) {

var ret;

	$.ajax({
		type  : "get" ,
		url   : "cgi/ajax/updSess.php" ,
		cache : false ,

		success : function(result) {
					console.debug(result);
			ret = result;
		} ,

		error : function(result) {
					console.debug('error at writeBlogPreA:' + result);
		} ,

		complete : function(result) {
			writeBlog();
		}
	});
}

/********************
ブログ書き出し
********************/
function writeBlog() {

	/* ブログ書き出し */
var groupNo  = $('#groupNo').val();
var branchNo = $('#branchNo').val();

var title    = $('#blogTitle').val();
var blogDate = $('#blogDate').val();
/*var newsTerm = $('#blogTerm').val();*/
var content  = $('#blogContent').val();
var blogNo   = $('#editBlogNo').val();

var dispBeg  = $('#blogDispBegDate').val();

/*
var begDate = $('#begDate').val();
var endDate = $('#endDate').val();
*/

var cate = $("input[name='blogCate']:checked").val();

var reshowTag;
var newsIDTag;
var targetBlogNo;

	$.ajax({
		type  : "post" ,
		url   : "cgi/ajax/writeBlog.php" ,
		data  : {
			groupNo  : groupNo  ,
			branchNo : branchNo ,
			blogNo   : blogNo   ,

			title    : title    ,
			blogDate : blogDate ,
/*			newsTerm : newsTerm ,*/

			/*
			begDate  : begDate  ,
			endDate  : endDate  ,
			*/

			content  : content  ,
			cate     : cate     ,
			dispBeg  : dispBeg
		} ,

		cache    : false  ,
		dataType : 'json' ,

		success :function(result) {
					console.debug(result);
			reshowTag = result['tag'];
			newsIDTag = result['id'];
			targetBlogNo = result['blogNo'];
					//console.debug(ret['TITLE']);
		} ,

		error :function(result) {
					console.debug('error at writeBlog:' + result);
		} ,

		complete : function(result) {
			writeBlogNext(blogNo ,reshowTag ,newsIDTag ,targetBlogNo);
			alert('ブログ出力完了');
			$("#editBlog").dialog("close");
		}
	});
}


/********************
HTMLファイル出力
********************/
function writeBlogNext(blogNo ,tdTag ,newsIDTag ,targetBlogNo) {

			console.debug('target blog no:' + targetBlogNo);

			console.debug('A');
	bldBlogHTMLPre(targetBlogNo);		/* HTMLファイル出力 */
			console.debug('B');
	//bldNewsSitemap();	/* サイトマップ出力 */
			console.debug('C');

		//showNewsList();		/* リスト再表示 */
	reshowBlogList(blogNo ,tdTag ,newsIDTag);

			console.debug('blogNO ' + blogNo);
			console.debug('tdTag  ' + tdTag);
			console.debug('newsID ' + newsIDTag);

			console.debug('D');
}

function bldBlogHTMLAll() {

var groupNo  = $('#groupNo').val();
var branchNo = $('#branchNo').val();

var ret;
var allPages;
var blogs;
var blogExp;
var blogMax;
var idx;

	$.ajax({
		type  : "post" ,
		url   : "cgi/ajax/bldBlogHTMLPreAll.php" ,
		data  : {
			groupNo  : groupNo  ,
			branchNo : branchNo
		} ,

		cache    : false  ,
		dataType : 'json' ,

		success : function(result) {
					//console.debug(result);
			ret = result;
			allPages = ret['allPages'];
			blogs    = ret['blogs'];

		} ,

		error : function(result) {
					console.debug('error at bldBlogHTML:' + result);
		} ,

		complete : function(result) {
				//console.debug('blogs:::' + blogs);
				//alert(blogs.length);

					//console.debug(result);
			bldBlogHTMLList(allPages);	/* リスト出力 */

				//blogExp = blogs.split(',');
			blogMax = blogs.length;
			for(idx=0 ;idx<blogMax ;idx++) {

						console.debug(blogs[idx]);
				bldBlogHTML1All(blogs[idx] ,idx);		/* 1件出力 */
			}
		}
	});
}

/********************
HTMLファイル出力
********************/
function bldBlogHTMLPre(targetBlogNo) {

		//console.debug('1件出力' + targetBlogNo);

var groupNo  = $('#groupNo').val();
var branchNo = $('#branchNo').val();

var ret;
var allPages;

	$.ajax({
		type  : "post" ,
		url   : "cgi/ajax/bldBlogHTMLPre.php" ,
		data  : {
			groupNo  : groupNo  ,
			branchNo : branchNo ,
			blogNo   : targetBlogNo
		} ,

		cache    : false  ,
		dataType : 'json' ,

		success : function(result) {
					//console.debug(result);
			ret = result;
			allPages = ret['allPages'];
		} ,

		error : function(result) {
					console.debug('error at bldBlogHTML:' + result);
		} ,

		complete : function(result) {
					//console.debug(result);
			bldBlogHTMLList(allPages);	/* リスト出力 */
			bldBlogHTML1(targetBlogNo);		/* 1件出力 */
		}
	});
}

function bldBlogHTML1(targetBlogNo) {

var groupNo  = $('#groupNo').val();
var branchNo = $('#branchNo').val();

var ret;

			console.debug('bldBlogHTML1');

	$.ajax({
		type  : "post" ,
		url   : "cgi/ajax/bldBlogHTML1.php" ,
		data  : {
			groupNo  : groupNo  ,
			branchNo : branchNo ,
			blogNo   : targetBlogNo
		} ,

		cache    : false  ,
//		dataType : 'json' ,

		success : function(result) {
					console.debug(result);
			ret = result;
					//console.debug(ret['TITLE']);
		} ,

		error : function(result) {
					console.debug('error at bldBlogHTML1:' + result);
		} ,

		complete : function(result) {
					//console.debug(ret);
		}
	});
}


function bldBlogHTML1All(targetBlogNo ,idx) {

var groupNo  = $('#groupNo').val();
var branchNo = $('#branchNo').val();

var ret;

			console.debug('bldBlogHTML1');

	$.ajax({
		type  : "post" ,
		url   : "cgi/ajax/bldBlogHTML1.php" ,
		data  : {
			groupNo  : groupNo  ,
			branchNo : branchNo ,
			blogNo   : targetBlogNo ,
			idx      : idx
		} ,

		cache    : false  ,
//		dataType : 'json' ,

		success : function(result) {
					console.debug(result);
			ret = result;
					//console.debug(ret['TITLE']);
		} ,

		error : function(result) {
					console.debug('error at bldBlogHTML1:' + result);
		} ,

		complete : function(result) {
					//console.debug(ret);
		}
	});
}


function bldBlogHTMLList(allPages) {

var groupNo  = $('#groupNo').val();
var branchNo = $('#branchNo').val();

var idx;

			console.debug('bldBlogHTMLList');

	for(idx=1 ;idx<=allPages ;idx++) {
		bldBlogHTMLListMain(groupNo ,branchNo ,idx ,allPages);
	}
}


function bldBlogHTMLListMain(groupNo ,branchNo ,targetPage ,allPages) {

var ret;

			console.debug('target page ' + targetPage);

	$.ajax({
		type  : "post" ,
		url   : "cgi/ajax/bldBlogHTMLList.php" ,
		data  : {
			groupNo  : groupNo  ,
			branchNo : branchNo ,
			page     : targetPage ,
			allPage  : allPages
		} ,

		cache    : false  ,
//		dataType : 'json' ,

		success : function(result) {
					console.debug(result);
			ret = result;
					//console.debug(ret['TITLE']);
		} ,

		error : function(result) {
					console.debug('error at bldBlogHTMLListMain:' + result);
		} ,

		complete : function(result) {
					//console.debug(ret);
		}
	});

}




/********************
サイトマップ出力
********************/
function bldBlogSitemap() {

var groupNo  = $('#groupNo').val();
var branchNo = $('#branchNo').val();

	$.ajax({
		type  :"post" ,
		url   :"cgi/ajax/bldNewsSitemap.php" ,
		data  : {
			groupNo  :groupNo  ,
			branchNo :branchNo
		} ,

		cache :false ,

		success :function(result) {
					console.debug(result);
			//ret = result;
					//console.debug(ret['TITLE']);
		} ,

		error :function(result) {
					console.debug('error at bldNewsSiteMap:' + result);
		}
	});
}

