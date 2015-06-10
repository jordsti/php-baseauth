<?php

require_once("actions/BaseAction.php");
require_once("database/DbNews.php");

class NewsAction extends BaseAction
{
	public static $NewNewsForm = 1;
	public static $BrowseNews = 2;
	public static $EditNewsForm = 3;
	
	public $news;
	public $currentNews;
	public $view;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->currentNews = new News();
	}
	
	public function execute()
	{
		$action = 'browse';
		$this->view = NewsAction::$BrowseNews;
		
		if(isset($_GET['action']))
		{
			$action = $_GET['action'];
		}
		
		if(strcmp($action, 'browse') == 0)
		{
			$this->mustHavePermission('manage_news');
			$this->news = DbNews::GetAll();
			$this->view = NewsAction::$BrowseNews;
		}
		else if(strcmp($action, 'new_news') == 0)
		{
			$this->mustHavePermission('manage_news');
			$this->view = NewsAction::$NewNewsForm;
		}
		else if(strcmp($action, 'add_news') == 0)
		{
			$this->mustHavePermission('manage_news');
			if(isset($_POST['news_title']) && isset($_POST['news_content']))
			{
				$title = $_POST['news_title'];
				$content = $_POST['news_content'];
				$user_id = $this->user->id;
				
				DbNews::Add($title, $content, $user_id);
				
				$this->addAlert(Alert::CreateSuccess('Success', 'News posted.'));
			}
			
			$this->reexecute(array('action' => 'browse'));
		}
		else if(strcmp($action, 'edit') == 0)
		{
			$this->mustHavePermission('manage_news');
			
			if($_GET['news_id'])
			{
				$news_id = $_GET['news_id'];
				$this->currentNews = DbNews::GetById($news_id);
				$this->view = NewsAction::$EditNewsForm;
				if($this->currentNews->isNull())
				{
					$this->reexecute(array('action' => 'browse'));
				}
			}
			else
			{
				$this->reexecute(array('action' => 'browse'));
			}
			
		}
		else if(strcmp($action, 'save_news') == 0)
		{
			$this->mustHavePermission('manage_news');
			if(isset($_POST['news_id']) &&
				isset($_POST['news_title']) &&
				isset($_POST['news_content'])
			)
			{
				$news = DbNews::GetById($_POST['news_id']);
				if(!$news->isNull())
				{
					$news->title = $_POST['news_title'];
					$news->content = $_POST['news_content'];
					
					DbNews::Update($news);
					
					$this->addAlert(Alert::CreateSuccess('Success', 'News saved.'));
				}
			}
			
			$this->reexecute(array('action' => 'browse'));
		}
		else if(strcmp($action, 'delete_news') == 0)
		{
			$this->mustHavePermission('manage_news');
			
			if(isset($_GET['news_id']))
			{
				$news_id = $_GET['news_id'];
				DbNews::Delete($news_id);
				$this->addAlert(Alert::CreateSuccess('Success', 'News deleted.'));
			}
			$this->reexecute(array('action' => 'browse'));
		}
	}
}
