<?php

namespace rkujawa\LaravelPaymentGateway\Traits;

use Illuminate\Support\Str;

trait SimulateAttributes
{
    /**
     * Executes when the class attribute you are trying to get is not found.
     * Checks if a getter function is defined and excecutes, otherwise returns.
     *
     * @param string $key The hidden attribute.
     * @return mixed
     */
    public function __get($key)
    {
        $method = 'get' . Str::studly($key);

        if (! method_exists(self::class, $method)) {
            return;
        }

        return $this->$method();
    }

    /**
     * Excecutes if attempting to set a value on an attribute that is not found.
     * Checks if a setter function is defined and excecutes, otherwise returns.
     *
     * @param string $key The hidden attribute.
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value): void
    {
        $method = 'set' . Str::studly($key);

        if (! method_exists(self::class, $method)) {
            return;
        }

        $this->$method($value);
    }
}
