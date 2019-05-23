<?php
/**
 * Product: test1.
 * Date: 2019-05-22
 */

if (!defined('BASEDIR'))
{
	exit('BASEDIR not defined');
}

$classes = [
	'Db',
	'View',
];

$models = [
	'BaseModel',
	'Task',
	'User',
];


$controllers = [
	'MainController',
	'AdminController',
];

$config = require_once BASEDIR.'/config/config.php';

session_start();
require_once BASEDIR.'/classes/App.php';

\app\classes\App::initClasses($classes);
\app\classes\App::initModels($models);
\app\classes\App::initControllers($controllers);

\app\classes\App::create($config);