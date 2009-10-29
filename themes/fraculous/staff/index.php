<span class="title">
	<img src="<?php $this->eprint($this->themepath);?>/images/icons/staff.png" alt="Staff" title="Staff" />
	Staff
</span>

<div class="boxcontainer">
<?php foreach($this->staff as $member): ?>
<div class="user">
	<a href="<?php $this->eprint($this->frac->createuri('staff/display/' . $member['id'])); ?>">
		<img alt="" width="32" height="32" src="http://www.gravatar.com/avatar/<?php echo md5($member['email']); ?>?size=32" />
		<?php $this->eprint($member['nickname']); ?>
	</a>
	<p>Actions: <a href="<?php $this->eprint($this->frac->createuri('staff/edit/' . $member['id'])); ?>">Edit</a>, <a href="<?php $this->eprint($this->frac->createuri('staff/delete/' . $member['id'])); ?>">Delete</a></p>
</div>
<?php endforeach; ?>
</div>

<span class="right small bold clear"><a href="<?php $this->eprint($this->frac->createuri('staff/create')); ?>">Create User</a></span>
