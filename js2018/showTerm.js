/**********
日時指定での表示制御
**********/

/**********
期間指定
**********/
function showStrP(begY ,begM ,begD ,begH ,begN ,endY ,endM ,endD ,endH ,endN ,str) {

	currDT    = new Date();
	targetDTB = setDateTime(begY ,begM ,begD ,begH ,begN ,0);
	targetDTE = setDateTime(endY ,endM ,endD ,endH ,endN ,0);

	compareB  = compareDT(currDT ,targetDTB);
	compareE  = compareDT(currDT ,targetDTE);

	if(compareB < 0) {	//targetDTBが過去で、
		if(compareE > 0) {	//targetDTEが未来なら表示
			document.write(str);
		}
	}
}


/**********
開始のみ指定
**********/
function showStrB(begY ,begM ,begD ,begH ,begN ,str) {

	currDT   = new Date();
	targetDT = setDateTime(begY ,begM ,begD ,begH ,begN ,0);

	compare  = compareDT(currDT ,targetDT);

	if(compare < 0) {	//targetDTが過去なら表示
		document.write(str);
	}
}


/**********
終了のみ指定
**********/
function showStrE(endY ,endM ,endD ,endH ,endN ,str) {

	currDT   = new Date();
	targetDT = setDateTime(endY ,endM ,endD ,endH ,endN ,0);

	compare  = compareDT(currDT ,targetDT);

	if(compare > 0) {	//targetDTが未来なら表示
		document.write(str);
	}
}



function setDateTime(y ,m ,d ,h ,n ,s) {

	//月は0～11を指定
	dateData = new Date(y ,m-1 ,d ,h ,n ,s);

	return dateData;
}



/**********
日時の比較
Aから見てBが
過去(A > B) :-1
一致(A == B):0
未来(A < B) :1
**********/
function compareDT(dtA ,dtB) {

	if(dtA == dtB) {
		ret = 0;
	} else {
		if(dtA > dtB) {	//過去
			ret = -1;
		} else {
			ret = 1;
		}
	}

	return ret;
}
