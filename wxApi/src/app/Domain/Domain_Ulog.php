<?php

namespace App\Domain;

class Domain_Ulog
{
	public function setSubscribe($user_id, $vod_id, $type_id, $ulog_nid, $openid, $mark)
	{
		$model = new \App\Model\Model_Ulog();
		$rs = $model->setSubscribe($user_id, $vod_id, $type_id, $ulog_nid, $openid, $mark);
		return $rs;
	}
	public function setLog($user_id, $vod_id, $play_form, $play_index, $play_time, $like, $ulog_type)
	{
		$model = new \App\Model\Model_Ulog();
		$rs = $model->setLog($user_id, $vod_id, $play_form, $play_index, $play_time, $like, $ulog_type);
		return $rs;
	}
	public function getLog($user_id, $vod_id)
	{
		$model = new \App\Model\Model_Ulog();
		$rs = $model->getLog($user_id, $vod_id);
		return $rs;
	}
	public function deleteLog($user_id, $vod_id)
	{
		$model = new \App\Model\Model_Ulog();
		$rs = $model->deleteLog($user_id, $vod_id);
		return $rs;
	}
	public function deleteLogTime($user_id)
	{
		$model = new \App\Model\Model_Ulog();
		$rs = $model->deleteLogTime($user_id);
		return $rs;
	}
	public function deleteLogAll($user_id)
	{
		$model = new \App\Model\Model_Ulog();
		$rs = $model->deleteLogAll($user_id, $vod_id);
		return $rs;
	}
	public function getLogAll($user_id)
	{
		$model = new \App\Model\Model_Ulog();
		$rs = $model->getLogAll($user_id);
		$vod_id = array_column($rs, "ulog_rid");
		$movModel = new \App\Model\OnlineModel();
		$items = $movModel->getTestMovById($vod_id);
		foreach ($items as $v) {
			$k = array_search($v["vod_id"], $vod_id);
			$newarr[$k] = array_merge($v, $rs[$k]);
		}
		return $newarr;
	}
}