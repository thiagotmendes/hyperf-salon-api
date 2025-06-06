<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\DbConnection\Db;
use App\Model\Appointments;
use App\Model\Slots;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use \Psr\Http\Message\ResponseInterface;

class  AppointmentController
{
    public function __construct(
        protected HttpResponse $response
    ) {}
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

        // Pega os dados do request
        $data = $request->all();

        // Atualiza os campos
        $appointment->update($data);

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

        $appointment->delete();

        return $this->response->json([
            'code' => 200,
            'msg' => 'Appointment deleted successfully!',
        ])->withStatus(200);
    }

}
