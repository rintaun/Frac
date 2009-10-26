<!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Frac :: <?php print($this->eprint($this->pagename)); ?></title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="<?php print($this->eprint($this->themepath)); ?>/stylesheet.css" type="text/css" />
</head>
<body>
	<?php $this->display("header.php"); ?>
	<div id="content">
		<div id="container">
		<?php $this->display($this->view); ?>
		</div
	</div>
	<?php $this->display("footer.php"); ?>
</body>
</html>
