<?php
namespace App\Http;

use App\Http\Middlewares\Midd1;
use App\Http\Middlewares\Midd2;
use Slaravel\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel{
    protected $middleware = [
        Midd1::class,
        Midd2::class,
    ];

}
