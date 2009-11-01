<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

class Template extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->setTableName('templates');

		$this->hasColumn('id', 'integer', null, array(
				'notnull' => true,
				'unsigned' => true,
				'primary' => true
			)
		);
		$this->hasColumn('name', 'string', 32, array(
				'notnull' => true
			)
		);
		$this->hasColumn('model', 'clob', null, array(
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
		$this->hasMany('Project as Projects', array(
				'local' => 'id',
				'foreign' => 'template'
			)
		);
	}
}
