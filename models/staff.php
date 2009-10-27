<?php
/*
 * Frac
 * Copyright (c) 2009 Matthew Lanigan
 *                    Tony Young
 *
 * See COPYING for license conditions.
 */

if(!defined("IN_FRAC_")) die("This file cannot be invoked directly.");

/**
 * Model for a staffer.
 */
class Staff extends Doctrine_Record
{
	/**
	 * Columns for a staff member.
	 *
	 * - id			-	integer(11)		-	Auto-incremeting ID primary key.
	 * - nickname	-	string(32)		-	Nickname for the staffer.
	 * - password	-	string(64)		-	SHA256 hash for the password (because MD5 is not safe :D)
	 * - comment	-	string(255)		-	Brief comment for the staffer.
	 * - email		-	string(255)		-	Email address of the staffer.
	 * - cell		-	string(32)		-	Cellphone number of the staffer.
	 * - auth		-	integer(10)		-	Auth flags for the staffer.
	 */
	public function setTableDefinition()
	{
		$this->setTableName("staff");
		
		$this->hasColumn("id", "integer", 10, array(
				"primary" => true,
				"autoincrement" => true,
				"unsigned" => true,
				"notnull" => true
			)
		);
		
		$this->hasColumn("nickname", "string", 32, array(
				"unique" => true,
				"notnull" => true
			)
		);
		
		$this->hasColumn("password", "string", 64, array(
				"regexp" => "/^[0-9a-f]{64}\$/i",
				"notnull" => true,
				"fixed" => true
			)
		);		
		
		$this->hasColumn("comment", "string", 1000);
		
		$this->hasColumn("email", "string", 255, array(
				"regexp" => "/^[0-9a-f_-.+]+@[0-9a-z_-]+.[0-9a-z_-.]+\$/i"
			)
		);
		
		$this->hasColumn("cell", "string", 32, array(
				"regexp" => "/^[0-9]*\$/"
			)
		);
		
		$this->hasColumn("auth", "integer", 10);
	}
	
	public function setUp()
	{
		$this->hasMany("Project as ProjectsLeading", array(
				"local" => "id",
				"foreign" => "leader"
			)
		);
		$this->hasMany("Permissions as Permissions", array(
				"local" => "id",
				"foreign" => "staff"
			)
		);
		$this->hasMany("Task as Tasks", array(
				"local" => "id",
				"foreign" => "staff"
			)
		);
	}
	
	/**
	 * Custom password setter - salts a password with $id and $username, and proceeds to hash it with SHA256.
	 *
	 * @param $password Plain-text password.
	 */
	public function setPassword($password)
	{
		// we can't use $this["id"] as a salt because it's not set yet when we're creating a new user.
		// find a workaround?
		//$this->_set("password", hash("sha256", $this["id"] . $this["nickname"] . $password));
		$this->_set("password", hash("sha256", $this["nickname"] . $password));
	}
}
