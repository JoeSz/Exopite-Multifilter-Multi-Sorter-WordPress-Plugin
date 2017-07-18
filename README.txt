=== Plugin Name ===
Contributors: ujbeszel
Donate link: http://joe.szalai.org
Tags: multisort, sort, filter, miltiple filter, custom post type filter, AJAX infinite load, AJAX load more, AJAX pagination, AJAX search
Requires at least: 4.7.0
Tested up to: 4.7.3
Stable tag: 4.7.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

AJAX sorter/filter any post types by multiple taxonomies and/or terms (like post by categories and/or tags).

== Description ==

AJAX sorter/filter any post types by multiple taxonomies and/or terms (like post by categories and/or tags).
Plugin working with a basic Bootstrap 4 Flex grid. Only enqueued if 'bootstrap' or 'bootstrap-4' style not already enqueued.

The plugin working via shortcodes, does not display any admin options.
You can use multiple shortcodes on the same page/post. On posts with 'pretty' permalink, pagination not working well.

Available options
* 'post_type' ['post-type-slug'] as post type slug
* 'posts_per_page': ['number'] how many post per page per shortcode
* 'posts_per_row': ['1' - '4'] how many posts per row per shortcode
* 'display_title': ['true' or 'false'] display post title
* 'display_pagination': ['true' or 'false'] display pagination
* 'display_filter': ['true' or 'false'] display filter
* 'blog_layout' ['top', 'left', 'right', 'zigzag' or 'none']
* 'no-gap': ['true' or 'false'] hide gap between post
* 'except_lenght' ['number'] the lenght of the exceprt by words, '0' means no exceprt
* 'except_more' ['text'] excerpt more
* 'pagination': ['pagination', 'readmore' or 'infinite'] the type of the pagination
* 'multi_selectable': ['true' or 'false'] single or multiselect: true or false
* 'thumbnail-size-single-row' => ['thumbnail-size-slug'] thumbnail size for single post per row
* 'thumbnail-size-multi-row'  => ['thumbnail-size-slug'] thumbnail size for multipe post per row
* 'taxonomies_terms'          => ['category1, category2(slug|slug), tag']
* 'update_paged'              => ['true' or 'false'] Update page in browser URL bar on readmore and infinite loading based on viewport
* 'display_page_number'       => ['true' or 'false'] Show page number between loads in infinite and readmore
* 'paged'                     => ['number'], Set start page number if not already paged
* 'effect'                    => ['apollo', 'duke', 'goliath', 'julia', 'lexi', 'ming' or 'steve']
https://tympanus.net/Development/HoverEffectIdeas/index.html
https://tympanus.net/Development/HoverEffectIdeas/index2.html
* 'search'                    => ['search'] search in previously definied post type. If set, filter will be disabled.
* 'store_session'             => ['true' or 'false'] Store current session (page, filters and search). Useful if visitor is hit back or click on back button.

== Installation ==

1. Upload `exopite-multifilter` files to the `/wp-content/plugins/exopite-multifilter` directory

OR

1. Install plugin from WordPress repository (not yet)

2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place [exopite-multifilter] shortcode to your content

== Screenshots ==

1. Multiple taxomonies as filter, pagination, thumbnail only and apollo hover effect.
2. Multiple taxomonies as filter, read more loading, thumbnail with title and ming effect.
2. Multiple taxomonies as filter selected, thumbnail with title and ming effect.

== Changelog ==

= 1.0.4 - 2017-07-18 =
* Added: options to choose between additive and subtractive query.
* Added: options to randomize query.
* Added: options to display meta.
* Added: AJAX nonce.

= 1.0.3 - 2017-03-21 =
* Added: update function (for private hosting). <br />
This will be removed if plugin is submitted to WordPress repository.
https://github.com/YahnisElsts/wp-update-server


= 1.0.2 - 2017-03-05 =
* Improvement: Automatic script and style versioning for local css and js files based on file time.
https://www.doitwithwp.com/enqueue-scripts-styles-automatic-versioning/

= 1.0.1 - 2017-03-04 =
* Added: restore previous session (localstorage).
* Added: more filters.

= 1.0 =
* Initial release.
