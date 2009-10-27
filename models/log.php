<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

class Log extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->setTableName('logs');
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
