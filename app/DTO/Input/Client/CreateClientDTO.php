<?php

namespace App\DTO\Input\Client;

/**
 * Represents a Data Transfer Object to create a Client
 */
class CreateClientDTO
{
    public string $name;
    public string $document;
    public string $email;

    public function __construct(string $name, string $document, string $email)
    {
        $this->name = $name;
        $this->document = $document;
        $this->email = $email;
    }
}
