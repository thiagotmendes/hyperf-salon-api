<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Slots;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;

class SlotController
{
    public function __construct(
        protected HttpResponse $response
    ) {}

    public function index(): ResponseInterface
    {
        $slots = Slots::all();

        if (! $slots) {
            return $this->response->json([
                'message' => 'Slots not found!',
            ]);
        }

        return $this->response->json([
            'data' => $slots,
        ]);
    }

    public function store( RequestInterface $request, HttpResponse $response ): ResponseInterface
    {
        $data = $request->all();

        $slot = Slots::create($data);

        return $this->response->json([
            'data' => $slot,
        ]);
    }

    public function show(int $id): ResponseInterface
    {
        $slot = Slots::find($id);
        if (! $slot) {
            return $this->response->json([
                'message' => 'Slot not found!',
            ]);
        }

        return $this->response->json([
            'data' => $slot,
        ]);
    }

    public function update(int $id, RequestInterface $request, HttpResponse $response): ResponseInterface
    {
        $slot = Slots::find($id);

        if (! $slot) {
            return $this->response->json([
                'message' => 'Slot not found!',
            ]);
        }

        $data = $request->all();

        $slot->update($data);

        return $this->response->json([
            'message' => 'Slot updated successfully!',
            'data' => $slot,
        ]);
    }

    public function destroy(int $id): ResponseInterface
    {
        $slot = Slots::find($id);

        if (! $slot) {
            return $this->response->json([
                'message' => 'Slot not found!',
            ]);
        }

        $slot->delete();

        return $this->response->json([
            'message' => 'Slot deleted successfully!',
        ]);
    }
}
