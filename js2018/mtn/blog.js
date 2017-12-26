/*************************
ブログ編集 Version 1.0
2016 July 03 ver 1.0
*************************/

var DISP_UPDATED = false;

var setNewList = false;
var setAllList = false;
var respBlogDisp   = false;
var blogNoList30;
var blogNoListAll;

$(function() {

	$("#editBlog").dialog({
		autoOpen: false ,
		modal:true ,
		width: 850 ,
		buttons: [
			{
				text :"出力",
				click:function() {
					var chkEnter = checkBlogEnter();
					if(chkEnter.length <= 0) {
						writeBlogPre();
					} else {
						alert(chkEnter);
					}
				}
			} ,
			{
				text :"キャンセル",
				click:function() {
					$(this).dialog("close");
				}
			}
		]
	});

var dtop = {

//	format : 'Y-m-d H:i' ,


	formatTime : 'H:i' ,

	inline    : false
};






/*	$("#blogDate").datetimepicker(dtop);*/
/*	$("#blogDateAA").datetimepicker();*/



	/***** ニュース表示(非編集) *****/
	/*
	$("#showBlog").dialog({
		autoOpen: false ,
		modal:true ,
		width: 850 ,
		buttons: [
			{
				text :"閉じる",
				click:function() {
					$(this).dialog("close");
				}
			}
		]
	});
	*/

	setNewList = false;
	setAllList = false;
	respBlogDisp   = false;

	/***** 直近30件のニュースの読み込み *****/
	getNewestBlog();

//			/***** ニュース全件の読み込み *****/
//			getAllBlogForShow();
});










/********************
ブログ情報の読み込み
********************/
function getNewestBlog() {

var groupNo  = $('#groupNo').val();
var branchNo = $('#branchNo').val();
var ret;

	$.ajax({
		type : "get" ,
		url  : "cgi/ajax/getNewestBlog.php" ,
		data : {
			groupNo  : groupNo  ,
			branchNo : branchNo
		} ,

		cache    : false ,
		dataType : 'json' ,

		success : function(result) {
					//console.debug(result);
			ret = result;
					//console.debug(ret['title']);
		} ,

		error : function(result) {
					console.debug('error at getNewestBlog:' + result);
		} ,

		complete : function(result) {
					console.debug('complete at getNewestBlog:' + result);

			$("#loadingBlog").remove();
			$("#blogListH").html(ret['title']);
			$("#blogListD").html(ret['data']);
			$(".dispBlogSW").toggleSwitch();

					//console.debug(ret['blogNoList']);

			resetBlogList30(ret['blogNoList']);
			setNewList = true;
			writeBlogDispMain();
			enableSW();
		}
	});

	return ret;
}



/********************
ブログの再表示
********************/
function reshowBlogList(blogNo ,tdTag ,blogIDTag) {

var newRec  = $('#newBlogRec').val();
var tdClass = 'td'   + blogIDTag;
var dispID  = 'blog' + blogIDTag;

var listMax;

	if(blogNo == newRec) {
		/*** 新規ニュース ***/
			console.debug('新規ニュース追加表示');
		$('#blogListD').prepend('<tr id="' + blogIDTag + '">' + tdTag + '</tr>');	//先頭に追加
		$('.' + tdClass).slideDown("fast");											//行が下がる視覚効果
		$('#' + dispID).toggleSwitch();

		listMax = blogNoList30.length;
		blogNoList30[listMax] = blogIDTag;
	} else {
		/*** 既存ニュース ***/
			console.debug('既存ニュース更新表示');
			console.debug(tdTag);
		$('#' + blogIDTag).html(tdTag);

		$('#' + dispID).toggleSwitch();
	}

}


function resetBlogList30(blogNoList) {

	blogNoList30 = blogNoList;
}


/********************
ニュース全件の読み込み
********************/
function getAllBlogForShow() {

var groupNo  = $('#groupNo').val();
var branchNo = $('#branchNo').val();
var ret;

console.debug('groupNo branchNo:' + groupNo + ' ' + branchNo);

	$.ajax({
		type :"get" ,
		url  :"cgi/ajax/getAllBlogForShow.php" ,
		data : {
			groupNo  :groupNo  ,
			branchNo :branchNo
		} ,

		cache    :false ,
		dataType :'json' ,

		success :function(result) {
					console.debug(result);
			ret = result;
					//console.debug(ret['title']);
		} ,

		error :function(result) {
					console.debug('error at getAllBlogForShow:' + result);
		} ,

		complete : function(result) {
					console.debug('complete at getAllBlogForShow:' + result);

			$("#loadingBlogAll").remove();
			$("#blogListAllH").html(ret['title']);
			$("#blogListAllD").html(ret['data']);
			$(".dispAllBlogSW").toggleSwitch();

			resetBlogListAll(ret['blogNoList']);
			setAllList = true;
			setNew30ToAll();
			writeBlogDispMain();
			enableSW();







		}

	});

	return ret;
}


function resetBlogListAll(blogNoList) {

	blogNoListAll = blogNoList;
}


/********************
新規30件の表示状態を全件へコピー
********************/
function setNew30ToAll() {

var listMax = blogNoList30.length;
var idx;

var blogNo;
var currCond;

	for(idx=0 ;idx<listMax ;idx++) {
		blogNo = blogNoList30[idx];
		currCond = $('#disp' + blogNo).prop('checked');
		$("#disp" + blogNo).prop("checked" ,currCond);
	}
}



function enableSW() {

	/*
	if(setNewList && setAllList) {
		$(".dispAllBlogSW").prop('disabled' ,false);
		$(".dispBlogSW").prop('disabled' ,false);
	}
	*/

}


/********************
新規ニュース
********************/
function newBlog() {

var groupNo  = $('#groupNo').val();
var branchNo = $('#branchNo').val();

/***** 記事日付 *****/
var today = new Date();
var yy = today.getFullYear();

var mmVal = today.getMonth() + 1;
var ddVal = today.getDate();

var tmHHVal = today.getHours();
var tmMMVal = today.getMinutes();

var mm = ("00" + mmVal).substr(-2);
var dd = ("00" + ddVal).substr(-2);

var tmHH = ("00" + tmHHVal).substr(-2);
var tmMM = ("00" + tmMMVal).substr(-2);

var dateStr = yy + '-' + mm + '-' + dd + ' ' + tmHH + ':' + tmMM;

var phraseData;

	$.ajax({
		type : "get" ,
		url  : "cgi/ajax/getFixPhrase.php" ,
		data : {
			groupNo  : groupNo  ,
			branchNo : branchNo
		} ,

		cache    : false  ,
		dataType : 'json' ,

		success :function(result) {
					//console.debug(result);
			phraseData = result;
					//console.debug(ret['TITLE']);
		} ,

		error :function(result) {
					console.debug(result);
					console.debug('error at getPhraseData:' + result);
		} ,

		complete :function(result) {
			$("#blogTitle").val('');
			$("#blogDate").val(dateStr);
			$("#blogTerm").val('');

			/*$("#digest").val('');*/
			$("#blogContent").val(phraseData['phraseInfo'][1]['STR']);

			$("#editBlogNo").val($('#newBlogRec').val());	/* 新規 */

			$("input[name='blogCate']").val(["E"]);


			$('#delBlog').css('display' ,'none');

			$("#editBlog").dialog( {
				title: '新規'
			});
			$("#editBlog").dialog("open");
		}
	});
}


/********************
ニュース編集
********************/
function editBlog(blogNo) {

var groupNo  = $('#groupNo').val();
var branchNo = $('#branchNo').val();

	getBlogData(groupNo ,branchNo ,blogNo ,'E');
}


/********************
ニュース表示
********************/
function showBlog(blogNo) {

var groupNo  = $('#groupNo').val();
var branchNo = $('#branchNo').val();

	getBlogData(groupNo ,branchNo ,blogNo ,'S');
}



/********************
ニュース情報取出し
********************/
function getBlogData(groupNo ,branchNo ,blogNo ,mode) {

var blogData;

		//console.debug(groupNo + ' ' + branchNo + ' ' + blogNo);
		//console.debug('blogNo: ' + blogNo);

	$.ajax({
		type :"get" ,
		url  :"cgi/ajax/getBlog.php" ,
		data : {
			groupNo  :groupNo  ,
			branchNo :branchNo ,
			blogNo   :blogNo
		} ,

		cache    :false ,
		dataType :'json' ,

		success :function(result) {
					console.debug(result);
			blogData = result;
					//console.debug(ret['title']);
		} ,

		error :function(result) {
					console.debug('error at getBlogData:' + result);
		} ,

		complete :function(result) {
					console.debug('complete at getBlogData:' + result);

			if(mode == 'S') {
				showBlogSet(blogData);
			} else {
				editBlogSet(blogData);
			}
		}
	});
}

function showBlogSet(blogData) {
	$("#blogTitle").html(blogData['title']);
	$("#blogDate").html(blogData['blogDate']);
	$("#blogTerm").html(blogData['term']);

	$("#showContent").html(blogData['content']);

	$("#showDispBegDate").html(blogData['dispBegDT']);

	$("#showBegDate").html(blogData['begDate']);
	$("#showEndDate").html(blogData['endDate']);


//	$("input[name='blogCate']").val([blogData['category']]);
//	$('#delBlog').css('display' ,'block');

	$("#showBlog").dialog( {
		title: '表示 ' + blogData['NO']
	});
	$("#showBlog").dialog("open");
}

function editBlogSet(blogData) {
				//console.debug(blogData['title']);
				console.debug('編集');

				//console.debug(blogData['blogDate']);

	$("#blogTitle").val(blogData['title']);
	$("#blogDate").val(blogData['blogDate']);
	$("#blogTerm").val(blogData['term']);

	$("#blogContent").val(blogData['content']);

	$("#editBlogNo").val(blogData['addDT']);

	$("#blogDispBegDate").val(blogData['dispBegDT']);

	/*
	$("#begDate").val(blogData['begDate']);
	$("#endDate").val(blogData['endDate']);
	*/


	$("input[name='blogCate']").val([blogData['category']]);


/*
	$('#delBlog').css('display' ,'block');
*/
	$("#editBlog").dialog( {
		title: '編集 ' + blogData['addDT']
	});
	$("#editBlog").dialog("open");

}



/********************
ニュースの内容チェック
********************/
function checkBlogEnter() {

var ret = '';
var str = '';

var title    = $('#blogTitle').val();
var blogDate = $('#blogDate').val();

var content  = $('#blogContent').val();

var cate = $("input[name='blogCate']:checked").val();

	if(title.length <= 0) {
		str = str + '・タイトル\n';
	}
	if(blogDate.length <= 0) {
		str = str + '・記事日付\n';
	}
	if(cate.length <= 0) {
		str = str + '・種類\n';
	}

	if(content.length <= 0) {
		str = str + '・記事\n';
	}

	if(str.length >= 1) {
		ret = '以下の項目が未入力です\n' + str;
	}

	return ret;
}




/********************
リスト表示
********************/
function showBlogList() {

	setNewList = false;
	setAllList = false;

	$("#bldBlogListA").prop('disabled' ,true);
/*	$("#bldBlogListB").prop('disabled' ,true);*/

	/***** 直近30件のニュースの読み込み *****/
	getNewestBlog();

	/***** ニュース全件の読み込み *****/
/*	getAllBlogForShow();*/
}


/********************
「表示可否反映」ボタンの有効化
********************/
function enableWriteBlogDisp() {

	respBlogDisp = true;
	writeBlogDispMain();
}


function writeBlogDispMain() {

	if(respBlogDisp) {
		if(setNewList) {
			$("#bldBlogListA").prop('disabled' ,false);
		}

		/*
		if(setAllList) {
			$("#bldBlogListB").prop('disabled' ,false);
		}
		*/
	}
}


function updBlogDispALL(blogNo) {

var currCond;

	if(setNewList && setAllList) {
		if(DISP_UPDATED) {
			DISP_UPDATED = false;
		} else {
			DISP_UPDATED = true;
			if($("#disp" + blogNo).prop('checked')) {
				currCond = true;
			} else {
				currCond = false;
			}

			$("#dispAll" + blogNo).prop("checked" ,currCond);
		}
	}
}

function updBlogDisp30(blogNo) {

var currCond;

	if(setNewList && setAllList) {
		if(DISP_UPDATED) {
			DISP_UPDATED = false;
		} else {
			DISP_UPDATED = true;
			if($("#dispAll" + blogNo).prop('checked')) {
				currCond = true;
			} else {
				currCond = false;
			}

			$("#disp" + blogNo).prop("checked" ,currCond);
		}
	}
}


/***********************************************************************************************************************/
/********************
削除
********************/
function cfmDelBlog() {

/*
	jConfirm('消していいの？' ,'プロファイルの削除' ,function(r) {
		if(r==true){jAlert('OKをクリックしました。', '確認ダイアログ分岐後');}
		else{jAlert('Cancelをクリックしました。', '確認ダイアログ分岐後');}
	});
*/

	$('.cfmDelPrompt').css('display' ,'block');

}

/*******************
削除のpopupを非表示
*******************/
function hideDelBlog() {

	$("#DelBlogDlg").css('display' ,'none');
}

/*******************
削除の本体
*******************/
function delBlogItem() {

var groupNo  = $('#groupNo').val();
var branchNo = $('#branchNo').val();

var blogNo   = $('#editBlogNo').val();

	$.ajax({
		type : "post" ,
		url  : "cgi/ajax/delBlogItem.php" ,
		data : {
			groupNo  : groupNo  ,
			branchNo : branchNo ,
			blogNo   : blogNo
		} ,

		cache   : false ,

		success : function(result) {
					//console.debug(result);
					//console.debug(ret['title']);
		} ,

		error : function(result) {
					console.debug('error at delBlogItem:' + result);
		} ,

		complete : function(result) {
			bldBlogHTML();		/* HTMLファイル出力 */
			showBlogList();		/* リスト表示 */

			hideDelBlog();
			$("#editBlog").dialog("close");
		}
	});
}
