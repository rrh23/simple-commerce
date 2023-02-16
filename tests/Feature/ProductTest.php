<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Faker\Factory as Faker;
use App\Models\Products;
use PDO;
define ("PRODUCT_URL", "api/v1/products");
define ("ACCEPT_MIME_TYPE", "application/json");
define ("DEFAULT_MESSAGE", "Data Retrieved Succesfully");

class ProductTest extends TestCase
{
    /** @test */
    public function it_can_create_a_products()
    {
        $faker = Faker::create();
        $data = [
            'product_title' => $faker->title(),
            'image_url' => $faker->url(),
            'price' => $faker->randomFloat(2, 0, 3),
            'rating' => $faker->randomFloat(1, 0, 1),
            'category' => $faker->name(),
            'is_deleted' => false
        ];

        $this->post(route('products.store'), $data)
            ->dump()
            ->assertStatus(201)
            ->assertJson(compact('data'));
    }
    private function generateProductPerPage(int $page, int $perPage, $products)
    {
        $result = [];
        $count = 0;
        for ($i = ($perPage * $page - $perPage); $i < ($perPage * $page); $i++) {
            $result[$count]["id"] = $i + 1;
            $result[$count]['product_title'] = $products[$i]['product_title'];
            $result[$count]['image_url'] = $products[$i]['image_url'];
            $result[$count]['price'] = $products[$i]['price'];
            $result[$count]['web_id'] = $products[$i]['web_id'];
            $result[$count]['rating'] = $products[$i]['rating'];
            $result[$count]['category'] = $products[$i]['category'];
            $result[$count]['is_deleted'] = $products[$i]['is_deleted'];
            $count++;
        }
        return $result;
    }

    public function testRetreieveProduct()
    {
        $products = Products::factory()->count(30)->create();

        $productFirst10 = $this->generateProductPerPage(1, 10, $products);
        $this->json('GET', '/api/v1/products?limit=10&page=1', ['Accept' => 'application/json'])
            ->dump()
            ->assertStatus(200)
            ->assertJson([
                "data" => $productFirst10,
                "total_page" => 3,
                "message" => "Data Retrieved Succesfully"
            ]);

        $productSecond10 = $this->generateProductPerPage(2, 10, $products);
        $this->json('GET', '/api/v1/products?limit=10&page=2', ['Accept' => 'application/json'])
            ->dump()
            ->assertStatus(200)
            ->assertJson([
                "data" => $productSecond10,
                "total_page" => 3,
                "message" => "Data Retrieved Succesfully"
            ]);

        $productThird10 = $this->generateProductPerPage(3, 10, $products);
        $this->json('GET', '/api/v1/products?limit=10&page=3', ['Accept' => 'application/json'])
            ->dump()
            ->assertStatus(200)
            ->assertJson([
                "data" => $productThird10,
                "total_page" => 3,
                "message" => "Data Retrieved Succesfully"
            ]);
    }

    public function testShowProductById()
    {
        $products = Products::factory()->count(15)->create();

        $id = $products[0]['web_id'];
        $products[0]['id'] = 1;

        $expected["id"] = 1;
        $expected['product_title'] = $products[0]['product_title'];
        $expected['image_url'] = $products[0]['image_url'];
        $expected['price'] = $products[0]['price'];
        $expected['web_id'] = $products[0]['web_id'];
        $expected['rating'] = $products[0]['rating'];
        $expected['category'] = $products[0]['category'];
        $expected['is_deleted'] = $products[0]['is_deleted'];

        print("id = " . $id);
        $this->json('GET', '/api/v1/products/' . $id, ['Accept' => 'application/json'])
            ->dump()
            ->assertStatus(200)
            ->assertJson([
                "data" => [$expected],
                "message" => "Data Retrieved Succesfully"
            ]);
    }


    public function testUpdateProduct()
    {
        $products = Products::factory()->count(20)->create();
        $id = $products[6]['web_id'];
        $data = [
            'product_title' => 'Keyboard Keychron',
            'price' => 2.5,
            'rating' => 3.4
        ];

        $this->put(PRODUCT_URL . "/" . $id, $data)
            ->assertStatus(204);

        $expected['id'] = 7;
        $expected['product_title'] = $data['product_title'];
        $expected['image_url'] = $products[6]['image_url'];
        $expected['price'] = $data['price'];
        $expected['web_id'] = $products[6]['web_id'];
        $expected['rating'] = $data['rating'];
        $expected['category'] = $products[6]['category'];
        $expected['is_deleted'] = $products[6]['is_deleted'];

        $this->json('GET', PRODUCT_URL . "/" . $id, ['Accept' => ACCEPT_MIME_TYPE])
            ->dump()
            ->assertStatus(200)
            ->assertJson([
                "data" => [$expected],
                "message" => DEFAULT_MESSAGE
            ]);
    }
}
