<?php

namespace App\Model;

class Model_Topic extends \PhalApi\Model\NotORMModel
{
	protected function getTableName($id)
	{
		return "mac_topic";
	}
	public function getTopicId($topid)
	{
		$data = $this->getORM()->select("topic_id,topic_name,topic_pic,topic_time,topic_rel_vod,topic_blurb,topic_tag")->where("topic_id='{$topid}' ")->fetchOne();
		return $data;
	}
	public function setTopicId($top)
	{
		$cache = \PhalApi\DI()->cache;
		$top = json_decode($top, true);
		$cache->delete("getTopicId_" . $top["topic_id"]);
		return $this->getORM()->where("topic_id", $top["topic_id"])->update($top);
	}
	public function getTopicAll($page, $perpage, $tags)
	{
		if ($tags == "全部" || $tags == "推荐" || $tags == undefined) {
			$sort = "topic_hits DESC";
			$tags = "%";
		} else {
			$tags = "%" . $tags . "%";
			$sort = "topic_hits DESC";
		}
		return $this->getORM()->select("topic_id,topic_name,topic_pic,topic_time,topic_tag,topic_blurb,topic_rel_vod")->where("topic_tag LIKE ?", $tags)->where("topic_status", 1)->order($sort)->order("topic_time DESC")->limit(($page - 1) * $perpage, $perpage)->fetchAll();
	}
}