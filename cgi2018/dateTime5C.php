<?php
/**
 * 日付時刻関数
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */

/**
 * 日付時刻関数
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */
class dateTime5C {

	/***** 日付時刻のインデックス *****/
	const YEAR  = 'year';	/* 年の値 */
	const MONTH = 'mon';	/* 月の値 */
	const DAY   = 'mday';	/* 日付の値 */
	const HOUR  = 'hour';	/* 時の値 */
	const MIN   = 'min';	/* 分の値 */
	const SEC   = 'sec';	/* 秒の値 */

	const STAMP = 'stamp';	/* タイムスタンプ */

			/*const DATE_SEP' ,'-');*/
	const DATE_SEP = '/';
	const TIME_SEP = ':';

	const ONE_DAY_SEC = 86400;	/* 1日の秒数 */

	const SEP_AMPM = 12;			/* 12時間を使用 */
	const JOI_AMPM = 24;			/* 24時間を使用 */

/**
 * タイムゾーンの指定
 *
 * @access
 * @param
 * @return
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function setTimeZone() {
		/********** timezoneが使えるのはphp5から **********/
		/* date_default_timezone_set('Asia/Tokyo'); */			/* GMT UTC */
	}

/**
 * 現在の日時の取得
 *
 * @access
 * @param string $dateSep 年月日の区切り文字
 * @return string 日付時刻
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function getCurrDT($dateSep=self::DATE_SEP) {

		/* self::setTimeZone(); */
		$format = 'Y' . $dateSep . 'm' . $dateSep . 'd' . ' ' . 'H' . self::TIME_SEP . 'i' . self::TIME_SEP . 's';
		return date($format);
	}

/**
 * 現在の日時の取得
 *
 * 現在の日時を区切り文字なしで取得する
 *
 * @access
 * @param
 * @return string 日付時刻
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function getCurrDTN() {

		/* self::setTimeZone(); */
		return date('YmdHis');
	}

/**
 * 現在の日時の取得
 *
 * 現在の日時からランダムな数字列を生成する
 *
 * @access
 * @param
 * @return string 数字列
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function getCurrDTR() {

		$str = self::getCurrDTN();

		$ret =  substr($str , 7 ,1) .
				substr($str , 4 ,1) .
				substr($str , 0 ,1) .
				substr($str ,12 ,1) .
				substr($str , 9 ,1) .
				substr($str , 8 ,1) .
				substr($str , 2 ,1) .
				substr($str , 5 ,1) .
				substr($str ,11 ,1) .
				substr($str ,10 ,1) .
				substr($str , 6 ,1) .
				substr($str , 3 ,1) .
				substr($str ,13 ,1) .
				substr($str , 1 ,1);

		return $ret;
	}


/**
 * 現在の日付の取得
 *
 * @access
 * @param string $dateSep 年月日の区切り文字
 * @return string 現在の日付
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function getDate($dateSep=self::DATE_SEP) {

		/* self::setTimeZone(); */
		$format = 'Y' . $dateSep . 'm' . $dateSep . 'd';
		return date($format);
	}

/**
 * 現在の時刻の取得
 *
 * @access
 * @param
 * @return string 現在の時刻
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function getTime() {

		/* self::setTimeZone(); */
		$format = 'H' . self::TIME_SEP . 'i' . self::TIME_SEP . 's';
		return date($format);
	}


/**
 * 日付の数値化
 *
 * @access
 * @param int $yy 年
 * @param int $mm 月
 * @param int $dd 日
 * @return int 8桁の数値
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function dayDataToNum($yy ,$mm ,$dd) {

		$ret = ($yy * 10000) + ($mm * 100) + $dd;

		return $ret;
	}

/**
 * 日付の数値化
 *
 * @access
 * @param string $ymd 年月日
 * @return int 8桁の数値
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function dayDataToNum2($ymd) {

		$divDate = self::divideDate($ymd);
		$yy = $divDate[self::YEAR ];
		$mm = $divDate[self::MONTH];
		$dd = $divDate[self::DAY  ];

		$ret = self::dayDataToNum($yy ,$mm ,$dd);

		return $ret;
	}

/**
 * 現在の時刻の数値化
 *
 * @access
 * @param string $sep 現在の時刻を12時間/24時間のいずれで取り出すか
 * @return int 5桁の数値
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function getTimeStr($sep=self::JOI_AMPM) {

		if($sep == self::JOI_AMPM) {
			$param = 'H' . self::TIME_SEP . 'i' . self::TIME_SEP . 's';		/* 24時間 */
		} else {
			$param = 'h' . self::TIME_SEP . 'i' . self::TIME_SEP . 's';		/* 12時間 */
		}
		/* self::setTimeZone(); */
		$timeStr = date($param);

		$timeData = explode(self::TIME_SEP ,$timeStr);	/* 時刻データの分割 */

		$hh = intval($timeData[0]);		/* 時の値 	0 ～ 23 or 1 ～ 12 */
		$mm = intval($timeData[1]);		/* 分の値 	0 ～ 59 */
		$ss = intval($timeData[2]);		/* 秒の値 	0 ～ 59 */

		$hh *= (60 * 60);
		$mm *= 60;

		$ret = $hh + $mm + $ss;

		return $ret;
	}

/**
 * 明日の日付を取り出す
 *
 * @access
 * @param
 * @return array int 年月日それぞれの値
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function getTommorrowDate() {

		$timestamp = strtotime('+1 day');
		$format    = 'Y' . self::DATE_SEP . 'm' . self::DATE_SEP . 'd';
		$retStrF   = date($format, $timestamp);

		$retStr = self::divideDate($retStrF);

		/***** 数値に変換 *****/
		$ret[self::YEAR ] = intval($retStr[self::YEAR ]);
		$ret[self::MONTH] = intval($retStr[self::MONTH]);
		$ret[self::DAY  ] = intval($retStr[self::DAY  ]);

		return $ret;
	}

/**
 * 今日から7日分の日付リスト
 *
 * @access
 * @param
 * @return array string 今日を起点とした将来7日分の日付リスト
 * @link
 * @see
 * @throws
 * @todo
 */
	function setDateList1W() {

		$format = 'Y' . '-' . 'm' . '-' . 'd';

		$now = time();
		$days[0] = date($format, $now);

		for($dayIdx=1 ;$dayIdx<=6 ;$dayIdx++) {
			$paramStr = '+' . $dayIdx . ' day';
			$timeStr = strtotime($paramStr ,$now);
			$days[$dayIdx] = date($format, $timeStr);
		}

		return $days;
	}


/**
 * 24時間前の日時を取り出す
 *
 * @access
 * @param
 * @return array string 24時間前の日時
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function get24BeforeDT() {

		$timestamp = strtotime('-1 day');
		$format    = 'Y' . self::DATE_SEP . 'm' . self::DATE_SEP . 'd' . ' ' . 'H' . self::TIME_SEP . 'i' . self::TIME_SEP . 's';
		$dtStr     = date($format, $timestamp);

		return $dtStr;
	}

/**
 * 曜日の取り出し
 *
 * @access
 * @param string $date 対象日付
 * @param string $sep  年月日の区切り文字
 * @return int 曜日の数値
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function getDOW($date ,$sep='/') {

		/***** yyyy-mm-ddからタイムスタンプを作る *****/
		$divDate = self::divideDate($date ,'0' ,$sep);
	    $dt1  = mktime(0 ,0 ,0 ,$divDate[self::MONTH] ,$divDate[self::DAY  ] ,$divDate[self::YEAR ]);
				/* $sday = strtotime($dt1); */

		$res = date('w', $dt1);

		return $res;
	}

/**
 * 日付データの分解
 *
 * @access
 * @param string $dateData 対象日付
 * @param string $zero     パディング文字
 * @param string $sep      年月日の区切り文字
 * @return array string 年月日の文字
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function divideDate($dateData ,$zero='0' ,$sep='/') {

		$divData = explode($sep ,$dateData);

		$ret[self::YEAR ] = $divData[0];

		if(strlen($zero) <= 0) {
			$format = '%d';
		} else {
			$format = '%' . $zero . '2d';
		}
		$ret[self::MONTH] = sprintf($format ,$divData[1]);
		$ret[self::DAY  ] = sprintf($format ,$divData[2]);

		return $ret;
	}

/**
 * 今日の日付の分解
 *
 * @access
 * @param
 * @return array string 年月日の文字
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function divideDateToday() {

		/* self::setTimeZone(); */

		$ret[self::YEAR ] = date('Y');
		$ret[self::MONTH] = date('m');
		$ret[self::DAY  ] = date('d');

		return $ret;
	}

/**
 * 時刻データの分解
 *
 * @access
 * @param string $timeData 時刻データ
 * @param string $zero     パディング文字（非使用）
 * @return array string 時分秒の文字
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function divideTime($timeData ,$zero='0') {

		$divData = explode(self::TIME_SEP ,$timeData);

		$ret[self::HOUR] = $divData[0];
		$ret[self::MIN ] = $divData[1];
		$ret[self::SEC ] = $divData[2];

		return $ret;
	}

/**
 * 日付文字列の生成
 *
 * @access
 * @param string $year 年
 * @param string $mon  月
 * @param string $day  日
 * @return string 日付文字
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function createDateStr($year ,$mon ,$day) {

		return $year . self::DATE_SEP . $mon . self::DATE_SEP . $day;
	}

/**
 * 時刻文字列の生成
 *
 * @access
 * @param string $hh 時
 * @param string $mm 分
 * @return string 時刻文字
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function createTimeStr($hh ,$mm) {

		return $hh . self::TIME_SEP . $mm;
	}


/**
 * 日時データの分解
 *
 * @access
 * @param string $dateTime 日時
 * @return array int 年月日時分秒
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function divideDTStr($dateTime) {

		$divData  = explode(' ' ,$dateTime);
		$dateData = explode(self::DATE_SEP ,$divData[0]);	/* 日付データの分割 */
		$timeData = explode(self::TIME_SEP ,$divData[1]);	/* 時刻データの分割 */

		$ret[self::YEAR ] = intval($dateData[0]);		/* 年の値（4桁）  	2005 など */
		$ret[self::MONTH] = intval($dateData[1]);		/* 月の値（数値） 	1 ～ 12 */
		$ret[self::DAY  ] = intval($dateData[2]);		/* 日付の値 	1 ～ 31 */
		$ret[self::HOUR ] = intval($timeData[0]);		/* 時の値 	0 ～ 23 */
		$ret[self::MIN  ] = intval($timeData[1]);		/* 分の値 	0 ～ 59 */
		$ret[self::SEC  ] = intval($timeData[2]);		/* 秒の値 	0 ～ 59 */

		return $ret;
	}

/**
 * 日付の差の計算
 *
 * 日付1からみて日付2は何日後かを計算する
 *
 * @access
 * @param int $year1  年1
 * @param int $month1 月1
 * @param int $day1   日1
 * @param int $year2  年2
 * @param int $month2 月2
 * @param int $day2   日2
 * @return int 日差
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function compareDate($year1 ,$month1 ,$day1 ,$year2 ,$month2 ,$day2) {

	    $dt1 = mktime(0 ,0 ,0 ,$month1 ,$day1 ,$year1);
	    $dt2 = mktime(0 ,0 ,0 ,$month2 ,$day2 ,$year2);
	    $diff = $dt2 - $dt1;
	    $diffDay = $diff / self::ONE_DAY_SEC;	/* 1日の秒数 */

	    return $diffDay;
	}

/**
 * 年月日のn日後の日付の取り出し
 *
 * @access
 * @param int $year1   年
 * @param int $month1  月
 * @param int $day1    日
 * @param int $addDays 加算日
 * @return string 日付
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function calcDate($year, $month, $day, $addDays) {

		$baseSec   = mktime(0, 0, 0, $month, $day, $year);	/* 基準日を秒で取得 */
		$addSec    = self::ONE_DAY_SEC * $addDays;			/* 1日の秒数*日数 */
		$targetSec = $baseSec + $addSec;

		return date('Y-m-d', $targetSec);
	}

/**
 * 日付の差の計算
 *
 * 日付1からみて日付2は何日後かを計算する
 *
 * @access
 * @param string $date1 日付1
 * @param string $date2 日付2
 * @return int 日差
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function calcDays($date1 ,$date2) {

		$ciDateExp = explode(self::DATE_SEP ,$date1);
		$coDateExp = explode(self::DATE_SEP ,$date2);

		$year1  = intval($ciDateExp[0]);
		$month1 = intval($ciDateExp[1]);
		$day1   = intval($ciDateExp[2]);

		$year2  = intval($coDateExp[0]);
		$month2 = intval($coDateExp[1]);
		$day2   = intval($coDateExp[2]);

		$days = self::compareDate($year1 ,$month1 ,$day1 ,$year2 ,$month2 ,$day2);

		return $days;
	}

/**
 * 時刻の差の計算
 *
 * 日付1からみて日付2は何秒後かを計算する
 *
 * @access
 * @param string $date1 日付1
 * @param string $date2 日付2
 * @return int 秒差
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function compareDT($dt1 ,$dt2) {

		$div1 = self::divideDTStr($dt1);
		$div2 = self::divideDTStr($dt2);

		$dt1 = mktime($div1[self::HOUR] ,$div1[self::MIN] ,$div1[self::SEC] ,$div1[self::MONTH] ,$div1[self::DAY] ,$div1[self::YEAR]);
		$dt2 = mktime($div2[self::HOUR] ,$div2[self::MIN] ,$div2[self::SEC] ,$div2[self::MONTH] ,$div2[self::DAY] ,$div2[self::YEAR]);

		$diff = $dt2 - $dt1;

		return $diff;
	}

/**
 * 時刻の差の計算
 *
 * 日付1からみて日付2は何秒後かを計算する
 *
 * @access
 * @param array string $dt1 日時1
 * @param array string $dt2 日時2
 * @return int 秒差
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function compareDT2($dt1 ,$dt2) {

		$dt1 = mktime($dt1[self::HOUR] ,$dt1[self::MIN] ,$dt1[self::SEC] ,$dt1[self::MONTH] ,$dt1[self::DAY] ,$dt1[self::YEAR]);
		$dt2 = mktime($dt2[self::HOUR] ,$dt2[self::MIN] ,$dt2[self::SEC] ,$dt2[self::MONTH] ,$dt2[self::DAY] ,$dt2[self::YEAR]);

		$diff = $dt2 - $dt1;

		return $diff;
	}

/**
 * 時刻の差の計算
 *
 * 日付1からみて日付2は何秒後かを計算する
 *
 * @access
 * @param array string $dt1 日付1
 * @param array string $dt2 日付2
 * @return int 秒差
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function compareDT3($dt1 ,$dt2) {

		$dt1 = mktime(0 ,0 ,0 ,$dt1[self::MONTH] ,$dt1[self::DAY] ,$dt1[self::YEAR]);
		$dt2 = mktime(0 ,0 ,0 ,$dt2[self::MONTH] ,$dt2[self::DAY] ,$dt2[self::YEAR]);

		$diff = $dt2 - $dt1;

		return $diff;
	}


/**
 * 秒の削除
 *
 * 日付データの秒を剥がす
 *
 * @access
 * @param string $dateTime 日時
 * @return string 年月日時分
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function removeSecondsString($dateTime) {

		$divData  = explode(' ' ,$dateTime);
		$timeData = explode(self::TIME_SEP ,$divData[1]);	/* 時刻データの分割 */

		$ret = $divData[0] . ' ' . $timeData[0] . ':' . $timeData[1];

		return $ret;
	}

/**
 * 日時から日付の取り出し
 *
 * @access
 * @param string $dateTime 日時
 * @return string 年月日
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function getDateStrFromDTStr($dateTime) {

		$divData = explode(' ' ,$dateTime);

		return $divData[0];
	}

/**
 * 年月の末日の取り出し
 *
 * @access
 * @param int $year  年
 * @param int $month 月
 * @return string 日
 * @link
 * @see
 * @throws
 * @todo
 * mktime関数で日付を0にすると前月の末日を指定したことになります
 * $month + 1 をしていますが、結果13月のような値になっても自動で補正されます
 */
	public static function getMonthEndDay($year, $month) {

		$dt = mktime(0, 0, 0, $month + 1, 0, $year);
		return date('d' ,$dt);
	}

/**
 * 曜日の取り出し
 *
 * @access
 * @param int $dow 曜日
 * @return string 曜日文字列
 * @link
 * @see
 * @throws
 * @todo
 */
	public static function getDOWStr($dow) {

		$weekday = array('日' ,'月' ,'火' ,'水' ,'木' ,'金' ,'土');

		return $weekday[$dow];
	}
}
?>
