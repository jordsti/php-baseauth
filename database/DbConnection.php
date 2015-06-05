<?php
require_once("database/Constants.php");
class DbConnection
{	
	private $connection;
	public function __construct()
	{
		$this->open();
	}
	
	public function prepare($query)
	{
		return $this->connection->prepare($query);
	}
	
	public function open()
	{
		$this->connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
	}
	
	public function reset()
	{
		$this->close();
		$this->open();
	}
	
	public function close()
	{
		$this->connection->close();
	}
}