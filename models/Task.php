<?php
/**
 * Product: test1.
 * Date: 2019-05-22
 */

namespace app\models;


use app\classes\App;

class Task extends BaseModel
{
	const STATUS_NEW = 'N';
	const STATUS_DONE = 'D';

	protected $fillFields = [
		'text', 'status'
	];

	protected $requiredFields = [
		'user_id', 'status', 'text'
	];

	/**
	 * @return string
	 */
	public static function getTableName()
	{
		return 'tasks';
	}

	/**
	 * @return array
	 */
	public static function getTableFields()
	{
		return [
			'user_id',
			'status',
			'text'
		];
	}

	/**
	 * @return array
	 */
	public static function getTableSortFields()
	{
		return [
			'id',
			'status',
			'users.login',
			'users.email',
		];
	}

	/**
	 * @return bool|string
	 */
	public static function getTableSelect()
	{
		return static::getTableName().".*"
			.", ".User::getTableName().".id as user_id "
			.", ".User::getTableName().".login as user_login "
			.", ".User::getTableName().".email as user_email "
		;
	}

	/**
	 * @return array
	 */
	public static function getJoinTable()
	{
		return [
				User::getTableName() => 'tasks.user_id = users.id'
		];
	}

	/**
	 * @param int $page
	 * @param null $sort
	 * @param null $sortType
	 * @return bool|mixed
	 * @throws \Exception
	 */
	public static function getAll($page = 1, $sort = null, $sortType = null)
	{
		$db = App::$app->getDb();
		return $db->get(static::getTableName(), null, static::getTableSelect(), $page, static::getJoinTable(), static::prepareTableSort($sort, $sortType));
	}

	/**
	 * @param null $sort
	 * @param null $sortType
	 * @return string
	 * @throws \Exception
	 */
	public static function prepareTableSort($sort = null, $sortType = null)
	{
		$result = parent::prepareTableSort($sort, $sortType);
		$tableFields = static::getTableSortFields();
		if (!in_array($sort, $tableFields))
		{
			return $result;
		}

		if (empty($sortType) || mb_strtolower($sortType) != 'desc')
		{
			$sortType = 'asc';
		}

		$tableName = static::getTableName();
		$sortFieldName = $sort;

		if ($anotherTableNamePos = mb_strpos($sort, '.'))
		{
			$anotherTable = mb_substr($sort, 0, $anotherTableNamePos);

			$joinTable = static::getJoinTable();
			if (!in_array($anotherTable, array_keys($joinTable)))
			{
				return $result;
			}
			else
			{
				$tableName = $anotherTable;
				$sortFieldName = mb_substr($sort, $anotherTableNamePos + 1, mb_strlen($sort));
			}
		}

		return $tableName.".".$sortFieldName." ".$sortType;
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public static function count()
	{
		$db = App::$app->getDb();
		return $db->getCount(static::getTableName(), null);
	}

	/**
	 * @param $id
	 * @return BaseModel|Task|bool
	 * @throws \Exception
	 */
	public static function getById($id)
	{
		return static::getByField('id', $id);
	}

	/**
	 * @return array
	 */
	public function getFields()
	{
		return $this->fields;
	}
}