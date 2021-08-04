<?php

namespace App\Domain;

class Domain_User
{
	public function wxRegister($user, $name, $avatarUrl, $scene, $mark, $platform)
	{
		$model = new \App\Model\Model_User();
		$rs = $model->wxRegister($user, $name, $avatarUrl, $scene, $mark, $platform);
		return $rs;
	}
	public function wxLogin($openid, $scene, $platform)
	{
		$model = new \App\Model\Model_User();
		$rs = $model->wxLogin($openid, $scene, $platform);
		return $rs;
	}
	public function pidNum($uid)
	{
		$model = new \App\Model\Model_User();
		$user = $model->pidNum($uid);
		return $user;
	}
	public function wxCode($code, $mark)
	{
		$rs = array("items" => array(), "total" => 0);
		$model = new \App\Model\Model_User();
		$rs = $model->wxCode($code, $mark);
		return $rs;
	}
	public function qqRegister($user, $name, $avatarUrl, $scene, $mark)
	{
		$model = new \App\Model\Model_User();
		$rs = $model->qqRegister($user, $name, $avatarUrl, $scene, $mark);
		return $rs;
	}
	public function qqLogin($openid, $scene)
	{
		$model = new \App\Model\Model_User();
		$rs = $model->qqLogin($openid, $scene);
		return $rs;
	}
	public function qqCode($code, $mark)
	{
		$rs = array("items" => array(), "total" => 0);
		$model = new \App\Model\Model_User();
		$rs = $model->qqCode($code, $mark);
		return $rs;
	}
}