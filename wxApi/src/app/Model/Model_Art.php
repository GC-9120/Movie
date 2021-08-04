<?php

namespace App\Model;

class Model_Art extends \PhalApi\Model\NotORMModel
{
	protected function getTableName($id)
	{
		return "mac_art";
	}
	public function getArtId($artid)
	{
		$data = $this->getORM()->select("art_id,art_name,art_pic,art_content")->where("art_id='{$artid}' ")->fetchOne();
		return $data;
	}
}