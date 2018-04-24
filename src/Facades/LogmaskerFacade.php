<?php

namespace Upaid\Logmasker\Facades;

use Illuminate\Support\Facades\Facade;

class LogmaskerFacade extends Facade
{
    protected static function getFacadeAccessor() { return 'Logmasker'; }
}