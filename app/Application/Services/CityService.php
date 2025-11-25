<?php

namespace App\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Domain\City\Entities\City;
use App\Domain\City\Repositories\CityRepositoryInterface;

class CityService
{
    public function __construct(
        private CityRepositoryInterface $cityRepository,
    ) { }

    public function getAllCities(): array
    {
        return $this->cityRepository->all();
    }

    public function getPaginatedCities(?int $perPage = 15): LengthAwarePaginator
    {
        return $this->cityRepository->paginate($perPage);
    }

    public function findCityById(int $id): ?City
    {
        return $this->cityRepository->find($id);
    }

    public function createCity(array $data): City
    {
       return $this->cityRepository->create($data);
    }

    public function updateCity(int $id, array $data): ?City
    {
        return $this->cityRepository->update($id, $data);
    }

    public function deleteCity(int $id): void
    {
        $this->cityRepository->delete($id);
    }
}
