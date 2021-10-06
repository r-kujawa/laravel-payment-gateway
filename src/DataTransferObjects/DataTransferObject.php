<?php

namespace rkujawa\LaravelPaymentGateway\DataTransferObjects;

use Illuminate\Contracts\Support\Arrayable;

abstract class DataTransferObject implements Arrayable
{
    protected $properties = [];

    public function __construct(array $parameters = [])
    {
        foreach ($this->properties() as $property) {
            if (isset($parameters[$property])) {
                $this->{$property} = $parameters[$property];
            } else {
                $this->properties[$property] = null;
            }
        }
    }

    abstract protected function properties(): array;

    public function __get($key)
    {
        $getMethod = 'get' . \Str::studly($key) . 'Property';

        if (method_exists($this, $getMethod) && debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0]['function'] !== $getMethod) {
            return $this->{$getMethod}();
        }

        return $this->properties[$key];
    }

    public function __set($key, $value)
    {
        if (!in_array($key, $this->properties())) {
            return;
        }

        $setMethod = 'set' . \Str::studly($key) . 'Property';

        if (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0]['function'] === $setMethod || !method_exists($this, $setMethod)) {
            $this->properties[$key] = $value;
        } else {
            $this->{$setMethod}($value);
        }
    }

    public function toArray()
    {
        $dto = [];

        foreach ($this->properties() as $property) {
            $dto[$property] = $this->{$property};

            if ($dto[$property] instanceof Arrayable) {
                $dto[$property] = $dto[$property]->toArray();
            }
        }

        return $dto;
    }
}
