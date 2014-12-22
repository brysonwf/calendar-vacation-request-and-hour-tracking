<?	
	$dateBeginMonth = substr($_POST['begin'], 4, 2);
	$dateBeginDay =  substr($_POST['begin'], 6,2);
	$dateBeginYear =  substr($_POST['begin'], 0,4);
	
	$dateEndMonth =  substr($_POST['end'], 4, 2);
	$dateEndDay =  substr($_POST['end'], 6,2);
	$dateEndYear =  substr($_POST['end'], 0,4);
	
	$timeoff = 0;
	$partial_month = 1;
	
	
	for ($year=$dateBeginYear; $year<=$dateEndYear; $year++){		
		
		$holidays = array();
		// month/day (jan 1st). iteration/wday/month (3rd monday in january)
		$hdata = array('1/1'/*newyr*/, '7/4'/*jul4*/, '12/24'/*xmas-eve*/, '12/25'/*xmas*/, '3/1/1'/*mlk*/, '3/1/2'/*pres*/, '5/1/5'/*memo*/, '1/1/9'/*labor*/, '2/1/10'/*col*/, '4/4/11'/*thanks*/, '4/5/11'/*black friday*/); 
		//$hdata = array('0/0'); 
		foreach ($hdata as $h1) {
			$h = explode('/', $h1);
			if (sizeof($h)==2) { // by date
				$htime = mktime(0, 0, 0, $h[0], $h[1], $year); // time of holiday
			} else { // by weekday
				$htime = mktime(0, 0, 0, $h[2], 1, $year); // get 1st day of month
				$w = date('w', $htime); // weekday of first day of month 
				$d = 1+($h[1]-$w+7)%7; // get to the 1st weekday 
				for ($t=$htime, $i=1; $i<=$h[0]; $i++, $d+=7) { // iterate to nth weekday 
					 $t = mktime(0, 0, 0, $h[2], $d, $y); // get next weekday 
					 if (date('n', $t)>$h[2]) break; // check that it's still in the same month 
					 $htime = $t; // valid 
				}
			}
			$holidays[] = $htime; // save the holiday
		} 
		
		if($partial_month == 1){
			$b=$dateBeginMonth;
			$partial_month = 0;
		}else{
			$b=1;
		}
		($year == $dateEndYear)? $c=$dateEndMonth : $c=12;
		
		for ($month=$b; $month<=$c; $month++){
			
			($month == $dateBeginMonth)? $a=$dateBeginDay : $a=1;
			
			($month == $dateEndMonth)? $current_month_days=$dateEndDay : $current_month_days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
			
			for($day=$a; $day<=$current_month_days; $day++){
				
				$time = mktime(0, 0, 0, $month, $day, $year); 
				
				$isHoliday = false;
				
				foreach ($holidays as $h) { // iterate through holidays
					
					if (date('m/d/Y', $time)==date('m/d/Y', $h)){
						$isHoliday = true;
					}// skip holidays
				} 
				if (!$isHoliday){
					$currentday = date("l", mktime(0, 0, 0, $month, $day, $year));
					if (($currentday == "Saturday") || ($currentday == "Sunday")){
					}else{
						$timeoff+=8;
					}
				}
			}
			
		}
	}
	echo $timeoff;
?>