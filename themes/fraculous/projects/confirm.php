<form action="<?php $this->eprint($this->frac->createuri('/projects/create')); ?>" method="post">
	<fieldset>
		<input type="hidden" name="go" value="go" />
		<input type="hidden" name="name" value="<?php $this->eprint($this->confirm['name']); ?>" />
		<input type="hidden" name="autolookup" value="<?php echo (isset($this->confirm['autolookup'])) ? $this->eprint($this->confirm['autolookup']); : ""; ?>" />
		<input type="hidden" name="shortname" value="<?php $this->eprint($this->confirm['shortname']); ?>" />
		<input type="hidden" name="description" value="<?php $this->eprint($this->confirm['description']); ?>" />
		<input type="hidden" name="epsaired" value="<?php $this->eprint($this->confirm['epsaired']); ?>" />
		<input type="hidden" name="epstotal" value="<?php $this->eprint($this->confirm['epstotal']); ?>" />
		<input type="hidden" name="autoeps" value="<?php $this->eprint($this->confirm['autoeps']); ?>" />
		<input type="hidden" name="airtime" value="<?php $this->eprint($this->confirm['airtime']); ?>" />
		<input type="hidden" name="autoupdate" value="<?php $this->eprint($this->confirm['autoupdate']); ?>" />
		<input type="hidden" name="leader" value="<?php $this->eprint($this->confirm['leader']); ?>" />
		<input type="hidden" name="template" value="<?php $this->eprint($this->confirm['template']); ?>" />
		<table class="confirm">
			<tr>
				<td>Project Name:</td>
				<td><?php $this->eprint($this->confirm['name']); ?></td>
				<?php if (count($this->search) > 1): ?>
				<td><select name="tid"><?php print($this->frac->options($this->search, $this->tid)); ?></select><input type="submit" value="Retry" /></td>
				<?php endif; ?>
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
				<td><input type="submit" name="confirm" value="Create" /></td>
			</tr>
		</table>
	</fieldset>
</form>
