<?php

namespace App\Service;

use Symfony\Component\Uid\Uuid;

class TokenGeneratorService
{
	public function generateToken(): Uuid
	{
		return Uuid::class::v4();
	}
}