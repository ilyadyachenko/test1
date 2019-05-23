<?php
/**
 * Product: test1.
 * Date: 2019-05-22
 */

namespace app\models;


use app\classes\App;

abstract class BaseModel
{

	protected $fillFields = [];
	protected $requiredFields = [];

	protected $id = null;

	protected $fields = [];
	protected $originalFields = [];

	protected $errors = false;

	/**
	 * @return array
	 * @throws \Exception
	 */
	public static function getTableFields()
	{
		throw new \Exception('Model Fields is empty');
	}

	/**
	 * @throws \Exception
	 */
	public static function getTableName()
	{
		throw new \Exception('Model tableName is empty');
	}

	/**
	 * @return bool
	 */
	public static function getTableSelect()
	{
		return false;
	}

	/**
	 * @return bool
	 */
	public static function getJoinTable()
	{
		return false;
	}

	/**
	 * @param null $sort
	 * @param null $sortType
	 * @return string
	 * @throws \Exception
	 */
	public static function prepareTableSort($sort = null, $sortType = null)
	{
		return static::getTableName().".id ASC";
	}


	/**
	 * @param $fields
	 * @return bool
	 */
	public function setAttributes($fields)
	{
		$allowFields = $this->getAllowFields($fields);
		if (empty($allowFields))
		{
			return false;
		}

		foreach ($allowFields as $fieldName)
		{
			$this->fields[$fieldName] = $fields[$fieldName];
		}

		return true;
	}


	/**
	 * @param $name
	 * @param $value
	 * @return bool
	 */
	public function setAttribute($name, $value)
	{
		$allowFields = $this->getAllowFields([$name => $value]);
		if (empty($allowFields))
		{
			return false;
		}

		$this->fields[$name] = $value;



		return true;
	}

	/**
	 * @param $name
	 * @param $value
	 * @return bool
	 */
	public function setAttributeNoDemand($name, $value)
	{
		$this->fields[$name] = $value;

		return true;
	}

	/**
	 * @param array $fields
	 * @return bool
	 */
	public function setAttributesNoDemand(array $fields)
	{
		foreach ($fields as $fieldName => $fieldValue)
		{
			$this->setAttributeNoDemand($fieldName, $fieldValue);
		}
		return true;
	}

	/**
	 * @param array $fields
	 * @return array
	 */
	protected function getAllowFields(array $fields)
	{
		return array_intersect(array_keys($fields), $this->fillFields);
	}



	/**
	 * @return bool
	 */
	public function validate()
	{
		if (empty($this->requiredFields))
		{
			return true;
		}

		foreach ($this->requiredFields as $requiteField)
		{

			if ((array_key_exists($requiteField, $this->fields)
					&& empty($this->fields[$requiteField]))
				|| (!array_key_exists($requiteField, $this->fields) && $this->getId() == 0))
			{
				$this->addError($requiteField, 'Field '.htmlspecialchars($requiteField).' is empty');
			}
		}

		if ($this->hasErrors())
		{
			return false;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * @return bool
	 */
	public function hasErrors()
	{
		return (!empty($this->getErrors()));
	}

	/**
	 * @param $field
	 * @param $text
	 */
	public function addError($field, $text)
	{
		$this->errors[$field][] = $text;
	}

	public function save()
	{
		if (empty($this->fields))
		{
			return false;
		}


		if (empty($this->id))
		{
			$sqlFieldNames = 'id';
			$sqlFieldValues = 'NULL';

			foreach ($this->fields as $fieldName => $fieldValue)
			{
				$sqlFieldNames .= (!empty($sqlFieldNames) ? ', ' : '') . addslashes($fieldName);
				$sqlFieldValues .= (!empty($sqlFieldValues) ? ', ' : '') . '"'.addslashes($fieldValue).'"';
			}

			$db = App::$app->getDb();

			$db->insert(static::getTableName(), $sqlFieldNames, $sqlFieldValues);
			$this->id = $db->getLastId();

			return ($this->id > 0);

		}
		else
		{
			$sqlFieldValues = '';
			foreach ($this->fields as $fieldName => $fieldValue)
			{
				if (array_key_exists($fieldName, $this->originalFields) && $fieldValue != $this->originalFields[$fieldName])
				{
					$sqlFieldValues .= (!empty($sqlFieldValues) ? ', ' : '') . '`'.addslashes($fieldName).'` = "'.addslashes($fieldValue).'"';
				}
			}

			if (empty($sqlFieldValues))
			{
				return true;
			}
			$db = App::$app->getDb();

			$db->update(static::getTableName(), $this->id, $sqlFieldValues);

			$this->originalFields = $this->fields;

			return true;
		}

	}

	/**
	 * @return null|int
	 */
	public function getId()
	{
		return $this->id;
	}


	/**
	 * @return null|int
	 */
	public function getStatus()
	{
		return (!empty($this->fields['status']) ? $this->fields['status'] : false);
	}


	/**
	 * @param $fieldName
	 * @param $fieldValue
	 * @return BaseModel|bool
	 * @throws \Exception
	 */
	protected static function getByField($fieldName, $fieldValue)
	{
		$db = App::$app->getDb();
		$userFields = $db->getOne(static::getTableName(), static::getTableName().".". addslashes($fieldName).' = "'.addslashes($fieldValue).'"', static::getTableSelect(), null, static::getJoinTable());
		if (empty($userFields))
		{
			return false;
		}
		$entity = new static();

		$entity->id = $userFields['id'];
		unset($userFields['id']);

		$entity->originalFields = $entity->fields = $userFields;
		return $entity;
	}
}