<?php

class Setting extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->setTableName('settings');

		$this->hasColumn('name', 'string', 32, array(
				'primary' => 'true',
				'notblank' => 'true'
			)
		);
		$this->hasColumn('value', 'string', 255, array(
				'notnull' => true
			)
		);
	}

	public function setUp()
	{
	}
}
