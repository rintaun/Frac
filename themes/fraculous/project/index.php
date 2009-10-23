<?php foreach ($this->projects AS $id => $project): ?>
<a href="<?php echo $this->eprint($this->basepath); ?>/project/display/<?php echo $this->eprint($id); ?>"><?php echo $this->eprint($project['name']); ?></a><br />
<?php endforeach; ?>
