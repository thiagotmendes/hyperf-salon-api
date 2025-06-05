<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Salons;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;

#[Controller(prefix: "salons")]
class SalonController
{
    public function __construct(
        protected HttpResponse $response
    ) {}

    #[GetMapping(path: "")]
    public function index(): ResponseInterface
    {
        $salons = Salons::all();

        return $this->response->json([
            'data' => $salons,
        ]);
    }

    #[PostMapping(path: "")]
    public function store(RequestInterface $request, HttpResponse $response): ResponseInterface
    {
        $data = $request->all(); // <- Aqui o certo!

        $salon = Salons::create($data);

        return $response->json([
            'message' => 'Salon created successfully!',
            'data' => $salon,
        ]);
    }
}