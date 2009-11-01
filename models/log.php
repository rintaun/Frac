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
		$this->setTableName('log');

		$this->hasColumn('id', 'integer', 10, array(
				'primary' => true,
				'unsigned' => true
			)
		);
		$this->hasColumn('time', 'timestamp', null, array(
				'notnull' => true
			)
		);
		$this->hasColumn('message', 'clob', null, array(
				'notnull' => true
			)
		);
	}

	public function setUp()
	{
		$this->hasOne('Staff as Staff', array(
			'local' => 'id',
			'foreign' => 'lastaction'
		);
	}
}
