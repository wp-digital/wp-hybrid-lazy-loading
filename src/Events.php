<?php

namespace Innocode\WPHybridLazyLoading;

use WP_Post;

/**
 * Class Events
 *
 * @package WPHybridLazyLoading
 */
final class Events
{
    /**
     * Registers hooks
     */
    public static function register()
    {
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );

        add_filter( 'wp_get_attachment_image_attributes', [ __CLASS__, 'add_attachment_image_attributes' ], 10, 2 );
        add_filter( 'embed_oembed_html', [ __CLASS__, 'filter_embed_oembed_html' ] );
        add_filter( 'oembed_result', [ __CLASS__, 'filter_oembed_result' ] );
        add_filter( 'get_avatar', [ __CLASS__, 'filter_avatar' ] );
        add_filter( 'the_content', [ __CLASS__, 'filter_content' ] );
    }

    /**
     * Enqueue assets
     */
    public static function enqueue_scripts()
    {
        Assets::enqueue_scripts();
    }

    /**
     * Adds "loading" attribute and "lazyload" CSS class to attachment image
     *
     * @param array        $attrs
     * @param WP_Post|null $attachment
     *
     * @return array
     */
    public static function add_attachment_image_attributes( array $attrs, WP_Post $attachment = null )
    {
        if ( ! $attachment ) {
            return $attrs;
        }

        $loading = Hooks::attachment_loading( $attachment->ID );
        $attrs['loading'] = $loading;

        if ( $loading != 'lazy' ) {
            return $attrs;
        }

        $attrs['class'] .= ' lazyload';

        foreach ( [
            'src',
            'srcset',
            'sizes',
        ] as $attr ) {
            if ( isset( $attrs[ $attr ] ) ) {
                $attrs[ "data-$attr" ] = $attrs[ $attr ];
                unset( $attrs[ $attr ] );
            }
        }

        return $attrs;
    }

    /**
     * Adds "loading" attribute and "lazyload" CSS class to oEmbed iframe
     *
     * @param string $html
     * @return string
     */
    public static function filter_embed_oembed_html( $html )
    {
        return DOM::iframes( $html );
    }

    /**
     * Adds "loading" attribute and "lazyload" CSS class to oEmbed iframe
     *
     * @param string $html
     * @return string
     */
    public static function filter_oembed_result( $html )
    {
        if ( false === $html ) {
            return $html;
        }

        return DOM::iframes( $html );
    }

    /**
     * Adds "loading" attribute and "lazyload" CSS class to avatars
     *
     * @param string $html
     * @return string
     */
    public static function filter_avatar( $html )
    {
        return DOM::images( $html );
    }

    /**
     * Adds "loading" attribute and "lazyload" CSS class to images and iframes in content
     *
     * @param string $html
     * @return string
     */
    public static function filter_content( $html )
    {
        $html = DOM::images( $html );
        $html = DOM::iframes( $html );

        return $html;
    }
}
