<?php

namespace App\Services;

use App\Models\User;
use App\DTO\User\CreateUserDTO;
use Dflydev\DotAccessData\Exception\DataException;

class UserService
{
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

    public function generateDocument(): string
    {
        $document = '';

        for ($i = 0; $i < 9; $i++) {
            $document .= rand(0, 9);
        }

        for ($i = 0; $i < 2; $i++) {
            $sum = 0;
            for ($i = 0; $i < strlen($document); $i++) {
                $sum += $document[$i] * (10 - $i);
            }

            $module = $sum % 11;

            $document .= ($module < 2) ? 0 : 11 - $module;
        }
        return $document;
    }

    public function createUser(CreateUserDTO $userDTO): User
    {
        if (!$this->validateDocument($userDTO->document)) {
            throw new DataException('Invalid document');
        }

        $name = ucwords($userDTO->name);
        $email = strtolower($userDTO->email);
    
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'document' => $userDTO->document,
        ]);

        return $user;
    }
}
