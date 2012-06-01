<?php

$iVersion = (float)phpversion();
echo "PHP version: " . $iVersion . " \n";

if($iVersion < 5.4)
{
	die("This application require PHP 5.4\n");
}

$sUser = "";
$sPassword = "";
$sDatabase = "";
$sDbHost = "";

do
{
	echo "MySQL user: ";
	$sUser = trim(fgets(STDIN));
}
while(empty($sUser));

do
{
	echo "MySql password: ";
	system('stty -echo');
	$sPassword = trim(fgets(STDIN));
	system('stty echo');
	echo "\n";

}
while(empty($sPassword));

do
{
	echo "MySQL database: ";
	$sDatabase = trim(fgets(STDIN));
}
while(empty($sDatabase));

do
{
	echo "MySQL host: ";
	$sDbHost = trim(fgets(STDIN));
}
while(empty($sDbHost));

// sprawdzenie połaczenia z bazą i wrzucenie migracji
try
{
	$oPDO = new PDO(
		'mysql:host='. $sDbHost .';dbname=' . $sDatabase,
		$sUser,
		$sPassword
	);

	$oPDO->exec(file_get_contents('application/models/Users.sql'));
	$oPDO->exec(file_get_contents('application/models/Tasks.sql'));
}
catch(PDOException $e)
{
	die($e->getMessage() . "\n");
}

$sConfig = file_get_contents('application/configs/application.ini');

$sConfig = str_replace('{host}', $sDbHost, $sConfig);
$sConfig = str_replace('{dbname}', $sDatabase, $sConfig);
$sConfig = str_replace('{username}', $sUser, $sConfig);
$sConfig = str_replace('{password}', $sPassword, $sConfig);

file_put_contents('application/configs/application2.ini', $sConfig);

