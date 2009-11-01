<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

class TaskTree extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->setTableName('tasktrees');

		$this->hasColumn('parent', 'integer', null, array(
				'unsigned' => true,
				'primary' => true
			)
		);
		$this->hasColumn('child', 'integer', null, array(
				'unsigned' => true,
				'primary' => true
			)
		);
	}

	public function setUp()
	{
		$this->hasOne('Task as Parent', array(
				'local' => 'parent',
				'foreign' => 'id'
			)
		);
		$this->hasOne('Task as Child', array(
				'local' => 'child',
				'foreign' => 'id'
			)
		);
	}
}
