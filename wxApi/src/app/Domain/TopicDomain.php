<?php

namespace App\Domain;

class TopicDomain
{
	public function getTopicId($topid)
	{
		$cache = \PhalApi\DI()->cache;
		$data = $cache->get("getTopicId_" . $topid);
		if (empty($data)) {
			$model = new \App\Model\Model_Topic();
			$items = $model->getTopicId($topid);
			$modelVod = new \App\Model\OnlineModel();
			$list = $modelVod->getTopicVod($items["topic_rel_vod"]);
			$items["list"] = $list;
			$cache->set("getTopicId_" . $topid, $items, 300);
			return $items;
		} else {
			return $data;
		}
	}
	public function setTopicId($top)
	{
		$model = new \App\Model\Model_Topic();
		$items = $model->setTopicId($top);
		return $items;
	}
	public function getTopicAll($page, $limit, $tags)
	{
		$cache = \PhalApi\DI()->cache;
		$data = $cache->get("getTopicAll_" . $tags . $page);
		if (empty($data)) {
			$model = new \App\Model\Model_Topic();
			$items = $model->getTopicAll($page, $limit, $tags);
			$cache->set("getTopicAll_" . $tags . $page, $items, 60);
			return $items;
		} else {
			return $data;
		}
	}
}