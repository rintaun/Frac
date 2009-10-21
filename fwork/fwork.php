<?php
/*
 * Fwork
 * Copyright (c) 2009 Matthew Lanigan
 *                    Tony Young
 *
 * See COPYING for license conditions.
 */

if(!defined("IN_FWORK_")) die("This file cannot be invoked directly.");

require_once(dirname(__FILE__) . "../config.php");
require_once(dirname(__FILE__) . "../lib/Doctrine/Doctrine.php");

/**
 * Fwork core class.
 *
 * This class is the core of the Fwork framework.
 */
class Fwork
{
    
    /**
     * Singleton manager for Doctrine.
     */
    private $doctrine_manager;    
    
    /**
     * Constructor for Fwork.
     *
     * Initialises connections.
     */
    public function __construct()
    {
        spl_autoload_register(array("Doctrine", "autoload"));
        
        // we should now connect to the database
        $this->dbconnection = Doctrine_Manager::connection($config["database"])
    }
    
    /**
     * Destructor for Fwork.
     *
     * Currently also does nothing.
     */
    public function __destruct() { }
}
