/********************/
/* ログアウト */
/********************/

function logout() {

	logoutMain();
}


function logoutMain() {

	$.ajax({
		type : "get" ,
		url  : "../cgi2018/ajax/mtn/logout.php" ,

		cache :false ,

		success :function(result) {
					//console.debug(result);
		} ,

		error :function(result) {
					console.debug('error at logoutMain :' + result);
		} ,

		complete : function(result) {
					console.debug('*complete at logoutMain : ' + result);

			location.href = 'login.html';
		}
	});
}
