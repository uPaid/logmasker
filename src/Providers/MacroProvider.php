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
                if ($key === 0 && is_string($value) && (count(explode('{', $value)) > 1)) {
                    preg_match_all('/\{(?:[^{}]|(?R))*\}/x', $value, $matches);
                    foreach ($matches[0] as $singleJson) {
                        $value = str_replace($singleJson, (new Collection(json_decode($singleJson, true)))->mapSensitiveData(), $value);
                    }
                    return $value;
                }
                if($value && !is_numeric($key)){
                    if (config('logmasker.mask_partial.fields') && in_array($key, config('logmasker.mask_all.fields'))) {
                        $value = LogmaskerFacade::maskAll($value);
                    }
                    if (config('logmasker.mask_partial.fields') && in_array($key, config('logmasker.mask_partial.fields'))) {
                        $value = LogmaskerFacade::maskPartial($value);
                    }
                    if (config('logmasker.mask_replace.fields') && in_array($key, config('logmasker.mask_replace.fields'))) {
                        $value = LogmaskerFacade::maskReplace();
                    }
                }
                return $value;
            });
        });
    }
}
