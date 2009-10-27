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
		$this->setTableName("tasktrees");
		
		$this->hasColumn("task", "integer", 10, array(
				"unsigned" => true,
				"notnull" => true,
				"primary" => true
			)
		);
		$this->hasColumn("nexttask", "integer", 10, array(
				"unsigned" => true,
				"notnull" => true,
				"primary" => true
			)
		);
	}
	public function setUp()
	{
		$this->hasOne("Task as Task", array(
				"local" => "task",
				"foreign" => "id"
			)
		);
		$this->hasOne("Task as NextTask", array(
				"local" => "nexttask",
				"foreign" => "id"
			)
		);
	}
}
