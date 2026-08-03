<?php

namespace App\Domain\Filters\Project;

use App\Domain\Filters\IFilter;
use Closure;

class UserProjectsFilter implements IFilter
{
    /**
     * @throws \Throwable
     */
    public function handle($query, Closure $next)
    {
        return $next($query)->where('user_id', auth()->id());
    }
}
