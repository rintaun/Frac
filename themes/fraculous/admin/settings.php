<span class="title">
	<img src="<?php $this->eprint($this->themepath);?>/images/icons/admin/settings.png" alt="Settings" title="Settings" />
	Settings
</span><br />

<form method="post" action="<?php $this->eprint($this->basepath); ?>/admin/settings">
<table>
	<tr>
		<th>Name</th>
		<th>Value</th>
	</tr>
	<?php foreach($this->settings as $s) { ?>
	<tr>
		<td><?php $this->eprint($s["name"]); ?></td>
		<td><input name="<?php $this->eprint(str_replace(".", "_", $s["name"])); /* . gets turned into _ sometimes for some reason, so let's just make it _ anyway */ ?>" type="text" value="<?php $this->eprint($s["value"]); ?>" /></td>
	</tr>
	<? } ?>
</table>
<input type="submit" value="Save" />
</form>
