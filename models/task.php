<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

class Task extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->setTableName('tasks');

		$this->hasColumn('id', 'integer', null, array(
				'unsigned' => true,
				'primary' => true,
				'autoincrement' => true
			)
		);
		$this->hasColumn('episode', 'integer', null, array(
				'unsigned' => true,
				'notnull' => true
			)
		);
		$this->hasColumn('tasktype', 'integer', null, array(
				'unsigned' => true,
				'notnull' => true
			)
		);
		$this->hasColumn('description', 'clob');
		$this->hasColumn('assignedto', 'integer', null, array(
				'unsigned' => true
			)
		);
		$this->hasColumn('active', 'boolean', null, array(
				'notnull' => true,
				'default' => 0
			)
		);
		$this->hasColumn('finished', 'boolean', null, array(
				'notnull' => true,
				'default' => 0
			)
		);
		$this->hasColumn('created', 'timestamp', null, array(
				'notnull' => true
			)
		);
	}

	public function setUp()
	{
		$this->hasOne('Episode as Episode', array(
				'local' => 'episode',
				'foreign' => 'id'
			)
		);
		$this->hasOne('TaskType as TaskType', array(
				'local' => 'tasktype',
				'foreign' => 'id'
			)
		);
		$this->hasOne('Staff as AssignedTo', array(
				'local' => 'assignedto',
				'foreign' => 'id'
			)
		);
		$this->hasMany('Task as Parents', array(
				'local' => 'id',
				'foreign' => 'child',
				'refClass' => 'TaskTree'
			)
		);
		$this->hasMany('Task as Children', array(
				'local' => 'id',
				'foreign' => 'parent',
				'refClass' => 'TaskTree'
			)
		);
	}
	public function setActive($boolean)
	{
		// if it's false, just set it and be done.
		if ($boolean == false)
		{
			$this->_set('active', false);
			return;
		}
		// if it's true, then SET IT ACTIVE and notify people.
		$this->_set('active', true);
		// notifyPeople();
	}

	// this can probably be more efficient...
	public function setFinished($boolean)
	{
		// if it's false, just set it and be done.
		if ($boolean == false)
		{
			$this->_set('finished', false);
			return;
		}
		// if it's true, then check and activate children.
		foreach ($this->Children as $node)
		{
			$child = $node->nexttask;
			foreach ($child->Parents AS $pnode)
			{
				if ($pnode->task->finished == false) continue 2;
			}
			$child->active = true;
		}
	}
}
