<p><span class="title">Staff List</span></p>
<?php foreach($this->staff as $member): ?>
<div class="user">
	<a href="<?php $this->eprint($this->frac->createuri('staff/display/' . $member[0])); ?>">
		<img alt="" width="32" height="32" src="http://www.gravatar.com/avatar/<?php echo md5($member[2]); ?>?size=32" />
		<?php $this->eprint($member[1]); ?>
	</a>
	<p>Actions: <a href="<?php $this->eprint($this->frac->createuri('staff/edit/' . $member[0])); ?>">Edit</a>, <a href="<?php $this->eprint($this->frac->createuri('staff/edit/' . $member[0])); ?>">Delete</a></p>
</div>
<?php endforeach; ?>