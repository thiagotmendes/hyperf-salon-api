<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Salons;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PutMapping;
use Hyperf\HttpServer\Annotation\PatchMapping;
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
        $data = $request->all();

        $salon = Salons::create($data);

        return $response->json([
            'message' => 'Salon created successfully!',
            'data' => $salon,
        ]);
    }

    #[PutMapping(path: "/{id}")]
    #[PatchMapping(path: "/{id}")]
    public function update(int $id, RequestInterface $request, HttpResponse $response)
    {
        $salon = Salons::find($id);

        if (! $salon) {
            return $response->json([
                'message' => 'Salon not found!',
            ])->withStatus(404);
        }

        $data = $request->all();

        $salon->update($data);

        return $response->json([
            'message' => 'Salon updated successfully!',
            'data' => $salon,
        ]);
    }

    public function show(int $id, HttpResponse $response): ResponseInterface
    {
        $salon = Salons::find($id);

        if (! $salon) {
            return $response->json([
                'message' => 'Salon not found!',
            ]);
        }

        return $this->response->json([
            'data' => $salon,
        ]);
    }

    public function destroy(int $id, HttpResponse $response)
    {
        $salon = Salons::find($id);

        if (! $salon) {
            return $response->json([
                'message' => 'Salon not found!',
            ])->withStatus(404);
        }

        $salon->delete();

        return $response->json([
            'message' => 'Salon deleted successfully!',
        ]);
    }
}