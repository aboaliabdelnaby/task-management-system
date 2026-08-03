<?php

namespace App\Domain\Services\Classes;



use App\Domain\Filters\Shared\SharedDatesFilter;
use App\Domain\Filters\Shared\SharedFilter;
use App\Domain\Filters\Shared\SharedSorting;
use App\Domain\Filters\Task\TaskSearch;
use App\Domain\Filters\Task\UserTasksFilter;
use App\Domain\Repostories\Interfaces\ITaskRepository;
use App\Domain\Services\Interfaces\ITaskService;


class TaskService implements ITaskService
{
    public function __construct(private readonly ITaskRepository $taskRepository) {}

    public function index(bool $paginate = false, array $filters = [], array $relations = [], array $select = ['*'])
    {
        return $this->taskRepository->all($paginate, $filters, $relations, $select);
    }

    public function filterData(bool $paginate = false)
    {
        return $this->index($paginate, [
            TaskSearch::class,
            UserTasksFilter::class,
            new SharedFilter('project_id'),
            new SharedFilter('status'),
            new SharedFilter('priority'),
            new SharedDatesFilter('created_at'),
            SharedSorting::class,
        ],relations: ['project']);
    }

    public function store(array $data): Application|array|string|Translator
    {
        $this->taskRepository->create($data);

        return 'New record has been added!';
    }

    public function update(array $data, int $id): Application|array|string|Translator
    {
        $this->taskRepository->update(['id' => $id],$data);

        return 'The record was successfully updated!';
    }

    public function show(int $id)
    {
        return $this->taskRepository->firstOrFail(conditions: ['id' => $id],relations: ['project']);
    }

    /**
     * @throws \Throwable
     */
    public function delete(int $id): Application|array|string|Translator
    {
        $this->taskRepository->delete(conditions: ['id' => $id]);

        return 'The record was successfully deleted!';
    }
}
