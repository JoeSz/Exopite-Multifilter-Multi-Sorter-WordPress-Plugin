<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://joe.szalai.org
 * @since             1.0.0
 * @package           Exopite_Multifilter
 *
 * @wordpress-plugin
 * Plugin Name:       Exopite multi-selectable AJAX sorter for any post types
 * Plugin URI:        https://joe.szalai.org/exopite/multifilter
 * Description:       Multi-selectable AJAX sorter for any post types. Working with taxonomies and terms as filters (e.g.: for post: categories and tags).
 * Version:           20171004
 * Author:            Joe Szalai
 * Author URI:        https://joe.szalai.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       exopite-multifilter
 * Domain Path:       /languages
 */
/**
 * ToDo:
 *
 * - select category via links? http://www.site.ext/?multifilter=filtertoselect  ?
 * - macy.js for masonry layout (https://github.com/bigbitecreative/macy.js/blob/master/src/macy.js)
 * - equal height with read more in bottom (hr4you)
 * - more hooks (eg, query)
 *
 * - on mobile 6 page number is too much -> how should be displayed?
 * - add widget (normal + VC)?
 *
 * - random maybe with pagination (im not sure, this make sence here)
 *   https://hugh.blog/2013/08/22/wordpress-random-post-order-with-correct-pagination/
 *
 * - Widget
 *   ( https://github.com/Codestar/codestar-framework/issues/51 ?
 *     https://github.com/Codestar/codestar-framework/issues/71
 *     https://github.com/Codestar/codestar-framework/issues/132
 *     https://github.com/Codestar/codestar-framework/issues/169
 *     https://github.com/Codestar/codestar-framework/pull/357 )
 *
 * - Interesting:
 *   - JSON in shortcode
 *     https://www.quora.com/Is-it-possible-to-pass-an-associative-array-with-a-shortcode-attribute
 *   - Working with Shortcodes
 *     https://www.sitepoint.com/unleash-the-power-of-the-wordpress-shortcode-api/
 */
/*
 * ToDo Widget:
 * - Visual Composer is active -> add VC widget
 * - PageBuilder actvie && CodeStar framework active -> add WP widget
 *
 * - WIDGET fields:
 *  - post_type -> all available post types including post and page
 *  - posts_per_page 1-100 or all (-1)
 *  - posts_per_row 1-4
 *  - display_title on/off true/false
 *  - display_pagination on/off true/false
 *  - display_filter on/off true/false
 *  - blog_layout top/zigzag/left/right
 *  - no-gap on/off true/false
 *  - except_lenght 0-100 or full
 *  - except_more text
 *  - pagination pagination/readmore/infinite
 *  - multi_selectable on/off true/false
 *  - thumbnail-size-single-row -> list all thumbnail size
 *  - thumbnail-size-multi-row -> list all thumbnail size
 *  - taxonomies_terms (list all taxonomy terms for the post type, but add multiple)
 *
*/
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'EXOPITE_MULTIFILTER_URL', plugin_dir_url( __FILE__ ) );
define( 'EXOPITE_MULTIFILTER_PATH', plugin_dir_path( __FILE__ ) );

if ( ! class_exists( 'ExopiteSettings' ) ) {
    class ExopiteSettings {

        public static $options = array();

        static public function setValue($key, $value) {
            ExopiteSettings::$options[$key] = $value;
        }

        static public function getValue($key) {
            if ( isset( ExopiteSettings::$options[$key] ) ) {
                return ExopiteSettings::$options[$key];
            } else {
                return null;
            }

        }

        static public function deleteValue($key) {
            unset( ExopiteSettings::$options[$key] );
        }

        static public function checkValue($key) {
            if ( array_key_exists( $key, ExopiteSettings::$options ) ) {
                return true;
            } else {
                return false;
            }
        }

    }
}

/*
 * Update
 * Required Exopite-Core plugin
 */
if ( class_exists( 'Puc_v4_Factory' ) ) {
    $MyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
        'http://update.szalai.org/?action=get_metadata&slug=exopite-multifilter', //Metadata URL.
        __FILE__, //Full path to the main plugin file.
        'exopite-multifilter' //Plugin slug. Usually it's the same as the name of the directory.
    );
}
// End Update

/**
 * CodeStar Widget
 */
require plugin_dir_path( __FILE__ ) . 'widgets/class-exopite-multifilter-cs-widget.php';

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-exopite-multifilter.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_exopite_multifilter() {

	$plugin = new Exopite_Multifilter();
	$plugin->run();

}
run_exopite_multifilter();
