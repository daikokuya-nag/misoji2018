/********************
人妻リストの表示
********************/

function alignWives(listID ,tnClass ,tnPrefix){

var divWidth = $(listID).width() - 1;					// 表示領域の幅
var tnWidth  = parseInt($(tnClass).css('width'));		// 一人分の幅

var wivesShow = Math.floor(divWidth / tnWidth);			// 表示する人数
var wivesCalc = wivesShow - 1;							// 計算に使用する人数およびマージンの分割数

var widthCalc = divWidth - tnWidth;						// 計算に使用する表示領域の幅(表示領域 - 一人分の幅)
var allMargin = widthCalc - (tnWidth * wivesCalc);		// 設定するマージンの合計値

var adjustMA   = Math.floor(allMargin / wivesCalc);		// 一人分のマージン値
var adjustCalc = calcAdjust(allMargin ,adjustMA ,wivesShow);

var targetClass = tnPrefix;
		/*
		console.debug('********************');
		console.debug('表示領域の幅 : ' + divWidth);
		console.debug('一人分の幅 : ' + tnWidth);
		console.debug('計算上のdivの幅 : ' + widthCalc);
		console.debug('表示人数 : ' + wivesShow + ' 計算上の人数 : ' + wivesCalc);
		console.debug('設定するマージンの合計値 : ' + allMargin);
		console.debug('adjustMA : ' + adjustMA);
		console.debug('adjustCalc : ' + adjustCalc);
		*/

	if(wivesShow == 9) {
		$(targetClass + 'TN91').css('margin-right' ,adjustCalc[0]);
		$(targetClass + 'TN92').css('margin-right' ,adjustCalc[1]);
		$(targetClass + 'TN93').css('margin-right' ,adjustCalc[2]);
		$(targetClass + 'TN94').css('margin-right' ,adjustCalc[3]);
		$(targetClass + 'TN95').css('margin-right' ,adjustCalc[4]);
		$(targetClass + 'TN96').css('margin-right' ,adjustCalc[5]);
		$(targetClass + 'TN97').css('margin-right' ,adjustCalc[6]);
		$(targetClass + 'TN98').css('margin-right' ,adjustCalc[7]);
		$(targetClass + 'TN99').css('margin-right' ,adjustCalc[8]);

		return;
	}

	if(wivesShow == 8) {
		$(targetClass + 'TN81').css('margin-right' ,adjustCalc[0]);
		$(targetClass + 'TN82').css('margin-right' ,adjustCalc[1]);
		$(targetClass + 'TN83').css('margin-right' ,adjustCalc[2]);
		$(targetClass + 'TN84').css('margin-right' ,adjustCalc[3]);
		$(targetClass + 'TN85').css('margin-right' ,adjustCalc[4]);
		$(targetClass + 'TN86').css('margin-right' ,adjustCalc[5]);
		$(targetClass + 'TN87').css('margin-right' ,adjustCalc[6]);
		$(targetClass + 'TN88').css('margin-right' ,adjustCalc[7]);

		return;
	}

	if(wivesShow == 7) {
		$(targetClass + 'TN71').css('margin-right' ,adjustCalc[0]);
		$(targetClass + 'TN72').css('margin-right' ,adjustCalc[1]);
		$(targetClass + 'TN73').css('margin-right' ,adjustCalc[2]);
		$(targetClass + 'TN74').css('margin-right' ,adjustCalc[3]);
		$(targetClass + 'TN75').css('margin-right' ,adjustCalc[4]);
		$(targetClass + 'TN76').css('margin-right' ,adjustCalc[5]);
		$(targetClass + 'TN77').css('margin-right' ,adjustCalc[6]);

		return;
	}

	if(wivesShow == 6) {
		$(targetClass + 'TN61').css('margin-right' ,adjustCalc[0]);
		$(targetClass + 'TN62').css('margin-right' ,adjustCalc[1]);
		$(targetClass + 'TN63').css('margin-right' ,adjustCalc[2]);
		$(targetClass + 'TN64').css('margin-right' ,adjustCalc[3]);
		$(targetClass + 'TN65').css('margin-right' ,adjustCalc[4]);
		$(targetClass + 'TN66').css('margin-right' ,adjustCalc[5]);

		return;
	}

	if(wivesShow == 5) {
		$(targetClass + 'TN51').css('margin-right' ,adjustCalc[0]);
		$(targetClass + 'TN52').css('margin-right' ,adjustCalc[1]);
		$(targetClass + 'TN53').css('margin-right' ,adjustCalc[2]);
		$(targetClass + 'TN54').css('margin-right' ,adjustCalc[3]);
		$(targetClass + 'TN55').css('margin-right' ,adjustCalc[4]);

		return;
	}

	if(wivesShow == 4) {
		$(targetClass + 'TN41').css('margin-right' ,adjustCalc[0]);
		$(targetClass + 'TN42').css('margin-right' ,adjustCalc[1]);
		$(targetClass + 'TN43').css('margin-right' ,adjustCalc[2]);
		$(targetClass + 'TN44').css('margin-right' ,adjustCalc[3]);

		return;
	}

	if(wivesShow == 3) {
		$(targetClass + 'TN31').css('margin-right' ,adjustCalc[0]);
		$(targetClass + 'TN32').css('margin-right' ,adjustCalc[1]);
		$(targetClass + 'TN33').css('margin-right' ,adjustCalc[2]);

		return;
	}

	if(wivesShow == 2) {
		$(targetClass + 'TN21').css('margin-left'  ,adjustCalc[0]);
		$(targetClass + 'TN21').css('margin-right' ,adjustCalc[1]);

		$(targetClass + 'TN22').css('margin-left'  ,adjustCalc[2]);
		$(targetClass + 'TN22').css('margin-right' ,adjustCalc[3]);

		return;
	}

	if(wivesShow == 1) {
		$(targetClass + 'TN91').css('margin-right' ,'0px');
		$(targetClass + 'TN92').css('margin-right' ,'0px');
		$(targetClass + 'TN93').css('margin-right' ,'0px');
		$(targetClass + 'TN94').css('margin-right' ,'0px');
		$(targetClass + 'TN95').css('margin-right' ,'0px');
		$(targetClass + 'TN96').css('margin-right' ,'0px');
		$(targetClass + 'TN97').css('margin-right' ,'0px');
		$(targetClass + 'TN98').css('margin-right' ,'0px');
		$(targetClass + 'TN99').css('margin-right' ,'0px');

		return;
	}
}


function calcAdjust(allMargin ,marginA ,wivesShow) {

var marginB    = marginA + 1;				// 補正に使用するマージン値
var wivesCalc  = wivesShow - 1;				// 計算に使用する人数
var marginCalc = wivesCalc * marginA;		// 計算上のマージンの合計値 (一人分のマージン値 * 人数)
var diff       = allMargin - marginCalc;	// マージン合計値の差 (設定するマージンの合計値 - 計算上のマージンの合計値)

		/*
		console.debug('allMargin:' + allMargin);
		console.debug('marginA:' + marginA);
		console.debug('wivesCalc:' + wivesCalc);
		console.debug('marginCalc:' + marginCalc);
		console.debug('diff:' + diff);
		*/

	if(wivesShow == 9) {
		if(diff == 8) {
			var ret = [marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,'0px'];
			return ret;
		}
		if(diff == 7) {
			var ret = [marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,'0px'];
			return ret;
		}
		if(diff == 6) {
			var ret = [marginB + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,marginB + 'px' ,'0px'];
			return ret;
		}
		if(diff == 5) {
			var ret = [marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,'0px'];
			return ret;
		}
		if(diff == 4) {
			var ret = [marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,'0px'];
			return ret;
		}
		if(diff == 3) {
			var ret = [marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
		if(diff == 2) {
			var ret = [marginA + 'px' ,marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
		if(diff == 1) {
			var ret = [marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
		if(diff == 0) {
			var ret = [marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
	}

	if(wivesShow == 8) {
		if(diff == 7) {
			var ret = [marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,'0px'];
			return ret;
		}
		if(diff == 6) {
			var ret = [marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,'0px'];
			return ret;
		}
		if(diff == 5) {
			var ret = [marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,marginB + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
		if(diff == 4) {
			var ret = [marginA + 'px' ,marginB + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,marginB + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
		if(diff == 3) {
			var ret = [marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
		if(diff == 2) {
			var ret = [marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
		if(diff == 1) {
			var ret = [marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
		if(diff == 0) {
			var ret = [marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
	}

	if(wivesShow == 7) {
		if(diff == 6) {
			var ret = [marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,'0px'];
			return ret;
		}
		if(diff == 5) {
			var ret = [marginB + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,'0px'];
			return ret;
		}
		if(diff == 4) {
			var ret = [marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,'0px'];
			return ret;
		}
		if(diff == 3) {
			var ret = [marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
		if(diff == 2) {
			var ret = [marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
		if(diff == 1) {
			var ret = [marginA + 'px' ,marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
		if(diff == 0) {
			var ret = [marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
	}

	if(wivesShow == 6) {
		if(diff == 5) {
			var ret = [marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,'0px'];
			return ret;
		}
		if(diff == 4) {
			var ret = [marginB + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,marginB + 'px' ,'0px'];
			return ret;
		}
		if(diff == 3) {
			var ret = [marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,'0px'];
			return ret;
		}
		if(diff == 2) {
			var ret = [marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
		if(diff == 1) {
			var ret = [marginA + 'px' ,marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
		if(diff == 0) {
			var ret = [marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
	}

	if(wivesShow == 5) {
		if(diff == 4) {
			var ret = [marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,'0px'];
			return ret;
		}
		if(diff == 3) {
			var ret = [marginB + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,'0px'];
			return ret;
		}
		if(diff == 2) {
			var ret = [marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,'0px'];
			return ret;
		}
		if(diff == 1) {
			var ret = [marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
		if(diff == 0) {
			var ret = [marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
	}

	if(wivesShow == 4) {
		if(diff == 3) {
			var ret = [marginB + 'px' ,marginB + 'px' ,marginB + 'px' ,'0px'];
			return ret;
		}
		if(diff == 2) {
			var ret = [marginB + 'px' ,marginA + 'px' ,marginB + 'px' ,'0px'];
			return ret;
		}
		if(diff == 1) {
			var ret = [marginA + 'px' ,marginB + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
		if(diff == 0) {
			var ret = [marginA + 'px' ,marginA + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
	}

	if(wivesShow == 3) {
		if(diff == 2) {
			var ret = [marginB + 'px' ,marginB + 'px' ,'0px'];
			return ret;
		}
		if(diff == 1) {
			var ret = [marginB + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
		if(diff == 0) {
			var ret = [marginA + 'px' ,marginA + 'px' ,'0px'];
			return ret;
		}
	}

	if(wivesShow == 2) {
		if(diff == 1) {
			var calcL  = Math.floor(marginB / 2);	// 左に表示する側のマージン合計値
			var calcLL = Math.floor(calcL   / 2);	// 左の左マージン
			var calcLR = calcL - calcLL;			// 左の右マージン

			var calcR  = marginB - calcL;			// 右に表示する側のマージン合計値
			var calcRL = Math.floor(calcR  / 2);	// 右の左マージン
			var calcRR = calcL - calcRL;			// 右の右マージン

			var ret = [calcLL + 'px' ,calcLR + 'px' ,calcRL + 'px' ,calcRR + 'px'];
			return ret;
		}
		if(diff == 0) {
			var calcL  = Math.floor(marginA / 2);	// 左に表示する側のマージン合計値
			var calcLL = Math.floor(calcL   / 2);	// 左の左マージン
			var calcLR = calcL - calcLL;			// 左の右マージン

			var calcR  = marginA - calcL;			// 右に表示する側のマージン合計値
			var calcRL = Math.floor(calcR  / 2);	// 右の左マージン
			var calcRR = calcL - calcRL;			// 右の右マージン

			var ret = [calcLL + 'px' ,calcLR + 'px' ,calcRL + 'px' ,calcRR + 'px'];
			return ret;
		}
	}

	if(wivesShow == 1) {
		var ret = ['0px'];
		return ret;
	}

	return ret;
}
