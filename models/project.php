<?php
/*
 * Frac
 * Copyright (c) 2009 Matthew Lanigan
 *                    Tony Young
 *
 * See COPYING for license conditions.
 */

if(!defined("IN_FRAC_")) die("This file cannot be invoked directly.");

/**
 * Model for a project.
 */
class Project extends Doctrine_Record
{

    /**
     * Columns for a staff member.
     *
     * id        -  integer(11)   -  Auto-incremeting ID primary key.
     * name      -  varchar(128)  -  Name of the project; generally the anime title.
     * shortname -  varchar(16)   -  Short name for the project. Example: "bake" for "Bakemonogatari"
     * episodes  -  integer(11)   -  Episode count of the project, if known.
     * leader    -  integer(11)   -  Staff ID of the project leader; possibly deprecated due to project-specific permissions.
     * description - string(1000) -  Description or synopsis of the series.
     */
    public function setTableDefinition()
    {
	$this->setTableName('projects');
        $this->hasColumn("id", "integer", 10, array(
                "primary" => true,
                "autoincrement" => true,
		"unsigned" => true,
		"notnull" => true
            )
        );
        
        $this->hasColumn("name", "string", 128, array(
                "unique" => true,
		"notnull" => true
            )
        );
        
        $this->hasColumn("shortname", "string", 16, array(
                "unique" => true,
		"notnull" => true
            )
        );
        
        $this->hasColumn("episodes", "integer", 10, array(
                "default" => 0,
		"unsigned" => true
            )
        );

        $this->hasColumn("leader", "integer", 10, array(
                "unsigned" => true,
                "notnull" => true
            )
        );

        $this->hasColumn("description", "clob");
    }
    public function setUp()
    {
        $this->hasOne("Staff as Leader", array(
                "local" => "leader",
                "foreign" => "id"
            )
        );
    }
}
