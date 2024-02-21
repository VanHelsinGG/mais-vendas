<?php

namespace App\Http\Controllers;

use App\DTO\Input\Client\CreateClientDTO;
use App\Models\Client;
use App\Services\ClientService;
use Dflydev\DotAccessData\Exception\DataException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @method create
 * @method find
 * @method getAll
 * @method delete
 * @method changeAccreditedStatus
 */
class ClientController extends Controller
{
    /**
     * The dependency injector for ClientService
     *
     * @var ClientService
     */
    protected $service;

    public function __construct(ClientService $service)
    {
        $this->service = $service;
    }

    /**
     * Create Client Method
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|min:3|string',
            'email' => 'required|email',
            'document' => 'required|string|min:11',
        ]);

        $DTO = new CreateClientDTO(
            $request->input('name'),
            $request->input('document'),
            $request->input('email')
        );

        try {
            $this->service->createClient($DTO);
        } catch (DataException $e) {
            return response()->json(['errors' => ['document' => $e->getMessage()]], 400);
        }

        return response()->json(['info' => ['client' => 'Client created successfully']], 201);
    }

    /**
     * Get all clients
     *
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        $clients = Client::all();

        if ($clients->isEmpty()) {
            return response()->json(['info' => ['client' => 'There is no client created']], 404);
        }

        return response()->json(['info' => ['clients' => $clients->toJson()]], 303);
    }

    /**
     * Find a client by UUID
     *
     * @param string $clientUUID
     * @return JsonResponse
     */
    public function find(string $clientUUID): JsonResponse
    {
        $client = Client::find($clientUUID);

        if (!$client) {
            return response()->json(['info' => ['client' => 'Client not found']], 404);
        }

        return response()->json(['info' => ['client' => $client->toJson()]], 303);
    }

    /**
     * Delete a client by UUID
     *
     * @param string $clientUUID
     * @return JsonResponse
     */
    public function delete(string $clientUUID): JsonResponse
    {
        $client = Client::find($clientUUID);

        if (!$client) {
            return response()->json(['info' => ['client' => 'Client not found']], 404);
        }

        $client->delete();

        return response()->json(['info' => ['client' => 'Client `' . $client->uuid . '` deleted successfully']]);
    }

    /**
     * Change accredited status of a client by UUID
     *
     * @param string $clientUUID
     * @return JsonResponse
     */
    public function changeAccreditedStatus(string $clientUUID): JsonResponse
    {
        $client = Client::find($clientUUID);

        if (!$client) {
            return response()->json(['info' => ['client' => 'Client not found']], 404);
        }

        $response = $this->service->changeAccreditedStatus($client);

        return response()->json(['info' => ['client' => 'Client `' . $client->uuid . '` accredited status changed to `' . $response . '`']]);
    }
}
