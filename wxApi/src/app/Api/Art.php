<?php

namespace App\Api;

class Art extends \PhalApi\Api
{
	public function getRules()
	{
		return array("getArtId" => array("artid" => array("name" => "artid", "type" => "string", "require" => true, "default" => "", "desc" => "文章ID"), "mark" => array("name" => "mark", "type" => "string", "require" => true, "default" => "", "desc" => "标识，参数必须")));
	}
	public function getArtId()
	{
		$domain = new \App\Domain\ArtDomain();
		$list = $domain->getArtId($this->artid);
		return $list;
	}
}