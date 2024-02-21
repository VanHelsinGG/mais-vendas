<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Client;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group ClientTests
     */
    public function testIfClientCanBeCreated(): void
    {
        $payload = [
            'name' => 'test',
            'email' => 'test@email.com',
            'document' => '45842715856',
        ];

        $response = $this->post('/api/clients', $payload);

        $response->assertStatus(201);
        $response->assertSimilarJson(['info' => ['client' => 'Client created successfully']]);

        $createdClient = Client::where('email', $payload['email'])->first();

        $this->assertNotNull($createdClient);

        $createdClient->delete();
    }
    
    /**
     * @group ClientTests
     */
    public function testIfClientCantBeCreatedWithInvalidDocument(): void
    {
        $payload = [
            'name' => 'test',
            'email' => 'test@email.com',
            'document' => '122.212.1221',
        ];

        $response = $this->post('/api/clients', $payload);

        $response->assertStatus(400);
        $response->assertSimilarJson(['errors' => ['document' => 'Invalid document']]);
    }

    /**
     * @group ClientTests
     */
    public function testIfGetAllClientsMethodReturnsExpected(): void
    {
        $response = $this->get('/api/clients');

        $Clients = Client::all();

        if ($Clients->isEmpty()) {
            $response->assertNotFound();
            $response->assertExactJson(['info' => ['client' => 'There is no client created']]);
        } else {
            $response->assertFound();
            $response->assertExactJson(['info' => ['client' => json_decode($Clients->toJson(), true)]]);
        }
    }

    /**
     * @group ClientTests
     */
    public function testIfIsPossibleToFindASpecificClient(): void
    {
        $ClientUUID = '123';

        $response = $this->get("/api/clients/" . $ClientUUID);
        $Client = Client::find($ClientUUID);

        if (!$Client) {
            $response->assertNotFound();
            $response->assertExactJson(['info' => ['client' => 'Client not found']]);
        } else {
            $response->assertFound();
            $response->assertExactJson(['info' => ['client' => json_decode($Client->toJson(), true)]]);
        }
    }

    /**
     * @group ClientTests
     */
    public function testIfIsPossibleToDeleteASpecificClient(): void
    {
        $client = Client::factory()->create();

        $response = $this->delete('/api/clients/' . $client->uuid);

        $response->assertOk();
        $response->assertExactJson(['info' => ['client' => 'Client `' . $client->uuid . '` deleted successfully']]);
    }

    /**
     * @group ClientTests
     */
    public function testIfIsPossibleToChangeClientAccreditedStatus(): void
    {
        $client = Client::factory()->create();

        $response = $this->patch('/api/clients/' . $client->uuid . '/accredited');

        $response->assertOk();
        $response->assertExactJson(['info' => ['client' => 'Client `' . $client->uuid . '` accredited status changed to `' . (!$client->accredited) . '`']]);
    }
}
