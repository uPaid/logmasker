<?php

namespace Upaid\Logmasker\Services;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;

class Logmasker
{
    protected $data;
    protected $config;

    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    public function mask($data):array
    {
        $this->data = $data;
        $collection = $this->normalize();
        return $collection->mapSensitiveData()->toArray();
    }

    public function maskAll(string $value):string
    {
        return str_repeat($this->config->get('logmasker.mask_all.replacer'), strlen($value));
    }

    public function maskPartial(string $value):string
    {
        return substr_replace($value, $this->config->get('logmasker.mask_partial.replacer'), $this->config->get('logmasker.mask_partial.start'), $this->config->get('logmasker.mask_partial.length'));
    }

    public function maskReplace():string
    {
        return $this->config->get('logmasker.mask_replace.replacer');
    }


    protected function normalize():Collection
    {
        if ($this->isXML()) {
            return collect(json_decode(json_encode($this->data), true));
        }

        if (is_object($this->data)) {
            return collect(json_decode(json_encode($this->data), true));
        }

        if (is_array($this->data)) {
            return collect($this->data);
        }

        if ($this->isJSON()) {
            return collect(json_decode($this->data));
        }

        if (is_string($this->data)) {
            return collect([$this->data]);
        }

        return collect([$this->data]);
    }

    protected function isXML():bool
    {
        libxml_use_internal_errors(true);
        $xml = @simplexml_load_string($this->data);
        if ($xml) {
            $this->data = $xml;
            return true;
        }
        return false;
    }

    protected function isJSON():bool
    {
        return is_string($this->data) && is_array(json_decode($this->data, true)) && (json_last_error() == JSON_ERROR_NONE);
    }
}
