<?php

declare(strict_types=1);

namespace App\Domain\Repostories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;

abstract class AbstractRepository
{
    public function __construct(protected $model) {}

    public function firstOrFail(array $conditions, array $relations = [], array $withCount = []): mixed
    {
        return $this->prepareQuery(conditions: $conditions, relations: $relations, withCount: $withCount)->firstOrFail();
    }

    public function first(array $conditions = [], array $relations = [], array $select = ['*']): mixed
    {
        return $this->prepareQuery(conditions: $conditions, relations: $relations, select: $select)->first();
    }

    public function listAllBy(array $conditions = [], array $relations = [], array $select = ['*'], string $orderBy = 'id', string $orderType = 'DESC'): array|Collection
    {
        return $this->prepareQuery(conditions: $conditions, relations: $relations, select: $select)->orderBy($orderBy, $orderType)->get();
    }

    public function create(array $data): mixed
    {
        return $this->model->create($data);
    }

    public function update(array $conditions, array $data, array $select = ['*']): Model
    {
        $model = $this->model->newQuery()->where($conditions)->select($select)->firstOrFail();

        $model->update($data);

        return $model;
    }

    public function updateAll(array $data, ?array $conditions = null)
    {
        $this->model->newQuery()->where($conditions)->update($data);
    }

    public function upsert(array $data, array $keys, array $values)
    {
        $this->model->upsert($data, $keys, $values);
    }

    public function updateOrCreate(array $find, array $data): Model|\Illuminate\Database\Eloquent\Builder
    {
        return $this->model->newQuery()->updateOrCreate($find, $data);
    }

    public function prepareQuery(array $conditions = [], array $relations = [], array $select = ['*'], array $withCount = []): Builder|\Illuminate\Database\Eloquent\Builder
    {
        return $this->model
            ->with($relations)
            ->withCount($withCount)
            ->where($conditions)
            ->select($select);
    }

    public function retrieve(array $conditions = [], array $relations = [], array $select = ['*'], string $orderBy = 'id', ?string $scope = null, array $withCount = []): LengthAwarePaginator
    {
        $model = $scope ? $this->model->$scope() : $this->model;

        return $model->where($conditions)->select($select)->orderBy($orderBy, 'DESC')->with($relations)->withCount($withCount)->paginate(request('itemsPerPage') ?? config('app.pagination.value', env('PAGINATE_COUNT')));
    }

    public function updateWhere(array $data, array $conditions = []): void
    {
        $this->model->where($conditions)->update($data);
    }

    public function delete(array $conditions, bool $hardDelete = false): void
    {
        $model = $this->firstOrFail(conditions: $conditions);

        if ($hardDelete) {
            $model->forceDelete();
        } else {
            $model->delete();
        }
    }

    public function replicate(Model $model, array $data = []): Model
    {
        $duplicatedRecord = $model->replicate()->fill($data);
        $duplicatedRecord->save();

        return $duplicatedRecord;
    }

    public function getWhereIn(array $ids, array $conditions = [], string $selectedColumn = 'id', array $select = ['*'], array $relations = []): mixed
    {
        return $this->prepareWhereIn(selectedColumn: $selectedColumn, ids: $ids, conditions: $conditions)->select($select)->with($relations)->get();
    }

    public function getWhereNotIn(array $ids, array $conditions = [], string $selectedColumn = 'id', array $select = ['*'], array $relations = []): mixed
    {
        return $this->prepareWhereNotIn(selectedColumn: $selectedColumn, ids: $ids, conditions: $conditions)->select($select)->with($relations)->get();
    }

    public function updateWhereIn(array $data, array $ids = [], string $selectedColumn = 'id', array $conditions = []): void
    {
        $this->prepareWhereIn(selectedColumn: $selectedColumn, ids: $ids, conditions: $conditions)->update($data);
    }

    public function truncate(): void
    {
        $this->model->truncate();
    }

    public function takeRaws(int $nRaws, array $conditions = [], array $relations = [], array $select = ['*'], string $orderColumn = 'id', string $orderType = 'DESC'): Collection|array
    {
        return $this->prepareQuery($conditions, $relations, $select)
            ->orderBy($orderColumn, $orderType)
            ->take($nRaws)
            ->get();
    }

    public function getFromTo(int $skip, int $limit)
    {
        return $this->model->orderByDesc('id')->skip($skip)->take($limit)->get();
    }

    public function getCount(array $conditions = [])
    {
        return $this->model->where($conditions)->count();
    }

    public function lastOrFailOnlyTrashedRecord(array $conditions = [], array $relations = [], array $select = ['*']): Model|Builder|null
    {
        return $this->prepareQuery($conditions, $relations, $select)->orderBy('created_at', 'desc')->onlyTrashed()->firstOrFail();
    }

    public function lastOrFailWithTrashedRecord(array $conditions = [], array $relations = [], array $select = ['*']): Model|Builder|null
    {
        return $this->prepareQuery($conditions, $relations, $select)->orderBy('created_at', 'desc')->withTrashed()->firstOrFail();
    }

    public function prepareWhereIn(string $selectedColumn = 'id', array $ids = [], array $conditions = []): mixed
    {
        return $this->model->whereIn($selectedColumn, $ids)->where($conditions);
    }

    private function prepareWhereNotIn(string $selectedColumn = 'id', array $ids = [], array $conditions = []): mixed
    {
        return $this->model->whereNotIn($selectedColumn, $ids)->where($conditions);
    }

    public function firstOrFailWithTrashed(array $conditions = [], array $relations = [], array $select = ['*'])
    {
        return $this->prepareQuery($conditions, $relations, $select)->withTrashed()->firstOrFail();
    }

    public function all(bool $paginate = false, array $filters = [], array $relations = [], array $select = ['*'], array $withCount = [], array $conditions = [])
    {
        $query = $this->model->newQuery()->where($conditions);

        $query = app(Pipeline::class)
            ->send($query)
            ->through($filters)
            ->thenReturn();

        $query->select($select)
            ->with($relations)
            ->withCount($withCount);

        if ($paginate) {
            $perPage = (int) request('itemsPerPage', (int) config('app.pagination.value', (int) env('PAGINATE_COUNT', 15)));
            $currentPage = max((int) request('page', 1), 1);

            $paginator = $query->paginate($perPage, ['*'], 'page', $currentPage);

            if ($paginator->isEmpty() && $currentPage > 1) {
                $paginator = $query->paginate($perPage, ['*'], 'page', 1);
            }

            return $paginator;
        }

        return $query->get();
    }

    public function listWhereIn(string $column, array $values, array $conditions = [], array $orConditions = [], array $relations = []): Collection|array
    {
        return $this->model->query()->with($relations)->where($conditions)->whereIn($column, $values)->get();
    }

    public function withCount(array $withCount = []): \Illuminate\Database\Eloquent\Builder
    {
        return $this->model->withCount($withCount);
    }
}
