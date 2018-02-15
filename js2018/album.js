/********************
index.html
********************/
/*** 人妻たち ***/
var listIDA   = 'div#wivesListI';
var tnClassA  = 'div.wivesListTN';
var tnPrefixA = 'div.wList';
var modeA     = 'topWives';

$(function() {

	alignWives(listIDA ,tnClassA ,tnPrefixA);

	$(listIDA).exResize(function(api){
		alignWives(listIDA ,tnClassA ,tnPrefixA);
	})
});
