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
 * Version:           20180509
 * Author:            Joe Szalai
 * Author URI:        https://joe.szalai.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       exopite-multifilter
 * Domain Path:       /languages
 * Requires at least: 4.7.0
 * Tested up to:      4.9.5
 * Stable tag:        4.9.5
 */
/**
 * ToDo:
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
/**
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
define( 'EXOPITE_MULTIFILTER_PLUGIN_NAME', 'exopite-multifilter' );

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
 */
if ( is_admin() ) {

    /**
     * A custom update checker for WordPress plugins.
     *
     * Useful if you don't want to host your project
     * in the official WP repository, but would still like it to support automatic updates.
     * Despite the name, it also works with themes.
     *
     * @link http://w-shadow.com/blog/2011/06/02/automatic-updates-for-commercial-themes/
     * @link https://github.com/YahnisElsts/plugin-update-checker
     * @link https://github.com/YahnisElsts/wp-update-server
     */
    if( ! class_exists( 'Puc_v4_Factory' ) ) {

        require_once join( DIRECTORY_SEPARATOR, array( EXOPITE_MULTIFILTER_PATH, 'vendor', 'plugin-update-checker', 'plugin-update-checker.php' ) );

    }

    $MyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
        'https://update.szalai.org/?action=get_metadata&slug=' . EXOPITE_MULTIFILTER_PLUGIN_NAME, //Metadata URL.
        __FILE__, //Full path to the main plugin file.
        EXOPITE_MULTIFILTER_PLUGIN_NAME //Plugin slug. Usually it's the same as the name of the directory.
    );

}
// End Update

/**
 * CodeStar Widget
 */
// require plugin_dir_path( __FILE__ ) . 'widgets/class-exopite-multifilter-cs-widget.php';

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
$exopite_multifilter = new Exopite_Multifilter();
$exopite_multifilter->run();
