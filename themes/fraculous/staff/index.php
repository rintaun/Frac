<span class="title">Staff List</span>
<ul>
<?php foreach($this->staff as $member): ?>
	<li><a href="<?php $this->eprint($this->frac->createuri('staff/display/' . $member[0])); ?>"><?php $this->eprint($member[1]); ?></a>
		(<a href="<?php $this->eprint($this->frac->createuri('staff/edit/' . $member[0])); ?>">Edit</a>,
		<a href="<?php $this->eprint($this->frac->createuri('staff/edit/' . $member[0])); ?>">Delete</a>)</li>
<?php endforeach; ?>
</ul>