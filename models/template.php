<?php

class Template extends Doctrine_Record
{

	public function setTableDefinition()
	{
		$this->hasColumn("id", "integer", 10, array(
				"notnull" => true,
				"unsigned" => true,
				"primary" => true
			)
		);
		$this->hasColumn("model", "string", 10240);
	}
}
