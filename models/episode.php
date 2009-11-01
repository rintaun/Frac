<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

class Episode extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->setTableName('episodes');

		$this->hasColumn('id', 'integer', null, array(
				'unsigned' => true,
				'primary' => true,
				'autoincrement' => true
			)
		);
		$this->hasColumn('project', 'integer', null, array(
				'notnull' => true,
				'unsigned' => true
			)
		);
		$this->hasColumn('episode', 'integer', null, array(
				'notnull' => true,
				'unsigned' => true
			)
		);
		$this->hasColumn('title', 'string', 255);
		$this->hasColumn('airdate', 'date');
		$this->hasColumn('created', 'timestamp', null, array(
				'notnull' => true
			)
		);

		$this->unique('project', 'episode');
	}

	public function setUp()
	{
		$this->hasOne('Project as Project', array(
				'local' => 'project',
				'foreign' => 'id'
			)
		);
		$this->hasMany('Task as Tasks', array(
				'local' => 'id',
				'foreign' => 'episode'
			)
		);
	}
}
