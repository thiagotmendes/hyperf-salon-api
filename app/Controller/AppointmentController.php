<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\DbConnection\Db;
use App\Model\Appointments;
use App\Model\Slots;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\Redis\RedisFactory;
use \Psr\Http\Message\ResponseInterface;

class  AppointmentController
{
    public function __construct(
        protected HttpResponse $response,
        protected RedisFactory $redisFactory
    )
    {
    }

    public function index(): ResponseInterface
    {
        $appointments = Appointments::all();

        if (empty($appointments)) {
            return $this->response->json([
                'code' => 400,
                'msg' => 'No data found'
            ])->withStatus(400);
        }

        return $this->response->json([
            'code' => 200,
            'msg' => 'Success',
            'data' => $appointments
        ])->withStatus(200);
    }

    public function show(int $id): ResponseInterface
    {
        $appointment = Appointments::find($id);

        if (empty($appointment)) {
            return $this->response->json([
                'code' => 400,
                'msg' => 'No data found'
            ])->withStatus(400);
        }

        return $this->response->json([
            'code' => 200,
            'msg' => 'Success',
            'data' => $appointment
        ])->withStatus(200);
    }

    public function store(RequestInterface $request, HttpResponse $response): ResponseInterface
    {
        $data = $request->all();

        // Iniciar transação
        Db::beginTransaction();

        try {
            // Verificar se o slot está disponível
            $slot = Slots::find($data['slot_id']); // Você teria que passar o slot_id no appointment!

            if (!$slot) {
                throw new \Exception('Slot not found');
            }

            if ($slot->status !== 'available') {
                throw new \Exception('Slot already booked');
            }

            // Criar o Appointment
            $appointment = Appointments::create($data);

            // Atualizar o Slot para 'booked'
            $slot->status = 'booked';
            $slot->save();

            $redis = $this->redisFactory->get('default');

            // Limpa cache global, se você estiver usando o slots:all
            $redis->del('slots:all');

            // Limpa cache específico, se usar cache por colaborador/data
            $cacheKey = "slots:collaborator:{$slot->collaborator_id}:date:{$slot->date}";
            $redis->del($cacheKey);

            // Commit
            Db::commit();

            return $this->response->json([
                'code' => 200,
                'msg' => 'Success',
                'data' => $appointment
            ])->withStatus(200);
        } catch (\Throwable $e) {
            // Rollback se der erro
            Db::rollBack();

            return $this->response->json([
                'code' => 500,
                'msg' => 'Error: ' . $e->getMessage(),
            ])->withStatus(500);
        }
    }

    public function update(int $id, RequestInterface $request, HttpResponse $response): ResponseInterface
    {
        $appointment = Appointments::find($id);

        if (empty($appointment)) {
            return $this->response->json([
                'code' => 400,
                'msg' => 'No data found'
            ])->withStatus(400);
        }

        $data = $request->all();
        $oldStatus = $appointment->status;
        $appointment->update($data);

        // --- Atualização do slot se necessário ---
        if (
            isset($data['status']) &&
            $data['status'] === 'canceled' &&
            $oldStatus !== 'canceled' // Evita rodar em updates inúteis
        ) {
            $slot = Slots::find($appointment->slot_id);
            if ($slot && $slot->status === 'booked') {
                $slot->status = 'available';
                $slot->save();

                // Limpa cache do slot
                $redis = $this->redisFactory->get('default');
                $redis->del('slots:all');
                $redis->del("slots:collaborator:{$slot->collaborator_id}:date:{$slot->date}");
            }
        }
        // --- FIM ---

        return $this->response->json([
            'code' => 200,
            'msg' => 'Appointment updated successfully!',
            'data' => $appointment
        ])->withStatus(200);
    }

    public function delete(int $id): ResponseInterface
    {
        $appointment = Appointments::find($id);

        if (empty($appointment)) {
            return $this->response->json([
                'code' => 400,
                'msg' => 'No data found'
            ])->withStatus(400);
        }

        // --- Liberação do slot se estiver booked ---
        $slot = Slots::find($appointment->slot_id ?? null);
        if ($slot && $slot->status === 'booked') {
            $slot->status = 'available';
            $slot->save();

            // Limpa o cache dos slots
            $redis = $this->redisFactory->get('default');
            $redis->del('slots:all');
            $redis->del("slots:collaborator:{$slot->collaborator_id}:date:{$slot->date}");
        }
        // ------------------------------------------

        $appointment->delete();

        return $this->response->json([
            'code' => 200,
            'msg' => 'Appointment deleted successfully!',
        ])->withStatus(200);
    }

}
