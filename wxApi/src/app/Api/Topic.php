<?php

namespace App\Api;

class Topic extends \PhalApi\Api
{
	public function getRules()
	{
		return array("getTopicId" => array("topid" => array("name" => "topid", "type" => "string", "require" => true, "default" => "", "desc" => "文章ID"), "mark" => array("name" => "mark", "type" => "string", "require" => true, "default" => "", "desc" => "标识，参数必须")), "getTopicAll" => array("page" => array("name" => "page", "type" => "int", "min" => 1, "default" => 1, "desc" => "第几页"), "tags" => array("name" => "tags", "type" => "string", "default" => "", "desc" => "标签"), "limit" => array("name" => "limit", "type" => "int", "min" => 1, "max" => 20, "default" => 10, "desc" => "分页数量")), "setTopicId" => array("top" => array("name" => "top", "type" => "string", "require" => true, "default" => "", "desc" => "文章")));
	}
	public function getTopicId()
	{
		$domain = new \App\Domain\TopicDomain();
		$list = $domain->getTopicId($this->topid);
		return $list;
	}
	public function setTopicId()
	{
		$domain = new \App\Domain\TopicDomain();
		$list = $domain->setTopicId($this->top);
		return $list;
	}
	public function getTopicAll()
	{
		$domain = new \App\Domain\TopicDomain();
		$list = $domain->getTopicAll($this->page, $this->limit, $this->tags);
		return $list;
	}
}