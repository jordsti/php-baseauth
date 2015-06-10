<?php
require_once("database/DbConnection.php");
require_once("classes/News.php");
class DbNews
{
	public static function Delete($news_id)
	{
		$con = new DbConnection();
		$query = "DELETE FROM news WHERE news_id = ?";
		$st = $con->prepare($query);
		$st->bind_param("i", $news_id);
		$st->execute();
		$con->close();
	}
	
	public static function Update($news)
	{
		if(!$news->isNull())
		{
			$n_id = $news->id;
			$n_title = $news->title;
			$n_content = $news->content;
			
			$con = new DbConnection();
			$query = "UPDATE news SET news_title = ?, news_content = ? WHERE news_id = ?";
			$st = $con->prepare($query);
			$st->bind_param("ssi", $n_title, $n_content, $n_id);
			$st->execute();
			$con->close();
		}
	}
	
	public static function Add($title, $content, $user_id)
	{
		$stamp = time();
		
		$con = new DbConnection();
		
		$query = "INSERT INTO news (news_title, news_content, news_posted_on, user_id) VALUES (?, ?, ?, ?)";
		
		$st = $con->prepare($query);
		$st->bind_param("ssii", $title, $content, $stamp, $user_id);
		$st->execute();
		
		$con->close();
	}
	
	public static function GetAll()
	{		
		$news = array();
		$con = new DbConnection();
		
		$query = "SELECT n.news_id, n.news_title, n.news_content, n.news_posted_on, n.user_id, u.username, u.first_name, u.last_name FROM news n JOIN users u ON u.user_id = n.user_id ORDER BY n.news_id DESC";
		$st = $con->prepare($query);
		$st->bind_result($n_id, $n_title, $n_content, $n_postedOn, $u_id, $u_username, $u_firstName, $u_lastName);
		$st->execute();
		
		while($st->fetch())
		{
			$n = new News();
			$n->id = $n_id;
			$n->title = $n_title;
			$n->content = $n_content;
			$n->postedOn = $n_postedOn;
			$n->userId = $u_id;
			$n->user->username = $u_username;
			$n->user->firstName = $u_firstName;
			$n->user->lastName = $u_lastName;
			
			$news[] = $n;
		}
		
		$con->close();
		
		return $news;
	}
	
	public static function GetById($news_id)
	{
		$news = new News();
		$con = new DbConnection();
		$query = "SELECT news_id, news_title, news_content, news_posted_on, user_id FROM news WHERE news_id = ?";
		$st = $con->prepare($query);
		$st->bind_param("i", $news_id);
		$st->bind_result($n_id, $n_title, $n_content, $n_postedOn, $u_id);
		$st->execute();
		
		if($st->fetch())
		{
			$news->id = $n_id;
			$news->title = $n_title;
			$news->content = $n_content;
			$news->postedOn = $n_postedOn;
			$news->userId = $u_id;
		}
		
		$con->close();
		
		return $news;
	}
	
	public static function GetLastNews($count=10)
	{
		$news = array();
		$con = new DbConnection();
		
		$query = "SELECT n.news_id, n.news_title, n.news_content, n.news_posted_on, n.user_id, u.username, u.first_name, u.last_name FROM news n JOIN users u ON u.user_id = n.user_id ORDER BY n.news_id DESC LIMIT ?";
		$st = $con->prepare($query);
		$st->bind_param("i", $count);
		$st->bind_result($n_id, $n_title, $n_content, $n_postedOn, $u_id, $u_username, $u_firstName, $u_lastName);
		$st->execute();
		
		while($st->fetch())
		{
			$n = new News();
			$n->id = $n_id;
			$n->title = $n_title;
			$n->content = $n_content;
			$n->postedOn = $n_postedOn;
			$n->userId = $u_id;
			$n->user->username = $u_username;
			$n->user->firstName = $u_firstName;
			$n->user->lastName = $u_lastName;
			
			$news[] = $n;
		}
		
		$con->close();
		
		return $news;
	}
}
