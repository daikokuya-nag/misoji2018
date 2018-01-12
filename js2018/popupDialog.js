/**********
popup dialog
**********/
$(function(){
	// クリックで、オーバーレイ、ダイアログボックス、ボタンを非表示
	$('a.btn-ok, #dialog-overlay, #dialog-box').click(function (){
		$('#dialog-overlay, #dialog-box').hide();
		return false;
	});

	// ウィンドウがリサイズされたら、ダイアログボックスをセンタリング表示する関数を呼ぶ
	$(window).resize(function () {
		// ダイアログボックスが表示されている場合のみ実行
		if (!$('#dialog-box').is(':hidden')) popupImg();
	});
});

	/**********
	ダイアログをポップアップ
	imgFilePath:ファイル名
	imgWidth:幅
	imgHeight:高さ
	**********/
function popupImg(imgFilePath ,imgWidth ,imgHeight){
	// スクリーンの幅と高さを取得
	var maskHeight = $(document).height();
	var maskWidth = $(window).width();

	// 垂直方法および水平方向にセンタリング表示する位置を計算
					//            var dialogTop =  (maskHeight/3) - ($('#dialog-box').height());
//	var dialogTop =  ($(window).height()/2) - ($('#dialog-box').height());
//	var dialogLeft = (maskWidth/2) - ($('#dialog-box').width()/2);

	var dialogTop =  ($(window).height()/2) - (imgHeight/2);
	var dialogLeft = (maskWidth/2)          - (imgWidth/2);

	// オーバーレイとダイアログボックスを表示
	$('#dialog-overlay').css({height:maskHeight, width:maskWidth}).show();
	$('#dialog-box').css({top:dialogTop, left:dialogLeft}).show();
	// ダイアログボックス内にメッセージを表示
			//	$('#dialog-message').html(message);


	showFileTag = "<img src='" + imgFilePath + "'>";
	$('#dialog-message').html(showFileTag);


}
