<?php

namespace App\Model;

class Model_Ulog extends \PhalApi\Model\NotORMModel
{
	protected function getTableName($id)
	{
		return "mac_ulog";
	}
	public function getLog($user_id, $vod_id)
	{
		return $this->getORM()->select("send_num")->where("user_id", $user_id)->where("ulog_rid", $vod_id)->where("ulog_type", 8)->fetchOne();
	}
	public function deleteLog($user_id, $vod_id)
	{
		return $this->getORM()->where("user_id='{$user_id}' ")->where("ulog_rid='{$vod_id}' ")->delete();
	}
	public function deleteLogAll($user_id)
	{
		return $this->getORM()->where("user_id='{$user_id}' ")->delete();
	}
	public function deleteLogTime($user_id)
	{
		$time = time() - 259200;
		return $this->getORM()->where("user_id='{$user_id}' ")->where("ulog_time < '{$time}'")->delete();
	}
	public function getLogAll($user_id)
	{
		return $this->getORM()->select("ulog_rid,ulog_time,ulog_type,play_index")->where("user_id='{$user_id}' ")->order("ulog_time DESC")->fetchAll();
	}
	public function setLog($user_id, $vod_id, $play_form, $play_index, $play_time, $like, $ulog_type)
	{
		$insert = array("user_id" => $user_id, "ulog_rid" => $vod_id, "play_form" => $play_form, "play_index" => $play_index, "play_time" => $play_time, "ulog_type" => $ulog_type, "ulog_time" => time());
		$unique = $this->getORM()->select("ulog_id")->where("user_id='{$user_id}' ")->where("ulog_rid='{$vod_id}' ")->fetchOne();
		if (!$unique) {
			$unique = array("user_id" => $user_id, "ulog_rid" => $vod_id);
		}
		if ($like) {
			$update = array("ulog_type" => $ulog_type, "ulog_time" => time());
		} else {
			$update = array("play_form" => $play_form, "play_index" => $play_index, "play_time" => $play_time, "ulog_type" => $ulog_type, "ulog_time" => time());
		}
		return $this->getORM()->insert_update($unique, $insert, $update);
	}
	public function setSubscribe($user_id, $vod_id, $type_id, $ulog_nid, $openid, $mark)
	{
		if ($type_id == 1 || $type_id == "1") {
			return array("code" => 200, "send_num" => 0, "msg" => "电影暂不支持订阅");
		}
		$insert = array("user_id" => $user_id, "ulog_rid" => $vod_id, "send_num" => $ulog_nid, "user_openid_weixin" => $openid, "ulog_sid" => $type_id, "mark" => $mark, "ulog_type" => 8, "ulog_time" => time());
		$unique = $this->getORM()->select("ulog_id")->where("user_id", $user_id)->where("ulog_rid", $vod_id)->where("ulog_type", 8)->fetchOne();
		if (!$unique) {
			$this->getORM()->insert($insert);
			return array("code" => 200, "send_num" => $ulog_nid, "msg" => "订阅成功");
		} else {
			$update = array("send_num" => \intval($ulog_nid));
			$this->getORM()->where("user_id", $user_id)->where("ulog_rid", $vod_id)->where("ulog_type", 8)->update($update);
			return array("code" => 200, "send_num" => \intval($ulog_nid), "msg" => "订阅成功");
		}
	}
}