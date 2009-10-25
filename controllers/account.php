<?php
// NOTE: we probably need site-wide session management of some sort so that we're not checking $this->session constantly.
// it should only be necessary to use $this->session 1) to check if the user is logged in, and then 2) to set login variables.

class AccountController extends Controller
{   
	public function index($args)
	{
		$this->useView = null;
		
	        // check if we're logged in; if not, default to login.
		// it may be necessary to alter this session management mechanism a bit,
		// but for now, it works.
		if (!isset($this->session["staffid"]))
		{
			Utils::redirect("account/login");
			return;
		}

		// if we are logged in, show the My Account page instead.
		else
		{
			Utils::redirect("staff/display/" . $this->session["staffid"]);
			return;
		}
	}

	public function login($args)
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
				->where('s.nickname = ?',$_POST['nickname']);

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

	public function logout($args)
	{
	}
}
