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
		$this->vars['staff'] = Doctrine_Query::create()
				->select('id, nickname, email')
				->from('Staff')
				->orderby('id ASC')
				->fetchArray();
	}

	public function display($args) // display a staff member profile
	{	
		if(count($args) == 0)
		{
			$this->view = null;
			Utils::redirect('staff');
			return;
		}
		
		$user = Doctrine_Query::create()
				->from('Staff')
				->where('id = ?', $args[0])
				->limit(1)
				->execute()
				->get(0);
		
		$this->vars['pagename'] = 'Staff :: ' . $user['nickname'];
		$this->vars['user'] = $user;
	}

	public function create($args) // create a new staff member
	{
		$p = PermissionHandler::getInstance();

		if (!$p->allowedto(PermissionHandler::PERM_CREATE_STAFF))
		{
			Utils::error("You don't have permission to edit staff members.");
			return;
		}
		
		// No postvars means there was no creation attempt. Do form stuff.
		if(!isset($_POST['nickname']))
		{
			$permissions = array();
			$permissionReflector = new ReflectionClass('PermissionHandler');
			foreach($permissionReflector->getConstants() as $key => $value)
			{
				if(substr($key, 0, 4) === 'PERM')
				{
					$permissions[] = array(ucwords(strtolower(str_replace('_', ' ', substr($key, 4)))), $value);
				}
			}
			$this->vars['permissions'] = $permissions;
			
			$this->vars['headextra'] = <<<EOS
				<script type="text/javascript"><!--
					var formblock;
					var forminputs;

					function prepare() {
						formblock = document.getElementById('createform');
						forminputs = formblock.getElementsByTagName('input');
					}

					function select_all(name, value) {
						for (i = 0; i < forminputs.length; i++) {
							if (forminputs[i].getAttribute('name') == name) {
								if (value == '1') {
									forminputs[i].checked = true;
								} else {
									forminputs[i].checked = false;
								}
							}
						}
					}

					if (window.addEventListener) {
						window.addEventListener("load", prepare, false);
					} else if (window.attachEvent) {
						window.attachEvent("onload", prepare)
					} else if (document.getElementById) {
						window.onload = prepare;
					}
				//--></script>
EOS;
		}
		else
		{
			// Deal with missing variables and stuff.
			if(empty($_POST['nickname']) || empty($_POST['email']))
			{
				Utils::error('You left out a required field. Please fill the form out again.');
				return;
			}
			
			// Validate permissions.
			$perm = 0;
			if(isset($_POST['perms']) && is_array($_POST['perms']))
			{
				foreach($_POST['perms'] as $value)
				{
					$perm &= (int) $value;
				}
			}

			// Generate random password if we need to. Otherwise use the specified one.
			if(empty($_POST['password']))
			{
				$possible = "0123456789abcdefghijklmnopqrstuvwxyz"; $i = 0;  
				while ($i < 6)
				{ 
					$char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
					
					if (!strstr($_POST['password'], $char))
					{
						$_POST['password'] .= $char;
						$i++;
					}
				}
			}
			else if($_POST['password'] !== $_POST['password2'])
			{
				Utils::error('Entered passwords do not match.');
				return;
			}
			
			// Create new staff...lol.
			$user = new Staff();
			$user->nickname = $_POST['nickname'];
			$user->password = hash('sha256', $_POST['nickname'] . $_POST['password']);
			$user->comment = $_POST['comment'];
			$user->email = $_POST['email'];
			$user->cell = $_POST['mobile'];
			$user->auth = $perm;

			if(!$user->isValid())
			{
				Utils::error('Invalid form data entered. Please fill out the form again.');
				return;
			}
			
			try
			{
				$user->save();
			}
			catch(Doctrine_Exception $e)
			{
				Utils::error('A database error occurred: ' . $e->errorMessage());
				return;
			}
			
			// ALL ACCORDING TO KEIKAKU.
			// NOTE: Keikaku means plan.
			Utils::success('User successfully created.', 'staff');
		}
	}

	public function delete($args) // delete a staff member
	{
		if(count($args) == 0)
		{
			$this->view = null;
			Utils::redirect('staff');
			return;
		}
	
		$p = PermissionHandler::getInstance();

		if (!$p->allowedto(PermissionHandler::PERM_DELETE_STAFF))
		{
			Utils::error('You don\'t have permission to delete staff members.');
			return;
		}
	}

	public function edit($args) // edit a staff member profile
	{
		if(count($args) == 0)
		{
			$this->view = null;
			Utils::redirect('staff');
			return;
		}
	
		$staff = $args[0];

		$p = PermissionHandler::getInstance();
		$session = SesMan::getInstance();

		// PERM_EDIT_STAFF is different slightly, since they are always allowed to edit their own profile.
		if ((!$p->allowedto(PermissionHandler::PERM_EDIT_STAFF)) && ($staff != $session['staffid']))
		{
			Utils::error('You don\'t have permission to edit other staff members.');
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
				Utils::error('An error occurred while retrieving information from the database. Please contact an administrator.');
				return;
			}
			// if there are 0 accounts listed, then the username was wrong; but don't tell them that.
			// ambiguity ftw
			else if (count($accounts) == 0)
			{
				Utils::error('Invalid username and/or password. Please try again.');
				return;
			}
			// if there's only 1 account listed, then check the password.
			else if (count($accounts) == 1)
			{
				// get our password hash
				$checkPass =  hash('sha256', $accounts[0]['nickname'] . $_POST['password']);

				// the password is wrong. give them an ambiguous error message.
				if ($checkPass != $accounts[0]['password'])
				{
					Utils::error('Invalid username and/or password. Please try again.');
					return;
				}

				// if we've gotten this far, then the password must be right! log the user in.

				$this->session['staffid'] = $accounts[0]['id'];
				// and make sure we display the correct view
				Utils::redirect('index');

				// we probably want to redirect so that these postvars aren't sitting around...
			}
		}
	}

	public function logout($args) // logout a staff member
	{
		$session = SesMan::getInstance();
		$session->flush();
		$this->view = null;
		Utils::redirect('staff/login');
	}
	
}
