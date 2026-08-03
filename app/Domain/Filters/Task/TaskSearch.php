<?php

namespace App\Domain\Filters\Task;

use App\Domain\Filters\IFilter;
use Closure;

class TaskSearch implements IFilter
{
    /**
     * @throws \Throwable
     */
    public function handle($query, Closure $next)
    {
        if (request()->filled('search')) {
            $search = request('search');

            return $next($query)->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', "%{$search}%");
            });
        }

        return $next($query);
    }
}
