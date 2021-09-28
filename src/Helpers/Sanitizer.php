<?php

namespace rkujawa\LaravelPaymentGateway\Helpers;

class Sanitizer
{
    /**
     * Runs all provided sanitization actions on value.
     *
     * @param mixed $value
     * @param array $actions
     * @return mixed
     */
    public static function run($value, array $actions)
    {
        foreach ($actions as $action => $args) {
            $value = is_int($action) ? self::{$args}($value) : self::{$action}($value, ...$args);
        }

        return $value;
    }

    /**
     * Removes any whitespace that exists in the extreme point of the provided value.
     *
     * @param string $value
     * @return string
     */
    public static function trim(string $value): string
    {
        return trim($value);
    }

    /**
     * Removes all whitespaces from a string, including tabs.
     *
     * @param string $value
     * @return string
     */
    public static function compress(string $value): string
    {
        return preg_replace('/\s+/', '', $value);
    }

    /**
     * Replaces one or more whitespaces, tabs and/or linebreaks for a single space.
     *
     * @param string $value
     * @return string
     */
    public static function tighten(string $value): string
    {
        return preg_replace('!\s+!', ' ', $value);
    }

    /**
     * Cut a string value to the specified maximum length.
     *
     * @param string $value
     * @param integer $maxLength
     * @return string
     */
    public static function shorten(string $value, int $maxLength): string
    {
        return substr($value, 0, $maxLength);
    }

    /**
     * Prefix the value with the provided string.
     *
     * @param string $value
     * @param string $prefix
     * @return string
     */
    public static function prefix(string $value, string $prefix): string
    {
        return $prefix . $value;
    }

    /**
     * Suffix the value with the provided string.
     *
     * @param string $value
     * @param string $suffix
     * @return string
     */
    public static function suffix(string $value, string $suffix): string
    {
        return $value . $suffix;
    }

    /**
     * Capitalize the provided value.
     *
     * @param string $value
     * @param string|null $by
     * @param boolean $lowerRest
     * @return string
     */
    public static function capitalize(string $value): string
    {
        return ucwords($value);
    }

    /**
     * Uppercase the provided value.
     *
     * @param string $value
     * @return string
     */
    public static function uppercase(string $value): string
    {
        return strtoupper($value);
    }

    /**
     * Lowercase the provided value.
     *
     * @param string $value
     * @return string
     */
    public static function lowercase(string $value): string
    {
        return strtolower($value);
    }

    /**
     * Remove all non-numeric characters.
     *
     * @param string $value
     * @return string
     */
    public static function numerify(string $value): string
    {
        return preg_replace('/[^0-9]/', '', $value);
    }
}
