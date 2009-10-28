<h3><?php $this->eprint($this->user->nickname); ?></h3>
<p><?php $this->eprint($this->user->comment); ?></p>
<ul>
	<li>E-mail: <?php $this->eprint($this->user->email); ?></li>
<?php if(empty($this->user->cell)): ?>
	<li>No mobile number available for this staff member.</li>
<?php else: ?>
	<li>Mobile: <?php $this->eprint($this->user->cell); ?></li>
<?php endif; ?>
</ul>