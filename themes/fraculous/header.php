<div id="topbar">
<? if (isset($this->session["staffid"])): ?>
	<span id="topright">
		<a href="#">My account</a>
		<a href="<?php echo $this->eprint($this->basepath); ?>/account/logout">Logout</a>
	</span>
	<span id="topleft">
		Hey there, <strong><?php echo $this->frac->idtouser($this->session["staffid"]); ?></strong>!
	</span>
	<span id="topmiddle">
<? if ($this->controller == "admin"): ?>
	<span class="navtabsel">Administration</span>
<? else: ?>
	<span class="navtab"><a href="<?php echo $this->eprint($this->basepath); ?>/admin">Administration</a></span>
<? endif; ?>
<? if ($this->controller == "staff"): ?>
	<span class="navtabsel">Staff</span>
<? else: ?>
	<span class="navtab"><a href="<?php echo $this->eprint($this->basepath); ?>/staff">Staff</a></span>
<? endif; ?>
<? if ($this->controller == "project"): ?>
	<span class="navtabsel">Projects</span>
<? else: ?>
	<span class="navtabsel"><a href="<?php echo $this->eprint($this->basepath); ?>/project">Projects</a></span>
<? endif; ?>
	</span>
<? //	<a href="#">Home</a> <a href="#">My Tasks</a> <a href="#">Help</a> ?>
<? else: ?>
<?	// show a minilogin or something. ?>
	<span id="login"><a href="<?php echo $this->eprint($this->basepath); ?>/account/login">Login</a></span>
<? endif; ?>
</div>
<div id="mainnav">
	<br />
	<a href="<?php echo $this->eprint($this->basepath); ?>">
		<img src="<?php print($this->eprint($this->themepath)); ?>/images/logo.png" alt="Frac" id="logo" />
	</a>
</div>
