<?php

namespace App\Domain\Filters\Projects;

use App\Domain\Filters\IFilter;
use Closure;

class AuthFilter implements IFilter
{
    /**
     * @throws \Throwable
     */
    public function handle($query, Closure $next)
    {
        return $next($query)->where('user_id', auth()->id());
    }
}
