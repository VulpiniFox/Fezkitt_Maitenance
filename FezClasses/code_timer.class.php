<?php

namespace Fez;

class code_timer
{

	private $starttime;
	private $endtime;

	// Constructor.
	// Purpose: Starts Timer.
	public function __construct()
	{
		$this->starttime = \microtime();
		return \true;
	}


	// Member Function: Stop_Timer
	// Purpose: Stop Timing.
	public function stop_timer()
	{
		$this->endtime = \microtime();
		return \true;
	}


	// Member Function: Show_Timer
	// Purpose: Display elapsed time in seconds to
	// precision passed in optional parameter
	public function show_timer($decimals = 8)
	{
		// $decimals will set the number of decimals
		// you want for your milliseconds.
		// format start time
		$decimals = \intval($decimals);
		if ($decimals > 8)
		{
			$decimals = 8;
		}
		if ($decimals < 0)
		{
			$decimals = 0;
		}
		$explodedendtime = \explode(" ", $this->endtime);
		$endtime = (float) $explodedendtime['1'] + (float) $explodedendtime['0'];
		$explodedstarttime = \explode(" ", $this->starttime);
		$starttime = (float) $explodedstarttime['1'] + (float) $explodedstarttime['0'];
		return \number_format($endtime - $starttime, $decimals);
	}


	// Member Function: Stop_Show_Timer
	// Purpose: Stop the timer and Display elapsed time
	// in seconds to precision passed in optional parameter
	public function stop_show_timer($decimals = 8)
	{
		$this->stop_timer();
		return $this->show_timer($decimals);
	}


}

?>