<?php

namespace App\Http\Controllers\Api;

use App\Domain\Responder\Interfaces\IApiHttpResponder;
use App\Domain\Services\Interfaces\ITaskService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;


class DashboardController extends Controller
{

    public function __construct(private ITaskService $taskService, private readonly IApiHttpResponder $apiHttpResponder)
    {
    }

    public function dashboard(): JsonResponse
    {
        return $this->apiHttpResponder->response($this->taskService->dashboard());
    }

}
