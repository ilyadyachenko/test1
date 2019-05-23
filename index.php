<?php
/**
 * Product: test1.
 * Date: 2019-05-22
 */

namespace app;


use app\controllers\MainController;

define('BASEDIR', dirname(__FILE__));

require_once (BASEDIR.'/config/init.php');


if (!empty($_GET['action']))
{
	$action = trim($_GET['action']);
	switch($action)
	{
		case "addtask":
			$result = MainController::addTask();
		break;
		case "index":
		default:
			$result = MainController::index();
		break;
	}
}
else
{
	$result = MainController::index();
}

echo $result;