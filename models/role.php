<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

class Role extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->setTableName('roles');

		$this->hasColumn('id', 'integer', null, array(
				'unsigned' => true,
				'primary' => true,
				'autoincrement' => true
			)
		);
		$this->hasColumn('name', 'string', 32, array(
				'notnull' => true
			)
		);
		$this->hasColumn('auth', 'integer', null, array(
				'notnull' => true
			)
		);
		$this->hasColumn('created', 'timestamp', null, array(
				'notnull' => true
			)
		);
	}

	public function setUp()
	{
		$this->hasMany('Staff as Staff', array(
				'local' => 'id',
				'foreign' => 'role'
			)
		);
		$this->hasMany('Permissions as Permissions', array(
				'local' => 'id',
				'foreign' => 'role'
			)
		);
	}
}
