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
     * Allows to enqueue lazysizes for all browsers or only for ones that are w/o native Lazy Loading support
     *
     * @return bool
     */
    public static function lazy_enqueue_lazysizes()
    {
        return apply_filters( 'innocode_wp_hybrid_lazy_loading_lazy_enqueue_lazysizes', true );
    }

    /**
     * Allows to control "lazyload" CSS class usage in browsers w/ native Lazy Loading support
     *
     * @return bool
     */
    public static function force_use_lazysizes()
    {
        return apply_filters( 'innocode_wp_hybrid_lazy_loading_force_use_lazysizes', false );
    }

    /**
     * Allows to select "loading" attribute value
     *
     * @param int $attachment_id
     *
     * @return string
     */
    public static function attachment_loading( $attachment_id )
    {
        $loading = apply_filters( 'innocode_wp_hybrid_lazy_loading_attachment_loading', 'lazy', $attachment_id );

        return in_array( $loading, [
            'auto',
            'eager',
            'lazy',
        ] ) ? $loading : 'auto';
    }
}
