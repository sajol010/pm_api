<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    private int $totalNumber = 20;
    /**
     * product
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
            'category_ids'=>[1,2],
            'images'=>['test1.jpg','test2.png'],
        ]);
        $response->assertCreated();
        $response->assertJson([
           'success'=>true
        ]);
    }

    /**
     * product
     *
     * @return void
     */
    public function test_product_update(): void
    {
        $response = $this->patchJson(route('products.update', 1), [
            'name'=>'Test product 1 after edit',
            'slug'=>'test-product-1',
            'price'=>10.00,
            'quantity'=>2,
        ]);
        $data = $response->json();

        $response->assertCreated();
        $response->assertJson([
            'success'=>true
        ]);
        $this->assertEquals(1, $data['data']['id']);
    }

    public function test_product_list(){
        Product::factory($this->totalNumber)->create();
        $response = $this->getJson(route('products.index'));
        $data = $response->json();

        $response->assertSuccessful();
        $response->assertJson([
            'success'=>true
        ]);
        $this->assertEquals(($this->totalNumber + 1), count($data['data']));
    }

    public function test_product_delete(){
        $response = $this->deleteJson(route('products.destroy', 2));
        $response->assertSuccessful();
        $response->assertJson([
            'success'=>true
        ]);
        $this->assertDatabaseMissing('products', ['id'=>2]);
    }


}
