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
		$this->setTableName("tasks");
		$this->hasColumn("id", "integer", 10, array(
				"notnull" => true,
				"unsigned" => true,
				"primary" => true,
				"autoincrement" => true
			)
		);
		$this->hasColumn("episode", "integer", 10, array(
				"unsigned" => true,
				"notnull" => true
			)
		);
		$this->hasColumn("tasktype", "integer", 10, array(
				"unsigned" => true,
				"notnull" => true
			)
		);
		$this->hasColumn("description", "string", 1000);
		$this->hasColumn("assignedto", "integer", 10, array(
				"unsigned" => true
			)
		);
		$this->hasColumn("active", "boolean");
		$this->hasColumn("finished", "boolean");

                $this->setAttribute(Doctrine::ATTR_VALIDATE, true);
	}
	public function setUp()
	{
		$this->hasOne("Episode as Episode", array(
				"local" => "episode",
				"foreign" => "id"
			)
		);
		$this->hasOne("TaskType as TaskType", array(
				"local" => "tasktype",
				"foreign" => "id"
			)
		);
		$this->hasOne("Staff as AssignedStaff", array(
				"local" => "assignedto",
				"foreign" => "id"
			)
		);

		$this->hasMany("Task as Parents", array(
				"local" => "id",
				"foreign" => "nexttask",
				"RefClass" => "TaskTree"
			)
		);
		$this->hasMany("Task as Children", array(
				"local" => "id",
				"foreign" => "task",
				"RefClass" => "TaskTree"
			)
		);
	}
	public function setActive($boolean)
	{
		// if it's false, just set it and be done.
		if ($boolean == false)
		{
			$this->_set("active", false);
			return;
		}
		// if it's true, then notify people.

		// notifyPeople();
	}

	// this can probably be more efficient...
	public function setFinished($boolean)
	{
		// if it's false, just set it and be done.
		if ($boolean == false)
		{
			$this->_set("finished", false);
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
