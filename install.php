<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */
 
define("IN_FRAC_", true);
define("IN_FWORK_", true);

// Perform some extension sanity checks.
// Though if you don't have these, your PHP install is messed up.
if(!function_exists('spl_autoload_register'))
	die('Frac requires the Standard PHP Library extension to be loaded.');
if(!function_exists('session_start'))
	die('Frac requires PHP Session support to be enabled.');
if(!class_exists('PDO'))
	die('Frac requires the PHP Data Objects extension to be loaded.');

// Find an action.
if(!isset($_GET['do']))
{
	$_GET['do'] = 'step1';
} 

// Do the action.
switch($_GET['do'])
{
	case 'step1':
		doCreateConfig();
		die;
	case 'step2':
		if($errors = doWriteConfig())
			doCreateConfig($errors);
		die;
	case 'step3':
		doPopulateDatabase();
		die;
	default:
		die;
}

function doCreateConfig($error = array())
{
	if(file_exists("config.php"))
	{
		header("Location: " . $_SERVER['SCRIPT_NAME'] . "?do=step3"); return;
	}
	
	$flash = '';
	if(is_array($error) && (count($error) > 0))
	{
		$flash = '<div style="border: 1px solid black; padding: 4px 4px 4px 4px;"><span style="color: red; font-size: 110%; font-weight: bold;">There were one or more errors with your configuration:</span><ul>';
		foreach($error as $err)
		{
			$flash .= '<li>' . $err . '</li>';
		}
		$flash .= '</ul></div>';
	}

	echo <<<EOS
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Frac :: Install</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
</head>
<body>
	<h2>Frac Installer</h2>
	$flash
	<p>You have not yet created a configuration file. Please create one from <code>config.php.dist</code> or use the form below.</p>
	<form action="install.php?do=step2" method="post">
		<h3>Database Options</h3>
EOS;
	
	_printInputField('sqlHost', 'Hostname:', 'The host running the database. This is usually <code>localhost</code>.', 'text', array('size' => 30, 'value' => 'localhost'));
	_printInputField('sqlUser', 'Username:', 'The SQL username.', 'text', array('size' => 30));
	_printInputField('sqlPass', 'Password:', 'The SQL password.', 'password', array('size' => 30));
	_printInputField('sqlDb', 'Database Name:', 'The name of the SQL database. The user above must have read/write access.', 'text', array('size' => 30));
	_printInputField('sqlPrefix', 'Database Prefix:', 'The database prefix. When in doubt, keep as default.', 'text', array('size' => 30, 'value' => 'frac_'));

	$pdoDrivers = array();
	foreach(PDO::getAvailableDrivers() as $driver)
		$pdoDrivers[$driver] = $driver;
	ksort($pdoDrivers);
	_printDropDown('sqlDriver', 'Database Driver:', 'The driver to use when connecting to the database.', $pdoDrivers);
	
	echo '<h3>Site Options</h3>';
	_printYesNo('siteGzip', 'Use GZIP Compression:', 'Choosing "yes" here enables the option to use GZIP compression, decreasing bandwidth but increasing CPU usage. This is only used if the browser supports it.');
	_printYesNo('siteGentime', 'Display Generation Time:', 'Choosing "yes" here will display generation time statistics as an HTML comment.');
	
	echo <<<EOS
		<br />
		<input type="submit" name="button" value="Generate Config" />
	</form>
</body>
</html>
EOS;
}

function doWriteConfig()
{
	// Initialize errors array.
	$errors = array();

	// Check to make sure an existing config doesn't exist.
	if(file_exists('config.php'))
		die('An existing configuration file exists, this script will not overwrite a previous configuration. If you wish to use this script, please remove the configuration file.'); // this will only happen if you try to access install.php?do=step2 by hand. If you do, you sir, are an idiot.

	$configTmpl = array(	
							'sqlHost'		=>	'localhost',
							'sqlUser'		=>	null,
							'sqlPass'		=>	null,
							'sqlDb'			=>	null,
							'sqlPrefix'		=>	'frac_',
							'sqlDriver'		=>	null,
							
							'siteGzip'		=>	true,
							'siteGentime'	=>	true,
						);

	foreach($configTmpl as $key => &$value)
	{
		$value = urldecode($_POST[$key]);
	}
	
	// First bad test. :\
	if(count($errors) > 0)
		return $errors;
	
	// Construct the config array.
	$configArray = array(	'database'	=> array(	'dsn'		=> "{$configTmpl['sqlDriver']}://{$configTmpl['sqlUser']}:{$configTmpl['sqlPass']}@{$configTmpl['sqlHost']}/{$configTmpl['sqlDb']}",
													'prefix'	=> $configTmpl['sqlPrefix']
												),
							'site'		=> array(	'gzip'		=> $configTmpl['siteGzip'],
													'gentime'	=> $configTmpl['siteGentime']
												)
						);
		
	
		
	// Test various things.
	try {
		$pdoTest = @new PDO(sprintf('%s:host=%s;port=%s;dbname=%s', $configTmpl['sqlDriver'], $configTmpl['sqlHost'], 3306, $configTmpl['sqlDb']), $configTmpl['sqlUser'], $configTmpl['sqlPass']);
	} catch (PDOException $e) {
		$errors[] = 'Unable to connect to the database: ' . $e->getMessage();
	}
	
	// Last chance for something to go wrong.
	if(count($errors) > 0)
		return $errors;
	
	// Dump to string.
	$config = "<?php\n\nif(!defined('IN_FRAC_')) die('This file cannot be invoked directly.');\n\n\$config = ";
	$config .= var_export($configArray, true);
	$config .= ";";
	
	$confFile = @fopen('config.php', 'w');
	if(!$confFile)
	{
		echo <<<EOS
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Frac :: Install</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
</head>
<body>
	<h2>Frac Installer</h2>
	<p>I was unable to open the configuration file for writing. Please create the file <code>config.php</code> in the frac root directory with the following contents:</p>
	<textarea rows="25" cols="125">$config</textarea>
	<p>When you have finished, proceed to the <a href="install.php?do=step3">final step</a>.</p>
</body>
</html>
EOS;
	}
	else
	{
		fwrite($confFile, $config);
		fclose($confFile);
		
		echo <<<EOS
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Frac :: Install</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta http-equiv="refresh" content="3;url=install.php?do=step3" />
</head>
<body>
	<h2>Frac Installer</h2>
	<p>Configuration written successfully, redirecting you to the final step...</p>
	<p><a href="install.php?do=step3">Click here if your browser does not redirect you</a></p>
</body>
</html>
EOS;
	}
	
	return false;
}


function doPopulateDatabase()
{
	// Check for a config file, if it doesn't exist go through the steps to create it.
	if(!file_exists(dirname(__FILE__) . '/config.php'))
	{
		header("Location: " . $_SERVER["SCRIPT_NAME"] . "?do=step1");
		return;
	}

	require_once(dirname(__FILE__) . "/config.php");

	require_once(dirname(__FILE__) . "/fwork/lib/Doctrine/Doctrine.php");
	spl_autoload_register(array("Doctrine", "autoload"));

	Doctrine_Manager::connection($config["database"]["dsn"]);
	Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_TBLNAME_FORMAT, $config["database"]["prefix"] . "%s");
	Doctrine::createTablesFromModels(dirname(__FILE__) . "/models");

	echo <<<EOS
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Frac :: Install</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
</head>
<body>
	<h2>Frac Installer</h2>
	<p>Table data populated successfully. You may now proceed to your <a href="./">Frac installation</a></p>
</body>
</html>
EOS;
}

// =============================================
// =============================================

function _printInputField($name, $label, $description, $inputType = 'text', $extra = array())
{
	echo '<label for="' . $name . '">' . $label . '</label> ';
	echo '<input type="' . $inputType . '" name="' . $name . '"';
	foreach($extra as $key => $value)
	{
		echo " $key=\"$value\"";
	}
	echo ' /> ' . $description . "<br />\n";
}

function _printDropDown($name, $label, $description, $elements, $default = 0)
{
	echo '<label for="' . $name . '">' . $label . '</label> ';
	echo '<select name="' . $name . '">';
	
	$i = 0;
	foreach($elements as $key => $value)
	{
		echo '<option value="' . $key . '" ' . ($default === $i++ ? 'selected="selected" ' : '') . '>' . $value . '</option>';
	}
	unset($i);
	
	echo '</select> ' . $description . "<br />\n";
}

function _printYesNo($name, $label, $description, $default = true)
{
	echo '<label for="' . $name . '">' . $label . '</label> ';
	echo '<input type="radio" name="' . $name . '" value="yes" ' . ($default ? 'checked="checked" ' : '') . '/> Yes&nbsp;&nbsp;&nbsp;';
	echo '<input type="radio" name="' . $name . '" value="no" ' . (!$default ? 'checked="checked" ' : '') . '/> No';
	echo ' ' . $description . "<br />\n";
}
