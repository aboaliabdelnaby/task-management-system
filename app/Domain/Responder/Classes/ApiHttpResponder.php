<?php

declare(strict_types=1);


namespace App\Domain\Responder\Classes;

use App\Domain\Responder\Interfaces\IApiHttpResponder;
use Illuminate\Http\JsonResponse;

class ApiHttpResponder implements IApiHttpResponder
{
    public function response(array $data = [], ?string $message = null, int $status = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}
