<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Cluster\Entities\Cluster;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentClusterRepository;

class ClusterRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentClusterRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new EloquentClusterRepository();
    }

    public function test_cria_um_cluster()
    {
        $entity = $this->repository->create([
            'name' => 'Sudeste',
        ]);

        $this->assertInstanceOf(Cluster::class, $entity);
        $this->assertDatabaseHas('clusters', [
            'name' => 'Sudeste',
        ]);
    }

    public function test_encontra_um_cluster()
    {
        $model = $this->repository->create([
            'name' => 'Sudeste'
        ]);

        $entity = $this->repository->find($model->id);

        $this->assertInstanceOf(Cluster::class, $entity);
        $this->assertEquals('Sudeste', $entity->name);
    }

    public function test_retorna_todos_os_clusters()
    {
        $this->repository->create([
            'name' => 'Sudeste',
        ]);

        $this->repository->create([
            'name' => 'Nordeste',
        ]);

        $clusters = $this->repository->all();

        $this->assertCount(2, $clusters);
        $this->assertInstanceOf(Cluster::class, $clusters[0]);
        $this->assertInstanceOf(Cluster::class, $clusters[1]);
    }

    public function test_atualizar_um_cluster()
    {
        $model = $this->repository->create([
            'name' => 'Sudeste',
        ]);

        $updated = $this->repository->update($model->id, [
            'name' => 'Sudeste atualizado',
            'uf' => 'SP'
        ]);

        $this->assertEquals('Sudeste atualizado', $updated->name);

        $this->assertDatabaseHas('clusters', [
            'id' => $model->id,
            'name' => 'Sudeste atualizado'
        ]);
    }

    public function test_excluir_um_cluster()
    {
        $model = $this->repository->create([
            'name' => 'Sudeste',
        ]);

        $this->repository->delete($model->id);

        $this->assertDatabaseMissing('clusters', ['id' => $model->id]);
    }
}
