<?php

namespace App\Http\Middlewares;

class Midd1 {
    public function handle($request, \Closure $next) {
        echo '<br>中间件1<br>';
        $next($request);
        echo '<br>中间件1<br>';
    }
}