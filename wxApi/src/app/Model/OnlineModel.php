<?php

namespace App\Model;

class OnlineModel extends \PhalApi\Model\NotORMModel
{
	protected function getTableName($id)
	{
		return "mac_vod";
	}
	public function getSyLevelAll($mark)
	{
		$uniapp = \PhalApi\DI()->uniapp[$mark];
		$sy_type = $uniapp["index"]["sy_type"];
		$rs = array();
		$lunbo = $this->getORM()->select("vod_id,type_id,vod_name,vod_actor,vod_pic,vod_remarks,vod_year,vod_score,vod_level,vod_hits")->where("vod_level = 9")->order("vod_year DESC")->order("vod_time DESC")->limit(0, 10)->fetchAll();
		$list = array();
		foreach ($sy_type as $key => $value) {
			if ($value["type_id"] < 20) {
				$where = "vod_level=" . $value["type_id"];
			} else {
				$where = "type_id=" . $value["type_id"];
			}
			$data = array("type_name" => $value["type_name"], "type_id" => $value["type_id"], "list" => $this->getORM()->select("vod_id,type_id,vod_name,vod_actor,vod_pic,vod_remarks,vod_year,vod_score,vod_level,vod_hits")->where($where)->order("vod_time DESC")->limit(0, 6)->fetchAll());
			array_push($list, $data);
		}
		return $rs = array("lunbo" => $lunbo, "item" => $list);
	}
	public function getHomeLevelAll($level, $page, $perpage)
	{
		$rs = array();
		if ($level == 23) {
			$rs = array("type_name" => "最新电影", "list" => $this->getORM()->select("vod_id,type_id,vod_name,vod_actor,vod_pic,vod_remarks,vod_year,vod_score")->where("type_id = 23")->order("vod_year DESC")->order("vod_time DESC")->limit(($page - 1) * $perpage, $perpage)->fetchAll());
		} else {
			$rs = array("type_name" => "同步剧场", "list" => $this->getORM()->select("vod_id,type_id,vod_name,vod_actor,vod_pic,vod_remarks,vod_year,vod_score")->where("vod_level", $level)->order("vod_year DESC")->order("vod_time DESC")->limit(($page - 1) * $perpage, $perpage)->fetchAll());
		}
		return $rs;
	}
	public function getLevelId($level, $page, $perpage)
	{
		return $this->getORM()->select("vod_id,type_id,vod_name,vod_actor,vod_pic,vod_remarks,vod_area,vod_year,vod_score,vod_content,vod_class,vod_level,vod_hits")->where("vod_level", $level)->order("vod_time DESC")->limit(($page - 1) * $perpage, $perpage)->fetchAll();
	}
	public function searchVod($keyWords, $page, $perpage)
	{
		$wxverify = \PhalApi\DI()->uniapp["wxverify"];
		$qure = "%" . $keyWords . "%";
		$data = array();
		if ($wxverify == "1") {
			return $this->getORM()->select("vod_id,type_id,vod_name,vod_actor,vod_pic,vod_remarks,vod_area,vod_year,vod_score,vod_content,vod_class,vod_level,vod_hits")->where("type_id", 23)->where("vod_name like '{$qure}' ")->order("vod_time DESC")->fetchAll();
		} else {
			return $this->getORM()->select("vod_id,type_id,vod_name,vod_actor,vod_pic,vod_remarks,vod_area,vod_year,vod_score,vod_content,vod_class,vod_level,vod_hits")->where("NOT type_id", array(23, 24))->where("vod_name like '{$qure}' ")->order("vod_time DESC")->fetchAll();
		}
	}
	public function getJsList($page)
	{
		$regResult = $this->getORM()->select("vod_id,vod_name,vod_pic,vod_hits,vod_remarks,vod_duration,vod_sub,vod_douban_id,vod_content,vod_play_from,vod_play_url,vod_level,vod_hits")->where("type_id", 24)->order("rand()")->limit(10)->fetchAll();
		return $regResult;
	}
	public function getMovById($vodid)
	{
		$data = $this->getORM()->select("vod_id,vod_play_from,type_id,vod_name,vod_actor,vod_director,vod_pic,vod_remarks,vod_area,vod_lang,vod_year,vod_score,vod_douban_score,vod_douban_id,vod_author,vod_points_play,vod_content,vod_play_url,vod_class,vod_level,vod_hits")->where("vod_id='{$vodid}' ")->fetchAll();
		$update = array("vod_hits" => \intval($data[0]["vod_hits"]) + 1);
		$this->getORM()->where("vod_id", $vodid)->update($update);
		return $data;
	}
	public function getTopicVod($vodid)
	{
		$ids = explode(",", $vodid);
		$data = $this->getORM()->select("vod_id,type_id,vod_name,vod_actor,vod_pic,vod_remarks,vod_area,vod_year,vod_score,vod_content,vod_class,vod_level,vod_hits")->where("vod_id", $ids)->order("vod_hits DESC")->fetchAll();
		return $data;
	}
	public function setHits($vodid)
	{
		return $this->getORM()->where("vod_id", $vodid)->updateCounter("vod_hits", 1);
	}
	public function setMovById($vod)
	{
		$vod = json_decode($vod, true);
		$data = $this->getORM()->select("vod_douban_id")->where("vod_id", $vod["vod_id"])->fetchOne();
		$this->getORM()->where("vod_id", $vod["vod_id"])->update($vod);
		return $data;
	}
	public function getTestMovById($vodid)
	{
		return $this->getORM()->select("vod_id,vod_play_from,type_id,vod_name,vod_actor,vod_director,vod_pic,vod_remarks,vod_area,vod_lang,vod_year,vod_score,vod_author,vod_points_play,vod_content,vod_play_url,vod_class,vod_level")->where("vod_id", $vodid)->fetchOne();
	}
	public function getCategoryFind($page, $perpage, $type, $area, $year, $sort, $classid)
	{
		if ($sort == 0) {
			$sort = "vod_hits DESC";
		} elseif ($sort == 1) {
			$sort = "vod_time DESC";
		} elseif ($sort == 2) {
			$sort = "vod_douban_score DESC";
		}
		if ($area == "全部") {
			$area = "%";
		} else {
			$area = "%" . $area . "%";
		}
		if ($year == "全部") {
			$year = "%";
		} else {
			$year = "%" . $year . "%";
		}
		if ($classid == "全部") {
			$classid = "%";
		} else {
			$classid = "%" . $classid . "%";
		}
		return $this->getORM()->select("vod_id,type_id,vod_name,vod_actor,vod_pic,vod_remarks,vod_year,vod_score,vod_level,vod_hits")->where("type_id", $type)->where("vod_class LIKE ?", $classid)->where("vod_area LIKE ?", $area)->where("vod_year LIKE ?", $year)->limit(($page - 1) * $perpage, $perpage)->order($sort)->fetchAll();
	}
}