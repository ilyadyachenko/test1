<?php
/**
 * Product: test1.
 * Date: 2019-05-22
 */

namespace app;


use app\controllers\AdminController;

define('BASEDIR', dirname(__FILE__) .'/../');

require_once (BASEDIR.'/config/init.php');


if (!empty($_GET['action']))
{
	$action = trim($_GET['action']);
	switch($action)
	{
		case "login":
			$result = AdminController::login();
		break;
		case "edittask":
			$result = AdminController::editTask();
		break;
		case "changestatus":
			$result = AdminController::changeTaskStatus();
		break;
		case "index":
		default:
			$result = AdminController::index();
		break;
	}
}
else
{
	$result = AdminController::index();
}

echo $result;