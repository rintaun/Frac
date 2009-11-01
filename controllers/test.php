<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

class TestController extends Controller
{   
	public function index($args)
	{
	        // stuff goes here
		$this->vars['action'] = "index";
	}
}
