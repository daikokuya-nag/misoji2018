<?php
/**
 * 出勤関係
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */

	require_once dirname(__FILE__) . '/profile5C.php';
	require_once dirname(__FILE__) . '/dateTime5C.php';

/**
 * 出勤関係
 *
 * @version 1.0.1
 * @copyright
 * @license
 * @author
 * @link
 */
class works5C {

/**
 * ファイル名の取得
 *
 * @access
 * @param int $dow 曜日
 * @return array string 出勤データのフィールド
 * @link
 * @see
 * @throws
 * @todo
 */
	function getDOWIdx($dow) {
		if($dow == 0) {
			$ret['F' ] = profile5C::WORK_DEF_SUN_F_S;
			$ret['T' ] = profile5C::WORK_DEF_SUN_T_S;
			$ret['MO'] = profile5C::WORK_DEF_SUN_MO_S;
		}
		if($dow == 1) {
			$ret['F' ] = profile5C::WORK_DEF_MON_F_S;
			$ret['T' ] = profile5C::WORK_DEF_MON_T_S;
			$ret['MO'] = profile5C::WORK_DEF_MON_MO_S;
		}
		if($dow == 2) {
			$ret['F' ] = profile5C::WORK_DEF_TUE_F_S;
			$ret['T' ] = profile5C::WORK_DEF_TUE_T_S;
			$ret['MO'] = profile5C::WORK_DEF_TUE_MO_S;
		}
		if($dow == 3) {
			$ret['F' ] = profile5C::WORK_DEF_WED_F_S;
			$ret['T' ] = profile5C::WORK_DEF_WED_T_S;
			$ret['MO'] = profile5C::WORK_DEF_WED_MO_S;
		}
		if($dow == 4) {
			$ret['F' ] = profile5C::WORK_DEF_THU_F_S;
			$ret['T' ] = profile5C::WORK_DEF_THU_T_S;
			$ret['MO'] = profile5C::WORK_DEF_THU_MO_S;
		}
		if($dow == 5) {
			$ret['F' ] = profile5C::WORK_DEF_FRI_F_S;
			$ret['T' ] = profile5C::WORK_DEF_FRI_T_S;
			$ret['MO'] = profile5C::WORK_DEF_FRI_MO_S;
		}
		if($dow == 6) {
			$ret['F' ] = profile5C::WORK_DEF_SAT_F_S;
			$ret['T' ] = profile5C::WORK_DEF_SAT_T_S;
			$ret['MO'] = profile5C::WORK_DEF_SAT_MO_S;
		}

		return $ret;
	}

/**
 * 出勤リストのテンプレート生成
 *
 * @access
 * @param
 * @return array string 当日から起算して7日分のカラの出勤データ
 * @link
 * @see
 * @throws
 * @todo
 */
	function setWeekDaysWork() {

		$format = 'Y' . dateTime5C::DATE_SEP . 'm' . dateTime5C::DATE_SEP . 'd';

		$now = time();
		$days[0][profile5C::WORK_DATE_S] = date($format, $now);
		$days[0][profile5C::WORK_FROM_S] = '';
		$days[0][profile5C::WORK_TO_S  ] = '';
		$days[0][profile5C::WORK_MODE_S] = profile5C::WORK_MODE_TO;

		for($dayIdx=1 ;$dayIdx<=6 ;$dayIdx++) {
			$paramStr = '+' . $dayIdx . ' day';
			$timeStr = strtotime($paramStr ,$now);

			$days[$dayIdx][profile5C::WORK_DATE_S] = date($format, $timeStr);
			$days[$dayIdx][profile5C::WORK_FROM_S] = '';
			$days[$dayIdx][profile5C::WORK_TO_S  ] = '';
			$days[$dayIdx][profile5C::WORK_MODE_S] = profile5C::WORK_MODE_TO;
		}

		return $days;
	}
}
?>
