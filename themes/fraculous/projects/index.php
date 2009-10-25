<span class="title">Project Listing</span><br />
<div class="projectlist">
<?php foreach ($this->projects as $id => $project): ?>
<div class="project">
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
</table>

