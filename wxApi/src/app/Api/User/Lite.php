<?php

error_reporting(E_ALL ^ E_NOTICE);
class User_Lite
{
	public function __construct($_var_0 = false)
	{
		$this->init($_var_0);
	}
	protected function init($_var_1)
	{
		DI()->loader->addDirs('./Library/User/User');
		PhalApi_Translator::addMessage(API_ROOT . '/Library/User');
	}
	public function check($_var_2 = false)
	{
		$_var_3 = DI()->request->get('user_id');
		$_var_4 = DI()->request->get('token');
		if (empty($_var_3) || empty($_var_4)) {
			DI()->logger->debug('user not login', array('userId' => $_var_3, 'token' => $_var_4));
			if ($_var_2) {
				throw new PhalApi_Exception_BadRequest(T('user not login'), 1);
			}
			return false;
		}
		$_var_5 = new Model_User_UserSession();
		$_var_6 = $_var_5->getExpiresTime($_var_3, $_var_4);
		if ($_var_6 <= $_SERVER['REQUEST_TIME']) {
			DI()->logger->debug('user need to login again', array('expiresTime' => $_var_6, 'userId' => $_var_3, 'token' => $_var_4));
			if ($_var_2) {
				throw new PhalApi_Exception_BadRequest(T('user need to login again'), 1);
			}
			return false;
		}
		return true;
	}
	public function logout()
	{
		$this->_renewalTo($_SERVER['REQUEST_TIME']);
	}
	public function heartbeat()
	{
		$this->_renewalTo($_SERVER['REQUEST_TIME'] + Domain_User_User_Session::getMaxExpireTime());
	}
	public function generateSession($_var_7, $_var_8 = "")
	{
		return Domain_User_User_Session::generate($_var_7, $_var_8);
	}
	protected function _renewalTo($_var_9)
	{
		$_var_10 = DI()->request->get('user_id');
		$_var_11 = DI()->request->get('token');
		if (empty($_var_10) || empty($_var_11)) {
			return null;
		}
		$_var_12 = new Model_User_UserSession();
		$_var_12->updateExpiresTime($_var_10, $_var_11, $_var_9);
	}
}