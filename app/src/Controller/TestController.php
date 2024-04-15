<?php

namespace App\Controller;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
	#[Route('/test-503', name: 'test-503')]
	public function test503(): Response
	{
		throw new Exception('Test 503');
	}
}
