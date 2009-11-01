<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

if(!defined('IN_FRAC_')) die('This file cannot be invoked directly.');

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
		$this->setTableName('staff');

		$this->hasColumn('id', 'integer', null, array(
				'primary' => true,
				'autoincrement' => true,
				'unsigned' => true
			)
		);
		$this->hasColumn('nickname', 'string', 32, array(
				'unique' => true,
				'notnull' => true
			)
		);
		$this->hasColumn('password', 'string', 64, array(
				'regexp' => '/^[0-9a-f]{64}$/i',
				'notnull' => true,
				'fixed' => true
			)
		);		
		$this->hasColumn('admin', 'boolean', null, array(
				'default' => false,
				'notnull' => true
			)
		);
		$this->hasColumn('comment', 'clob');
		$this->hasColumn('email', 'string', 320, array(
				'email' => array('check_mx' => false),
			)
		);
		$this->hasColumn('sms', 'string', 24, array(
				'regexp' => '/^[0-9.-+()]*$/'
			)
		);
		$this->hasColumn('role', 'integer', null, array(
				'notnull' => true,
				'unsigned' => true
			)
		);
		$this->hasColumn('created', 'timestamp', null, array(
				'notnull' => true
			)
		);
		$this->hasColumn('lastip', 'string', 15);
		$this->hasColumn('lastlogin', 'timestamp', null, array(
				'notnull' => true
			)
		);
		$this->hasColumn('lastaction', 'integer', null, array(
				'unsigned' => true
			)
		);
		$this->hasColumn('theme', 'string');
	}
	
	public function setUp()
	{
		$this->hasOne('Role as Role', array(
				'local' => 'role',
				'foreign' => 'id'
			)
		);
		$this->hasOne('Log as LastAction', array(
				'local' => 'lastaction',
				'foreign' => 'id'
			)
		);
		$this->hasMany('Project as Projects', array(
				'local' => 'id',
				'foreign' => 'leader'
			)
		);
		$this->hasMany('Permissions as Permissions', array(
				'local' => 'id',
				'foreign' => 'staff'
			)
		);
		$this->hasMany('Task as Tasks', array(
				'local' => 'id',
				'foreign' => 'staff'
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
		// we can't use $this['id'] as a salt because it's not set yet when we're creating a new user.
		// find a workaround?
		//$this->_set('password', hash('sha256', $this['id'] . $this['nickname'] . $password));
		$this->_set('password', hash('sha256', $this['nickname'] . $password));
	}
}
