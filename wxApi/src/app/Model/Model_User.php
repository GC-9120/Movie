<?php

namespace App\Model;

class Model_User extends \PhalApi\Model\NotORMModel
{
	protected function getTableName($id)
	{
		return "mac_user";
	}
	public function wxRegister($user, $name, $avatarUrl, $scene, $mark, $platform)
	{
		$ip = \PhalApi\Tool::getClientIp();
		$openid = $this->wxCode($user, $mark);
		if (!$openid) {
			return false;
		}
		$userData = $this->getORM()->select("user_name,user_id,user_points,user_login_ip,user_login_time,user_login_num,user_portrait,user_openid_weixin,user_nick_name,user_status,user_pid,user_pid_num")->where("user_name='{$openid}' ")->where("user_pwd", md5($openid))->fetchOne();
		if (!$userData) {
			$data = array("user_name" => $openid, "user_pwd" => md5($openid), "user_points" => 10, "group_id" => 2, "user_status" => 1, "user_points_froze" => 0, "user_reg_time" => time(), "user_reg_ip" => $ip, "user_portrait_thumb" => $mark, "user_nick_name" => $name, "user_openid_weixin" => $openid, "user_login_time" => time(), "user_login_num" => 1, "user_login_ip" => $scene, "user_portrait" => $avatarUrl, "user_phone" => $platform);
			$regResult = $this->getORM()->insert($data);
			if ($regResult <= 0) {
				return $regResult;
			}
			return $data;
		} else {
			$update = array("user_last_login_time" => $userData["user_login_time"], "user_last_login_ip" => $userData["user_login_ip"], "user_login_num" => \intval($userData["user_login_num"]) + 1, "user_login_time" => time(), "user_login_ip" => $scene, "user_portrait_thumb" => $mark);
			$this->getORM()->where("user_name", $user)->update($update);
			return $userData;
		}
	}
	public function wxLogin($openid, $scene, $platform)
	{
		$cache = \PhalApi\DI()->cache;
		$cacheData = $cache->get($openid);
		if (empty($cacheData)) {
			$userData = $this->getORM()->select("user_name,user_id,user_points,group_id,user_end_time,user_portrait,user_openid_weixin,user_nick_name,user_login_time,user_login_ip,user_login_num,user_status,user_pid,user_pid_num")->where("user_name", $openid)->fetchOne();
			if (!$userData) {
				return $userData;
			} else {
				$data = array("user_last_login_time" => $userData["user_login_time"], "user_last_login_ip" => $userData["user_login_ip"], "user_login_num" => \intval($userData["user_login_num"]) + 1, "user_login_time" => time(), "user_login_ip" => $scene, "user_phone" => $platform);
				$this->getORM()->where("user_name", $openid)->update($data);
				$cache->set($openid, $userData, 60);
				return $userData;
			}
		} else {
			return $cacheData;
		}
	}
	public function pidNum($uid)
	{
		$cache = \PhalApi\DI()->cache;
		$cacheData = $cache->get($uid);
		if (empty($cacheData)) {
			$num = $this->getORM()->select("user_pid_num")->where("user_id", $uid)->fetchOne();
			$cache->set($uid, $num, 60);
			return $num;
		} else {
			return $cacheData;
		}
	}
	public function wxCode($code, $mark)
	{
		$appid = \PhalApi\DI()->uniapp[$mark]["site"]["appid"];
		$AppSecret = \PhalApi\DI()->uniapp[$mark]["site"]["AppSecret"];
		$config = array("appid" => $appid, "secret_key" => $AppSecret);
		$wechatmini = new \PhalApi\Wechatmini\Lite($config);
		$rs = $wechatmini->getOpenid($code);
		if (empty($rs["openid"])) {
			return false;
		} else {
			return $rs["openid"];
		}
	}
	public function qqRegister($user, $name, $avatarUrl, $scene, $mark)
	{
		$ip = \PhalApi\Tool::getClientIp();
		$openid = $this->qqCode($user, $mark);
		if (!$openid) {
			return false;
		}
		$userData = $this->getORM()->select("user_name,user_id,user_points,user_login_ip,user_login_time,user_login_num,user_portrait,user_openid_weixin,user_nick_name,user_status")->where("user_name='{$openid}' ")->where("user_pwd", md5($openid))->fetchOne();
		if (!$userData) {
			$data = array("user_name" => $openid, "user_pwd" => md5($openid), "user_points" => 10, "group_id" => 2, "user_status" => 1, "user_points_froze" => 0, "user_reg_time" => time(), "user_reg_ip" => $ip, "user_portrait_thumb" => $mark, "user_nick_name" => $name, "user_openid_weixin" => $openid, "user_login_time" => time(), "user_login_num" => 1, "user_login_ip" => $scene, "user_portrait" => $avatarUrl);
			$regResult = $this->getORM()->insert($data);
			if ($regResult <= 0) {
				return $regResult;
			}
			return $data;
		} else {
			$update = array("user_last_login_time" => $userData["user_login_time"], "user_last_login_ip" => $userData["user_login_ip"], "user_login_num" => \intval($userData["user_login_num"]) + 1, "user_login_time" => time(), "user_login_ip" => $scene, "user_portrait_thumb" => $mark);
			$this->getORM()->where("user_name", $user)->update($update);
			return $userData;
		}
	}
	public function qqLogin($openid, $scene)
	{
		$cache = \PhalApi\DI()->cache;
		if (empty($cacheData)) {
			$userData = $this->getORM()->select("user_name,user_id,user_points,group_id,user_end_time,user_portrait,user_openid_weixin,user_nick_name,user_login_time,user_login_ip,user_login_num,user_status")->where("user_name", $openid)->fetchOne();
			if (!$userData) {
				return $userData;
			} else {
				$data = array("user_last_login_time" => $userData["user_login_time"], "user_last_login_ip" => $userData["user_login_ip"], "user_login_num" => \intval($userData["user_login_num"]) + 1, "user_login_time" => time(), "user_login_ip" => $scene);
				$this->getORM()->where("user_name", $openid)->update($data);
				$cache->set($openid, $userData, 60);
				return $userData;
			}
		} else {
			return $cacheData;
		}
	}
	public function qqCode($code, $mark)
	{
		$uniapp = \PhalApi\DI()->uniapp[$mark];
		$curl = new \PhalApi\CUrl();
		$curl->setHeader(array("dataType" => "json"));
		$url = "https://api.q.qq.com/sns/jscode2session?appid=" . $uniapp["site"]["QQappid"] . "&secret=" . $uniapp["site"]["QQAppSecret"] . "&js_code=" . $code . "&grant_type=authorization_code";
		$rs = $curl->get($url, 3000);
		$rs = json_decode($rs, true);
		if (empty($rs["openid"])) {
			return false;
		} else {
			return $rs["openid"];
		}
	}
}