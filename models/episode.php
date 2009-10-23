<?php

class Episode extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->setTableName("episodes");
		$this->hasColumn("id", "integer", 10, array(
				"notnull" => true,
				"unsigned" => true,
				"primary" => true,
				"autoincrement" => true
			)
		);
		$this->hasColumn("project", "integer", 10, array(
				"notnull" => true,
				"unsigned" => true
			)
		);
		$this->hasColumn("episode", "integer", 10, array(
				"notnull" => true,
				"unsigned" => true
			)
		);
		$this->hasColumn("title", "string", 255);
		$this->hasColumn("airdate", "date");
	}
	public function setUp()
	{
		$this->hasOne("Project as Project", array(
				"local" => "project",
				"foreign" => "id"
			)
		);
		$this->hasMany("Task as Tasks", array(
				"local" => "id",
				"foreign" => "episode"
			)
		);
	}
}
