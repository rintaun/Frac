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

		$this->hasMany("Task as PrevTask", array(
				"local" => "id",
				"foreign" => "nexttask",
				"RefClass" => "TaskTree"
			)
		);
		$this->hasMany("Task as NextTask", array(
				"local" => "id",
				"foreign" => "task",
				"RefClass" => "TaskTree"
			)
		);
	}
}
