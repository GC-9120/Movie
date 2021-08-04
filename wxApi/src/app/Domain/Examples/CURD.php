<?php

namespace App\Domain\Examples;

class CURD
{
	public function insert($newData)
	{
		$newData["post_date"] = date("Y-m-d H:i:s", $_SERVER["REQUEST_TIME"]);
		$model = new \App\Model\Examples\CURD();
		return $model->insert($newData);
	}
	public function update($id, $newData)
	{
		$model = new \App\Model\Examples\CURD();
		return $model->update($id, $newData);
	}
	public function get($id)
	{
		$model = new \App\Model\Examples\CURD();
		return $model->get($id);
	}
	public function delete($id)
	{
		$model = new \App\Model\Examples\CURD();
		return $model->delete($id);
	}
	public function getList($state, $page, $perpage)
	{
		$rs = array("items" => array(), "total" => 0);
		$model = new \App\Model\Examples\CURD();
		$items = $model->getListItems($state, $page, $perpage);
		$total = $model->getListTotal($state);
		$rs["items"] = $items;
		$rs["total"] = $total;
		return $rs;
	}
}