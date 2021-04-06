<?php
/**
 * Script used to build a sample site on Docker container.
 *
 * If configuration.php file is provide on start your container,
 * this script make a site with default Gov BR sample.
 *
 * @package     Joomla.Docker
 * @subpackage  PHP.Sample
 *
 * @copyright   Copyright (C) 2013 - 2021 JoomlaGovBR. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://github.com/joomlagovbr
 * @since       1.0.0
 */

function usage($command)
{
	echo PHP_EOL;
	echo 'Usage: php ' . $command . ' /path/to/file.sql' . PHP_EOL;
	echo PHP_EOL;
}

const PHP_TAB = "\t";

$sampleSqlFile = $argv['1'];

if (!file_exists($sampleSqlFile) || empty($sampleSqlFile))
{
	usage($argv[0]);
	die();
}

if (strpos(getenv('JOOMLA_DB_HOST', true), ':') === false)
{
	$host = getenv('JOOMLA_DB_HOST', true);
	$port = 3306;
}
else
{
	list($host, $port) = explode(':', getenv('JOOMLA_DB_HOST', true), 2);
}

$database = getenv('JOOMLA_DB_NAME', true);
$user = getenv('JOOMLA_DB_USER', true);
$password = getenv('JOOMLA_DB_PASSWORD', true);
$prefixTable = getenv('JOOMLA_DB_PREFIX', true);

$sampleContent = file_get_contents($sampleSqlFile);

$regex = "/#__/";
$sampleContent = preg_replace($regex, $prefixTable, $sampleContent);
