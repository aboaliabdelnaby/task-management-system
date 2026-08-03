<?php

namespace App\Domain\Services\Interfaces;

interface ITaskService
{
    public function index(bool $paginate = false, array $filters = [], array $relations = [], array $select = ['*']);

    public function filterData(bool $paginate = false);

    public function store(array $data);

    public function update(array $data, int $id);

    public function show(int $id);

    public function delete(int $id);

}
