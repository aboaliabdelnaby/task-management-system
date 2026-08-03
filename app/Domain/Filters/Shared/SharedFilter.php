<?php


namespace App\Domain\Filters\Shared;

use App\Domain\Filters\IFilter;
use Closure;

class SharedFilter implements IFilter
{
    public function __construct(protected $key, protected bool $useLike = false, protected ?string $param = null)
    {
    }

    public function handle($query, Closure $next)
    {
        if (request()->filled($this->key) || (request()->filled($this->param) && $this->param)) {
            $value = request($this->key) ?? request($this->param);
            if ($this->useLike) {
                return $next($query)->where($this->key, 'like', '%' . $value . '%');
            }

            return $next($query)->where($this->key, $value);
        }

        return $next($query);
    }
}
