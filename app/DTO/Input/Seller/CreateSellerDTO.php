<?php

namespace App\DTO\Input\Seller;

/**
 * Represents a Data Transfer Object to create a Seller
 */
class CreateSellerDTO
{
    public string $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }
}
