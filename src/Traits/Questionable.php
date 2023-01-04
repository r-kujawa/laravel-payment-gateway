<?php

namespace rkujawa\LaravelPaymentGateway\Traits;

trait Questionable
{
    protected function askName($entity)
    {
        return $this->ask("What payment {$entity} would you like to add?");
    }

    protected function askId($entity, $name)
    {
        return $this->ask(
            "How would you like to identify the {$name} payment {$entity}?",
            preg_replace('/[^a-z0-9]+/i', '_', strtolower($name))
        );
    }
}