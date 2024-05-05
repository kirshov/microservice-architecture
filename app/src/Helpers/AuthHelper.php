<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\Request;

class AuthHelper
{
	public static function getUserIdFromRequest(Request $request): ?int
	{
		$headers = $request->headers;
		return (int)$headers->get('X-UserId') ?: null;
	}
}