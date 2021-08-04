<?php

namespace App\Model\Examples;

class CURD extends \PhalApi\Model\NotORMModel
{
	protected function getTableName($id)
	{
		return "phalapi_curd";
	}
	public function getListItems($state, $page, $perpage)
	{
		return $this->getORM()->select("*")->where("state", $state)->order("post_date DESC")->limit(($page - 1) * $perpage, $perpage)->fetchAll();
	}
	public function getListTotal($state)
	{
		$total = $this->getORM()->where("state", $state)->count("id");
		return intval($total);
	}
}