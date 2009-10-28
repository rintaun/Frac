<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

class AdminController extends Controller
{   
	public function index($args)
	{
		$this->vars["pagename"] = "Administration";
	}
	
	public function information($args)
	{
		$this->vars["pagename"] = "Adminstration :: Information";
	}
	
	public function tasktypes($args)
	{
		$this->vars["pagename"] = "Adminstration :: Task Types";
		
		$p = PermissionHandler::getInstance();
		if (!$p->allowedto(PermissionHandler::PERM_MANAGE_TASKTYPES))
		{
			$this->error("You don't have permission to create task types.");
			return;
		}
		
		if(isset($_POST["action"]))
		{
			switch($_POST["action"])
			{
				case("create"):
					if(isset($_POST["tasktype"]))
					{
						$tasktype = new TaskType();
						$tasktype->name = $_POST["tasktype"];
						$tasktype->save();
					}
					break;
				
				case("delete"):
					if(isset($_POST["tasktypes"]))
					{
						$q = Doctrine_Query::create()->delete("TaskType");				
						foreach($_POST["tasktypes"] as $task => $status)
						{
							if($status == "on")
							{
								$q->orWhere("id = ?", $task);
							}
						}
						$q->execute();
					}
				
				default:
					// what is this i don't even
					break;
			}
		}
		
		$q = Doctrine_Query::create()
			->from("TaskType");
		$tasktypes = $q->execute();
		
		for ($i = 0; $i < count($tasktypes); $i++)
		{
			$tasktype = $tasktypes->get($i);
			$arrTasktype = array(
				"id" => $tasktype->id,
				"name" => $tasktype->name
			);
			$result[$tasktype->id] = $arrTasktype;
		}
		
		$this->vars["tasktypes"] = $result;
	}
	
	public function settings($args) // edit the settings
	{
		$this->vars["pagename"] = "Administration :: Settings";
		
		$p = PermissionHandler::getInstance();
		// do we have an error thing?
		if (!$p->allowedto(PermissionHandler::PERM_EDIT_SETTINGS))
		{
			Utils::error("You don't have permission to edit settings.");
			return;
		}
	}
}
