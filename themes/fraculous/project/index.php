<span class="title">Project Listing</span><br />
<div class="projectlist">
<?php foreach ($this->projects as $id => $project): ?>
<div class="project">
	<strong>
		<a href="<?php echo $this->eprint($this->basepath); ?>/project/display/<?php echo $this->eprint($id); ?>"><?php echo $this->eprint($project['name']); ?></a>
		<p class="desc"><?php echo $this->frac->shorten($this->eprint($project['description']), 150); ?></p>
	</strong>
</div>
<div class="project">
	<strong>
		<a href="<?php echo $this->eprint($this->basepath); ?>/project/display/<?php echo $this->eprint($id); ?>"><?php echo $this->eprint($project['name']); ?></a>
	</strong>
</div>
<div class="project">
	<strong>
		<a href="<?php echo $this->eprint($this->basepath); ?>/project/display/<?php echo $this->eprint($id); ?>"><?php echo $this->eprint($project['name']); ?></a>
	</strong>
</div>
<div class="project">
	<strong>
		<a href="<?php echo $this->eprint($this->basepath); ?>/project/display/<?php echo $this->eprint($id); ?>"><?php echo $this->eprint($project['name']); ?></a>
	</strong>
</div>
<div class="project">
	<strong>
		<a href="<?php echo $this->eprint($this->basepath); ?>/project/display/<?php echo $this->eprint($id); ?>"><?php echo $this->eprint($project['name']); ?></a>
	</strong>
</div>
<div class="project">
	<strong>
		<a href="<?php echo $this->eprint($this->basepath); ?>/project/display/<?php echo $this->eprint($id); ?>"><?php echo $this->eprint($project['name']); ?></a>
	</strong>
</div>
<?php endforeach; ?>
</div>
</table>

