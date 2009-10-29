<span class="title">
	Projects :: <?php $this->eprint($this->name); ?>
</span>

<br /><br />
<table cellspacing="0" class="list">
	<tr class="header">
		<th>#</th>
		<th>Air Date</th>
		<th>Tasks (<abbr title="Active">A</abbr>/<abbr title="Open">O</abbr>/<abbr title="Complete">C</abbr>)</th>
	</tr>
	<? if (isset($this->episodes)): ?>
	<?php foreach ($this->episodes AS $cur_ep): ?>
	<tr class="listing">
		<td><?php $this->eprint($cur_ep['episode']); ?></td>
		<td><?php $this->eprint((!empty($cur_ep['airdate'])) ? $cur_ep['airdate'] : "Unknown"); ?></td>
		<td><?php $this->eprint($cur_ep['active'] . "/" . $cur_ep['open'] . "/" . $cur_ep['finished']); ?></td>
	</tr>
	<?php endforeach; ?>
	<? else: ?>
	<tr class="listing">
		<td colspan="3">
			No episodes tracked.
		</td>
	</tr>
	<? endif; ?>
</table>
