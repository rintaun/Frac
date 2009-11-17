<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

class EpisodesController extends Controller
{   
	public function index($args) // == display
	{
		$this->display($args);
	}

	// $args should have the episode ID.
	public function display($args) // display an episode
	{
		$q = Doctrine_Query::create()
			->from('Task t')
			->leftJoin('t.Episode e')
			->where('e.id = ?', $args);
		$tasks = $q->execute();
	}

	public function create($args) // create an episode
	{
		$p = PermissionHandler::getInstance();
		// do we have an error thing?
		if (!$p->allowedto(PermissionHandler::PERM_CREATE_EPISODE))
		{
			Utils::error('You don\'t have permission to create episodes.');
			return;
		}
	}

	public function delete($args) // delete an episode
	{
		$project = $args[0];

		$p = PermissionHandler::getInstance();
		// do we have an error thing?
		if (!$p->allowedto(PermissionHandler::PERM_DELETE_EPISODE, $project))
		{
			Utils::error('You don\'t have permission to delete this episode.');
			return;
		}

	}

	public function edit($args) // edit an episode's settings
	{
		$project = $args[0];

		$p = PermissionHandler::getInstance();
		// do we have an error thing?
		if (!$p->allowedto(PermissionHandler::PERM_EDIT_EPISODE, $project))
		{
			Utils::error('You don\'t have permission to edit this episode.');
			return;
		}

	}
}
