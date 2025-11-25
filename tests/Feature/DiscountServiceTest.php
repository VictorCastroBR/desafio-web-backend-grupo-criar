<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Application\Services\DiscountService;
use App\Domain\Campaign\Repositories\CampaignRepositoryInterface;
use App\Domain\Cluster\Repositories\ClusterRepositoryInterface;

class DiscountServiceTest extends TestCase
{
    use RefreshDatabase;

    private DiscountService $discountService;
    private CampaignRepositoryInterface $campaignRepository;
    private ClusterRepositoryInterface $clusterRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->discountService = $this->app->make(DiscountService::class);
        $this->campaignRepository = $this->app->make(CampaignRepositoryInterface::class);
        $this->clusterRepository = $this->app->make(ClusterRepositoryInterface::class);
    }

    private function createDependencies()
    {
        $cluster = $this->clusterRepository->create([
            'name' => 'Cluster Sul'
        ]);

        $campaign = $this->campaignRepository->create([
            'name' => 'Campanha Desconto',
            'active' => true,
            'cluster_id' => $cluster->id
        ]);

        return $campaign;
    }

    public function test_nao_pode_criar_desconto_com_valor_e_percentual_simultaneos()
    {
        $campaign = $this->createDependencies();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Não é possível fornecer valor e percentual simultaneamente');

        $this->discountService->createDiscount([
            'value' => 50.0,
            'percent' => 10.0,
            'campaign_id' => $campaign->id
        ]);
    }

    public function test_nao_pode_criar_desconto_sem_valor_e_sem_percentual()
    {
        $campaign = $this->createDependencies();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('É necessário fornecer valor ou percentual');

        $this->discountService->createDiscount([
            'value' => null,
            'percent' => null,
            'campaign_id' => $campaign->id
        ]);
    }

    public function test_pode_criar_desconto_apenas_com_valor()
    {
        $campaign = $this->createDependencies();

        $discount = $this->discountService->createDiscount([
            'value' => 100.0,
            'percent' => null,
            'campaign_id' => $campaign->id
        ]);

        $this->assertEquals(100.0, $discount->value);
        $this->assertNull($discount->percent);
        $this->assertDatabaseHas('discounts', [
            'id' => $discount->id,
            'value' => '100.00',
            'percent' => null
        ]);
    }

    public function test_pode_criar_desconto_apenas_com_percentual()
    {
        $campaign = $this->createDependencies();

        $discount = $this->discountService->createDiscount([
            'value' => null,
            'percent' => 25.0,
            'campaign_id' => $campaign->id
        ]);

        $this->assertNull($discount->value);
        $this->assertEquals(25.0, $discount->percent);
        $this->assertDatabaseHas('discounts', [
            'id' => $discount->id,
            'value' => null,
            'percent' => '25.00'
        ]);
    }

    public function test_nao_pode_atualizar_desconto_com_valor_e_percentual_simultaneos()
    {
        $campaign = $this->createDependencies();

        $discount = $this->discountService->createDiscount([
            'value' => 50.0,
            'percent' => null,
            'campaign_id' => $campaign->id
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Não é possível fornecer valor e percentual simultaneamente');

        $this->discountService->updateDiscount($discount->id, [
            'value' => 100.0,
            'percent' => 20.0,
            'campaign_id' => $campaign->id
        ]);
    }

    public function test_pode_alterar_desconto_de_valor_para_percentual()
    {
        $campaign = $this->createDependencies();

        $discount = $this->discountService->createDiscount([
            'value' => 50.0,
            'percent' => null,
            'campaign_id' => $campaign->id
        ]);

        $discountAtualizado = $this->discountService->updateDiscount($discount->id, [
            'value' => null,
            'percent' => 15.0,
            'campaign_id' => $campaign->id
        ]);

        $this->assertNull($discountAtualizado->value);
        $this->assertEquals(15.0, $discountAtualizado->percent);
        $this->assertDatabaseHas('discounts', [
            'id' => $discount->id,
            'value' => null,
            'percent' => '15.00'
        ]);
    }
}
