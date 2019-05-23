<?php
/**
 * Product: test1.
 * Date: 2019-05-22
 */

namespace app\models;


use app\classes\App;

class User extends BaseModel
{
	protected $fillFields = [
		'login', 'email'
	];

	protected $requiredFields = [
		'login', 'password', 'email'
	];

	/**
	 * @return string
	 */
	public static function getTableName()
	{
		return 'users';
	}

	/**
	 * @return array
	 */
	public static function getTableFields()
	{
		return [
			'login',
			'password',
			'email',
			'is_admin',
			'active'
		];
	}

	/**
	 * @param $login
	 * @return bool|User
	 * @throws \Exception
	 */
	public static function getByLogin($login)
	{
		return static::getByField('login', $login);
	}

	/**
	 * @param $email
	 * @return bool|User
	 * @throws \Exception
	 */
	public static function getByEmail($email)
	{
		return static::getByField('email', $email);
	}

	/**
	 * @param null $password
	 * @return bool
	 */
	public function isValidPassword($password = null)
	{
		return (!empty($this->fields['password']) && $this->fields['password'] == md5($password));
	}

	/**
	 * @return bool
	 */
	public function isAdmin()
	{
		return (!empty($this->fields['is_admin']) && $this->fields['is_admin'] == 1);
	}
}