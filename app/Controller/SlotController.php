<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Slots;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\Redis\RedisFactory;

class SlotController
{
    public function __construct(
        protected HttpResponse $response,
        protected RedisFactory $redisFactory
    ) {}

    public function index(): ResponseInterface
    {
        $redis = $this->redisFactory->get('default');
        $cacheKey = 'slots:all';

        // Tenta obter do cache
        $cached = $redis->get($cacheKey);

        if ($cached) {
            $slots = json_decode($cached, true);
        } else {
            $slots = Slots::all();
            if (!$slots) {
                return $this->response->json([
                    'message' => 'Slots not found!',
                ]);
            }

            // Salva no cache por 60 segundos
            $redis->set($cacheKey, json_encode($slots), 60);
        }

        return $this->response->json([
            'data' => $slots,
        ]);
    }

    public function store( RequestInterface $request, HttpResponse $response ): ResponseInterface
    {
        $data = $request->all();

        $slot = Slots::create($data);

        $this->redisFactory->get('default')->del('slots:all');

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

        $this->redisFactory->get('default')->del('slots:all');

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

        $this->redisFactory->get('default')->del('slots:all');

        return $this->response->json([
            'message' => 'Slot deleted successfully!',
        ]);
    }
}
