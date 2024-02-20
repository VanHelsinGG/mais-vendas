<?php

namespace App\DTO\User;

class CreateUserDTO
{
    public $name;
    public $document;
    public $email;

    public function __construct($name, $document, $email)
    {
        $this->name = $name;
        $this->document = $document;
        $this->email = $email;
    }
}