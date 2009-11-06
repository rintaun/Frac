<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

class Template extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->setTableName('templates');

		$this->hasColumn('id', 'integer', null, array(
				'notnull' => true,
				'unsigned' => true,
				'primary' => true
			)
		);
		$this->hasColumn('name', 'string', 32, array(
				'notnull' => true
			)
		);
		$this->hasColumn('model', 'clob', null, array(
				'notnull' => true
			)
		);
		$this->hasColumn('created', 'timestamp', null, array(
				'notnull' => true
			)
		);
	}

	public function setUp()
	{
		$this->hasMany('Project as Projects', array(
				'local' => 'id',
				'foreign' => 'template'
			)
		);
	}

	public function createTasks($episode_id)
	{
		$nodes = explode(";", trim($this->model));
		foreach ($nodes AS $node)
		{
			if (empty($node)) continue;
			$tasks = explode("->",$node);
			$mynode = new TaskTree();
			foreach ($tasks AS $task)
			{
				$data = explode(":", $task);
				$task_id = trim($data[0]);
				$task_num = trim($data[1]);
				if (!isset($tasklist[$task_id][$task_num]))
				{
					$tasklist[$task_id][$task_num] = new Task();
					$tasklist[$task_id][$task_num]->tasktype = $task_id;
					$tasklist[$task_id][$task_num]->episode = $episode_id;
					$tasklist[$task_id][$task_num]->active = false;
					$tasklist[$task_id][$task_num]->finished = false;
					$tasklist[$task_id][$task_num]->created = date('Y-m-d H:i:s');
					$tasklist[$task_id][$task_num]->save();
				}
				if (!isset($p))
				{
					$p = true;
					$mynode->Parent = $tasklist[$task_id][$task_num];	
				}
				else
				{
					$mynode->Child = $tasklist[$task_id][$task_num];
				}
			}
			$mynode->save();
			unset($p);
		}
		foreach ($tasklist AS $task_id => $task_data)
		{
			foreach ($task_data AS $task_num => $task)
			{
				if ((count($task->Parents) == 0) && (time() > strtotime($task->Episode->airdate)))
				{
					$task->active = true;
					$task->save();
				}
			}
		}
	}
}
