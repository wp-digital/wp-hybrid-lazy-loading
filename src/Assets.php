<?php

namespace Innocode\WPHybridLazyLoading;

/**
 * Class Assets
 * @package WPHybridLazyLoading
 */
final class Assets
{
    /**
     * Returns assets path URL
     *
     * @param string $path
     * @return string
     */
    public static function get_url( $path )
    {
        return plugins_url( "assets/$path", INNOCODE_WP_HYBRID_LAZY_LOADING_FILE );
    }

    /**
     * Enqueue assets files
     */
    public static function enqueue_scripts()
    {
        $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

        wp_enqueue_script(
            'innocode-wp-hybrid-lazy-loading',
            static::get_url( "build/main$suffix.js" ),
            [],
            INNOCODE_WP_HYBRID_LAZY_LOADING_VERSION,
            true
        );
        wp_localize_script(
            'innocode-wp-hybrid-lazy-loading',
            'innocodeWPHybridLazyLoadingConfig',
            [
                'lazyEnqueueLazysizes' => Hooks::lazy_enqueue_lazysizes(),
                'forceUseLazysizes'    => Hooks::force_use_lazysizes(),
                'publicAssetsPath'     => static::get_url( 'build/' ),
            ]
        );
    }
}
