<?php

namespace Innocode\WPHybridLazyLoading;

/**
 * Class Hooks
 *
 * @package Innocode\WPHybridLazyLoading
 */
final class Hooks
{
    /**
     * Checks if lazysizes library should lazily
     *
     * @return bool
     */
    public static function lazy_enqueue_lazysizes()
    {
        /**
         * Allows to enqueue lazysizes for all browsers or only for ones that are w/o Native Lazy Loading support
         *
         * @param bool $lazy_enqueue_lazysizes
         */
        return apply_filters( 'innocode_wp_hybrid_lazy_loading_lazy_enqueue_lazysizes', true );
    }

    /**
     * Checks if lazysizes library should loads in all browsers
     *
     * @return bool
     */
    public static function force_use_lazysizes()
    {
        /**
         * Allows to control "lazyload" CSS class usage in browsers w/ native Lazy Loading support
         *
         * @param bool $force_use_lazysizes
         */
        return apply_filters( 'innocode_wp_hybrid_lazy_loading_force_use_lazysizes', false );
    }

    /**
     * Returns attachment "loading" attribute
     *
     * @param int $attachment_id
     *
     * @return string
     */
    public static function attachment_loading( $attachment_id )
    {
        /**
         * Allows to select "loading" attribute value
         *
         * @param string $type          Value of "loading" attribute. Possible values: "auto", "lazy" or "eager"
         * @param int    $attachment_id Attachment ID.
         */
        $loading = apply_filters( 'innocode_wp_hybrid_lazy_loading_attachment_loading', 'lazy', $attachment_id );

        return in_array( $loading, [
            'auto',
            'eager',
            'lazy',
        ] ) ? $loading : 'auto';
    }
}
