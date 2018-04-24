<?php
namespace Upaid\Logmasker\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;
use Upaid\Logmasker\Facades\LogmaskerFacade;

class MacroProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        Collection::macro('mapSensitiveData', function ()  {
            return $this->map(function ($value, $key) {
                if (is_array($value) || is_object($value)) {
                    return (new Collection($value))->mapSensitiveData();
                }
                if($value && !is_numeric($key)){
                    if (in_array($key, config('logmasker.mask_all.fields'))) {
                        $value = LogmaskerFacade::maskAll($value);
                    }
                    if (in_array($key, config('logmasker.mask_partial.fields'))) {
                        $value = LogmaskerFacade::maskPartial($value);
                    }
                }
                return $value;
            });
        });
    }
}
