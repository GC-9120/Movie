<?php

namespace App\Domain;

class ArtDomain
{
	public function getArtId($artid)
	{
		$cache = \PhalApi\DI()->cache;
		if (empty($data)) {
			$model = new \App\Model\Model_Art();
			$items = $model->getArtId($artid);
			$cache->set("getArtId_" . $artid, $items, 300);
			return $items;
		} else {
			return $data;
		}
	}
}