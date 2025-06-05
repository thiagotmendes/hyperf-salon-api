<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Collaborators;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;

class CollaboratorController
{
    public function __construct(
        protected HttpResponse $response
    ) {}

    public function index(RequestInterface $request): ResponseInterface
    {
        $salonId = $request->input('salon_id'); // <- pega o parâmetro da query string

        $query = Collaborators::query();

        if (!empty($salonId)) {
            $query->where('salon_id', $salonId);
        }

        $collaborators = $query->get();

        return $this->response->json([
            'data' => $collaborators,
        ]);
    }

    public function store(RequestInterface $request): ResponseInterface
    {
        $data = $request->all();

        $collaborator = Collaborators::create($data);

        return $this->response->json(['data' => $collaborator]);
    }

    public function update(int $id, RequestInterface $request, HttpResponse $response)
    {
        $data = $request->all();
        $collaborator = Collaborators::find($id);

        if (empty($collaborator)) {
            return $response->json([
                'message' => 'Collaborator not found!',
            ])->withStatus(404);
        }

        $collaborator->update($data);

        return $response->json([
            'data' => $collaborator
        ]);
    }

    public function destroy(int $id, HttpResponse $response): ResponseInterface
    {
        $collaborator = Collaborators::find($id);
        
        if (empty($collaborator)) {
            return $response->json([
                'message' => 'Collaborator not found!',
            ]);
        }

        $collaborator->delete();

        return $response->json([
            'message' => 'Collaborator deleted successfully!',
        ]);
    }
}
