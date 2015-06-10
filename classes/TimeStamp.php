<?php

class TimeStamp {

	private $stamp;

	public function __construct($stamp=0)
	{
		$this->stamp = $stamp;
	}
	
	public function substract($timeStamp)
	{
		$nStamp = $this->stamp - $timeStamp->getStamp();
		return new TimeStamp($nStamp);
	}
	
	public function add($timeStamp)
	{
		$nStamp = $this->stamp + $timeStamp->getStamp();
		return new TimeStamp($nStamp);
	}
	
	public function getStamp()
	{
		return $this->stamp;
	}
	
	public function date($format)
	{
		return date($format, $this->stamp);
	}
	
	public function seconds()
	{
		return $this->stamp / 1000;
	}
	
	public function minutes()
	{
		return $this->seconds() / 60;
	}
	
	public function hours()
	{
		return $this->minutes() / 60;
	}
	
	public function days()
	{
		return $this->hours() / 24;
	}
	
	public function timeAgo()
	{
		$now = time();
		
		$sec = $now-$this->stamp;
		
		$min = $sec / 60;
		$hour = $min / 60;
		$day = $hour / 24;
		
		if($this->stamp == 0)
		{
			return "";
		}
		else if($sec < 60)
		{
			return floor($sec)." second(s) ago";
		}
		else if($min < 60)
		{
			return floor($min)." minute(s) ago";
		}
		else if($hour < 24)
		{
			return floor($hour)." hour(s) ago";
		}
		else
		{
			return floor($day)." day(s) ago";
		}
	}

}
