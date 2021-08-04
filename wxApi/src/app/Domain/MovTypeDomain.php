<?php

namespace App\Domain;

class MovTypeDomain
{
	public function getTypeList()
	{
		$rs = array("items" => array(), "total" => 0);
		$model = new \App\Model\OnlineTypeModel();
		$items = $model->getMovType();
		return $items;
	}
	public function getTypeid($typeid)
	{
		$model = new \App\Model\OnlineTypeModel();
		$items = $model->getMovTypeid($typeid);
		return $items;
	}
}