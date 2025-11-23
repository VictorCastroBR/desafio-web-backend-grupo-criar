<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Domain\Campaign\Entities\Campaign;
use App\Domain\Campaign\Repositories\CampaignRepositoryInterface;
use App\Domain\Cluster\Repositories\ClusterRepositoryInterface;

class CampaignRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private CampaignRepositoryInterface $campaignRepository;
    private ClusterRepositoryInterface $clusterRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->campaignRepository = $this->app->make(CampaignRepositoryInterface::class);
        $this->clusterRepository = $this->app->make(ClusterRepositoryInterface::class);
    }

    private function createDependencies()
    {
        $cluster = $this->clusterRepository->create([
            'name' => 'Grupo A'
        ]);

        return $cluster;
    }


    public function test_criar_uma_campanha()
    {
        $cluster = $this->createDependencies();

        $campaign = $this->campaignRepository->create([
            'name' => 'Campanha 2025',
            'active' => true,
            'cluster_id' => $cluster->id
        ]);

        $this->assertInstanceOf(Campaign::class, $campaign);
        $this->assertEquals('Campanha 2025', $campaign->name);
        $this->assertTrue($campaign->active);
        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'name' => 'Campanha 2025',
            'active' => true,
            'cluster_id' => $cluster->id
        ]);
    }

    public function test_encontra_uma_campanha()
    {
        $cluster = $this->createDependencies();

        $model = $this->campaignRepository->create([
            'name' => 'Campanha 2025',
            'active' => true,
            'cluster_id' => $cluster->id
        ]);

        $entity = $this->campaignRepository->find($model->id);

        $this->assertInstanceOf(Campaign::class, $entity);
        $this->assertEquals('Campanha 2025', $entity->name);
        $this->assertTrue($entity->active);
        $this->assertEquals($cluster->id, $entity->cluster->id);
    }

    public function test_retorna_todas_as_campanhas()
    {
        $cluster = $this->createDependencies();

        $this->campaignRepository->create([
            'name' => 'Campanha 2025',
            'active' => true,
            'cluster_id' => $cluster->id
        ]);

        $this->campaignRepository->create([
            'name' => 'Campanha de Natal',
            'active' => true,
            'cluster_id' => $cluster->id
        ]);

        $this->campaignRepository->create([
            'name' => 'Campanha de Ano Novo',
            'active' => true,
            'cluster_id' => $cluster->id
        ]);

        $campaigns = $this->campaignRepository->all();

        $this->assertCount(3, $campaigns);
        $this->assertInstanceOf(Campaign::class, $campaigns[0]);
        $this->assertInstanceOf(Campaign::class, $campaigns[1]);
        $this->assertInstanceOf(Campaign::class, $campaigns[2]);
    }

    public function test_atualiza_uma_campanha()
    {
        $cluster = $this->createDependencies();

        $model = $this->campaignRepository->create([
            'name' => 'Campanha 2025',
            'active' => true,
            'cluster_id' => $cluster->id
        ]);

        $entity = $this->campaignRepository->update($model->id, [
            'name' => 'Campanha 2025 atualizada',
            'active' => false,
            'cluster_id' => $cluster->id
        ]);

        $this->assertDatabaseHas('campaigns', [
            'id' => $model->id,
            'name' => 'Campanha 2025 atualizada',
            'active' => 0,
            'cluster_id' => $cluster->id
        ]);
        $this->assertEquals('Campanha 2025 atualizada', $entity->name);
        $this->assertFalse($entity->active);
    }

    public function test_exclui_uma_campanha()
    {
        $cluster = $this->createDependencies();

        $model = $this->campaignRepository->create([
            'name' => 'Campanha 2025',
            'active' => true,
            'cluster_id' => $cluster->id
        ]);

        $this->campaignRepository->delete($model->id);

        $this->assertDatabaseMissing('campaigns', ['id' => $model->id]);
    }
}
