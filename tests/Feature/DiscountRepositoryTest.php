<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Domain\Discount\Entities\Discount;
use App\Domain\Discount\Repositories\DiscountRepositoryInterface;
use App\Domain\Campaign\Repositories\CampaignRepositoryInterface;
use App\Domain\Cluster\Repositories\ClusterRepositoryInterface;

class DiscountRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private DiscountRepositoryInterface $discountRepository;
    private CampaignRepositoryInterface $campaignRepository;
    private ClusterRepositoryInterface $clusterRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->discountRepository = $this->app->make(DiscountRepositoryInterface::class);
        $this->campaignRepository = $this->app->make(CampaignRepositoryInterface::class);
        $this->clusterRepository = $this->app->make(ClusterRepositoryInterface::class);
    }

    private function createDependencies()
    {
        $cluster = $this->clusterRepository->create([
            'name' => 'Sudeste'
        ]);

        $campaign = $this->campaignRepository->create([
            'name' => 'Campanha 2025',
            'active' => true,
            'cluster_id' => $cluster->id
        ]);

        return $campaign;
    }


    public function test_criar_um_desconto_em_valor()
    {
        $campaign = $this->createDependencies();

        $discount = $this->discountRepository->create([
            'value' => 50.0,
            'percent' => null,
            'campaign_id' => $campaign->id
        ]);

        $this->assertInstanceOf(Discount::class, $discount);
        $this->assertEquals(50.0, $discount->value);
        $this->assertNull($discount->percent);
        $this->assertDatabaseHas('discounts', [
            'id' => $discount->id,
            'value' => '50.00',
            'percent' => null,
            'campaign_id' => $campaign->id
        ]);
    }

    public function test_criar_um_desconto_em_percentual()
    {
        $campaign = $this->createDependencies();

        $discount = $this->discountRepository->create([
            'value' => null,
            'percent' => 15.5,
            'campaign_id' => $campaign->id
        ]);

        $this->assertInstanceOf(Discount::class, $discount);
        $this->assertEquals(15.50, $discount->percent);
        $this->assertNull($discount->value);
        $this->assertDatabaseHas('discounts', [
            'id' => $discount->id,
            'value' => null,
            'percent' => '15.50',
            'campaign_id' => $campaign->id
        ]);
    }

    public function test_encontra_um_desconto()
    {
        $campaign = $this->createDependencies();

        $model = $this->discountRepository->create([
            'value' => 50.0,
            'percent' => null,
            'campaign_id' => $campaign->id
        ]);

        $entity = $this->discountRepository->find($model->id);

        $this->assertInstanceOf(Discount::class, $entity);
        $this->assertEquals(50.0, $model->value);
        $this->assertNull($model->percent);
        $this->assertEquals($campaign->id, $entity->campaign->id);
    }

    public function test_retorna_todos_os_descontos()
    {
        $campaign = $this->createDependencies();

        $this->discountRepository->create([
            'value' => 50.0,
            'percent' => null,
            'campaign_id' => $campaign->id
        ]);

        $this->discountRepository->create([
            'value' => null,
            'percent' => 10.0,
            'campaign_id' => $campaign->id
        ]);

        $this->discountRepository->create([
            'value' => 100.0,
            'percent' => null,
            'campaign_id' => $campaign->id
        ]);

        $discounts = $this->discountRepository->all();

        $this->assertCount(3, $discounts);
        $this->assertInstanceOf(Discount::class, $discounts[0]);
        $this->assertInstanceOf(Discount::class, $discounts[1]);
        $this->assertInstanceOf(Discount::class, $discounts[2]);
        $this->assertEquals(50.0, $discounts[0]->value);
        $this->assertEquals(10.0, $discounts[1]->percent);
        $this->assertEquals(100.0, $discounts[2]->value);
    }

    public function test_atualiza_uma_campanha()
    {
        $campaign = $this->createDependencies();

        $model = $this->discountRepository->create([
            'value' => 50.0,
            'percent' => null,
            'campaign_id' => $campaign->id
        ]);

        $entity = $this->discountRepository->update($model->id, [
            'value' => null,
            'percent' => 20.0,
            'campaign_id' => $campaign->id
        ]);

        $this->assertDatabaseHas('discounts', [
            'id' => $model->id,
            'value' => null,
            'percent' => '20.00'
        ]);
        $this->assertNull($entity->value);
        $this->assertEquals(20.00, $entity->percent);
    }

    public function test_exclui_uma_campanha()
    {
        $campaign = $this->createDependencies();

        $model = $this->discountRepository->create([
            'value' => 50.0,
            'percent' => null,
            'campaign_id' => $campaign->id
        ]);

        $this->discountRepository->delete($model->id);

        $this->assertDatabaseMissing('discounts', ['id' => $model->id]);
    }
}
