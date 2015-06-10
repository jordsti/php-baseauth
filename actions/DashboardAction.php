<?php
require_once("actions/BaseAction.php");
require_once("database/DbNews.php");

class DashboardAction extends BaseAction {

	public $lastNews;

	public function __construct()
	{
		parent::__construct();
	}
	
	public function execute()
	{
		$news = array();
		$this->lastNews = new News();
		
		
		if($this->testPermission('view_news'))
		{
			$news = DbNews::GetLastNews(1);
			if(count($news) > 0)
			{
				
				$this->lastNews = $news[0];
			}
		}
	}

}
