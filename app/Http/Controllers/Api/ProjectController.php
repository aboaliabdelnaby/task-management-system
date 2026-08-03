<?php

namespace App\Http\Controllers\Api;

use App\Domain\Responder\Interfaces\IApiHttpResponder;
use App\Domain\Services\Interfaces\IProjectService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class ProjectController extends Controller
{

    public function __construct(private IProjectService $projectService,private readonly IApiHttpResponder $apiHttpResponder){}

    public function index(): AnonymousResourceCollection
    {
        return ProjectResource::collection($this->projectService->filterData(true));
    }


    public function store(StoreProjectRequest $request): JsonResponse
    {
        return $this->apiHttpResponder->response([], $this->projectService->store($request->validated()));
    }

    /**
     * @throws AuthorizationException
     */
    public function show(int $id): ProjectResource
    {

        return new ProjectResource($this->projectService->show($id));
    }

    public function update(UpdateProjectRequest $request, int $id): JsonResponse
    {
        return $this->apiHttpResponder->response([],  $this->projectService->update($request->validated(), $id));
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(int $id): JsonResponse
    {

        return $this->apiHttpResponder->response([], $this->projectService->delete($id));
    }
}
