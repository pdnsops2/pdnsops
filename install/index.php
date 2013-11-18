<html>
<head>
  <title>Install PDNSOps</title>
</head>
<body>
<?php

function createConnectionString($driver, $host, $port, $database)
{
	return "$driver:dbname=$database;host=$host" . ($port != '' ? ';port=' . $port : '');
}

function getRequest($key, $method = '_REQUEST') {
    global ${$method};
    if (isset(${$method}[$key])) {
        return ${$method}[$key];
    }

    return false;
}

function connect($cstr, $user, $password, $database)
{
	$db = new PDO($cstr,$user,$password);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->query("USE `$database`");
	return $db;
}

if (isset($_POST['install']))
{
	try 
	{
		// create database
		$cstr = createConnectionString($_POST['driver'], $_POST['host'], $_POST['port'], $_POST['database']);
		$db = connect($cstr, $_POST['user'], $_POST['password'], $_POST['database']);
		
		$path = dirname($_SERVER['SCRIPT_FILENAME']) . '/../protected/data/schema.mysql.sql';
		$realpath = realpath($path); 
		$sql = file($realpath);

		$buffer = '';
		foreach ($sql as $line)
		{
			// skip comments
			if (substr(trim($line), 0, 2) == '--')
				continue;
			
			if (trim($line) == '')
				continue;
			
			$buffer .= "\n" . $line;
			
			if (false !== strpos($line, ';'))
			{
				$db->query($buffer);
				$buffer = '';
			}
		}
		
		// create config
		$path = dirname($_SERVER['SCRIPT_FILENAME']) . '/../protected/config/main.php.sample';
		$realpath = realpath($path); 		
		
		$config = file_get_contents($realpath);		
		$newconfig = str_replace(array("'connectionString'=>'',","'username'=>'',","'password'=>'',"),
								 array("'connectionString'=>'$cstr',","'username'=>'{$_POST['user']}',","'password'=>'{$_POST['password']}',"),
								 $config);
		
		$path = dirname($_SERVER['SCRIPT_FILENAME']) . '/../protected/config';
		$realpath = realpath($path) . '/main.php'; 

		$res = file_put_contents($realpath, $newconfig);
		if ($res === false)
			throw new Exception('could not write config.');
	
		// install complete
		die('Installation successful! Please click <a href="../">here</a> to get to your new PDNSOps installation.');
	} 
	catch (Exception $ex)
	{
		echo 'Error during install: ' . $ex->getMessage();
	}
}

// check prereqs
$prereqs = array();
$errorCount = 0;

// PHP
if (version_compare(PHP_VERSION, '5.0.0') >= 0)
{
	$status = 'OK';
}
else
{
	$status = 'Error: PHP version 5.0.0 required.';
	$errorCount++;
}
$prereqs['PHP'] = $status;

// PDO
$drivers = PDO::getAvailableDrivers();
if (count($drivers) > 0)
{
	$status = 'OK';
}
else
{
	$status = 'Error: No PDO drivers found.';
	$errorCount++;
}
$prereqs['PDO'] = $status;

// Yii framework
$path = dirname($_SERVER['SCRIPT_FILENAME']) . '/../../yii/';
$realpath = realpath($path);
if (is_dir($realpath) === true ||
   (is_link($realpath) === true && is_dir(readlink($realpath)) === true))
{	
	$status = 'OK - Please check that you meet the <a href="../../yii/requirements/" target="_blank">requirements of the yii framework</a>.';
}
else 
{
	$status = "Error: Yii Framework expected under $path";
	$errorCount++;
}
$prereqs['Yii Framework'] = $status;

// Config dir writeable
$configPath = dirname($_SERVER['SCRIPT_FILENAME']) . '/../protected/config/';
$configReal = realpath($configPath); 

$protectedPath = dirname($_SERVER['SCRIPT_FILENAME']) . '/../protected/config/';
$protectedReal = realpath($protectedPath); 

if (is_writeable($configReal) === false)
{
	$status = "Error: Cannot write to '$configReal'. Please make sure that the directory '$protectedReal' and its contents are writeable.";
	$errorCount++;
}
else if (is_file($configReal . '/main.php'))
{
	$status = "Error: Configuration '$configReal/main.php' already exists.";
	$errorCount++;
}
else
{
	$status = 'OK';
}
$prereqs['Configuration'] = $status;

// DB connection 
if (isset($_POST['test']))
{
	try {
		$cstr = createConnectionString($_POST['driver'], $_POST['host'], $_POST['port'], $_POST['database']);	
		$db = connect($cstr, $_POST['user'], $_POST['password'], $_POST['database']);
		$db = null;
		$status = "OK";
	} catch (PDOException $ex) {
		$status = 'Error: unable to establish connection to database.';
		$errorCount++;
	}
}
else
{
	$status = "Error: no connection.";
	$errorCount++;
}
$prereqs['Database connection'] = $status;

?>

<table border="1" width="900px">
  <tr>
    <th>Prerequisite</th>
	<th>Status</th>
  </tr>
  
<?php
foreach ($prereqs as $key => $value)
{
	echo "<tr><td>$key</td><td>$value</td></tr>";
}
?>

</table>

<br />

<form action="?" method="post">

<?php

echo '<label for="driver">Driver:</label><br />';

if ($errorCount > 0)
{
	echo '<select name="driver" id="driver">';
	foreach ($drivers as $driver)
	{
		echo "<option value=\"$driver\">$driver</option>";
	}
	echo '</select><br />';
} 
else
{
	echo '<input type="text" readonly="readonly" value="' . $_POST['driver'] . '" id="driver" name="driver" /><br />';
}
echo '<label for="host">Host:</label><br />';
echo '<input type="text" ' . ($errorCount > 0 ? '' : 'readonly="readonly" ') . ' id="host" name="host" value="' . getRequest('host') . '" /><br />';
echo '<label for="port">Port:</label><br />';
echo '<input type="text" ' . ($errorCount > 0 ? '' : 'readonly="readonly" ') . ' id="port" name="port" value="' . getRequest('port') . '" /><br />';
echo '<label for="database">Database:</label><br />';
echo '<input type="text" ' . ($errorCount > 0 ? '' : 'readonly="readonly" ') . ' id="database" name="database" value="' . getRequest('database') . '" /><br />';
echo '<label for="user">User:</label><br />';
echo '<input type="text" ' . ($errorCount > 0 ? '' : 'readonly="readonly" ') . ' id="user" name="user" value="' . getRequest('user') . '" /><br />';
echo '<label for="password">Password:</label><br />';
echo '<input type="password" ' . ($errorCount > 0 ? '' : 'readonly="readonly" ') . ' id="password" name="password" value="' . getRequest('password') . '" /><br /><br />';

if ($errorCount > 0) {
?>
	<input type="hidden" name="test" value="1" /><input type="submit" value="Check Prerequisites" />
<?php
}
else
{
?>
  <input type="hidden" name="install" value="1" />
  <input type="submit" value="Install" />
<?php	
}  
?>
</form>

</body>
</html>