<?php

namespace App\Common\User;

class User
{
	protected $id = 0;
	protected $profile = array();
	public function __construct()
	{
		$user_id = \PhalApi\DI()->request->get("user_id");
		$user_id = intval($user_id);
		$token = \PhalApi\DI()->request->get("token");
		if ($user_id && $token) {
			$domain = new \App\Domain\User\UserSession();
			$is_login = $domain->checkSession($user_id, $token);
			if ($is_login) {
				$this->login($user_id);
			}
		}
	}
	public function login($user_id)
	{
		$userDomain = new \App\Domain\User\User();
		$profile = $userDomain->getUserInfo($user_id, "id,username,nickname,reg_time,avatar,mobile,sex,email");
		$this->profile = $profile ? $profile : $this->profile;
		$this->id = $user_id;
	}
	public function isLogin()
	{
		return $this->id > 0 ? true : false;
	}
	public function getUserId()
	{
		return $this->id;
	}
	public function getProfile()
	{
		return $this->profile;
	}
	public function getProfileBy($filed, $default = null)
	{
		return isset($this->profile[$filed]) ? $this->profile[$filed] : $default;
	}
	public function __get($name)
	{
		return isset($this->profile[$name]) ? $this->profile[$name] : null;
	}
}