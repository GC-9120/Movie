<?php

error_reporting(E_ALL ^ E_NOTICE);
class Domain_Session
{
	const MAX_EXPIRE_TIME_FOR_SESSION = 2592000;
	public static function generate($_var_0, $_var_1 = "")
	{
		if ($_var_0 <= 0) {
			return '';
		}
		$_var_2 = strtoupper(substr(sha1(uniqid(null, true)) . sha1(uniqid(null, true)), 0, 64));
		$_var_3 = array();
		$_var_3['user_id'] = $_var_0;
		$_var_3['token'] = $_var_2;
		$_var_3['client'] = $_var_1;
		$_var_3['times'] = 1;
		$_var_3['login_time'] = $_SERVER['REQUEST_TIME'];
		$_var_3['expires_time'] = $_SERVER['REQUEST_TIME'] + self::getMaxExpireTime();
		$_var_4 = new Model_User_UserSession();
		$_var_4->insert($_var_3, $_var_0);
		return $_var_2;
	}
	public static function generateSession($_var_5)
	{
		if ($_var_5 <= 0) {
			return '';
		}
		return $_var_5;
	}
	public static function getMaxExpireTime()
	{
		return self::MAX_EXPIRE_TIME_FOR_SESSION;
	}
}