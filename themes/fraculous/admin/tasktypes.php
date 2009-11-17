<span class="title">
	<img src="<?php $this->eprint($this->themepath);?>/images/icons/admin/tasktypes.png" alt="Task Types" title="Task Types" />
	Task Types
</span><br />

<form method="post" action="<?php $this->eprint($this->basepath); ?>/admin/tasktypes">
<table>
	<tr>
		<th><input type="checkbox" name="atasktypes" /><!-- this checkbox will select all checkboxes. when I figure out how. --></th>
		<th>Name</th>
	</tr>
	<?php foreach($this->tasktypes as $t) { ?>
	<tr>
		<td><input type="checkbox" name="tasktypes[<?php $this->eprint($t["id"]); ?>]" id="tasktype<?php $this->eprint($t["id"]); ?>" /></td>
		<td><label for="tasktype<?php $this->eprint($t["id"]); ?>"><?php $this->eprint($t["name"]); ?></label></td>
	</tr>
	<? } ?>
</table>
<input type="hidden" name="action" value="delete" />
<input type="submit" value="Delete Selected Task Types" />
</form>
<br />
<form method="post" action="<?php $this->eprint($this->basepath); ?>/admin/tasktypes">
<input type="hidden" name="action" value="create" />
<input type="text" name="tasktype" />
<input type="submit" value="Create Task Type" />
</form>
<?php // endif; ?>
