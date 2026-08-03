<?php


namespace App\Domain\Filters\Shared;

use App\Domain\Filters\IFilter;
use Carbon\Carbon;
use Closure;

class SharedDatesFilter implements IFilter
{
    public function __construct(protected $key)
    {
    }

    public function handle($query, Closure $next)
    {
        if (request()->filled('from') || request()->filled('to')) {
            if (!$this->key || !in_array($this->key, \Schema::getColumnListing($query->getModel()->getTable()))) {
                throw new \InvalidArgumentException('invalid fillable key');
            }

            $from = request()->filled('from') ? Carbon::parse(request('from'))->startOfDay() : null;
            $to = request()->filled('to') ? Carbon::parse(request('to'))->endOfDay() : null;

            return $next($query)->where(function ($query) use ($from, $to) {
                if ($from && $to) {
                    $query->whereBetween($this->key, [$from, $to]);
                } elseif ($from) {
                    $query->where($this->key, '>=', $from);
                } elseif ($to) {
                    $query->where($this->key, '<=', $to);
                }
            });
        }

        return $next($query);
    }
}
