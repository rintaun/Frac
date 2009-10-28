<span class="title">
	<img src="<?php $this->eprint($this->themepath);?>/images/icons/projects.png" alt="Projects" title="Projects" />
	Projects
</span><br />

<div class="boxcontainer">
<?php foreach ($this->projects as $id => $project): ?>
<div id="project_<?php echo $this->eprint($id); ?>" class="project">
	<strong>
		<a href="<?php echo $this->eprint($this->basepath); ?>/projects/display/<?php echo $this->eprint($id); ?>"><?php echo $this->eprint($project['name']); ?></a>
	</strong>
	<br />
	<span class="eps">Episodes Tracking: <?php echo $this->eprint($project['trackedeps']); ?> / Total Episodes: <?php echo $this->eprint($project['totaleps']); ?></span>
	<br />
	<p class="desc"><?php echo $this->eprint($this->frac->shorten($project['description'], 150)); ?></p>
</div>
<?php endforeach; ?>
</div>
