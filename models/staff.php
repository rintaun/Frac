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
	 * id		-	integer(11)		-	Auto-incremeting ID primary key.
	 * nickname	-	string(32)		-	Nickname for the staffer.
	 * password	-	string(64)		-	SHA256 hash for the password (because MD5 is not safe :D)
	 * comment	-	string(255)	-	Brief comment for the staffer.
	 * email	-	string(255)	-	Email address of the staffer.
	 * cell		-	string(32)		-	Cellphnoe number of the staffer.
	 * auth		-	integer(10)		-	Auth flags for the staffer.
	 */
	public function setTableDefinition()
	{
		$this->setTableName("staff");
		
		$this->hasColumn("id", "integer", 11, array(
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
				"regexp" => "/^[0-9a-f]{60}\$/i",
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
}
