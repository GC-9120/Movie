<?php

namespace App\Domain;

class MovDomain
{
	public function getOnlineMovById($vodid)
	{
		$cache = \PhalApi\DI()->cache;
		$model = new \App\Model\OnlineModel();
		$data = $cache->get("getOnlineMovById_" . $vodid);
		if (empty($data)) {
			$items = $model->getMovById($vodid);
			$cache->set("getOnlineMovById_" . $vodid, $items, 30);
			$level = $this->getHomeLevelAll($items[0]["type_id"], 1, 10);
			$list = array("items" => $items, "level" => $level);
			return $list;
		} else {
			$update = $model->setHits($vodid);
			$level = $this->getHomeLevelAll($items[0]["type_id"], 1, 10);
			$list = array("items" => $data, "level" => $level);
			return $list;
		}
	}
	public function getTopicVod($vodid)
	{
		$cache = \PhalApi\DI()->cache;
		$data = $cache->get($vodid);
		if (empty($data)) {
			$model = new \App\Model\OnlineModel();
			$list = $model->getTopicVod($vodid);
			$cache->set($vodid, $list, 3000);
			return $list;
		} else {
			return $data;
		}
	}
	public function setMovById($vod)
	{
		$model = new \App\Model\OnlineModel();
		$items = $model->setMovById($vod);
		return $items;
	}
	public function hotKeywords()
	{
		$cache = \PhalApi\DI()->cache;
		$data = $cache->get("hotKeywords");
		if (empty($data)) {
			$curl = new \PhalApi\CUrl();
			$items = $curl->get("https://movie.douban.com/j/new_search_subjects?sort=U&range=0,10&tags=&start=0&year_range=2020,2020", 3000);
			$items = json_decode($items, false);
			$cache->set("hotKeywords", $items, 1000);
			return $items;
		} else {
			return $data;
		}
	}
	public function searchVod($keyWords, $page, $perpage)
	{
		$rs = array("items" => array(), "total" => 0, "cache" => false);
		$cache = \PhalApi\DI()->cache;
		$data = $cache->get("searchVod_" . $keyWords . "-" . $page);
		if (empty($data)) {
			$model = new \App\Model\OnlineModel();
			$items = $model->searchVod($keyWords, $page, $perpage);
			$rs["items"] = $items;
			$cache->set("searchVod_" . $keyWords . "-" . $page, $rs, 600);
			return $rs;
		} else {
			$rs = $data;
			$rs["cache"] = true;
			return $rs;
		}
	}
	public function getHomeLevel()
	{
		$model = new \App\Model\OnlineModel();
		$items = $model->getHomeLevel();
		return $items;
	}
	public function getHomeListall($typeid)
	{
		$model = new \App\Model\SeriModel();
		if ($typeid == 0) {
			$items = "a";
		} else {
			$items = "b";
		}
		return $items;
	}
	public function getHomeLevelAll($level, $page, $limit)
	{
		$cache = \PhalApi\DI()->cache;
		$data = $cache->get("getHomeLevelAll_" . $level . "-" . $page);
		if (empty($data)) {
			$model = new \App\Model\OnlineModel();
			$items = $model->getHomeLevelAll($level, $page, $limit);
			$cache->set("getHomeLevelAll_" . $level . "-" . $page, $items, 3000);
			return $items;
		} else {
			$items = $data;
			return $items;
		}
	}
	public function getLevelId($level, $page, $limit)
	{
		$cache = \PhalApi\DI()->cache;
		$data = $cache->get("getLevelId_" . $level . "-" . $page);
		if (empty($data)) {
			$model = new \App\Model\OnlineModel();
			$items = $model->getLevelId($level, $page, $limit);
			$cache->set("getLevelId_" . $level . "-" . $page, $items, 300);
			return $items;
		} else {
			$items = $data;
			return $items;
		}
	}
	public function getSyLevelAll($mark)
	{
		$cache = \PhalApi\DI()->cache;
		$data = $cache->get("getSyLevelAll_" . $mark);
		if (empty($data)) {
			$model = new \App\Model\OnlineModel();
			$items = $model->getSyLevelAll($mark);
			$cache->set("getSyLevelAll_" . $mark, $items, 300);
			return $items;
		} else {
			$items = $data;
			return $items;
		}
	}
	public function getJsList($page)
	{
		$cache = \PhalApi\DI()->cache;
		$data = $cache->get("getJsList_" . $page);
		if (empty($data)) {
			$model = new \App\Model\OnlineModel();
			$items = $model->getJsList($page);
			$cache->set("getJsList_" . $page, $items, 300);
			return $items;
		} else {
			$items = $data;
			return $items;
		}
	}
	public function getCategory($type, $area, $year, $sort, $page, $limit, $classid)
	{
		$cache = \PhalApi\DI()->cache;
		$data = $cache->get("getCategory_" . $type . "-" . $classid . "-" . $area . "-" . $year . "-" . $sort . "-" . $page);
		if (empty($data)) {
			$model = new \App\Model\OnlineModel();
			$items = $model->getCategoryFind($page, $limit, $type, $area, $year, $sort, $classid);
			$cache->set("getCategory_" . $type . "-" . $classid . "-" . $area . "-" . $year . "-" . $sort . "-" . $page, $items, 300);
			return $items;
		} else {
			$items = $data;
			return $items;
		}
	}
	public function getConfig($host, $mark, $scene, $platform)
	{
		$cache = \PhalApi\DI()->cache;
		$uniapp = \PhalApi\DI()->uniapp[$mark];
		$time = getdate()["minutes"];
		if (strpos($uniapp["weeks"], $scene) !== false) {
			if ($uniapp["site"]["platform"] == "1") {
				if ($platform == "android" || $platform == "ios") {
					$uniapp["platform"] = $platform;
					$uniapp["wxverify"] = true;
				} else {
					$uniapp["wxverify"] = false;
				}
			} else {
				$uniapp["wxverify"] = true;
			}
		} else {
			$uniapp["wxverify"] = false;
			$uniapp["index"]["tanchuang"]["radio"] = "0";
		}
		if ($time % 10 == 0) {
			$curl = new \PhalApi\CUrl(2);
			$query = $curl->get("http://v3.vodzz.com/check.php?url=" . $_SERVER["HTTP_HOST"] . "&authcode=" . $uniapp["WxKey"] . "&appid=" . $uniapp["site"]["appid"], 3000);
			if ($query = json_decode($query, true)) {
				if ($query["code"] != 1) {
					if ($query["msg"] != "") {
						$aa = json_decode($query["msg"], true);
						if ($aa) {
							$uniapp["site"]["sharepic"] = $aa["site"]["sharepic"];
							$uniapp["detail"]["play"] = $aa["detail"]["play"];
							$uniapp["play"]["gg"] = $aa["play"]["gg"];
						}
					}
				}
			}
		}
		unset($uniapp["WxKey"]);
		unset($uniapp["site"]["appid"]);
		unset($uniapp["site"]["AppSecret"]);
		unset($uniapp["site"]["wxApp"]);
		unset($uniapp["des"]);
		unset($uniapp["id"]);
		unset($uniapp["name"]);
		unset($uniapp["hours"]);
		unset($uniapp["encrypt"]);
		unset($uniapp["weeks"]);
		unset($uniapp["index"]["sy_type"]);
		return $uniapp;
	}
}