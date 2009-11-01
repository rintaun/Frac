<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

if(!defined('IN_FRAC_')) die('This file cannot be invoked directly.');

/**
 * Model for project-level permissions.
 */
class Permissions extends Doctrine_Record
{

	/**
	 * Columns for permissions
	 * project	- integer(11) - Reference to Project ID
	 * staff	- integer(11) - Reference to Staff ID
	 * auth		- integer(10) - Permission flags
	 */
	public function setTableDefinition()
	{
		$this->setTableName('permissions');
		
		$this->hasColumn('project', 'integer', null, array(
				'unsigned' => true,
				'primary' => true
			)
		);
		$this->hasColumn('staff', 'integer', null, array(
				'unsigned' => true,
				'primary' => true
			)
		);
		$this->hasColumn('role','integer', null, array(
				'notnull' => true,
			)
		);
	}

	public function setUp()
	{
		$this->hasOne('Project as Project', array(
				'local' => 'project',
				'foreign' => 'id'
			)
		);
		$this->hasOne('Staff as Staff', array(
				'local' => 'staff',
				'foreign' => 'id'
			)
		);
		$this->hasOne('Role as Role', array(
				'local' => 'role',
				'foreign' => 'id'
			)
		);
	}
}
