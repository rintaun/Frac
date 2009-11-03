<span class="title">
	<img src="<?php $this->eprint($this->themepath);?>/images/icons/projects.png" alt="Projects" title="Projects" />
	Projects
</span><br />

<div class="boxcontainer">
<?php if (count($this->projects) >= 1): ?>
<?php foreach ($this->projects as $id => $project): ?>
<div id="project_<?php echo $this->eprint($id); ?>" class="project">
	<strong>
		<?php $this->eprint($project['name']); ?>
	</strong>
	<br />
	<span class="eps">Episodes Tracking: <?php echo $this->eprint($project['trackedeps']); ?> / Total Episodes: <?php echo $this->eprint($project['totaleps']); ?></span>
	<br />
	<?php if (!empty($project['description'])): ?>
	<p class="desc"><?php echo $this->eprint($this->frac->shorten($project['description'], 150)); ?></p>
	<?php else: ?>
	<p class="desc">No description.</p>
	<?php endif; ?>
</div>
<?php endforeach; ?>
<?php else: ?>
<p><strong>You are not tracking any projects.</strong></p>
<?php endif; ?>
</div>
<div class="clear"></div>
<span class="right small bold"><a href="<?php $this->eprint($this->frac->createuri('projects/create')); ?>">Create Project</a></span>
