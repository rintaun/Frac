<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

if(!defined('IN_FRAC_')) die('This file cannot be invoked directly.');

/**
 * Model for a project.
 */
class Project extends Doctrine_Record
{

	/**
	 * Columns for a project.
	 *
	 * id        -  integer(11)   -  Auto-incremeting ID primary key.
	 * name      -  varchar(128)  -  Name of the project; generally the anime title.
	 * shortname -  varchar(16)   -  Short name for the project. Example: 'bake' for 'Bakemonogatari'
	 * episodes  -  integer(11)   -  Episode count of the project, if known.
	 * leader    -  integer(11)   -  Staff ID of the project leader; possibly deprecated due to project-specific permissions.
	 * description - string(1000) -  Description or synopsis of the series.
	 */
	public function setTableDefinition()
	{
		$this->setTableName('projects');

		$this->hasColumn('id', 'integer', null, array(
				'primary' => true,
				'autoincrement' => true,
				'unsigned' => true
			)
		);
		$this->hasColumn('name', 'string', 128, array(
				'unique' => true,
				'notnull' => true
			)
		);
		$this->hasColumn('shortname', 'string', 16, array(
				'unique' => true,
				'notnull' => true
			)
		);
		$this->hasColumn('episodes', 'integer', null, array(
				'unsigned' => true,
				'notnull' => true
			)
		);
		$this->hasColumn('leader', 'integer', null, array(
				'unsigned' => true
			)
		);
		$this->hasColumn('description', 'clob');
		$this->hasColumn('template', 'integer', null, array(
				'unsigned' => true
			)
		);
		$this->hasColumn('syoboi_id', 'integer', null, array(
				'unsigned' => true
			)
		);
		$this->hasColumn('anidb_id', 'integer', null, array(
				'unsigned' => true
			)
		);
		$this->hasColumn('created', 'timestamp', null, array(
				'notnull' => true
			)
		);
	}

	public function setUp()
	{
		$this->hasOne('Staff as Leader', array(
				'local' => 'leader',
				'foreign' => 'id'
			)
		);
		$this->hasOne('Template as Template', array(
				'local' => 'template',
				'foreign' => 'id'
			)
		);
		$this->hasMany('Permissions as Permissions', array(
				'local' => 'id',
				'foreign' => 'project'
			)
		);
		$this->hasMany('Episode as Episodes', array(
				'local' => 'id',
				'foreign' => 'project'
			)
		);
	}
}
