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
	<span class="navtab">Administration</span> <span class="navtab">Staff</span> <span class="navtab">Projects</span>
	<br />
	<img src="<?php print($this->eprint($this->themepath)); ?>/images/logo.png" alt="Frac" id="logo" />
</div>
