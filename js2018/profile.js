/********************
初期化
********************/
$().ready(function(){
	showWorks();
});



/********************
「週間出勤表」の表示
********************/
function showWorks(){

var profDir  = $('#profDir').val();

var workStr;

var result = $.ajax({
		type : "post" ,
		url  : "../cgi2018/ajax/bldProfWorkHTML.php" ,
		data : {
			branchNo : BRANCH_NO ,
			profDir  : profDir
		} ,

		cache    : false  ,
		dataType : 'json'
	});



	result.done(function(response) {
					console.debug(response);

		workStr = response;
		if(workStr['validDays'] >= 1) {
			/*** 出勤日が1日以上あるとき ***/
			$('#noSchedule').css('display' ,'none');
			setDays(workStr['daysStr'] ,workStr['daysClass'] ,workStr['workStr'] ,workStr['workClass']);
			$('#weekWorkList').css('display' ,'block');
		} else {
			/*** 出勤日がないとき ***/
			$('#noSchedule').css('display' ,'block');
			$('#weekWorkList').css('display' ,'none');
		}
	});

	result.fail(function(response, textStatus, errorThrown) {
					console.debug('error at bldProfWorkHTML:' + response.status + ' ' + textStatus);
	});

	result.always(function() {
	});
}


function setDays(dayStr ,dayClass ,timeStr ,timeClass) {

var idx;
var id;

var DAYSAT = 'daySat';
var DAYSUN = 'daySun';

var WORKDAY   = 'workDay';
var NOWORKDAY = 'nowork';

	for(idx=0 ;idx<7 ;idx++) {
		/* 日付表示 */
		id = '#wd' + idx;

		$(id).html(dayStr[idx]);

		if(dayClass[idx].length >= 1) {
			$(id).addClass(dayClass[idx]);
		} else {
			$(id).removeClass(DAYSAT);
			$(id).removeClass(DAYSUN);
		}

		/* 時間表示 */
		id = '#wt' + idx;

		$(id).html(timeStr[idx]);

		$(id).removeClass(WORKDAY);
		$(id).removeClass(NOWORKDAY);
		$(id).addClass(timeClass[idx]);
	}
}
