<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRules;

class SellerController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['required', PasswordRules::min(8)],
        ]);

        // TODO: AccessCode system
        $accessCode = rand(10000,99999);

        $seller = Seller::create([
            'access_code' => $accessCode,
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json(['info' => ['seller' => 'Seller created successfully']], 201);
    }

    public function getAll(): JsonResponse
    {
        $seller = Seller::all();

        if ($seller->isEmpty()) {
            return response()->json(['info' => ['seller' => 'There is no seller created']], 404);
        }

        return response()->json(['info' => ['seller' => $seller->toJson()]], 302);
    }

    public function find(string $sellerUUID): JsonResponse
    {
        $seller = Seller::find($sellerUUID);

        if (!$seller) {
            return response()->json(['info' => ['seller' => 'seller not found']], 404);
        }

        return response()->json(['info' => ['seller' => $seller->toJson()]], 303);
    }

    public function delete(string $sellerUUID): JsonResponse
    {
        $seller = Seller::find($sellerUUID);

        if (!$seller) {
            return response()->json(['info' => ['seller' => 'seller not found']], 404);
        }

        $seller->delete();

        return response()->json(['info' => ['seller' => 'seller `' . $seller->uuid . '` deleted successfully']]);
    }
}
