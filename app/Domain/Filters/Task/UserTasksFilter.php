<?php

namespace App\Domain\Filters\Task;

use App\Domain\Filters\IFilter;
use Closure;

class UserTasksFilter implements IFilter
{
    /**
     * @throws \Throwable
     */
    public function handle($query, Closure $next)
    {
        return $next($query)->whereHas('project', function ($query) {
            $query->where('user_id', auth()->id());
        });
    }
}
