<?php

namespace App\Http\Controllers\Api;

use App\Domain\Responder\Interfaces\IApiHttpResponder;
use App\Domain\Services\Interfaces\ITaskService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class TaskController extends Controller
{

    public function __construct(private ITaskService $taskService,private readonly IApiHttpResponder $apiHttpResponder){}

    public function index(): AnonymousResourceCollection
    {
        return TaskResource::collection($this->taskService->filterData(true));
    }


    public function store(StoreTaskRequest $request): JsonResponse
    {
        return $this->apiHttpResponder->response([], $this->taskService->store($request->validated()));
    }

    /**
     * @throws AuthorizationException
     */
    public function show(int $id): TaskResource
    {

        return new TaskResource($this->taskService->show($id));
    }

    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        return $this->apiHttpResponder->response([],  $this->taskService->update($request->validated(), $id));
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(int $id): JsonResponse
    {

        return $this->apiHttpResponder->response([], $this->taskService->delete($id));
    }
}
