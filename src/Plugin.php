<?php

namespace Innocode\WPHybridLazyLoading;

/**
 * Class Plugin
 *
 * @package Innocode\WPHybridLazyLoading
 */
final class Plugin
{
    /**
     * Initializes plugin
     */
    public static function init()
    {
        Events::register();
    }
}
