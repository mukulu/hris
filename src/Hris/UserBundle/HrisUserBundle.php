<?php

namespace Hris\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class HrisUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
