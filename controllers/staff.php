<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

class StaffController extends Controller
{   
	public function index($args) // list staff members
	{
		$q = Doctrine_Query::create()
				->select('s.id, s.nickname')
				->from('Staff s')
				->orderby('s.id ASC');
				
		$staff = $q->execute();
		$result = array();
		for($i = 0; $i < count($staff); $i++)
		{
			$user = $staff->get($i);
			$result[] = array($user->id, $user->nickname);
		}
		
		$this->vars['staff'] = $result;
	}

	public function display($args) // display a staff member profile
	{	
		if(count($args) == 0)
		{
			$this->view = null;
			Utils::redirect('staff/');
			return;
		}
		
		$staff = $args[0];
		
		$q = Doctrine_Query::create()
				->select('*')
				->from('Staff s')
				->where('s.id = ?', $staff)
				->limit(1);
				
		$this->vars['user'] = $q->execute()->get(0);
	}

	public function create($args) // create a new staff member
	{
		$p = PermissionHandler::getInstance();
		// do we have an error thing?
		if (!$p->allowedto(PERM_CREATE_STAFF))
		{
			Utils::error("You don't have permission to edit staff members.");
			return;
		}
	}

	public function delete($args) // delete a staff member
	{
		if(count($args) == 0)
		{
			$this->view = null;
			Utils::redirect('staff/');
			return;
		}
	
		$p = PermissionHandler::getInstance();
		// do we have an error thing?
		if (!$p->allowedto(PERM_DELETE_STAFF))
		{
			Utils::error("You don't have permission to delete staff members.");
			return;
		}
	}

	public function edit($args) // edit a staff member profile
	{
		if(count($args) == 0)
		{
			$this->view = null;
			Utils::redirect('staff/');
			return;
		}
	
		$staff = $args[0];

		$p = PermissionHandler::getInstance();
		$session = SesMan::getInstance();
		// do we have an error thing?
		// PERM_EDIT_STAFF is different slightly, since they are always allowed to edit their own profile.
		if ((!$p->allowedto(PERM_EDIT_STAFF)) && ($staff != $session['staffid']))
		{
			Utils::error("You don't have permission to edit other staff members.");
			return;
		}
	}

	public function login($args) // login a staff member
	{
		// if there aren't postvars, nobody has tried to login. set variables and exit.
		if (!isset($_POST['nickname']))
		{
			// there aren't any variables to set just yet.
		}
		// if there ARE postvars, however, then we have a login attempt. process it.
		else if (isset($_POST['nickname']))
		{
			// now send Doctrine to get the relevant Staff from the database.
			$q = Doctrine_Query::create()
				->select('s.id, s.nickname, s.password')
				->from('Staff s')
				->where('s.nickname = ?', $_POST['nickname']);

			$accounts = $q->fetchArray();

			// if there's more than one, then something is fishy. get the hell out of dodge.
			// by the way, this should never happen; nicknames are unique.
			if (count($accounts) > 1)
			{
				// put in an error message or something
				// "There seems to have been some kind of error. Please contact an administrator."
				return;
			}
			// if there are 0 accounts listed, then the username was wrong; but don't tell them that.
			// ambiguity ftw
			else if (count($accounts) == 0)
			{
				// put in an error message or something
				// "Invalid username and/or password. Please try again."
				return;
			}
			// if there's only 1 account listed, then check the password.
			else if (count($accounts) == 1)
			{
				// get our password hash
				$checkPass =  hash("sha256", $accounts[0]['nickname'] . $_POST['password']);

				// the password is wrong. give them an ambiguous error message.
				if ($checkPass != $accounts[0]['password'])
				{
					// put in an error message or something
					// "Invalid username and/or password. Please try again."
					return;
				}

				// if we've gotten this far, then the password must be right! log the user in.

				$this->session['staffid'] = $accounts[0]['id'];
				// and make sure we display the correct view
				Utils::redirect("index");

				// we probably want to redirect so that these postvars aren't sitting around...
			}
		}
	}

	public function logout($args) // logout a staff member
	{
		$session = SesMan::getInstance();
		$session->flush();
		$this->view = null;
		Utils::redirect("staff/login");
	}
}
