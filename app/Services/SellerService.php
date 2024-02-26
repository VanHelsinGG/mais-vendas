<?php

namespace App\Services;

use App\DTO\Input\Seller\CreateSellerDTO;
use App\Models\Seller;
use Dflydev\DotAccessData\Exception\DataException;

class SellerService
{
    public function createSeller(CreateSellerDTO $DTO): Seller
    {
        $seller = Seller::create([
            'access_code' => $this->generateAccessCode('common'),
            'password' => $DTO->password,
        ]);

        return $seller;
    }

    private function generateAccessCode($accessLevel): string
    {
        $prefix = match ($accessLevel) {
            'common' => '02',
            'admin'  => '03',
            'master' => '04',
            default  => throw new DataException('Access Level got an invalid value'),
        };

        do {
            $accessCode = $prefix . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        } while (Seller::where('access_code', $accessCode)->exists());

        return $accessCode;
    }
}
