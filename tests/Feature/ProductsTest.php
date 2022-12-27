<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_product_create(): void
    {
        $response = $this->postJson(route('products.store'), [
            'name'=>'Test product 1',
            'slug'=>'test-product-1',
            'price'=>10.00,
            'quantity'=>2,
        ]);
        $response->assertCreated();
        $response->assertJson([
           'success'=>true
        ]);
    }
}
