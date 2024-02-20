<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function testIfUserCanBeCreated(): void
    {
        $payload = [
            'name' => 'test',
            'email' => 'test@email.com',
            'document' => '45842715856',
        ];

        $response = $this->post('/api/users', $payload);

        $response->assertStatus(201);
        $response->assertSimilarJson(['info' => ['users' => 'User created successfully']]);

        $createdUser = User::where('email', $payload['email'])->first();

        $this->assertNotNull($createdUser);

        $createdUser->delete();
    }

    public function testIfUserCantBeCreatedWithInvalidDocument(): void
    {
        $payload = [
            'name' => 'test',
            'email' => 'test@email.com',
            'document' => '122.212.1221',
        ];

        $response = $this->post('/api/users', $payload);

        $response->assertStatus(400);
        $response->assertSimilarJson(['errors' => ['document' => 'Invalid document']]);
    }

    public function testIfGetAllUsersMethodReturnsExpected(): void
    {
        $response = $this->get('/api/users');

        $users = User::all();

        if ($users->isEmpty()) {
            $response->assertNotFound();
            $response->assertExactJson(['info' => ['users' => 'There is no users created']]);
        } else {
            $response->assertFound();
            $response->assertExactJson(['info' => ['users' => json_decode($users->toJson(), true)]]);
        }
    }

    public function testIfIsPossibleToFindASpecificUser(): void
    {
        $userUUID = '123';

        $response = $this->get("/api/users/" . $userUUID);
        $user = User::find($userUUID);

        if (!$user) {
            $response->assertNotFound();
            $response->assertExactJson(['info' => ['user' => 'User not found']]);
        } else {
            $response->assertFound();
            $response->assertExactJson(['info' => ['user' => json_decode($user->toJson(), true)]]);
        }
    }

    public function testIfIsPossibleToDeleteASpecificUser(): void
    {
        $user = User::factory()->create();

        $response = $this->delete('/api/users/' . $user->uuid);

        $response->assertOk();
        $response->assertExactJson(['info' => ['users' => 'user `'.$user->uuid.'` deleted succesfully']]);
    }
}
