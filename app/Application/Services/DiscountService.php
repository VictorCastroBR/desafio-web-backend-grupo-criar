<?php

namespace App\Application\Services;

use App\Domain\Discount\Entities\Discount;
use App\Domain\Discount\Repositories\DiscountRepositoryInterface;

class DiscountService
{
    public function __construct(
        private DiscountRepositoryInterface $discountRepository,
    ) { }

    public function getAllDiscounts(): array
    {
        return $this->discountRepository->all();
    }

    public function getPaginatedDiscounts(?int $perPage = 15): array
    {
        return $this->discountRepository->paginate($perPage);
    }

    public function findDiscountById(int $id): ?Discount
    {
        return $this->discountRepository->find($id);
    }

    public function createDiscount(array $data): Discount
    {
        $this->validateDiscountData($data);

        return $this->discountRepository->create($data);
    }

    public function updateDiscount(int $id, array $data): ?Discount
    {
        $this->validateDiscountData($data);

        return $this->discountRepository->update($id, $data);
    }

    public function deleteDiscount(int $id): void
    {
        $this->discountRepository->delete($id);
    }

    /**
     * Valida se 'value' ou 'percent' foram fornecidos
     * mas não ambos!
     * @param array $data Dados do desconto a serem validados
     * @throws InvalidArgumentException SE a validação flhar!
     */
    private function validateDiscountData(array $data): void
    {
        $hasValue = isset($data['value']) && $data['value'] !== null;
        $hasPercent = isset($data['percent']) && $data['percent'] !== null;

        if ($hasValue && $hasPercent)
            throw new \InvalidArgumentException('Cannot provide both value and percent');

        elseif (!$hasValue && !$hasPercent)
            throw new \InvalidArgumentException('Either value or percent must be provided');
    }
}
