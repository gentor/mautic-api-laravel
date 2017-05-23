<?php

namespace Gentor\Mautic\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Gentor\Mautic\MauticService
 */
class Mautic extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mautic';
    }
}
