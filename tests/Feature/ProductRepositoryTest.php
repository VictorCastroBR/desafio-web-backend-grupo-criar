<?php

namespace Tests\Feature;

use App\Domain\Product\Entities\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Domain\Product\Repositories\ProductRepositoryInterface;

class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ProductRepositoryInterface $productRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productRepository = $this->app->make(ProductRepositoryInterface::class);
    }

    public function test_criar_um_produto()
    {
        $product = $this->productRepository->create([
            'name' => 'Notebook Lenovo',
            'price' => 3500.0,
        ]);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Notebook Lenovo', $product->name);
        $this->assertEquals(3500.0, $product->price);
        $this->assertDatabaseHas('products', [
            'name' => 'Notebook Lenovo',
            'price' => 3500.0,
        ]);
    }

    public function test_encontra_um_produto()
    {
        $model = $this->productRepository->create([
            'name' => 'Notebook Lenovo',
            'price' => 3500.0,
        ]);

        $entity = $this->productRepository->find($model->id);

        $this->assertInstanceOf(Product::class, $entity);
        $this->assertEquals('Notebook Lenovo', $entity->name);
        $this->assertEquals(3500.0, $entity->price);
    }

    public function test_retorna_todos_os_produtos()
    {
        $this->productRepository->create([
            'name' => 'Notebook Lenovo',
            'price' => 3500.0,
        ]);

        $this->productRepository->create([
            'name' => 'Monitor 32"',
            'price' => 1400.0,
        ]);

        $this->productRepository->create([
            'name' => 'Cabo HDMI',
            'price' => 25.50,
        ]);

        $products = $this->productRepository->all();

        $this->assertCount(3, $products);

        $this->assertInstanceOf(Product::class, $products[0]);
        $this->assertInstanceOf(Product::class, $products[1]);
        $this->assertInstanceOf(Product::class, $products[2]);
        $this->assertEquals(3500.0, $products[0]->price);
        $this->assertEquals(1400.0, $products[1]->price);
        $this->assertEquals(25.5, $products[2]->price);
    }

    public function test_atualiza_um_produto()
    {
        $model = $this->productRepository->create([
            'name' => 'Notebook Lenovo',
            'price' => 3500.0,
        ]);

        $entity  = $this->productRepository->update($model->id, [
            'name' => 'Notebook Lenovo atualizado',
            'price' => 3200.0
        ]);

        $this->assertEquals(3200.0, $entity->price);
        $this->assertEquals('Notebook Lenovo atualizado', $entity->name);
        $this->assertDatabaseHas('products', [
            'id' => $model->id,
            'name' => 'Notebook Lenovo atualizado',
            'price' => '3200.00'
        ]);
    }

    public function test_exclui_um_produt()
    {
        $model = $this->productRepository->create([
            'name' => 'Notebook Lenovo',
            'price' => 3500.0,
        ]);

        $this->productRepository->delete($model->id);

        $this->assertDatabaseMissing('products', ['id' => $model->id]);
    }
}
