<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_displays_products_index_page()
    {
        $product = Product::factory()->create();

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    public function test_it_displays_products_filtered_by_search()
    {
        Product::factory()->create(['name' => 'Laptop']);
        Product::factory()->create(['name' => 'Phone']);

        $response = $this->get(route('products.index', ['search' => 'Laptop']));
        $response->assertStatus(200);
        $response->assertSee('Laptop');
        $response->assertDontSee('Phone');
    }

    public function test_it_displays_product_create_form()
    {
        $response = $this->get(route('products.create'));

        $response->assertStatus(200);
        $response->assertSee('Create'); // assuming "Create" is in the form title
    }

    public function test_it_stores_a_new_product()
    {
        $category = Category::factory()->create();

        $response = $this->post(route('products.store'), [
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 99.99,
            'quantity' => 10,
            'category_id' => $category->id,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }

    public function test_it_displays_product_edit_form()
    {
        $product = Product::factory()->create();

        $response = $this->get(route('products.edit', $product->id));
        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    public function test_it_updates_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->put(route('products.update', $product->id), [
            'name' => 'Updated Name',
            'description' => $product->description,
            'price' => $product->price,
            'quantity' => $product->quantity,
            'category_id' => $product->category_id,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Name']);
    }

    public function test_it_deletes_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->delete(route('products.destroy', $product->id));

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

}
