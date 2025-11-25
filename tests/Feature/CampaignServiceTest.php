<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Application\Services\CampaignService;
use App\Domain\Cluster\Repositories\ClusterRepositoryInterface;

class CampaignServiceTest extends TestCase
{
    use RefreshDatabase;

    private CampaignService $campaignService;
    private ClusterRepositoryInterface $clusterRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->campaignService = $this->app->make(CampaignService::class);
        $this->clusterRepository = $this->app->make(ClusterRepositoryInterface::class);
    }

    private function createDependencies()
    {
        $cluster = $this->clusterRepository->create([
            'name' => 'Cluster Sudeste'
        ]);

        return $cluster;
    }

    public function test_ao_criar_campanha_ativa_desativa_outras_do_mesmo_cluster()
    {
        $cluster = $this->createDependencies();

        // Cria primeira campanha ativa
        $campanha1 = $this->campaignService->createCampaign([
            'name' => 'Campanha Black Friday',
            'active' => true,
            'cluster_id' => $cluster->id
        ]);

        $this->assertTrue($campanha1->active);
        $this->assertDatabaseHas('campaigns', [
            'id' => $campanha1->id,
            'active' => true
        ]);

        // Cria segunda campanha ativa no mesmo cluster
        $campanha2 = $this->campaignService->createCampaign([
            'name' => 'Campanha Cyber Monday',
            'active' => true,
            'cluster_id' => $cluster->id
        ]);

        $this->assertTrue($campanha2->active);

        // Verifica que a primeira foi desativada
        $this->assertDatabaseHas('campaigns', [
            'id' => $campanha1->id,
            'active' => false
        ]);

        // Verifica que apenas a segunda está ativa
        $this->assertDatabaseHas('campaigns', [
            'id' => $campanha2->id,
            'active' => true
        ]);
    }

    public function test_ao_atualizar_campanha_para_ativa_desativa_outras_do_mesmo_cluster()
    {
        $cluster = $this->createDependencies();

        // Cria duas campanhas inativas
        $campanha1 = $this->campaignService->createCampaign([
            'name' => 'Campanha Natal',
            'active' => false,
            'cluster_id' => $cluster->id
        ]);

        $campanha2 = $this->campaignService->createCampaign([
            'name' => 'Campanha Ano Novo',
            'active' => true,
            'cluster_id' => $cluster->id
        ]);

        // Ativa a primeira campanha
        $campanhaAtualizada = $this->campaignService->updateCampaign($campanha1->id, [
            'name' => 'Campanha Natal Atualizada',
            'active' => true
        ]);

        $this->assertTrue($campanhaAtualizada->active);

        // Verifica que a segunda foi desativada
        $this->assertDatabaseHas('campaigns', [
            'id' => $campanha2->id,
            'active' => false
        ]);

        // Verifica que a primeira está ativa
        $this->assertDatabaseHas('campaigns', [
            'id' => $campanha1->id,
            'active' => true
        ]);
    }

    public function test_campanhas_ativas_em_clusters_diferentes_nao_se_afetam()
    {
        $clusterSudeste = $this->clusterRepository->create(['name' => 'Sudeste']);
        $clusterNordeste = $this->clusterRepository->create(['name' => 'Nordeste']);

        // Cria campanha ativa no Sudeste
        $campanhaSudeste = $this->campaignService->createCampaign([
            'name' => 'Campanha Sudeste',
            'active' => true,
            'cluster_id' => $clusterSudeste->id
        ]);

        // Cria campanha ativa no Nordeste
        $campanhaNordeste = $this->campaignService->createCampaign([
            'name' => 'Campanha Nordeste',
            'active' => true,
            'cluster_id' => $clusterNordeste->id
        ]);

        // Ambas devem permanecer ativas
        $this->assertDatabaseHas('campaigns', [
            'id' => $campanhaSudeste->id,
            'active' => true
        ]);

        $this->assertDatabaseHas('campaigns', [
            'id' => $campanhaNordeste->id,
            'active' => true
        ]);
    }

    public function test_pode_criar_multiplas_campanhas_inativas_no_mesmo_cluster()
    {
        $cluster = $this->createDependencies();

        $campanha1 = $this->campaignService->createCampaign([
            'name' => 'Campanha Inativa 1',
            'active' => false,
            'cluster_id' => $cluster->id
        ]);

        $campanha2 = $this->campaignService->createCampaign([
            'name' => 'Campanha Inativa 2',
            'active' => false,
            'cluster_id' => $cluster->id
        ]);

        $campanha3 = $this->campaignService->createCampaign([
            'name' => 'Campanha Inativa 3',
            'active' => false,
            'cluster_id' => $cluster->id
        ]);

        // Todas devem permanecer inativas
        $this->assertDatabaseHas('campaigns', ['id' => $campanha1->id, 'active' => false]);
        $this->assertDatabaseHas('campaigns', ['id' => $campanha2->id, 'active' => false]);
        $this->assertDatabaseHas('campaigns', ['id' => $campanha3->id, 'active' => false]);
    }
}
