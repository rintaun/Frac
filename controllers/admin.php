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
			Utils::error("You don't have permission to create task types.");
			return;
		}
		
		if(isset($_POST["action"]))
		{
			switch($_POST["action"])
			{
				case("create"):
					if(isset($_POST["tasktype"]) && !empty($_POST["tasktype"]))
					{
						$tasktype = new TaskType();
						$tasktype->name = $_POST["tasktype"];
						$tasktype->created = date("Y-m-d H:i:s");
						$tasktype->save();
					} else {
						Utils::error("No task type entered to create.");
						return;
					}
					break;
				
				case("delete"):
						if(isset($_POST["tasktypes"]) && !empty($_POST["tasktypes"]))
						{
							if(isset($_POST["confirmed"]) && !empty($_POST["confirmed"]))
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
							} else {
								$this->vars["confirmdelete"] = true;
							}
						} else {
							Utils::error("No task types selected to delete.");
						}
				
				default:
					// what is this i don't even
					break;
			}
		}
		
		$this->vars["tasktypes"] = Doctrine_Query::create()->from("TaskType")->fetchArray();
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
