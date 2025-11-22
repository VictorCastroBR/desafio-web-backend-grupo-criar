<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\State\Entities\State;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentStateRepository;

class StateRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentStateRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new EloquentStateRepository();
    }

    public function test_cria_um_estado()
    {
        $entity = $this->repository->create([
            'name' => 'São Paulo',
            'uf' => 'SP'
        ]);

        $this->assertInstanceOf(State::class, $entity);
        $this->assertDatabaseHas('states', [
            'name' => 'São Paulo',
            'uf' => 'SP'
        ]);
    }

    public function test_encontra_um_estado()
    {
        $model = $this->repository->create([
            'name' => 'São Paulo',
            'uf' => 'SP'
        ]);

        $entity = $this->repository->find($model->id);

        $this->assertInstanceOf(State::class, $entity);
        $this->assertEquals('São Paulo', $entity->name);
    }

    public function test_retorna_todos_os_estados()
    {
        $this->repository->create([
            'name' => 'Minas Gerais',
            'uf' => 'MG'
        ]);

        $this->repository->create([
            'name' => 'São Paulo',
            'uf' => 'SP'
        ]);

        $states = $this->repository->all();

        $this->assertCount(2, $states);
        $this->assertInstanceOf(State::class, $states[0]);
        $this->assertInstanceOf(State::class, $states[1]);
    }

    public function test_atualizar_um_estado()
    {
        $model = $this->repository->create([
            'name' => 'São Paulo',
            'uf' => 'SP'
        ]);

        $updated = $this->repository->update($model->id, [
            'name' => 'São Paulo atualizado',
            'uf' => 'SP'
        ]);

        $this->assertEquals('São Paulo atualizado', $updated->name);

        $this->assertDatabaseHas('states', [
            'id' => $model->id,
            'name' => 'São Paulo atualizado'
        ]);
    }

    public function test_excluir_um_estado()
    {
        $model = $this->repository->create([
            'name' => 'São Paulo',
            'uf' => 'SP'
        ]);

        $this->repository->delete($model->id);

        $this->assertDatabaseMissing('states', ['id' => $model->id]);
    }
}
