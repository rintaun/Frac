<form action="<?php echo $this->eprint($this->basepath); ?>/projects/create" method="post">
	<fieldset>
		<input type="hidden" name="go" value="go" />
		<table>
			<tr>
				<td>Project Name:</td>
				<td><?php $this->eprint($this->confirm['name']); ?></td>
				<?php if (count($this->search) > 1): ?>
				<td><select></select></td>
				<?php endif;
			</tr>
			<tr>
				<td>Short Name:</td>
				<td><?php $this->eprint($this->confirm['shortname']); ?></td>
			</tr>
			<tr>
				<td>Description:</td>
				<td colspan="2"><?php $this->eprint($this->confirm['description']); ?></td>
			</tr>
			<tr>
				<td>Episodes:</td>
				<td><?php $this->eprint($this->confirm['epsaired']); ?> / <?php $this->eprint($this->confirm['epstotal']); ?></td>
				<td>
					Auto-create: <?php $this->eprint($this->confirm['autoeps']); ?>
				</td>
			</tr>
			<tr>
				<td>Air Time:</td>
				<td><?php $this->eprint($this->confirm['airtime']) ?></td>
				<td>Automatically create new episodes: <?php $this->eprint((isset($this->confirm['autoupdate'])) ? "yes" : "no"); ?></td>
			</tr>
			<tr>
				<td>Leader:</td>
				<td><?php $this->eprint(($this->confirm['leader'] != "none") ? $this->frac->idtouser($this->confirm['leader']) : "none"); ?></td>
			</tr>
			<tr>
				<td>Ep. Template:</td>
				<td><?php // implement this LOL ?>none</td>
			</tr>
			<tr>
				<td><input type="submit" value="Create" /></td>
			</tr>
		</table>
	</fieldset>
</form>
