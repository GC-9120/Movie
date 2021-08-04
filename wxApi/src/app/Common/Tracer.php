<?php

namespace App\Common;

class Tracer extends \PhalApi\Helper\Tracer
{
	public function sql($statement)
	{
		parent::sql($statement);
		\PhalApi\DI()->logger->log("SQL", $statement, array("s" => \PhalApi\DI()->request->getService()));
	}
}