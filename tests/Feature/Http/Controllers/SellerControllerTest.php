<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Seller;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SellerControllerTest extends TestCase
{
    /**
     * @group SellerTests
     */
    public function testIfIsPossibleToCreateASeller(): void
    {
        $payload = [
            'password' => '12345678',
        ];

        $request = $this->post('/api/sellers', $payload);

        $request->assertCreated();
        $request->assertExactJson(['info' => ['seller' => 'Seller created successfully']]);
    }

    /**
     * @group SellerTests
     */
    public function testIfGetAllSellersMethodReturnsExpected(): void
    {
        $response = $this->get('/api/sellers');

        $sellers = Seller::all();

        if ($sellers->isEmpty()) {
            $response->assertNotFound();
            $response->assertExactJson(['info' => ['seller' => 'There is no seller created']]);
        } else {
            $response->assertFound();
            $response->assertExactJson(['info' => ['seller' => $sellers->toJson()]]);
        }
    }

    /**
     * @group SellerTests
     */
    public function testIfIsPossibleToFindASpecificSeller(): void
    {
        $SellerUUID = '123';

        $response = $this->get("/api/sellers/" . $SellerUUID);
        $seller = Seller::find($SellerUUID);

        if (!$seller) {
            $response->assertNotFound();
            $response->assertExactJson(['info' => ['seller' => 'seller not found']]);
        } else {
            $response->assertFound();
            $response->assertExactJson(['info' => ['seller' => json_decode($seller->toJson(), true)]]);
        }
    }

    /**
     * @group SellerTests
     */
    public function testIfIsPossibleToDeleteASpecificSeller(): void
    {
        $seller = Seller::factory()->create();

        $response = $this->delete('/api/sellers/' . $seller->uuid);

        $response->assertOk();
        $response->assertExactJson(['info' => ['seller' => 'seller `' . $seller->uuid . '` deleted successfully']]);
    }
}
