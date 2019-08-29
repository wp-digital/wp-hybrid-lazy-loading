<?php

namespace Innocode\WPHybridLazyLoading;

/**
 * Class DOM
 * @package Innocode\WPHybridLazyLoading
 */
final class DOM
{
    /**
     * @param string $tag
     * @param string $html
     * @return string
     */
    public static function elements( $tag, $html )
    {
        if ( $html === '' ) {
            return $html;
        }

        // <script /> may contains html template with tags
        $scripts_regex = '/<script[^>]*>.+?<\/script>/si';
        $scripts = '';

        if ( preg_match_all( $scripts_regex, $html, $scripts_matches, PREG_PATTERN_ORDER ) ) {
            foreach ( $scripts_matches[ 0 ] as $script ) {
                $html = str_replace( $script, '', $html );
                $scripts .= $script;
            }
        }

        $document = new \DOMDocument();
        $converted_html = mb_convert_encoding( $html, 'HTML-ENTITIES', 'UTF-8' );
        // LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD have a problems in case when there is no general wrapper in html
        $document->loadHTML(
            utf8_decode( $converted_html ),
            LIBXML_BIGLINES |
            LIBXML_COMPACT |
            LIBXML_NOERROR |
            LIBXML_NOWARNING |
            LIBXML_PARSEHUGE
        );
        $elements = $document->getElementsByTagName( $tag );
        $elements = iterator_to_array( $elements );

        /**
         * @var \DOMElement $element
         */
        foreach ( $elements as $element ) {
            $loading = 'lazy';

            if ( $element->hasAttribute( 'class' ) ) {
                $value = $element->getAttribute( 'class' );
                $classes = explode( ' ', trim( $value ) );

                if ( false !== ( $attachment_id = static::find_attachment_id( $classes ) ) ) {
                    $loading = Hooks::attachment_loading( $attachment_id );
                }

                if ( $loading == 'lazy' && false === array_search( 'lazyload', $classes ) ) {
                    $classes[] = 'lazyload';
                    $value = implode( ' ', $classes );
                    $element->setAttribute( 'class', $value );
                }
            } else {
                $element->setAttribute( 'class', 'lazyload' );
            }

            $element->setAttribute( 'loading', $loading );

            if ( $loading == 'lazy' ) {
                foreach ( [
                    'src',
                    'srcset',
                    'sizes',
                ] as $attr ) {
                    if ( $element->hasAttribute( $attr ) ) {
                        $value = $element->getAttribute( $attr );
                        $element->setAttribute( "data-$attr", $value );
                        $element->removeAttribute( $attr );
                    }
                }
            }
        }

        $html = '';

        foreach ( [ 'head', 'body' ] as $tag ) {
            $container = $document->getElementsByTagName( $tag );

            if ( $container->length ) {
                $elements = $container->item( 0 )->childNodes;

                foreach ( $elements as $node ) {
                    $html .= $document->saveHTML( $node );
                }
            }
        }

        $html .= $scripts;

        return $html;
    }

    /**
     * @param string $html
     * @return string
     */
    public static function iframes( $html )
    {
        return static::elements( 'iframe', $html );
    }

    /**
     * @param string $html
     * @return string
     */
    public static function images( $html )
    {
        return static::elements( 'img', $html );
    }

    /**
     * @param string $html
     *
     * @return int|false
     */
    public static function retrieve_attachment_id( $html )
    {
        if ( ! preg_match( '/wp-image-([0-9]+)/i', $html, $class_id ) ) {
            return false;
        }

        return absint( $class_id[1] );
    }

    /**
     * @param array $classes
     *
     * @return int|false
     */
    public static function find_attachment_id( array $classes )
    {
        foreach ( $classes as $class ) {
            if ( false !== ( $attachment_id = static::retrieve_attachment_id( $class ) ) ) {
                return $attachment_id;
            }
        }

        return false;
    }
}
