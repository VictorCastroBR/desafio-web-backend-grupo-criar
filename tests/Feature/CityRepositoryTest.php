<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\City\Entities\City;
use App\Domain\City\Repositories\CityRepositoryInterface;
use App\Domain\State\Repositories\StateRepositoryInterface;
use App\Domain\Cluster\Repositories\ClusterRepositoryInterface;


class CityRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private StateRepositoryInterface $stateRepository;
    private ClusterRepositoryInterface $clusterRepository;
    private CityRepositoryInterface $cityRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cityRepository = $this->app->make(CityRepositoryInterface::class);
        $this->clusterRepository = $this->app->make(ClusterRepositoryInterface::class);
        $this->stateRepository = $this->app->make(StateRepositoryInterface::class);
    }

    private function createDependencies()
    {
        $state = $this->stateRepository->create([
            'name' => 'São Paulo',
            'uf' => 'SP'
        ]);

        $cluster = $this->clusterRepository->create([
            'name' => 'Sudeste'
        ]);

        return [$state, $cluster];
    }


    public function test_criar_uma_cidade()
    {
        [$state, $cluster] = $this->createDependencies();

        $city = $this->cityRepository->create([
            'name' => 'Guariba',
            'state_id' => $state->id,
            'cluster_id' => $cluster->id
        ]);

        $this->assertEquals('Guariba', $city->name);
        $this->assertDatabaseHas('cities', [
            'id' => $city->id,
            'name' => $city->name,
            'state_id' => $city->state->id,
            'cluster_id' => $city->cluster->id
        ]);
    }

    public function test_encontra_uma_cidade()
    {
        [$state, $cluster] = $this->createDependencies();

        $model = $this->cityRepository->create([
            'name' => 'Guariba',
            'state_id' => $state->id,
            'cluster_id' => $cluster->id
        ]);

        $entity = $this->cityRepository->find($model->id);

        $this->assertInstanceOf(City::class, $entity);
        $this->assertEquals('Guariba', $entity->name);
    }

    public function test_retorna_todas_as_cidades()
    {
        [$state, $cluster] = $this->createDependencies();

        $this->cityRepository->create([
            'name' => 'Guariba',
            'state_id' => $state->id,
            'cluster_id' => $cluster->id
        ]);

        $this->cityRepository->create([
            'name' => 'Ribeirão Preto',
            'state_id' => $state->id,
            'cluster_id' => $cluster->id
        ]);

        $this->cityRepository->create([
            'name' => 'São Paulo',
            'state_id' => $state->id,
            'cluster_id' => $cluster->id
        ]);


        $cities = $this->cityRepository->all();

        $this->assertCount(3, $cities);
    }

    public function test_atualiza_uma_cidade()
    {
        [$state, $cluster] = $this->createDependencies();

        $model = $this->cityRepository->create([
            'name' => 'Guariba',
            'state_id' => $state->id,
            'cluster_id' => $cluster->id
        ]);

        $entity = $this->cityRepository->update($model->id, [
            'name' => 'Guariba atualizada',
            'state_id' => $state->id,
            'cluster_id' => $cluster->id
        ]);

        $this->assertDatabaseHas('cities', [
            'id' => $model->id,
            'name' => 'Guariba atualizada'
        ]);
        $this->assertEquals($entity->name, 'Guariba atualizada');
    }

    public function test_exclui_uma_cidade()
    {
        [$state, $cluster] = $this->createDependencies();

        $model = $this->cityRepository->create([
            'name' => 'Guariba',
            'state_id' => $state->id,
            'cluster_id' => $cluster->id
        ]);

        $this->cityRepository->delete($model->id);

        $this->assertDatabaseMissing('cities', ['id' => $model->id]);
    }
}
