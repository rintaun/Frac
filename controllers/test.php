<?php
class TestController extends Controller
{   
	public function index($args)
	{
	        // stuff goes here
		$this->vars['action'] = "index";
	}

	function insertDummyData()
	{
		$this->vars['action'] = "insertDummyData";
		$staff = array(
			array(
				'nickname' => 'rintaun',
				'password' => 'changeme',
				'email' => 'rintaun@gmail.com',
				'cell' => '4122603246',
				'comment' => 'Master of Awesomeness',
				'auth' => 0xFFFFFFFF
			),
			array(
				'nickname' => 'Lefty',
				'password' => 'changeme',
				'email' => 'flamez167@gmail.com',
				'comment' => 'Not a righty',
				'auth' => 0x0
			),
			array(
				'nickname' => 'rofflwaffls',
				'password' => 'changeme',
				'email' => 'rofflwaffls@gmail.com',
				'comment' => 'That other guy',
				'auth' => 0xFFFFFFFF
			)
		);
		foreach ($staff AS $cur_staff)
		{
			$user = new Staff();
			$user->fromArray($cur_staff);
			$user->save();
			$users[$user['nickname']] = $user['id'];
			unset($user);
		}

		$projects = array(
			array(
				'name' => 'The Sacred Blacksmith',
				'shortname' => 'bsmith',
				'episodes' => 12,
				'leader' => $users['Lefty'],
				'description' => "We'll fill this in later."
			),
			array(
				'name' => 'Bakemonogatari',
				'shortname' => 'bake',
				'episodes' => 15,
				'leader' => $users['rintaun'],
				'description' => "We'll fill this in later."
			)
		);
		foreach ($projects AS $cur_project)
		{
			$project = new Project();
			$project->fromArray($cur_project);
			$project->save();
			unset($project);
		}

		$permissions = new Permissions();
		$permissions->fromArray(array(
			'project' => 1,
			'staff' => 2,
			'auth' => 0xFFFFFFFF
		));
		$permissions->save();
		
		$tasktypes = array(
			array('name' => 'Raw Cap'),
			array('name' => 'Translate'),
			array('name' => 'Time'),
			array('name' => 'Translation Check'),
			array('name' => 'Typeset'),
			array('name' => 'Edit'),
			array('name' => 'Encode'),
			array('name' => 'Quality Check'),
			array('name' => 'Karaoke'),
			array('name' => 'Miscellaneous'),
			array('name' => 'Translate Signs'),
			array('name' => 'Release'),
		);
		foreach ($tasktypes AS $cur_type)
		{
			$tasktype = new TaskType();
			$tasktype->fromArray($cur_type);
			$tasktype->save();
			unset($tasktype);
		}

		$episodes = array(
			array(
				'project' => 1,
				'episode' => 2,
				'title' => '',
				'airdate' => ''
			),
			array(
				'project' => 1,
				'episode' => 3,
				'title' => '',
				'airdate' => ''
			),
			array(
				'project' => 2,
				'episode' => 11,
				'title' => '',
				'airdate' => ''
			),
			array(
				'project' => 2,
				'episode' => 12,
				'title' => '',
				'airdate' => ''
			)
		);
		foreach ($episodes AS $cur_ep)
		{
			$episode = new Episode();
			$episode->fromArray($cur_ep);
			$episode->save();
			unset($episode);
		}
	
		$tasks = array(
			array(
				'episode' => 1,
				'tasktype' => 1,
				'description' => '',
				'active' => true,
				'finished' => false
			),
			array(
				'episode' => 1,
				'tasktype' => 2,
				'description' => '',
				'active' => false,
				'finished' => false,
			),
			array(
				'episode' => 1,
				'tasktype' => 3,
				'assignedto' => 2,
				'description' => '',
				'active' => false,
				'finished' => false,
			),
			array(
				'episode' => 1,
				'tasktype' => 4,
				'assignedto' => 1,
				'description' => '',
				'active' => false,
				'finished' => false,
			),
			array(
				'episode' => 1,
				'tasktype' => 6,
				'description' => '',
				'active' => false,
				'finished' => false,
			),
			array(
				'episode' => 1,
				'tasktype' => 11,
				'assignedto' => 1,
				'description' => '',
				'active' => false,
				'finished' => false,
			),
			array(
				'episode' => 1,
				'tasktype' => 6,
				'description' => '',
				'active' => false,
				'finished' => false,
			),
			array(
				'episode' => 1,
				'tasktype' => 5,
				'description' => '',
				'active' => false,
				'finished' => false,
			),
			array(
				'episode' => 1,
				'tasktype' => 7,
				'description' => '',
				'active' => false,
				'finished' => false,
			),
			array(
				'episode' => 1,
				'tasktype' => 8,
				'description' => '',
				'active' => false,
				'finished' => false,
			),
			array(
				'episode' => 1,
				'tasktype' => 8,
				'description' => '',
				'active' => false,
				'finished' => false,
			),
			array(
				'episode' => 1,
				'tasktype' => 12,
				'description' => '',
				'active' => false,
				'finished' => false,
			)
		);
		foreach ($tasks AS $cur_task)
		{
			$task = new Task();
			$task->fromArray($cur_task);
			$task->save();
			unset($task);
		}
	
		$tasktree = array(
			array('task' => 1, 'nexttask' => 2),
			array('task' => 2, 'nexttask' => 3),
			array('task' => 2, 'nexttask' => 4),
			array('task' => 3, 'nexttask' => 5),
			array('task' => 4, 'nexttask' => 5),
			array('task' => 1, 'nexttask' => 6),
			array('task' => 6, 'nexttask' => 7),
			array('task' => 7, 'nexttask' => 8),
			array('task' => 5, 'nexttask' => 9),
			array('task' => 8, 'nexttask' => 9),
			array('task' => 9, 'nexttask' => 10),
			array('task' => 9, 'nexttask' => 11),
			array('task' => 10, 'nexttask' => 12),
			array('task' => 11, 'nexttask' => 12)
		);
		foreach ($tasktree AS $cur_node)
		{
			$node = new TaskTree();
			$node->fromArray($cur_node);
			$node->save();
			unset($node);
		}
	}
}
