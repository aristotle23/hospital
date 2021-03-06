<?php

//this class control the database connection and how to manage the database

class dbHandler{

	private $db = NULL;
	private $pass = NULL;
	private $query = NULL;

	public function __construct($pass = NULL){		
		$this->pass = $pass;
	}
	public function testconn(){
		$pass = $this->pass;
		$db = new PDO("mysql:host=localhost;dbname=hospital","root",$pass);
		$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);		
		return $db;
	}
	private function connection(){

		$db = new PDO("mysql:host=localhost;dbname=sys","hospital","hospital");

		$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);		

		return $db;

	}

	//get all result fromt he result set

	public  function getAll($sql,$param = NULL){

	

		$this->db =	$this->connection();

		$statement = $this->db->prepare($sql);

		$statement->execute($param);

		$result = $statement->fetchAll(PDO::FETCH_ASSOC);

		$this->db = NULL;

		return $result;


	}

	//get only one result from the result set

	public  function getOne($sql,$param = NULL){



		$this->db =	$this->connection();			

		$statement = $this->db->prepare($sql);

		$statement->execute($param);

		$result = $statement->fetch(PDO::FETCH_ASSOC);

		$this->db = NULL;

		return $result;


	}

	//get the Id of the last inserted row

	public  function executeGetId($sql,$param = NULL){


		$this->db =	$this->connection();			

		$statement = $this->db->prepare($sql);

		$statement->execute($param);

		$insertid = $this->db->lastInsertId();

		$this->db = NULL;

		return $insertid;


	}

	//execute query that does not return result

	public  function execute($sql,$param = NULL){

		$this->db =	$this->connection();						

		$statement = $this->db->prepare($sql);

		$statement->execute($param);

		$this->db = NULL;

	}

	//close the database connection
	public function close(){
		$this->db = NULL;
	}
	public  function setup($sql,$param = NULL){	
	
		$this->db =	$this->testconn();						

		$statement = $this->db->prepare($sql);

		$statement->execute($param);

		$this->db = NULL;

	}
	
}

