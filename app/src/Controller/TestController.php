<?php

namespace App\Controller;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
	#[Route('/test-503', name: 'test-503')]
	public function test503(): Response
	{
		throw new Exception('Test 503');
	}

	#[Route('/test-headers', name: 'test-headers')]
	public function testHeaders(Request $request): Response
	{
		var_dump($request->headers);

		return new Response();
	}
}
