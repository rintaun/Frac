<div id="topbar">
<?php if (isset($this->session["staffid"])): ?>
	<span id="topright">
		<a href="<?php $this->eprint($this->frac->createuri('staff/edit/' . $this->staffid)); ?>">My account</a>
		<a href="<?php $this->eprint($this->frac->createuri('staff/logout')); ?>">Logout</a>
	</span>
	<span id="topleft">
		Hey there, <a href="<?php $this->eprint($this->frac->createuri('staff/display/' . $this->staffid)); ?>"><strong><?php echo $this->frac->idtouser($this->session['staffid']); ?></strong></a>!
	</span>
	<span id="topmiddle">
	<span class="navtab<?php echo $this->controller === 'projects' ? 'sel' : ''; ?>"><a href="<?php $this->eprint($this->frac->createuri('projects')); ?>">Projects</a></span>
	<span class="navtab<?php echo $this->controller === 'staff' ? 'sel' : ''; ?>"><a href="<?php $this->eprint($this->frac->createuri('staff')); ?>">Staff</a></span>
	<span class="navtab<?php echo $this->controller === 'admin' ? 'sel' : ''; ?>"><a href="<?php $this->eprint($this->frac->createuri('admin')); ?>">Administration</a></span>
	</span>
<?php //	<a href="#">Home</a> <a href="#">My Tasks</a> <a href="#">Help</a> ?>
<?php else: ?>
<?php // show a minilogin or something. ?>
	<span id="topright"><a href="<?php $this->eprint($this->frac->createuri('staff/login')); ?>">Login</a></span>
<?php endif; ?>
</div>
<div id="mainnav">
	<br />
	<a href="<?php echo $this->eprint($this->basepath); ?>">
		<img src="<?php print($this->eprint($this->themepath)); ?>/images/logo.png" alt="Frac" id="logo" />
	</a>
</div>
