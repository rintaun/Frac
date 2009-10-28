<span class="title">
	<img src="<?php $this->eprint($this->themepath);?>/images/icons/admin.png" alt="Administration" title="Administration" />
	Administration
</span><br />

<ul id="adminnav">
	<li>
		<a href="<?php $this->eprint($this->frac->createuri("admin/tasktypes")); ?>">
			<img src="<?php $this->eprint($this->themepath);?>/images/icons/admin/tasktypes.png" alt="Task Types" title="Task Types" />
			Task Types
		</a><br />
		Manage the list of possible task types globally.
	</li>
	
	<li>
		<a href="<?php $this->eprint($this->frac->createuri("templates")); ?>">
			<img src="<?php $this->eprint($this->themepath);?>/images/icons/admin/templates.png" alt="Task Templates" title="Task Templates" />
			Task Templates
		</a><br />
		Manage task templates.
	</li>
	
	<li>	
		<a href="<?php $this->eprint($this->frac->createuri("admin/settings")); ?>">
			<img src="<?php $this->eprint($this->themepath);?>/images/icons/admin/settings.png" alt="Settings" title="Settings" />
			Settings
		</a><br />
		Configure Frac settings.
	</li>
	
	<li>
		<a href="<?php $this->eprint($this->frac->createuri("admin/information")); ?>">
			<img src="<?php $this->eprint($this->themepath);?>/images/icons/admin/information.png" alt="Information" title="Information" />
			Information
		</a><br />
		Get information about Frac and your web server.
	</li>
</ul>
