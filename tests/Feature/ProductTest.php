<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = Category::factory()->create();
    }

    public function test_get_products_list()
    {
        Product::factory(5)->create(['categoryid' => $this->category->id]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
    }

    public function test_admin_can_create_product()
    {
        $admin = User::factory()->admin()->create();
        $token = $admin->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/products', [
                'categoryid' => $this->category->id,
                'name' => 'New Product',
                'price' => 1000000,
                'saleprice' => 900000,
                'imageUrl' => 'product.jpg',
            ]);

        $response->assertStatus(201);
    }

    public function test_customer_cannot_create_product()
    {
        $customer = User::factory()->create(['role' => 'Customer']);
        $token = $customer->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/products', [
                'categoryid' => $this->category->id,
                'name' => 'New Product',
                'price' => 1000000,
            ]);

        $response->assertStatus(403);
    }
}

