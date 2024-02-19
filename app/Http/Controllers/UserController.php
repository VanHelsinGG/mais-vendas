<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function create(Request $request)
    {
        if (!$this->validateDocument($request->input('document'))) {
            return response()->json(['errors' => ['document' => 'Invalid document']], 400);
        }

        $request->validate([
            'name' => 'required|min:3|string',
            'email' => 'required|email',
        ]);

        User::create([
            'name' => $request->input('name'),
            'document' => $request->input('document'),
            'email' => $request->input('email'),
        ]);

        return response()->json(['info' => 'User created successfully'], 201);
    }

    public function getAll()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return response()->json(['info' => ['users' => 'There is no users created']],404);
        }

        return response()->json(['info' => ['users' => $users->toJson()]],303);
    }

    public function find(string $userUUID)
    {
        $user = User::find($userUUID);

        if(!$user){
            return response()->json(['info' => ['user' => 'User not found']],404);
        }

        return response()->json(['info' => ['user' => $user->toJson()]],303);
    }

    private function validateDocument(string $document)
    {
        $document = preg_replace('/[^0-9]/', '', $document);

        if (strlen($document) !== 11) {
            return false;
        }

        if (preg_match('/^(\d)\1+$/', $document)) {
            return false;
        }

        for ($i = 9; $i < 11; $i++) {
            $sum = 0;
            for ($j = 0; $j < $i; $j++) {
                $sum += $document[$j] * (($i + 1) - $j);
            }
            $module = $sum % 11;
            $digit = ($module < 2) ? 0 : 11 - $module;
            if ((int) $document[$i] !== $digit) {
                return false;
            }
        }

        return true;
    }
}
