<?php


namespace App\Http\Middleware;


use App\Services\Api\V1\Filter;
use Closure;

class FilterMiddleware {

    protected  $api;

    public function __construct(Filter $filter) {

        $this->filter = $filter;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$filters)
    {
        foreach ($filters as $filter_method) {

            $this->filter->$filter_method();
        }

        return $next($request);
    }
} 