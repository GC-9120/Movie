<?php

namespace App\Common\Request;

class Version
{
	public static function formatVersion($value, $rule)
	{
		if (count(explode(".", $value)) < 3) {
			throw new \PhalApi\Exception\BadRequestException("版本号格式错误");
		}
		return $value;
	}
}