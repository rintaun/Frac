<span class="title">
	<img src="<?php $this->eprint($this->themepath);?>/images/icons/admin/tasktypes.png" alt="Task Types" title="Task Types" />
	Task Types
</span><br />

<?php if(isset($this->confirmdelete)): ?>
<form method="post" action="<?php $this->eprint($this->basepath); ?>/admin/tasktypes">
<strong>Warning:</strong> Are you absolutely sure you want to do this? Doing so will have very far-reaching consequences, such as removing all tasks of the selected types, as well as templates and task trees.
<?php foreach($_POST["tasktypes"] as $k => $v): ?>
<input type="hidden" name="tasktypes[<?php print $k; ?>]" value="<?php print $v; ?>" />
<?php endforeach; ?>
<input type="hidden" name="action" value="delete" />
<input type="hidden" name="confirmed" value="true" />
<br />
<input type="submit" value="Yes, I know what I'm doing" />
<a href="<?php $this->eprint($this->basepath); ?>/admin/tasktypes">No, take me back</a>
</form>
<?php else: ?>

<form method="post" action="<?php $this->eprint($this->basepath); ?>/admin/tasktypes">
<table>
	<tr>
		<th><input type="checkbox" name="atasktypes" /><!-- this checkbox will select all checkboxes. when I figure out how. --></th>
		<th>Name</th>
	</tr>
	<?php foreach($this->tasktypes as $t) { ?>
	<tr>
		<td><input type="checkbox" name="tasktypes[<?php print $t["id"]?>]" id="tasktype<?php print $t["id"]?>" /></td>
		<td><label for="tasktype<?php print $t["id"]?>"><?php print $t["name"]?></label></td>
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
<?php endif; ?>
