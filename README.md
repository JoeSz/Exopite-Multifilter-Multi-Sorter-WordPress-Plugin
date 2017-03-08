# EXOPITE-MULTIFILTER WORDPRESS PLUGIN
Sort/filter any post types by multiple taxonomies and/or terms (like post by categories and/or tags).

DESCRIPTION
-----------

Sort/filter any post types by multiple taxonomies and/or terms (like post by categories and/or tags).
Plugin working with a basic Bootstrap 4 Flex grid. Only enqueued if 'bootstrap' or 'bootstrap-4' style not already enqueued.

USAGE
-----

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
* 'taxonomies_terms'          => ['category, category(slug|slug)']
* 'update_paged'              => ['true' or 'false'] Update page in browser URL bar on readmore and infinite loading based on viewport
* 'display_page_number'       => ['true' or 'false'] Show page number between loads in infinite and readmore
* 'paged'                     => ['number'], Set start page number if not already paged
* 'effect'                    => ['apollo', 'duke', 'goliath', 'julia', 'lexi', 'ming' or 'steve']
https://tympanus.net/Development/HoverEffectIdeas/index.html
https://tympanus.net/Development/HoverEffectIdeas/index2.html
* 'search'                    => ['search'] if set, filter will be disabled
* 'store_session'             => ['true' or 'false'] Store current session (page, filters and search). Useful if visitor is hit back or click on back button.

INSTALLATION
------------

1. Upload `exopite-multifilter.php` to the `/wp-content/plugins/` directory

OR

1. Install plugin from WordPress repository (not yet)

2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place [exopite-multifilter] shortcode to your content

PLANNED
-------

* On mobile 6 page number is too much -> how should be displayed?
* Add widget (normal + VC)
* Display post meta
* Working with comments too
* AJAX nounce
* Random posts
* Add to WordPress repo.

CHANGELOG
---------

= 1.0.2 - 2017-03-05 =
* Improvement: Automatic script and style versioning for local css and js files based on file time.
https://www.doitwithwp.com/enqueue-scripts-styles-automatic-versioning/

= 1.0.1 - 2017-03-04 =
* Add: restore previous session (localstorage)
* Add: more filters

= 1.0 =
* Initial release.

LICENSE DETAILS
---------------
The GPL license of Exopite Multifilter grants you the right to use, study, share (copy), modify and (re)distribute the software, as long as these license terms are retained.

DISCLAMER
---------

NO WARRANTY OF ANY KIND! USE THIS SOFTWARES AND INFORMATIONS AT YOUR OWN RISK! READ DISCLAMER.TXT!
License: GNU General Public License v3
