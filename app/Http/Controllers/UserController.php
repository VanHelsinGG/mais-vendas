<?php

namespace App\Http\Controllers;

use App\DTO\User\CreateUserDTO;
use App\Models\User;
use App\Services\UserService;
use Dflydev\DotAccessData\Exception\DataException;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|string',
            'email' => 'required|email',
            'document' => 'required|string|min:11',
        ]);

        $DTO = new CreateUserDTO($request->input('name'), $request->input('document'), $request->input('email'));

        try {
            $this->service->createUser($DTO);
        } catch (DataException $e) {
            return response()->json(['errors' => ['document' => $e->getMessage()]],400);
        }

        return response()->json(['info' => ['users' => 'User created successfully']], 201);
    }

    public function getAll()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return response()->json(['info' => ['users' => 'There is no users created']], 404);
        }

        return response()->json(['info' => ['users' => $users->toJson()]], 303);
    }

    public function find(string $userUUID)
    {
        $user = User::find($userUUID);

        if (!$user) {
            return response()->json(['info' => ['user' => 'User not found']], 404);
        }

        return response()->json(['info' => ['user' => $user->toJson()]], 303);
    }

    public function delete(string $userUUID)
    {
        $user = User::find($userUUID);

        if (!$user) {
            return response()->json(['info' => ['user' => 'User not found']], 404);
        }

        $user->delete();

        return response()->json(['info' => ['users' => 'user `' . $user->uuid . '` deleted succesfully']]);
    }
}
