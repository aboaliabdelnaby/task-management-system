<?php


namespace App\Domain\Filters\Shared;

use App\Domain\Filters\IFilter;
use Closure;
use Exception;

class SharedSorting implements IFilter
{
    /**
     * @throws \Throwable
     */
    public function handle($query, Closure $next)
    {
        $sortBy = request('sort_by', 'id');
        $sortDir = request('sort_dir', 'desc');
        throw_if(!in_array($sortDir, ['asc', 'desc']), new Exception('invalid sort dir'));

        $columns = \Schema::getColumnListing($query->getModel()->getTable());
        $sortBy = in_array($sortBy, $columns) ? $sortBy : 'id';

        return $next($query)->orderBy($sortBy, $sortDir);
    }
}
