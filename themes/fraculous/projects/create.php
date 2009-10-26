<form action="<?php echo $this->eprint($this->basepath); ?>/projects/create" method="post">
	<fieldset>
		<input type="hidden" name="go" value="go" />
		<table>
			<tr>
				<td>Project Name:</td>
				<td><input type="text" name="name" /></td>
				<td><input type="checkbox" name="autolookup" />Autolookup?</td>
			</tr>
			<tr>
				<td>Short Name:</td>
				<td><input type="text" name="shortname" /></td>
			</tr>
			<tr>
				<td>Description:</td>
				<td colspan="2"><textarea rows="5" cols="50"></textarea></td>
			</tr>
			<tr>
				<td>Episodes:</td>
				<td><input type="text" size="3" maxLength="3" value="Aired" /> / <input type="text" size="3" maxLength="3" value="Total" /></td>
				<td>
					Auto-create:
					<input type="radio" name="autoeps" value="aired" />Aired
					<input type="radio" name="autoeps" value="total" />All
					<input type="radio" name="autoeps" value="none" />None
				</td>
			</tr>
			<tr>
				<td>Air Time:</td>
				<td><input type="text" name="airtime" /></td>
				<td><input type="checkbox" name="autoupdate" />Automatically create new episodes when they air</td>
			</tr>
			<tr>
				<td>Leader:</td>
				<td><select name="leader"><option value="none">-- None --</option></select></td>
			</tr>
			<tr>
				<td>Ep. Template:</td>
				<td><select name="template"><option value="none">-- None --</option></select></td>
			</tr>
			<tr>
				<td><input type="submit" value="Create" /></td>
			</tr>
		</table>
	</fieldset>
</form>
