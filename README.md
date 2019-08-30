# Hybrid Lazy Loading

### Description

A Progressive Migration To Native Lazy Loading.

The idea of plugin is to use [Native Lazy Loading](https://web.dev/native-lazy-loading) when
browser [supports](https://caniuse.com/#search=lazy%20loading) it and for all others implements 
lazy loading through [lazysizes](https://github.com/aFarkas/lazysizes) library that loads only
when needed.

Plugin adds **loading** attribute for all attachments, oEmbed iframes, images and iframes that 
are inserted to content through editor, also CSS class **lazyload** adds to those elements as well
as **data-src**, **data-srcset** and **data-sizes** are created from corresponding attributes.

## Installation

Clone this repo to `wp-content/plugins/`:

````
cd wp-content/plugins/
git clone git@github.com:innocode-digital/wp-hybrid-lazy-loading.git
````

or use [Composer](https://getcomposer.org/) for that.

Activate **Hybrid Lazy Loading** from Plugins page 
or [WP-CLI](https://make.wordpress.org/cli/handbook/): `wp plugin activate wp-hybrid-lazy-loading`.

## Configuration

By default plugin loads [lazysizes](https://github.com/aFarkas/lazysizes) lazily, which means only 
for ones that are w/o [Native Lazy Loading](https://web.dev/native-lazy-loading) support but it's 
possible to change this behaviour with filter: 

```
add_filter( 'innocode_wp_hybrid_lazy_loading_lazy_enqueue_lazysizes', '__return_false' ); // Default is "true"
```

It makes sense when you e.g. use [lazysizes](https://github.com/aFarkas/lazysizes) also for other
functionality.

---

By default CSS class "lazyload" removes from elements in browsers with 
[Native Lazy Loading](https://web.dev/native-lazy-loading) support but it's possible to change 
this behaviour with filter: 

```
add_filter( 'innocode_wp_hybrid_lazy_loading_force_use_lazysizes', '__return_true' ); // Default is "false"
```

It makes sense when you e.g. want to use 
[lazysizes CSS classes](https://github.com/aFarkas/lazysizes#css-api) that are adding to element 
during loading process in all browsers.

---

By default all attachments are loading lazily but it's possible to change this behaviour with 
filter:

```
add_filter( 'innocode_wp_hybrid_lazy_loading_attachment_loading', function ( $type, $attachment_id ) {
    $type = 'eager'; // Default is "lazy"
    
    return $type;
}, 10, 2 );
```

It makes sense when you e.g. want to load featured image immediately. 
