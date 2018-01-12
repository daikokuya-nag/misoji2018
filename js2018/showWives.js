/********************
人妻リストの表示
********************/

function alignWives(listID ,tnClass ,tnPrefix ,showMode){

var divWidth = parseInt($(listID).css('width')) - 1;
var tnWidth  = parseInt($(tnClass).css('width'));
var widthM = divWidth - tnWidth;				//計算上のdivの幅
var wivesM = Math.floor(widthM / tnWidth);		//計算上の人数
var wivesS = wivesM + 1;						//表示する人数
var divs   = wivesS - 1;						//マージンの合計値を何分割するか

var allMargins = widthM - (tnWidth * wivesM);	//設定するマージンの合計値

var adjustMA   = Math.floor(allMargins / divs);
var adjustCalc = calcAdjust(allMargins ,adjustMA ,wivesS);		//adjustMA;	// + 1;

var targetClass = tnPrefix;

		console.debug('*****width : ' + divWidth);
		console.debug('幅 : ' + tnWidth);
		console.debug('計算上のdivの幅 : ' + widthM);
		console.debug('表示人数 : ' + wivesS + ' 計算上の人数 : ' + wivesM);
		console.debug('隙間 : ' + divs);
		console.debug('設定するマージンの合計値 : ' + allMargins);

//	$('.wivesListTN').css('float' ,'left');

	if(wivesS == 9) {
		$(targetClass + 'TN91').css('margin-right' ,adjustCalc[1]);
		$(targetClass + 'TN92').css('margin-right' ,adjustCalc[2]);
		$(targetClass + 'TN93').css('margin-right' ,adjustCalc[3]);
		$(targetClass + 'TN94').css('margin-right' ,adjustCalc[4]);
		$(targetClass + 'TN95').css('margin-right' ,adjustCalc[5]);
		$(targetClass + 'TN96').css('margin-right' ,adjustCalc[6]);
		$(targetClass + 'TN97').css('margin-right' ,adjustCalc[7]);
		$(targetClass + 'TN98').css('margin-right' ,adjustCalc[8]);
		$(targetClass + 'TN99').css('margin-right' ,'0px');
//		$(targetClass + 'TN99').css('float' ,'right');

		return;
	}

	if(wivesS == 8) {
		$(targetClass + 'TN81').css('margin-right' ,adjustCalc[1]);
		$(targetClass + 'TN82').css('margin-right' ,adjustCalc[2]);
		$(targetClass + 'TN83').css('margin-right' ,adjustCalc[3]);
		$(targetClass + 'TN84').css('margin-right' ,adjustCalc[4]);
		$(targetClass + 'TN85').css('margin-right' ,adjustCalc[5]);
		$(targetClass + 'TN86').css('margin-right' ,adjustCalc[6]);
		$(targetClass + 'TN87').css('margin-right' ,adjustCalc[7]);
		$(targetClass + 'TN88').css('margin-right' ,'0px');
//		$(targetClass + 'TN88').css('float' ,'right');

		return;
	}

	if(wivesS == 7) {
		$(targetClass + 'TN71').css('margin-right' ,adjustCalc[1]);
		$(targetClass + 'TN72').css('margin-right' ,adjustCalc[2]);
		$(targetClass + 'TN73').css('margin-right' ,adjustCalc[3]);
		$(targetClass + 'TN74').css('margin-right' ,adjustCalc[4]);
		$(targetClass + 'TN75').css('margin-right' ,adjustCalc[5]);
		$(targetClass + 'TN76').css('margin-right' ,adjustCalc[6]);
		$(targetClass + 'TN77').css('margin-right' ,'0px');
//		$(targetClass + 'TN77').css('float' ,'right');

		return;
	}

	if(wivesS == 6) {
		$(targetClass + 'TN61').css('margin-right' ,adjustCalc[1]);
		$(targetClass + 'TN62').css('margin-right' ,adjustCalc[2]);
		$(targetClass + 'TN63').css('margin-right' ,adjustCalc[3]);
		$(targetClass + 'TN64').css('margin-right' ,adjustCalc[4]);
		$(targetClass + 'TN65').css('margin-right' ,adjustCalc[5]);
		$(targetClass + 'TN66').css('margin-right' ,'0px');
//		$(targetClass + 'TN66').css('float' ,'right');

		return;
	}

	if(wivesS == 5) {
		$(targetClass + 'TN51').css('margin-right' ,adjustCalc[1]);
		$(targetClass + 'TN52').css('margin-right' ,adjustCalc[2]);
		$(targetClass + 'TN53').css('margin-right' ,adjustCalc[3]);
		$(targetClass + 'TN54').css('margin-right' ,adjustCalc[4]);
		$(targetClass + 'TN55').css('margin-right' ,'0px');
//		$(targetClass + 'TN55').css('float' ,'right');

		return;
	}

	if(wivesS == 4) {
		$(targetClass + 'TN41').css('margin-right' ,adjustCalc[1]);
		$(targetClass + 'TN42').css('margin-right' ,adjustCalc[2]);
		$(targetClass + 'TN43').css('margin-right' ,adjustCalc[3]);
		$(targetClass + 'TN44').css('margin-right' ,'0px');
//		$(targetClass + 'TN44').css('float' ,'right');

		return;
	}

	if(wivesS == 3) {
		$(targetClass + 'TN31').css('margin-right' ,adjustCalc[1]);
		$(targetClass + 'TN32').css('margin-right' ,adjustCalc[2]);
		$(targetClass + 'TN33').css('margin-right' ,'0px');
//		$(targetClass + 'TN33').css('float' ,'right');

		return;
	}

	if(wivesS == 2) {
		$(targetClass + 'TN21').css('margin-right' ,adjustCalc[1]);
		$(targetClass + 'TN22').css('margin-right' ,'0px');
//		$(targetClass + 'TN22').css('float' ,'right');

		return;
	}

	if(wivesS == 1) {
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


function calcAdjust(allMargins ,adjustA ,wivesS) {

var wivesM = wivesS - 1;
var calcA = wivesM * adjustA;
var adjustB = adjustA + 1;
var diff = allMargins - calcA;

		/*
		console.debug('allMargins:' + allMargins);
		console.debug('adjustA:' + adjustA);
		console.debug('wivesM:' + wivesM);
		console.debug('calcA:' + calcA);
		console.debug('diff:' + diff);
		*/

	if(wivesS == 9) {
		if(diff == 8) {
			var ret = ['0px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px'];
			return ret;
		}
		if(diff == 7) {
			var ret = ['0px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px'];
			return ret;
		}
		if(diff == 6) {
			var ret = ['0px' ,adjustB + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustB + 'px'];
			return ret;
		}
		if(diff == 5) {
			var ret = ['0px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px'];
			return ret;
		}
		if(diff == 4) {
			var ret = ['0px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px'];
			return ret;
		}
		if(diff == 3) {
			var ret = ['0px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px'];
			return ret;
		}
		if(diff == 2) {
			var ret = ['0px' ,adjustA + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustA + 'px'];
			return ret;
		}
		if(diff == 1) {
			var ret = ['0px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px'];
			return ret;
		}
		if(diff == 0) {
			var ret = ['0px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px'];
			return ret;
		}
	}

	if(wivesS == 8) {
		if(diff == 7) {
			var ret = ['0px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px'];
			return ret;
		}
		if(diff == 6) {
			var ret = ['0px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px'];
			return ret;
		}
		if(diff == 5) {
			var ret = ['0px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustA + 'px'];
			return ret;
		}
		if(diff == 4) {
			var ret = ['0px' ,adjustA + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustA + 'px'];
			return ret;
		}
		if(diff == 3) {
			var ret = ['0px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px'];
			return ret;
		}
		if(diff == 2) {
			var ret = ['0px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px'];
			return ret;
		}
		if(diff == 1) {
			var ret = ['0px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px'];
			return ret;
		}
		if(diff == 0) {
			var ret = ['0px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px'];
			return ret;
		}
	}

	if(wivesS == 7) {
		if(diff == 6) {
			var ret = ['0px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px'];
			return ret;
		}
		if(diff == 5) {
			var ret = ['0px' ,adjustB + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px'];
			return ret;
		}
		if(diff == 4) {
			var ret = ['0px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px'];
			return ret;
		}
		if(diff == 3) {
			var ret = ['0px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px'];
			return ret;
		}
		if(diff == 2) {
			var ret = ['0px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px'];
			return ret;
		}
		if(diff == 1) {
			var ret = ['0px' ,adjustA + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px'];
			return ret;
		}
		if(diff == 0) {
			var ret = ['0px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px'];
			return ret;
		}
	}

	if(wivesS == 6) {
		if(diff == 5) {
			var ret = ['0px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px'];
			return ret;
		}
		if(diff == 4) {
			var ret = ['0px' ,adjustB + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustB + 'px'];
			return ret;
		}
		if(diff == 3) {
			var ret = ['0px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px'];
			return ret;
		}
		if(diff == 2) {
			var ret = ['0px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px'];
			return ret;
		}
		if(diff == 1) {
			var ret = ['0px' ,adjustA + 'px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustA + 'px'];
			return ret;
		}
		if(diff == 0) {
			var ret = ['0px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px'];
			return ret;
		}
	}

	if(wivesS == 5) {
		if(diff == 4) {
			var ret = ['0px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px'];
			return ret;
		}
		if(diff == 3) {
			var ret = ['0px' ,adjustB + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px'];
			return ret;
		}
		if(diff == 2) {
			var ret = ['0px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px'];
			return ret;
		}
		if(diff == 1) {
			var ret = ['0px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px' ,adjustA + 'px'];
			return ret;
		}
		if(diff == 0) {
			var ret = ['0px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px'];
			return ret;
		}
	}

	if(wivesS == 4) {
		if(diff == 3) {
			var ret = ['0px' ,adjustB + 'px' ,adjustB + 'px' ,adjustB + 'px'];
			return ret;
		}
		if(diff == 2) {
			var ret = ['0px' ,adjustB + 'px' ,adjustA + 'px' ,adjustB + 'px'];
			return ret;
		}
		if(diff == 1) {
			var ret = ['0px' ,adjustA + 'px' ,adjustB + 'px' ,adjustA + 'px'];
			return ret;
		}
		if(diff == 0) {
			var ret = ['0px' ,adjustA + 'px' ,adjustA + 'px' ,adjustA + 'px'];
			return ret;
		}
	}

	if(wivesS == 3) {
		if(diff == 2) {
			var ret = ['0px' ,adjustB + 'px' ,adjustB + 'px'];
			return ret;
		}
		if(diff == 1) {
			var ret = ['0px' ,adjustB + 'px' ,adjustA + 'px'];
			return ret;
		}
		if(diff == 0) {
			var ret = ['0px' ,adjustA + 'px' ,adjustA + 'px'];
			return ret;
		}
	}

	if(wivesS == 2) {
		if(diff == 1) {
			var ret = ['0px' ,adjustB + 'px'];
			return ret;
		}
		if(diff == 0) {
			var ret = ['0px' ,adjustA + 'px'];
			return ret;
		}
	}

	if(wivesS == 1) {
		var ret = ['0px' ,adjustA + 'px'];
		return ret;
	}

	return ret;
}
