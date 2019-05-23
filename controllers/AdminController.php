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

class AdminController
{
	/**
	 * @throws \Exception
	 */
	public static function index()
	{
		if (!App::$app->isAdmin())
		{
			App::$app->redirect('/admin/?action=login');
		}

		$page = (!empty($_GET['page']) ? intval($_GET['page']) : 1);
		$sortType = (!empty($_GET['type']) && $_GET['type'] == 'desc' ? 'desc' : 'asc');
		$sort = (!empty($_GET['sort']) ? trim($_GET['sort']) : 'id');

		if ($page <= 0)
		{
			$page = 1;
		}

		$tasks = Task::getAll($page, $sort, $sortType);

		$countAll = Task::count();

		$pagination = App::$app->getPagination($page, 3, $countAll, '/admin/?page=#PAGE#&sort='.htmlspecialchars($sort).'&type='.htmlspecialchars($sortType));

		return View::render('admin/main', ['tasks' => $tasks, 'pagination' => $pagination, 'sort' => htmlspecialchars($sort), 'sortType' => htmlspecialchars($sortType), 'page' => $page]);
	}

	public static function login()
	{
		$errors = [];
		if (isset($_POST['submit']))
		{

			$user = null;

			if (!empty($_POST['login']))
			{
				$user = User::getByLogin($_POST['login']);
			}

			if (!$user)
			{
				$errors['login'][] = 'User not found';
			}

			if (empty($_POST['password']))
			{
				$errors['password'][] = 'Password is empty';
			}
			elseif ($user && $user->isValidPassword($_POST['password']))
			{
				App::$app->auth($user);
				if (!$user->isAdmin())
				{
					$errors['login'][] = 'Your are not admin';
				}
			}
			else
			{
				$errors['password'][] = 'Wrong password';
			}

			if (empty($errors) && $user && $user->isAdmin())
			{
				App::$app->redirect('/admin');
			}

		}

		return View::render('admin/login', $_POST + ['errors' => $errors]);
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public static function editTask()
	{
		if (!App::$app->isAdmin())
		{
			App::$app->redirect('/admin/?action=login');
		}

		$id = intval($_GET['id']);

		if (empty($id))
		{
			return View::render('error', ['errorMessage' => 'Id is empty']);
		}

		$task = Task::getById($id);

		if (!$task)
		{
			return View::render('error', ['errorMessage' => 'Task not found']);
		}

		$fields = $task->getFields();

		$errors = [];
		if (isset($_POST['save']))
		{
			$task->setAttributes($_POST);
			if ($task->validate())
			{
				if ($task->save())
				{
					App::$app->redirect('/admin/');
				}
			}
			else
			{
				$errors = array_merge($errors, $task->getErrors());
			}
			$fields = $_POST;
		}

		return View::render('admin/change_task', $fields + ['errors' => $errors, 'id' => $id]);
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public static function changeTaskStatus()
	{
		if (!App::$app->isAdmin())
		{
			App::$app->redirect('/admin/?action=login');
		}

		$id = intval($_GET['id']);

		if (empty($id))
		{
			return View::render('error', ['errorMessage' => 'Id is empty']);
		}

		$task = Task::getById($id);

		if (!$task)
		{
			return View::render('error', ['errorMessage' => 'Task not found']);
		}

		$status = $task->getStatus();
		if ($status == Task::STATUS_NEW)
		{
			$status = Task::STATUS_DONE;
		}
		else
		{
			$status = Task::STATUS_NEW;
		}

		$task->setAttributeNoDemand('status', $status);
		if ($task->validate())
		{
			$task->save();
		}

		$backUrl = (!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/admin');
		App::$app->redirect($backUrl);
	}
}