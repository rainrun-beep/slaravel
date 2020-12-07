<?php

namespace App\Http\Middlewares;

class Midd2 {
    public function handle($request, \Closure $next) {
        echo '<br>中间件2<br>';
        $next($request);
        echo '<br>中间件2<br>';
    }
}