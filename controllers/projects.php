<?php
class ProjectsController extends Controller
{   
	public function index($args) // list projects
	{
		// well since we're here, that means we should be listing projects.
		// or something.
		$q = Doctrine_Query::create()
			->from('Project p')
			->leftJoin('p.Episodes e');
		$projects = $q->execute();

		for ($i = 0; $i < count($projects); $i++)
		{
			$project = $projects->get($i);
			$arrProject = array(
				'name' => $project->name,
				'description' => $project->description,
				'totaleps' => $project->episodes,
				'trackedeps' => count($project->Episodes->getData())
			);
			$result[$project->id] = $arrProject;
		}
		$this->vars['projects'] = $result;
	}

	// $args is the Project ID. or at least, it should be O_o
	public function display($args) // display the episodes from a project
	{
	}

	public function create($args) // create a new project
	{
	}

	public function delete($args) // delete a project
	{
	}

	public function edit($args) // edit a project's settings
	{
	}
}
