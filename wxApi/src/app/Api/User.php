<?php

namespace App\Api;

class User extends \PhalApi\Api
{
	public function getRules()
	{
		return array("wxRegister" => array("user" => array("name" => "user", "require" => true, "min" => "1", "desc" => "登录账号"), "name" => array("name" => "name", "require" => true, "min" => "0", "desc" => "微信名字"), "avatarUrl" => array("name" => "avatarUrl", "require" => true, "min" => "0", "desc" => "微信头像"), "host" => array("name" => "host", "require" => true, "min" => "0", "desc" => "域名"), "scene" => array("name" => "scene", "type" => "string", "default" => "", "desc" => "场景"), "platform" => array("name" => "platform", "default" => "", "desc" => "客户端"), "mark" => array("name" => "mark", "type" => "string", "require" => true, "default" => "", "desc" => "标识，参数必须")), "wxLogin" => array("openid" => array("name" => "openid", "require" => true, "min" => "1", "desc" => "openid"), "platform" => array("name" => "platform", "default" => "", "desc" => "客户端"), "scene" => array("name" => "scene", "type" => "string", "default" => "", "desc" => "场景")), "pidNum" => array("uid" => array("name" => "uid", "require" => true, "min" => "1", "desc" => "uid")), "wxCode" => array("code" => array("name" => "code", "require" => true, "min" => "1", "desc" => "code"), "mark" => array("name" => "mark", "type" => "string", "require" => true, "default" => "", "desc" => "标识，参数必须")), "qqRegister" => array("user" => array("name" => "user", "require" => true, "min" => "1", "desc" => "QQ账号"), "name" => array("name" => "name", "require" => true, "min" => "0", "desc" => "QQ名字"), "avatarUrl" => array("name" => "avatarUrl", "require" => true, "min" => "0", "desc" => "QQ头像"), "host" => array("name" => "host", "require" => true, "min" => "0", "desc" => "域名"), "scene" => array("name" => "scene", "type" => "string", "default" => "", "desc" => "场景"), "mark" => array("name" => "mark", "type" => "string", "require" => true, "default" => "", "desc" => "标识，参数必须")), "qqLogin" => array("openid" => array("name" => "openid", "require" => true, "min" => "1", "desc" => "openid"), "scene" => array("name" => "scene", "type" => "string", "default" => "", "desc" => "场景")), "qqCode" => array("code" => array("name" => "code", "require" => true, "min" => "1", "desc" => "code"), "mark" => array("name" => "mark", "type" => "string", "require" => true, "default" => "", "desc" => "标识，参数必须")), "setSubscribe" => array("code" => array("name" => "code", "require" => true, "min" => "1", "desc" => "code"), "mark" => array("name" => "mark", "type" => "string", "require" => true, "default" => "", "desc" => "标识，参数必须")));
	}
	public function setSubscribe()
	{
		$appid = \PhalApi\DI()->uniapp[$this->mark]["site"]["appid"];
		$AppSecret = \PhalApi\DI()->uniapp[$this->mark]["site"]["AppSecret"];
		$config = array("appid" => $appid, "secret_key" => $AppSecret);
		$wechatmini = new \PhalApi\Wechatmini\Lite($config);
		return $wechatmini->getOpenid($this->code);
	}
	public function wxRegister()
	{
		$rs = array("code" => 1, "userInfo" => array());
		$domain = new \App\Domain\Domain_User();
		$user = $domain->wxRegister($this->user, $this->name, $this->avatarUrl, $this->scene, $this->mark, $this->platform);
		if (!$user) {
			$rs["code"] = 400;
			$rs["tips"] = "登录失败";
			$rs["userInfo"] = $user;
		} else {
			$rs["code"] = 200;
			$rs["tips"] = "登录成功";
			$rs["userInfo"] = $user;
		}
		return $rs;
	}
	public function wxLogin()
	{
		$domain = new \App\Domain\Domain_User();
		$user = $domain->wxLogin($this->openid, $this->scene, $this->platform);
		return $user;
	}
	public function pidNum()
	{
		$domain = new \App\Domain\Domain_User();
		$num = $domain->pidNum($this->uid);
		return $num;
	}
	public function wxCode()
	{
		$domain = new \App\Domain\Domain_User();
		$user = $domain->wxCode($this->code, $this->mark);
		return $user;
	}
	public function qqRegister()
	{
		$rs = array("code" => 1, "userInfo" => array());
		$domain = new \App\Domain\Domain_User();
		$user = $domain->qqRegister($this->user, $this->name, $this->avatarUrl, $this->scene, $this->mark);
		if (!$user) {
			$rs["code"] = 400;
			$rs["tips"] = "注册失败";
			$rs["userInfo"] = $user;
		} else {
			$rs["code"] = 200;
			$rs["tips"] = "注册成功";
			$rs["userInfo"] = $user;
		}
		return $rs;
	}
	public function qqLogin()
	{
		$domain = new \App\Domain\Domain_User();
		$user = $domain->qqLogin($this->openid, $this->scene);
		return $user;
	}
	public function qqCode()
	{
		$domain = new \App\Domain\Domain_User();
		$user = $domain->qqCode($this->code, $this->mark);
		return $user;
	}
}