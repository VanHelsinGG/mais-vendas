<?php

namespace App\Services;

use App\Models\Client;
use App\DTO\Input\Client\CreateClientDTO;
use Carbon\Carbon;
use Dflydev\DotAccessData\Exception\DataException;

/**
 * Service class responsible for handling operations related to clients.
 * @method validateDocument
 * @method generateDocument
 * @method createClient
 */
class ClientService
{
    private const DOCUMENT_LENGTH = 11;

    private function validateDocument(string $document): bool
    {
        $document = preg_replace('/[^0-9]/', '', $document);

        if (strlen($document) !== 11) {
            return false;
        }

        if (preg_match('/^(\d)\1+$/', $document)) {
            return false;
        }

        for ($i = 9; $i < self::DOCUMENT_LENGTH; $i++) {
            $sum = 0;
            for ($j = 0; $j < $i; $j++) {
                $sum += $document[$j] * (($i + 1) - $j);
            }
            $module = $sum % self::DOCUMENT_LENGTH;
            $digit = ($module < 2) ? 0 : self::DOCUMENT_LENGTH - $module;
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

    /**
     * Create a Client
     *
     * @param CreateClientDTO $clientDTO Client Data transfer object
     * @return Client A Client instance
     * @throws DataException If document is invalid
     */
    public function createClient(CreateClientDTO $clientDTO): Client
    {
        if (!$this->validateDocument($clientDTO->document)) {
            throw new DataException('Invalid document');
        }

        $name = ucwords($clientDTO->name);
        $email = strtolower($clientDTO->email);

        $client = Client::create([
            'name' => $name,
            'email' => $email,
            'document' => $clientDTO->document,
        ]);

        return $client;
    }

    public function changeAccreditedStatus(Client $client): bool
    {
        $timestamp = Carbon::now();

        $status = !$client->accredited;

        $client->update([
            'accredited_at' => $timestamp,
            'accredited' => $status,
        ]);

        return $status;
    }
}
