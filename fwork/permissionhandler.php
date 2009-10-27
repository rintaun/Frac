<?php

// define permissions. please discuss renaming before doing so.
define('PERM_EDIT_SETTINGS',	0x00000001); // allowed to edit Frac admin settings
define('PERM_MANAGE_TASKTYPES',	0x00000002); // allowed to manage task type definitions
				
define('PERM_CREATE_STAFF',	0x00000004); // allowed to add new staff accounts
define('PERM_EDIT_STAFF',	0x00000008); // allowed to edit staff accounts
define('PERM_DELETE_STAFF',	0x00000010); // allowed to delete staff accounts
				
define('PERM_CREATE_PROJECT',	0x00000020); // allowed to add new projects
define('PERM_EDIT_PROJECT',	0x00000040); // allowed to edit project settings
define('PERM_DELETE_PROJECT',	0x00000080); // allowed to delete projects
				
define('PERM_CREATE_EPISODE',	0x00000100); // allowed to create new episodes
define('PERM_EDIT_EPISODE',	0x00000200); // allowed to edit episode settings
define('PERM_DELETE_EPISODE',	0x00000400); // allowed to delete projects
				
define('PERM_CREATE_TEMPLATE',	0x00000800); // allowed to create tasktree templates
define('PERM_EDIT_TEMPLATE',	0x00001000); // allowed to edit tasktree templates
define('PERM_DELETE_TEMPLATE',	0x00002000); // allowed to delete tasktree templates
				
define('PERM_CREATE_TASKS',	0x00004000); // allowed to add new tasks to an episode
define('PERM_EDIT_TASKS',	0x00008000); // allowed to edit (e.g. change relationships of) tasks on an episode
define('PERM_DELETE_TASKS',	0x00010000); // allowed to delete tasks from an episode
define('PERM_ASSIGN_TASKS',	0x00020000); // allowed to assign tasks on an episode to a staff member
define('PERM_REASSIGN_TASKS',	0x00040000); // allowed to reassign tasks on an episode to a staff member
define('PERM_UNASSIGN_TASKS',	0x00040000); // alias for PERM_REASSIGN_TASKS simply for code semantics ;p

/*
you may notice that there are no *_VIEW_* permissions. this is intentional.
basically, it is my philosophy that the workings of a group should be open to the eyes of its members.
following that philosophy, any staff can view the settings of any other staff (i.e. email and such, ofc not password)
and any staff can see the admin settings. There aren't hidden pages or links except to people who aren't logged in.
It's just when they try to click on the "DELETE PROJECT" link, it will give them a permission error with a "GO BACK" link.
this model is more efficient, too, since we don't have to check permissions except when a user actually tries to do something.
after all, they may have PERM_CREATE_TASKS (or anything else) on a specific project, but not on any others (for example)

if we're missing anything, please add to the list after consultation.

So. We need something to handle permissions. This is it.
Maybe we should make this into a singleton or something?
Who knows! For now,
[12:40] Chibu: 8 - FUCKIN' RAPID!
*/

final class PermissionHandler extends Singleton
{
	protected $global;
	protected $local;
	
	public $id;
	
	public static function getInstance()
	{
		$c = get_class();
		if(!isset(self::$instances[$c])) self::$instances[$c] = new $c;
		return self::$instances[$c];
	}
	
	protected function __construct()
	{
		$this->global = 0;
		$this->local = array();
		
		$this->id = -1;
	}
		
	// accept a user id as input. with this, we'll pull permissions from all over the database.
	public function init()
	{
		$id = $this->id; // too lazy to change $id to $this->id
		
		// if $id is not a number of some sort, then gtfo
		if ((!is_numeric($id)) && (!is_int($id))) return null;

		$q = Doctrine_Query::create()
			->select('s.auth, p.project, p.auth')
			->from('Staff s')
			->leftJoin('s.Permissions p')
			->where('s.id = ?', $id);

		$p = $q->fetchArray();

		// if $p is empty or not an array, then gtfo
		if ((!is_array($p[0])) || (empty($p[0]))) return null;
		
		$this->id = $id;
		$this->global = $p[0]['auth'];
		foreach ($p[0]['Permissions'] as $row)
		{
			// staff id / project id combinations are uniqe, so each of these should only get set once.
			$this->local[$row['project']] = $row['auth'];
		}
	}

	public function allowedto($type, $project=null)
	{
		if(empty($this->local)) $this->init();
		
		// if the flag is set in $this->global, return true, always.
		if ($this->global & $type) return true;

		// if $project (the project id) is null, then we only check global
		// since it clearly wasn't in global, flag isn't set.
		if ($project == null) return false;

		// if the flag is set in local for $project, okay.
		if ($this->local[$project] & $type) return true;

		// if we got this far it means the flag wasn't set in local or global, so return false
		return false;
	}

	// we might want to add things like set() and unset(), but for the moment they are essentially irrelevant.

}
