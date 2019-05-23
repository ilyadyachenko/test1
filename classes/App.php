<?php
/**
 * Product: test1.
 * Date: 2019-05-22
 */

namespace app\classes;


use app\models\User;

class App
{
	/** @var App $app */
	public static $app;

	protected $dbConfig = null;

	/** @var null|Db */
	protected $db = null;

	protected function __construct()
	{
	}

	/**
	 * @param array $dbConfig
	 * @return App
	 * @throws \Exception
	 */
	public static function create(array $dbConfig)
	{
		$instance = new static();
		$instance->dbConfig = $dbConfig;

		/** @var App $app */
		App::$app = new static();
		App::$app->dbConfig = $dbConfig;

		App::$app->initDb();

		return $instance;
	}

	/**
	 * @throws \Exception
	 */
	protected function initDb()
	{
		$this->db = Db::create($this->dbConfig);
	}

	/**
	 * @return Db|null
	 * @throws \Exception
	 */
	public function getDb()
	{
		if (empty($this->db))
		{
			$this->db = $this->initDb();
		}

		return $this->db;
	}

	/**
	 * @param array $models
	 */
	public static function initModels(array $models)
	{
		static::includeEntities('models', $models);
	}

	/**
	 * @param array $classes
	 */
	public static function initClasses(array $classes)
	{
		static::includeEntities('classes', $classes);
	}

	/**
	 * @param array $classes
	 */
	public static function initControllers(array $classes)
	{
		static::includeEntities('controllers', $classes);
	}

	/**
	 * @param $directoryName
	 * @param array $entities
	 */
	protected static function includeEntities($directoryName, array $entities)
	{
		foreach ($entities as $entityName)
		{
			$entityPath = BASEDIR . '/' . $directoryName. '/' . $entityName . '.php';
			if (file_exists($entityPath))
			{
				include_once $entityPath;
			}
		}
	}

	/**
	 * @param $page
	 * @param $limit
	 * @param $countAllItems
	 * @param $baseUrl
	 * @return string
	 * @throws \Exception
	 */
	public function getPagination($page, $limit, $countAllItems, $baseUrl)
	{
		$totalPages = (int) ceil($countAllItems / $limit);

		if ($totalPages <= 1)
		{
			return false;
		}

		$pagination = [
			'current_page' => $page,
			'last_page' => $totalPages,
			'pages' => []
		];

		if ($page != $totalPages)
		{
			$pagination['next_page'] = $page + 1;
			$pagination['next_page_url'] = str_replace('#PAGE#', $pagination['next_page'], $baseUrl);
		}

		if ($page > 1)
		{
			$pagination['previous_page'] = $page - 1;
			$pagination['previous_page_url'] = str_replace('#PAGE#', $pagination['previous_page'], $baseUrl);
		}

		$startPage = $page;
		$stopPage = $page;

		if (!empty($pagination['previous_page']))
		{
			$startPage = $pagination['previous_page'];
		}

		if (!empty($pagination['next_page']))
		{
			$stopPage = $pagination['next_page'];
		}

		if (!empty($pagination['next_page']) && ($pagination['next_page'] + 1) <= $totalPages)
		{
			$stopPage = $pagination['next_page'] + 1;
		}

		for ($i = $startPage; $i <= $stopPage; $i++)
		{
			$pagination['pages'][$i] = [
				'page' => $i,
				'url' => str_replace('#PAGE#', $i, $baseUrl)
			];
		}

		return View::renderTemplateOnly('pagination', ['pagination' => $pagination]);

	}

	/**
	 * @param $url
	 */
	public function redirect($url)
	{
		header("Location: ". $url);
		exit;
	}

	/**
	 * @return bool
	 */
	public function isAdmin()
	{
		return (!empty($_SESSION['IS_ADMIN']) && $_SESSION['IS_ADMIN'] === true);
	}

	/**
	 * @param User $user
	 * @return bool
	 */
	public function auth(User $user)
	{
		if ($user->isAdmin())
		{
			$_SESSION['IS_ADMIN'] = true;
		}
	}
}