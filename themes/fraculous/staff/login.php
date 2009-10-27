<div id="fulllogin">
	You are not logged in. Please login now.<br /><br />
	<form action="<?php echo $this->eprint($this->basepath); ?>/staff/login" method="post">
		<fieldset>
			Nickname: <input type="text" name="nickname" /><br />
			Password: <input type="password" name="password" /><br />
			<input type="checkbox" name="remember" />Remember me<br />
			<input type="submit" value="Login" />
		</fieldset>
	</form>
</div>
