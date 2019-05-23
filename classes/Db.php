<?php
/**
 * Product: test1.
 * Date: 2019-05-22
 */

namespace app\classes;


class Db
{
	protected static $config = null;

	/** @var null|Db  */
	protected static $instance = null;

	/** @var int */
	protected static $limit = 3;

	/** @var null|\mysqli */
	protected $link = null;



	/**
	 * @param $config
	 * @return Db|null
	 * @throws \Exception
	 */
	public static function create($config)
	{
		if (empty($config))
		{
			throw new \Exception('Db config is empty');
		}

		$instance = new static();
		$instance::$config = $config;

		static::$instance = $instance;

		return static::$instance;
	}

	/**
	 * @return Db|null
	 * @throws \Exception
	 */
	public static function getInstance()
	{
		if (!static::$instance)
		{
			static::create(static::$config);
		}

		return static::$instance;
	}

	/**
	 * @return false|\mysqli|null
	 */
	public function getLink()
	{
		if (!$this->link)
		{
			$this->link = mysqli_connect(static::$config['host'], static::$config['user'], static::$config['password'], static::$config['db_name']);
		}

		return $this->link;
	}

	/**
	 * @param $table
	 * @param null $where
	 * @param null $select
	 * @param null $page
	 * @param null $joinTable
	 * @param null $sortTable
	 * @return bool|mixed
	 * @throws \Exception
	 */
	public function get($table, $where = null, $select = null, $page = null, $joinTable = null, $sortTable = null)
	{
		$offset = $this->getOffsetByPage($page);

		$query = $this->createQuery($table, $where, $select, $offset, static::getLimit(), $joinTable, $sortTable);

		$result = $this->executeQuery($query);

		if ($result)
		{
			return $this->all($result);
		}

		return false;
	}

	/**
	 * @param $table
	 * @param null $where
	 * @param null $select
	 * @param null $page
	 * @param null $joinTable
	 * @param null $sortTable
	 * @return bool|mixed
	 * @throws \Exception
	 */
	public function getOne($table, $where = null, $select = null, $page = null, $joinTable = null, $sortTable = null)
	{
		$offset = $this->getOffsetByPage($page);

		$query = $this->createQuery($table, $where, $select, $offset, static::getLimit(), $joinTable, $sortTable);
		$result = $this->executeQuery($query);

		if ($result)
		{
			return $this->one($result);
		}

		return false;
	}

	/**
	 * @param $page
	 * @param null $limit
	 * @return float|int
	 */
	protected function getOffsetByPage($page, $limit = null)
	{
		if (empty($limit))
		{
			$limit = static::getLimit();
		}

		$page = (!empty($page) ? $page : 1);
		return $limit * ($page - 1);
	}

	/**
	 * @param $query
	 * @return bool|\mysqli_result
	 * @throws \Exception
	 */
	protected function executeQuery($query)
	{
		$link = $this->getLink();

		try
		{
			$result = $link->query($query);
		}
		catch (\Exception $exception)
		{
			throw new \Exception('SQL error: '. $exception->getCode()." ". $exception->getMessage());
		}

		if (!$result && !empty($link->error))
		{
			throw new \Exception('SQL error '.$link->errno.': '. $link->error."\n ". htmlspecialchars($query));

		}
		return $result;
	}

	/**
	 * @param $table
	 * @param null $where
	 * @param null $select
	 * @param null $offset
	 * @param null $limit
	 * @param null $joinTable
	 * @param null $sortTable
	 * @return string
	 */
	protected function createQuery($table, $where = null, $select = null, $offset = null, $limit = null, $joinTable = null, $sortTable = null)
	{
		$sqlSelect = '*';
		if (!empty($select))
		{
			$sqlSelect = $select;
		}
		$tableName = addslashes($table);

		$sqlQuery = "SELECT ".$sqlSelect." FROM " . $tableName. " AS " . $tableName;

		if (!empty($joinTable))
		{
			foreach($joinTable as $joinEntityTableName => $joinEntityRule)
			{
				$sqlQuery .= " LEFT JOIN ".$joinEntityTableName." ON ".$joinEntityRule;
			}
		}
		if (!empty($where))
		{
			$sqlQuery .= ' WHERE ' . $where;
		}

		if (!empty($sortTable))
		{
			$sqlQuery .= ' ORDER BY '.$sortTable;
		}


		if (!empty($limit))
		{
			$sqlQuery .= ' LIMIT ' . intval($limit);
		}

		if (!empty($offset))
		{
			$sqlQuery .= ' OFFSET ' . intval($offset);
		}


		return $sqlQuery;
	}

	/**
	 * @param $table
	 * @param $names
	 * @param $values
	 * @throws \Exception
	 */
	public function insert($table, $names, $values)
	{
		$query = 'INSERT INTO '.addslashes($table).' ('.$names.') VALUES('.$values.')';
		return $this->executeQuery($query);
	}

	/**
	 * @param $table
	 * @param $id
	 * @param $values
	 * @return bool|\mysqli_result
	 * @throws \Exception
	 */
	public function update($table, $id, $values)
	{
		$query = 'UPDATE '.addslashes($table).' SET ' . $values . ' WHERE id='.intval($id);
		return $this->executeQuery($query);
	}

	/**
	 * @return mixed
	 */
	public function getLastId()
	{
		$link = $this->getLink();
		return $link->insert_id;
	}

	/**
	 * @param $table
	 * @param null $where
	 * @return bool
	 */
	public function getCount($table, $where = null)
	{
		$query = $this->createQuery($table, $where);
		$result = $this->executeQuery("SELECT COUNT(1) AS count FROM (".$query.")AS COUNT_".addslashes($table));
		if ($resultData = $this->one($result))
		{
			return intval($resultData['count']);
		}

		return false;
	}

	/**
	 * @param \mysqli_result $result
	 * @return mixed
	 */
	public function all(\mysqli_result $result)
	{
		return $result->fetch_all(MYSQLI_ASSOC);
	}

	/**
	 * @param \mysqli_result $result
	 * @return mixed
	 */
	public function one(\mysqli_result $result)
	{
		return $result->fetch_array(MYSQLI_ASSOC);
	}

	/**
	 * @return int
	 */
	public static function getLimit()
	{
		return static::$limit;
	}
}