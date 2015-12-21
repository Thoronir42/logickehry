<?php
namespace model;
/**
 * Description of DatetimeManager
 *
 * @author Stepan
 */
class DatetimeManager {
	
	const DB_FORMAT = "y-m-d H:i:s";
	const HUMAN_DATE_ONLY_FORMAT = "d. m. y";
	
	public static function getWeeksBounds($weekOffset = 0){
		if($weekOffset == 0){
			$looseWeek = time();
		} else {
			if ($weekOffset < 0){
				$looseWeek = strtotime("$weekOffset week");
			} else {
				$looseWeek = strtotime("+$weekOffset week");
			}
		}
		$isMonday = date('w', $looseWeek) == 1;
		
		$start = strtotime((($isMonday) ? 'this' : 'last').' Monday', $looseWeek);

		$end = strtotime(($isMonday ? 'next' : 'this').' Monday', $looseWeek);
		
		return ['time_from' => $start, 'time_to' => $end]; 
	}

	public static function format($timePars, $format) {
		$return = [];
		foreach($timePars as $key => $val){
			$return[$key] = date($format, $val);
		}
		return $return;
	}

}
