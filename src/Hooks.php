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
     * @param string $name
     *
     * @return string
     */
    public static function tag( $name )
    {
        return "innocode_wp_hybrid_lazy_loading_$name";
    }

    /**
     * @return bool
     */
    public static function lazy_enqueue_lazysizes()
    {
        return apply_filters( static::tag( 'lazy_enqueue_lazysizes' ), true );
    }

    /**
     * @return bool
     */
    public static function force_use_lazysizes()
    {
        return apply_filters( static::tag( 'force_use_lazysizes' ), false );
    }

    /**
     * @param int $attachment_id
     *
     * @return string
     */
    public static function attachment_loading( $attachment_id )
    {
        $loading = apply_filters( static::tag( 'attachment_loading' ), 'lazy', $attachment_id );

        return in_array( $loading, [
            'auto',
            'eager',
            'lazy',
        ] ) ? $loading : 'auto';
    }
}
