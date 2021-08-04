<?php

namespace App\Api;

class Mov extends \PhalApi\Api
{
	public function getRules()
	{
		return array("searchVod" => array("key" => array("name" => "key", "type" => "string", "require" => true, "default" => "", "desc" => "电影搜索关键词，参数必须"), "page" => array("name" => "page", "type" => "int", "min" => 1, "default" => 1, "desc" => "第几页"), "limit" => array("name" => "limit", "type" => "int", "min" => 1, "max" => 20, "default" => 10, "desc" => "分页数量")), "getOnlineMvById" => array("vodid" => array("name" => "vodid", "type" => "int", "require" => true, "default" => "1", "desc" => "影片id，参数必须"), "mark" => array("name" => "mark", "type" => "string", "require" => true, "default" => "", "desc" => "标识，参数必须")), "getTopicVod" => array("vodid" => array("name" => "vodid", "type" => "string", "require" => true, "default" => "1", "desc" => "影片id，参数必须")), "setMovById" => array("vod" => array("name" => "vod", "type" => "string", "require" => true, "default" => "", "desc" => "影片，参数必须")), "getHomeList" => array("typeid" => array("name" => "typeid", "type" => "int", "require" => true, "default" => "", "desc" => "分类id，参数必须")), "getBanner" => array("page" => array("name" => "page", "type" => "int", "min" => 1, "default" => 1, "desc" => "第几页"), "limit" => array("name" => "limit", "type" => "int", "min" => 1, "max" => 20, "default" => 10, "desc" => "分页数量")), "getCateGroup" => array("group_id" => array("name" => "group_id", "type" => "int", "min" => 0, "max" => 10, "default" => 1, "desc" => "大类分组ID")), "getHomeLevel" => array("level" => array("name" => "level", "type" => "int", "min" => 1, "max" => 10, "default" => 1, "desc" => "推荐位索引")), "getHomeLevelAll" => array("level" => array("name" => "level", "type" => "int", "min" => 1, "max" => 30, "default" => 1, "desc" => "推荐位索引"), "page" => array("name" => "page", "type" => "int", "min" => 1, "default" => 1, "desc" => "第几页"), "limit" => array("name" => "limit", "type" => "int", "min" => 1, "max" => 20, "default" => 10, "desc" => "分页数量")), "sethits" => array("vodid" => array("name" => "vodid", "type" => "int", "require" => true, "min" => 1, "default" => 1, "desc" => "影片ID"), "hits" => array("name" => "hits", "type" => "int", "require" => true, "min" => 1, "default" => 1, "desc" => "影片热度")), "getTopicList" => array("topic_id" => array("name" => "topic_id", "type" => "int", "min" => 1, "max" => 10, "default" => 1, "desc" => "专题ID")), "getCategory" => array("type" => array("name" => "type", "type" => "int", "require" => true, "default" => 1, "desc" => "分类id，参数必须"), "classid" => array("name" => "classid", "type" => "string", "require" => true, "default" => "全部", "desc" => "类型，参数必须"), "area" => array("name" => "area", "type" => "string", "require" => true, "default" => "全部", "desc" => "地区，参数必须"), "year" => array("name" => "year", "type" => "string", "require" => true, "default" => "全部", "desc" => "年代，参数必须"), "sort" => array("name" => "sort", "type" => "string", "require" => true, "default" => "0", "desc" => "排序方式，2按更新时间、3评分高低、1用户点赞数量，参数必须"), "page" => array("name" => "page", "type" => "int", "min" => 1, "default" => 1, "desc" => "第几页"), "limit" => array("name" => "limit", "type" => "int", "min" => 1, "max" => 20, "default" => 10, "desc" => "分页数量")), "getSyLevelAll" => array("mark" => array("name" => "mark", "type" => "string", "require" => true, "default" => "", "desc" => "标识，参数必须")), "getConfig" => array("host" => array("name" => "host", "type" => "string", "require" => true, "default" => "", "desc" => "域名，参数必须"), "platform" => array("name" => "platform", "type" => "string", "default" => "", "desc" => "客户端"), "mark" => array("name" => "mark", "type" => "string", "require" => true, "default" => "", "desc" => "标识，参数必须"), "scene" => array("name" => "scene", "type" => "string", "default" => "", "desc" => "场景值")), "hotKeywords" => array(), "updateUserIcon" => array("uid" => array("name" => "uid", "type" => "int", "require" => true, "min" => 1, "default" => 1, "desc" => "用户ID"), "index" => array("name" => "index", "type" => "string", "require" => true, "default" => "0", "desc" => "用户头像id")), "getJsList" => array("page" => array("name" => "page", "type" => "int", "min" => 1, "default" => 1, "desc" => "第几页")), "getLevelId" => array("level" => array("name" => "level", "type" => "int", "min" => 1, "max" => 30, "default" => 1, "desc" => "推荐位索引"), "page" => array("name" => "page", "type" => "int", "min" => 1, "default" => 1, "desc" => "第几页")));
	}
	public function getOnlineMvById()
	{
		$domain = new \App\Domain\MovDomain();
		$list = $domain->getOnlineMovById($this->vodid);
		return $list;
	}
	public function getTopicVod()
	{
		$domain = new \App\Domain\MovDomain();
		$list = $domain->getTopicVod($this->vodid);
		return $list;
	}
	public function setMovById()
	{
		$domain = new \App\Domain\MovDomain();
		$list = $domain->setMovById($this->vod);
		return $list;
	}
	public function getJsList()
	{
		$domain = new \App\Domain\MovDomain();
		$list = $domain->getJsList($this->page);
		return $list;
	}
	public function getHomeLevelAll()
	{
		$domain = new \App\Domain\MovDomain();
		$list = $domain->getHomeLevelAll($this->level, $this->page, $this->limit);
		return $list;
	}
	public function getLevelId()
	{
		$domain = new \App\Domain\MovDomain();
		$limit = 10;
		$list = $domain->getLevelId($this->level, $this->page, $limit);
		return $list;
	}
	public function getSyLevelAll()
	{
		$domain = new \App\Domain\MovDomain();
		$list = $domain->getSyLevelAll($this->mark);
		return $list;
	}
	public function searchVod()
	{
		$rs = array();
		$domain = new \App\Domain\MovDomain();
		$list = $domain->searchVod($this->key, $this->page, $this->limit);
		$rs["items"] = $list["items"];
		$rs["page"] = $this->page;
		$rs["limit"] = $this->limit;
		return $list["items"];
	}
	public function getCategory()
	{
		$domain = new \App\Domain\MovDomain();
		$list = $domain->getCategory($this->type, $this->area, $this->year, $this->sort, $this->page, $this->limit, $this->classid);
		return $list;
	}
	public function getConfig()
	{
		$domain = new \App\Domain\MovDomain();
		$list = $domain->getConfig($this->host, $this->mark, $this->scene, $this->platform);
		return $list;
	}
	public function hotKeywords()
	{
		$domain = new \App\Domain\MovDomain();
		$list = $domain->hotKeywords();
		return $list;
	}
	public function getArtLevel()
	{
		$domain = new \App\Domain\MovDomain();
		$list = $domain->hotKeywords();
		return $list;
	}
}