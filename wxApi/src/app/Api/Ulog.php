<?php

namespace App\Api;

class Ulog extends \PhalApi\Api
{
	public function getRules()
	{
		return array("getLog" => array("user_id" => array("name" => "user_id", "type" => "int", "min" => 1, "default" => 1, "desc" => "会员id"), "vod_id" => array("name" => "vod_id", "type" => "int", "min" => 1, "default" => 1, "desc" => "视频id")), "getLogAll" => array("user_id" => array("name" => "user_id", "type" => "int", "min" => 1, "default" => 1, "desc" => "会员id")), "deleteLog" => array("user_id" => array("name" => "user_id", "type" => "int", "min" => 1, "default" => 1, "desc" => "会员id"), "vod_id" => array("name" => "vod_id", "type" => "int", "min" => 1, "default" => 1, "desc" => "视频id")), "deleteLogTime" => array("user_id" => array("name" => "user_id", "type" => "int", "min" => 1, "default" => 1, "desc" => "会员id")), "deleteLogAll" => array("user_id" => array("name" => "user_id", "type" => "int", "min" => 1, "default" => 1, "desc" => "会员id")), "setLog" => array("user_id" => array("name" => "user_id", "type" => "int", "min" => 1, "default" => 1, "desc" => "会员id"), "vod_id" => array("name" => "vod_id", "type" => "int", "min" => 1, "default" => 1, "desc" => "视频id"), "play_form" => array("name" => "play_form", "type" => "int", "min" => 0, "default" => 0, "desc" => "来源"), "play_index" => array("name" => "play_index", "type" => "int", "min" => 0, "default" => 0, "desc" => "集数"), "play_time" => array("name" => "play_time", "type" => "int", "min" => 0, "default" => 0, "desc" => "时间"), "like" => array("name" => "like", "type" => "boolean", "default" => false, "desc" => "收藏"), "ulog_type" => array("name" => "ulog_type", "type" => "int", "min" => 1, "default" => 4, "desc" => "记录分类")), "setSubscribe" => array("user_id" => array("name" => "user_id", "type" => "int", "min" => 1, "default" => 1, "desc" => "会员id"), "vod_id" => array("name" => "vod_id", "type" => "int", "min" => 1, "default" => 1, "desc" => "视频id"), "type_id" => array("name" => "type_id", "type" => "int", "min" => 0, "default" => 1, "desc" => "分类id"), "ulog_nid" => array("name" => "ulog_nid", "type" => "int", "min" => 1, "default" => 1, "desc" => "视频数量"), "openid" => array("name" => "openid", "require" => true, "min" => "1", "desc" => "openid"), "mark" => array("name" => "mark", "type" => "string", "require" => true, "default" => "", "desc" => "标识，参数必须")));
	}
	public function setSubscribe()
	{
		$domain = new \App\Domain\Domain_Ulog();
		$list = $domain->setSubscribe($this->user_id, $this->vod_id, $this->type_id, $this->ulog_nid, $this->openid, $this->mark);
		return $list;
	}
	public function setLog()
	{
		$domain = new \App\Domain\Domain_Ulog();
		$list = $domain->setLog($this->user_id, $this->vod_id, $this->play_form, $this->play_index, $this->play_time, $this->like, $this->ulog_type);
		return $list;
	}
	public function getLog()
	{
		$domain = new \App\Domain\Domain_Ulog();
		$list = $domain->getLog($this->user_id, $this->vod_id);
		return $list;
	}
	public function getLogAll()
	{
		$domain = new \App\Domain\Domain_Ulog();
		$list = $domain->getLogAll($this->user_id);
		return $list;
	}
	public function deleteLog()
	{
		$domain = new \App\Domain\Domain_Ulog();
		$list = $domain->deleteLog($this->user_id, $this->vod_id);
		return $list;
	}
	public function deleteLogTime()
	{
		$domain = new \App\Domain\Domain_Ulog();
		$list = $domain->deleteLogTime($this->user_id);
		return $list;
	}
	public function deleteLogAll()
	{
		$domain = new \App\Domain\Domain_Ulog();
		$list = $domain->deleteLogAll($this->user_id);
		return $list;
	}
}