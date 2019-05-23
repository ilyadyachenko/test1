<?php
/**
 * Product: test1.
 * Date: 2019-05-22
 */

namespace app\controllers;

use app\classes\App;
use app\classes\View;
use app\models\Task;
use app\models\User;

class MainController
{
	/**
	 * @throws \Exception
	 */
	public static function index()
	{
		$page = (!empty($_GET['page']) ? intval($_GET['page']) : 1);
		$sortType = (!empty($_GET['type']) && $_GET['type'] == 'desc' ? 'desc' : 'asc');
		$sort = (!empty($_GET['sort']) ? trim($_GET['sort']) : 'id');

		if ($page <= 0)
		{
			$page = 1;
		}

		$tasks = Task::getAll($page, $sort, $sortType);

		$countAll = Task::count();

		$pagination = App::$app->getPagination($page, 3, $countAll, '/?page=#PAGE#&sort='.htmlspecialchars($sort).'&type='.htmlspecialchars($sortType));

		return View::render('main', ['tasks' => $tasks, 'pagination' => $pagination, 'sort' => htmlspecialchars($sort), 'sortType' => htmlspecialchars($sortType), 'page' => $page]);
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public static function addTask()
	{
		$errors = [];
		if (isset($_POST['save']))
		{

			$user = null;

			if (!empty($_POST['login']))
			{
				$user = User::getByLogin($_POST['login']);
			}

			if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
			{
				$errors['email'][] = 'Email not valid';
			}

			if (!$user && !empty($_POST['email']))
			{
				$user = User::getByEmail($_POST['email']);
			}

			if (!$user)
			{
				$user = new User();
				$user->setAttributeNoDemand('password', md5(time()));
			}

			$user->setAttributes($_POST);
			if ($user->validate())
			{
				$user->save();
			}
			elseif ($user->hasErrors())
			{
				$errors = array_merge($errors, $user->getErrors());
			}

			if (empty($errors))
			{

				$task = new Task();
				$task->setAttributes($_POST);
				$task->setAttributesNoDemand([
					'user_id' => $user->getId(),
					'status' => Task::STATUS_NEW
				]);

				if ($task->validate())
				{
					if ($task->save())
					{
						App::$app->redirect('/');
					}
				}
				else
				{
					$errors = array_merge($errors, $task->getErrors());
				}
			}

		}

		return View::render('add_task', $_POST + ['errors' => $errors]);
	}
}