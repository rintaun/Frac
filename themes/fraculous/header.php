<div id="topbar">
<? if ($this->loggedin === true): ?>
	<span id="login">Login stuff</span>
	<a href="#">Home</a> <a href="#">My Tasks</a> <a href="#">Help</a>
<? else: ?>
<?	// show a minilogin or something. ?>
	<span id="login"><a href="<?php echo $this->eprint($this->basepath); ?>/account/login">Login</a></span>
<? endif; ?>
</div>
<div id="mainnav">
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
	<span class="navtab"><a href="<?php echo $this->eprint($this->basepath); ?>/project">Projects</a></span>
<? endif; ?>
	<br />
	<a href="<?php echo $this->eprint($this->basepath); ?>">
		<img src="<?php print($this->eprint($this->themepath)); ?>/images/logo.png" alt="Frac" id="logo" />
	</a>
</div>
