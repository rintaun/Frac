<div class="userdetails">
	<div class="header">
		<img alt="" width="75" height="75" src="http://www.gravatar.com/avatar/<?php echo md5($this->user->email); ?>?size=75" />
		<p>
			<span class="title"><?php $this->eprint($this->user->nickname); ?></span><br />
			<span class="comment"><?php $this->eprint($this->user->comment); ?></span>
		</p>
	</div>
	
	<ul>
		<li>E-mail: <?php $this->eprint($this->user->email); ?></li>
<?php if(empty($this->user->cell)): ?>
		<li>No mobile number available for this staff member.</li>
<?php else: ?>
		<li>Mobile: <?php $this->eprint($this->user->cell); ?></li>
<?php endif; ?>
	</ul>
</div>