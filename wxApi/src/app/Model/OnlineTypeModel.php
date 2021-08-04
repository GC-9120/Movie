<?php

namespace App\Model;

class OnlineTypeModel extends \PhalApi\Model\NotORMModel
{
	protected function getTableName($id)
	{
		return "mac_type";
	}
	public function getMovType()
	{
		return $this->getORM()->select("type_name,type_id,type_extend")->order("type_id ASC")->where("type_id<=4")->fetchAll();
	}
	public function getMovTypeid($typeid)
	{
		return $this->getORM()->select("type_id,type_name")->where("type_pid='{$typeid}'")->fetchAll();
	}
}