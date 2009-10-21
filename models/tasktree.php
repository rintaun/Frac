<?php

class TaskTree extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->setTableName($config["database"]["prefix"] . "tasktrees");
		
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
