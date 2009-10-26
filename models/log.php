<?php

class Log extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->hasColumn('id', 'integer', 10, array(
				'primary' => true,
				'unsigned' => true
			)
		);
		$this->hasColumn('time', 'timestamp');
		$this->hasColumn('message', 'string', 10000);
	}

	public function setUp()
	{
	}
}

?>
