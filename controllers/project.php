<?php
class ProjectController extends Controller
{   
	public function index($args)
	{
		// well since we're here, that means we should be listing projects.
		// or something.
		$q = Doctrine_Query::create()
			->from('Project p')
			->leftJoin('p.Episodes e');
		$projects = $q->execute();

		for ($i = 0; $i <= $projects->count; $i++)
		{
			$project = $projects->get($i);
			$arrProject = array(
				'name' => $project->name,
				'totaleps' => $project->episodes,
				'trackedeps' => count($project->Episodes->getData())
			);
			$result[$project->id] = $arrProject;
		}
		$this->vars['projects'] = $result;
	}

	// $args is the Project ID. or at least, it should be O_o
	public function display($args)
	{
	}
}
