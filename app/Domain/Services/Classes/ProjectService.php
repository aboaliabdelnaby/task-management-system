<?php

namespace App\Domain\Services\Classes;



use App\Domain\Filters\Projects\AuthFilter;
use App\Domain\Filters\Shared\SharedDatesFilter;
use App\Domain\Filters\Shared\SharedFilter;
use App\Domain\Filters\Shared\SharedSorting;
use App\Domain\Repostories\Interfaces\IProjectRepository;
use App\Domain\Services\Interfaces\IProjectService;


class ProjectService implements IProjectService
{
    public function __construct(private readonly IProjectRepository $projectRepository) {}

    public function index(bool $paginate = false, array $filters = [], array $relations = [], array $select = ['*'])
    {
        return $this->projectRepository->all($paginate, $filters, $relations, $select);
    }

    public function filterData(bool $paginate = false)
    {
        return $this->index($paginate, [
            AuthFilter::class,
            new SharedFilter('status'),
            new SharedDatesFilter('created_at'),
            SharedSorting::class,
        ]);
    }

    public function store(array $data): Application|array|string|Translator
    {
        $this->projectRepository->create($data);

        return 'New record has been added!';
    }

    public function update(array $data, int $id): Application|array|string|Translator
    {
        $this->projectRepository->update(['id' => $id],$data);

        return 'The record was successfully updated!';
    }

    public function show(int $id)
    {
        return $this->projectRepository->firstOrFail(conditions: ['id' => $id]);
    }

    /**
     * @throws \Throwable
     */
    public function delete(int $id): Application|array|string|Translator
    {
        $this->projectRepository->delete(conditions: ['id' => $id]);

        return 'The record was successfully deleted!';
    }
}
