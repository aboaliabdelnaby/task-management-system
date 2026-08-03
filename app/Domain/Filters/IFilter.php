<?php

namespace App\Domain\Filters;

use Closure;

interface IFilter
{
    public function handle($query, Closure $next);
}
