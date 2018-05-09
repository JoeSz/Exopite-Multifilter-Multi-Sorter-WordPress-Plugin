<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://joe.szalai.org
 * @since      1.0.0
 *
 * @package    Exopite_Multifilter
 * @subpackage Exopite_Multifilter/public
 */
/*
 * ToDo:
 * -
 $q = new WP_Query( array(
    'meta_query' => array(
        'relation' => 'AND',
        'state_clause' => array(
            'key' => 'state',
            'value' => 'Wisconsin',
        ),
        'city_clause' => array(
            'key' => 'city',
            'compare' => 'EXISTS',
        ),
    ),
    'orderby' => array(
        'city_clause' => 'ASC',
        'state_clause' => 'DESC',
    ),
) );
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Exopite_Multifilter
 * @subpackage Exopite_Multifilter/public
 * @author     Joe Szalai <joe@szalai.org>
 */
class Exopite_Multifilter_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

    private $development;
    private $masonry_type;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

        // MASONRY
        $this->development = false;

        // Load masonry versions
        $version = ( $this->development ) ? '' : 'min.';
        $this->masonry_type = array(
            'waterfall-kudago' => 'jquery.waterfall.' . $version . 'js',
            'masonry-desandro' => 'masonry.pkgd.' . $version . 'js',
        );
        // END MASONRY

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

        $version = ( $this->development ) ? 'dev' : 'min';

        // If Exopite Code plugin not inastalled and activated get Bootstrap 4 grid only
        if ( ! defined( EXOPITE_CORE_URL ) ) {
            if ( ! wp_style_is( 'bootstrap' ) && ! wp_style_is( 'bootstrap-41' ) ) {

                /*
                 * Enqueue scripts and styles with automatic versioning
                 *
                 * https://www.doitwithwp.com/enqueue-scripts-styles-automatic-versioning/
                 */
                $bootstrap_css_url  = plugin_dir_url( __FILE__ ) . 'css/bootstrap4-grid-light.' . $version . '.css';
                $bootstrap_css_path = EXOPITE_MULTIFILTER_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'bootstrap4-grid-light.' . $version . '.css';
                wp_enqueue_style( 'bootstrap-light', $bootstrap_css_url, array(), filemtime( $bootstrap_css_path ), 'all' );
            }
        }

        /*
         * Slick carousel
         */
        // Register styles for carousel
        $slick_css_url  = plugin_dir_url( __FILE__ ) . 'css/slick.' . $version . '.css';
        $slick_css_path = EXOPITE_MULTIFILTER_PATH . 'public' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'slick.' . $version . '.css';
        wp_register_style( 'slick', $slick_css_url, array(), filemtime( $slick_css_path ) );

        // Theme style for slick (carousel)
        $slick_theme_css_url  = plugin_dir_url( __FILE__ ) . 'css/slick-theme.' . $version . '.css';
        $slick_theme_css_path = EXOPITE_MULTIFILTER_PATH .  'public' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'slick-theme.' . $version . '.css';
        wp_register_style( 'slick-theme', $slick_theme_css_url, array(), filemtime( $slick_theme_css_path ) );

        /*
         * Plugin styles
         */
        // Register plugin main styles
        $public_css_url  = plugin_dir_url( __FILE__ ) . 'css/exopite-multifilter-public.' . $version . '.css';
        $public_css_path = EXOPITE_MULTIFILTER_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'exopite-multifilter-public.' . $version . '.css';
        wp_register_style( $this->plugin_name, $public_css_url, array(), filemtime( $public_css_path ) );

        // Register hover effets
        $public_effect_css_url  = plugin_dir_url( __FILE__ ) . 'css/effects.' . $version . '.css';
        $public_effect_css_path = EXOPITE_MULTIFILTER_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'effects.' . $version . '.css';
        wp_register_style( 'exopite-effects', $public_effect_css_url, array(), filemtime( $public_effect_css_path ) );

	}
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

        $version = ( $this->development ) ? 'dev' : 'min';

		// If Exopite Code plugin not inastalled and activated get core JavaScript functions
        if ( ! defined( EXOPITE_CORE_URL ) || ! wp_script_is( 'exopite-core-js' ) ) {

            // https://www.doitwithwp.com/enqueue-scripts-styles-automatic-versioning/
            $core_js_url  = plugin_dir_url( __FILE__ ) . 'js/exopite-core.' . $version . '.js';
            $core_js_path = plugin_dir_path( __FILE__ ) . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'exopite-core.' . $version . '.js';

            // Exopite core scripts (debounce, throttle, filter & action hooks)
            wp_enqueue_script( 'exopite-core-js', $core_js_url, array(), filemtime( $core_js_path ), true );

        }

        // Register JavaScripts for masonry
        foreach ( $this->masonry_type as $script_name => $file_name ) {

            if ( ! wp_script_is( $script_name, 'registered' ) ) {

                $js_url  = plugin_dir_url( __FILE__ ) . 'js/' . $file_name;
                $js_path = plugin_dir_path( __FILE__ ) . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . $file_name;

                wp_register_script( $script_name, $js_url, array( 'jquery' ), filemtime( $js_path), true );

            }

        }

        // Slick for carousel style
        $slick_js_url  = plugin_dir_url( __FILE__ ) . 'js/slick.' . $version . '.js';
        $slick_js_path = plugin_dir_path( __FILE__ ) . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'slick.' . $version . '.js';
        wp_register_script( 'slick', $slick_js_url, array( 'jquery' ), filemtime( $slick_js_path), true );

        // if plugin main Javascript file is not yes registered, then register it
        if ( ! wp_script_is( $this->plugin_name, 'registered' ) ) {

            $public_js_url  = plugin_dir_url( __FILE__ ) . 'js/exopite-multifilter-public.' . $version . '.js';
            $public_js_path = plugin_dir_path( __FILE__ ) . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'exopite-multifilter-public.' . $version . '.js';

            wp_register_script( $this->plugin_name, $public_js_url, array( 'jquery' ), filemtime( $public_js_path ), true );

        }

        /**
         *  In backend there is global ajaxurl variable defined by WordPress itself.
         *
         * This variable is not created by WP in frontend. It means that if you want to use AJAX calls in frontend, then you have to define such variable by yourself.
         * Good way to do this is to use wp_localize_script.
         *
         * @link http://wordpress.stackexchange.com/a/190299/90212
         */
        wp_localize_script( $this->plugin_name, 'wp_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	}

    /**
     * Get all the registered image sizes along with their dimensions
     *
     * @global array $_wp_additional_image_sizes
     *
     * @link http://core.trac.wordpress.org/ticket/18947 Reference ticket
     * @return array $image_sizes The image sizes
     */
    function _get_all_image_sizes() {
        global $_wp_additional_image_sizes;

        $default_image_sizes = array( 'thumbnail', 'medium', 'medium_large', 'large' );

        foreach ( $default_image_sizes as $size ) {
            $image_sizes[ $size ][ 'width' ] = intval( get_option( "{$size}_size_w" ) );
            $image_sizes[ $size ][ 'height' ] = intval( get_option( "{$size}_size_h" ) );
            $image_sizes[ $size ][ 'crop' ] = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
        }

        if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
            $image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
        }

        return $image_sizes;
    }

    function get_thumbnail( $post_id, $blog_layout = 'top', $thumbnail_size = 'medium', $args, $link_url = '', $target = '' ) {

        $post_password_required = post_password_required();

        $thumbnail_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $thumbnail_size );
        $url = $thumbnail_url['0'];

        $class = '';

        if ( $blog_layout == ( 'left' || 'right' ) ) {
           $class = ' image-' . $blog_layout;
        }

        $effect = ( $args['effect'] != 'none' ) ? ' effect-' . $args['effect'] : '';
        $effect .= ( $post_password_required ) ? ' image-protected' : '';

        $ret = '';

        if ( empty( $url ) ) {

            $image_sizes = $this->_get_all_image_sizes();
            $image_sizes_w = ( $image_sizes[$thumbnail_size]['width'] < 100 ) ? 200 : $image_sizes[$thumbnail_size]['width'];
            $image_sizes_h = ( $image_sizes[$thumbnail_size]['height'] < 100 ) ? 200 : $image_sizes[$thumbnail_size]['height'];

            $url = apply_filters( 'exopite-multifilter-placeholder-image', 'https://dummyimage.com/' . $image_sizes_w . 'x' . $image_sizes_h . '/cccccc/fff.gif' );

            // $url = apply_filters( 'exopite-multifilter-placeholder-image', 'http://lorempixel.com/' . $image_sizes[$thumbnail_size]['width'] . '/' . $image_sizes[$thumbnail_size]['height'] . '/technics/' );
        }

        $ret .= '<div class="entry-thumbnail-container clearfix' . $class . '">';

        if ( $args['gallery_mode'] ) {

            $attachement_link = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' )[0];
            if ( $attachement_link ) $link_url = $attachement_link;

        }

        $video_url = ( ! empty( $args['video'] ) ) ? get_post_meta( $post_id, esc_attr( $args['video'] ), true ) : '';

        if ( empty( $args['video'] ) || empty( $video_url ) ) $ret .= '<a href="' . $link_url . '"' . $target . '>';

        $ret .= '<figure class="effect-multifilter' . $effect . ' entry-thumbnail">'; //for animation

        // $ret .= ( $post_password_required ) ? '' : '<img src="' . $url . '" alt="thumbnail">';

        // If post required password do not display thumbnail
        if ( $post_password_required ) {

            $ret .= '';

        } else {

            /*
             * If shortcode has video
             * - get url from meta, id: $args['video']
             * - add thumbnail as poster
             * - if video url missing, display post thumbnail
             */
            if ( ! empty( $args['video'] && ! empty( $video_url ) ) ) {

                $ret .= '<video class="multifilter-video" ' . $args['video-args'] . ' poster="' . $url . '" src="' . $video_url . '"></video>';

            } else {
                /*
                 * If no video
                 * - display thumbnail
                 * - display overlay effect on demand
                 * - display metas on demand
                 */

                $ret .= '<img src="' . $url . '" alt="thumbnail">';

                $ret .= '<figcaption>';
                $ret .= '<div class="figure-caption animation">';

                if ( $args['effect'] != '' && $args['effect'] != 'none' ) {
                    $ret .= '<div class="figure-caption-title">';
                    $ret .= get_the_title( $post_id );
                    $ret .= '</div>';
                }

                if ( ( ! ( $args['display_title'] && $args['except_lenght'] == 0 ) ) && ! $post_password_required && count( $args['display_metas'] ) > 0 ) {
                    $ret .= '<div class="figure-caption-meta">';
                    // $ret .= $this->display_metas( $args, $post_id );
                    $ret .= strip_tags( $this->display_metas( $args, $post_id ), '<li><ul><i>' );
                    $ret .= '</div>';
                }

                $ret .= '</div>';
                $ret .= '</figcaption>';

            }

        }


        $ret .= '</figure>';
        if ( empty( $args['video'] ) || empty( $video_url ) )  $ret .= '</a>';

        $ret .= '</div>';

        return $ret;

    }

    /**
     * in_array() on multidimensional arrays
     *
     * @link http://stackoverflow.com/questions/4128323/in-array-and-multidimensional-array
     * @param  string $item  needle to find
     * @param  array  $array haystack
     * @return boolean
     */
    function in_array_r($item , $array){
        return preg_match('/"'.$item.'"/i' , json_encode($array));
    }

    /**
     * Display selected taxonomies
     */
    function get_filters( $selected_post_type, $selected_taxonomies, $is_multifilter ) {

        $ret = '';

        $taxonomies = get_object_taxonomies( $selected_post_type, 'object' );

        foreach( $taxonomies as $taxonomy ){

            if ( ! $this->in_array_r( $taxonomy->name, $selected_taxonomies ) ) continue;

            $terms = get_terms( array(
                'taxonomy' => $taxonomy->name,
                'hide_empty' => true,
            ) );

            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                $ret .= '<div class="exopite-multifilter-filter-taxonomy exopite-multifilter-filter-taxonomy-' . $taxonomy->name . '" data-post-type="' . $selected_post_type . '" data-multiselect="' . $is_multifilter . '" data-taxonomy="' . $taxonomy->name . '">';
                foreach ( $terms as $term ) {
                    $ret .= '<span class="exopite-multifilter-filter-item exopite-multifilter-filter-item-' . $term->slug . '" data-term="' . $term->slug . '">' . $term->name . '</span>';
                }
                $ret .= '</div>';
            }

        }

        return $ret;

    }

    function excerpt_length() {
        return ExopiteSettings::getValue('exopite_multifilter_except_lenght');
    }

    // create pagination link
    function get_pagination( $page_id, $max_num_page, $current_page, $pagination, $format = 'page' ) {
        if( $max_num_page <= 1 ) return '';

        $ret = '';

        if ( is_numeric( $page_id ) ) {
            $base_url = get_the_permalink( $page_id );
        } else {
            $base_url = $page_id;
        }

        if ( $pagination != 'pagination' ) {
            $next_url = $base_url . $format . '/' . ( $current_page + 1 ) . '/';
        }

        switch ( $pagination ) {
            case 'readmore':
                if ( $current_page < $max_num_page ) {
                    $ret .='<a href="' . $next_url . '" class="btn btn-material btn-readmore next">' . esc_attr__( 'Read more', 'exopite-multifilter' ) . '</a>';
                } else {
                    $ret .= '<span class="nothing-more">' . esc_attr__( 'Nothing more to load.', 'exopite-multifilter' ) . '</span>';
                }
                break;

            case 'infinite':
                $ret .= '<span class="next" data-next="' . $next_url . '"></span>';
                break;

            default:
                // pagination
                $args = array(
                    'base'      => $base_url . '%_%',
                    'format'    => $format . '/%#%/',
                    'current'   => max( 1, $current_page ),
                    'total'     => $max_num_page,
                    'prev_text' => esc_attr__('&lsaquo;', 'exopite-multifilter' ),
                    'next_text' => esc_attr__('&rsaquo;', 'exopite-multifilter' ),
                    'show_all'  => false,
                    'end_size'  => 1,
                    'mid_size'  => 2,
                    'type'               => 'array',
                );

                if( isset($_GET['s']) ){
                    $args['add_args'] = array(
                        's' => $_GET['s'] // your search query passed via your ajax function
                    );
                }

                $ret .= join( paginate_links( $args ) );
                break;
        }

        return '<div class="col-12">' . $ret . '</div>';

    }

    function display_metas( $args, $post_id, $no_link = false ) {

        if ( ! is_array( $args['display_metas'] ) ) return;

        $sticky = $author = $date = $commentcount = $lastmodified = '';

        // If the post is sticky, mark it.
        if ( is_sticky() ) {
            $sticky ='<li class="meta-featured-post"><i class="fa fa-thumb-tack"></i> ' . esc_attr__( 'Sticky', 'exopite' ) . ' </li>';
        }

        // Get the post author.
        if ( in_array( 'author', $args['display_metas'] ) ) {
            $author_name = ( $no_link ) ? '%2$s' : '<a href="%1$s" rel="author">%2$s</a></li>';
            $author = sprintf(
                '<li class="exopite-multifilter-meta-author">By ' . $author_name . '</li>',
                esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
                get_the_author()
            );
        }

        // Get the date.
        if ( in_array( 'date', $args['display_metas'] ) ) {
            $date_link = ( $no_link ) ? get_the_date() : '<a href="' . esc_url( get_site_url() ) . '/' . get_the_date( 'Y' ) . '/' . get_the_date( 'm' ) . '/' . get_the_date( 'd' ) . '" rel="date">' . get_the_date() . '</a>';
            $date = '<li class="exopite-multifilter-meta-date"> ' . $date_link . ' </li>';
        }

        // Get last modification date.
        // https://andorwp.com/how-to-display-last-update-date-for-posts-and-pages-in-wordpress/
        if ( is_array( $args['display_metas'] ) && in_array( 'last-modified', $args['display_metas'] ) ) {
            $u_time = get_the_time('U');
            $u_modified_time = get_the_modified_time('U');

            if ( $u_modified_time >= $u_time + 86400 ) {
                $lastmodified = '<li class="meta-last-modified"><time datetime="' . get_the_modified_time('Y-m-d') . '">';
                $lastmodified .= esc_attr__( ' Last modified on ', 'exopite-multifilter' );
                $lastmodified .= get_the_modified_time( get_option( 'date_format' ) );
                $lastmodified .= '</time></li>';
            }
        }

        if ( in_array( 'taxonomy', $args['display_metas'] ) ) {

            if ( ! empty( $args['display_metas_taxonomies'] ) ) {

                $taxonomies = '';

                foreach ( $args['display_metas_taxonomies'] as $display_metas_taxonomy ) {

                    $terms = get_the_term_list( $post_id, $display_metas_taxonomy, '', ', ', '' );
                    if ( $no_link ) {
                        $terms = strip_tags( $terms  );
                    }

                    $taxonomies .= ( ! empty( $terms ) ) ? '<span class="exopite-multifilter-meta-taxonomy-' . $display_metas_taxonomy . '">' . $terms . '</span> ' : '';

                }

                $taxonomies = ( !empty( $taxonomies ) ) ? '<li class="exopite-multifilter-meta-taxonomies">' . $taxonomies .  '</li>' : '';

            }

        }

        // Comments link.
        if ( in_array( 'commentcount', $args['display_metas'] ) ) {
            if ( comments_open() ) {
            //if ( comments_open() && ( is_single() || have_comments() ) ) :
                $commentcount = '<li class="meta-reply">';
                ob_start();
                if ( is_single() ) {
                    comments_popup_link(
                        esc_attr__( 'Let us know what you think!', 'exopite-multifilter' ),
                        esc_attr__( 'One comment so far', 'exopite-multifilter' ),
                        esc_attr__( 'View all % comments', 'exopite-multifilter' )
                    );
                } else {
                    comments_popup_link(
                        '<i class="fa fa-comment" aria-hidden="true"></i>',
                        '<i class="fa fa-comment" aria-hidden="true"></i> 1',
                        '<i class="fa fa-comment" aria-hidden="true"></i> %'
                    );
                }

                $commentcount .= ob_get_clean();
                $commentcount .= '</li>';
            }
        }

        $ret = '<ul class="list-inline entry-meta">';

        foreach ( $args['display_metas'] as $key ) {

            switch ( $key ) {
                case 'author':
                    $ret .= $author;
                    break;
                case 'date':
                    $ret .= $date;
                    break;
                case 'taxonomy':
                    $ret .= $taxonomies;
                    break;
                case 'last-modified':
                    $ret .= $lastmodified;
                    break;
                case 'commentcount':
                    $ret .= $commentcount;
                    break;

            }
        }

        $ret .= '</ul>';

        return $ret;

    }

    function excerpt( $excerpt ) {

        return '<div class="entry-excerpt">' . $excerpt . '</div>';

    }

    public function check_date( $date ) {

        if ( ! empty( $date ) && preg_match( "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date ) ) {
            return true;
        }

        return false;

    }

    function get_articles( $args ) {

        // WP_Query: http://www.billerickson.net/code/wp_query-arguments/
        // Set post type
        $args['query'] = array(
            'post_status'   => 'publish',
            's'             => $args['search'],
        );

        if ( ! empty( $args['post_not_in'] ) ) {
            $args['query']['post__not_in'] = explode( ',', preg_replace( '/\s+/', '', $args['post_not_in'] ) );
        }

        if ( ! empty( $args['post_in'] ) ) {

            $args['post_in'] = explode( ',', preg_replace( '/\s+/', '', $args['post_in'] ) );

        } else {

            $args['post_in'] = array();
            $args['query']['post_type'] = $args['post_type'];

        }

        $ret = '';

        // If random, disable pagination
        if ( $args['random'] ) {
            $args['pagination'] = 'none';
            $args['query']['orderby'] = 'rand';
        }

        if ( $this->check_date( $args['date_to'] ) || $this->check_date( $args['date_from'] ) ) {

            $date_query = array();

            if ( $this->check_date( $args['date_from'] ) ) $date_query += array( 'after' => $args['date_from'] );
            if ( $this->check_date( $args['date_to'] ) ) $date_query += array( 'before' => $args['date_to'] );

            $args['query']['date_query'] = $date_query;

        }

        /*
         * OR -> match all taxonomy queries (subtractive query),
         * AND -> posts which match at least one taxonomy query (additive query).
         *        $args['query']['tax_query']['relation'] = 'AND';
         */
        if ( ! empty( $args['taxonomies_terms'] ) ) {

            $args['query']['tax_query']['relation'] = ( $args['in_all_taxnomies'] ) ? 'AND' : 'OR';

            foreach ( $args['taxonomies_terms'] as $taxonomy => $terms ) {

                // Set taxonomies and terms
                if ( is_array( $terms ) && ! empty( $terms ) ) {
                    foreach ( $terms as $term ) {
                        $args['query']['tax_query'][] = array(
                            'taxonomy' => $taxonomy,
                            'terms' => $term,
                            'include_children' => true,
                            'field' => 'slug',
                        );
                    }
                }

            }

        }

        if ( ! empty( $args['meta_key'] ) ) $args['query']['meta_key'] = esc_attr( $args['meta_key'] );
        if ( ! empty( $args['meta_value'] ) ) $args['query']['meta_value'] = esc_attr( $args['meta_value'] );

        // Set search if not random
        if ( isset( $args['search'] ) && ! $args['random'] ) {
            $args['query']['s'] = $args['search'];
        }

        // Pagination
        if ( ! $args['random'] ) {
            $args['query']['paged'] = $args['paged'];
        }
        $args['query']['posts_per_page'] = $args['posts_per_page'];

        // START Deal with archives
        if ( $args['archive_mode'] !== false && is_array( $args['archive_mode'] ) ) {
            // If only one
            // $key = array_keys( $args['archive_mode'] )[0];
            // $args['query'][$key] = $args['archive_mode'][$key];
            foreach ( $args['archive_mode'] as $key => $value ) {
                $args['query'][$key] = $value;
            }
        }
        // END Deal with archives

        if ( empty( $args['post_in'] ) ) {

            // START Deal with sticky posts
            // The idea here is, include sticky posts, so they does not exceeds the post per page amount
            $include_sticky = true;
            $args['query']['ignore_sticky_posts'] = 1;

            if ( ! $args['random'] && $include_sticky && $args['query']['post_type'] == 'post' ) {

                // http://www.kriesi.at/support/topic/sticky-posts-in-b-og-grid/
                $sticky = get_option( 'sticky_posts' );
                $sticky_args = $args['query'];
                $sticky_args['post__not_in'] = $sticky;
                $sticky_args['posts_per_page'] = -1;

                /**
                 * Get only post ids from query
                 *
                 * @link http://wordpress.stackexchange.com/questions/166029/get-post-ids-from-wp-query/166034#166034
                 */
                $sticky_args['fields'] = 'ids';

                $posts_query = new WP_Query( $sticky_args );
                $posts_ids = $posts_query->posts;

                if ( is_array( $sticky ) && is_array( $posts_ids ) ) {
                    $posts_ids = array_merge( $sticky, $posts_ids );
                }

                $args['query']['post__in'] = $posts_ids;

            }
            // END Deal with sticky posts

        } else {

            $args['query']['post__in'] = $args['post_in'];

        }

        // only images
        if ( $args['gallery_mode'] ) $args['query']['meta_query'] = array( array( 'key' => '_thumbnail_id' ) );

        $args['query']['order'] = $args['order'];

        if ( ! empty( $args['orderby'] ) ) {
            $orderby = explode( '|', $args['orderby'] );
            $args['query']['orderby'] = array_filter( $orderby );
        }

        $the_query = new WP_Query( $args['query'] );

        // The Loop
        if ( $the_query->have_posts() ) {

            $class_row = ( $args['no-gap'] ) ? ' no-gap-container' : '';
            $class_row .= ( $args['style'] == 'carousel' ) ? ' slick-carousel' : '';
            $ret .= '<div ';

            // MASONRY -> DEBUG
            // Add unique id to the article conteiner based on wrapper id
            // If I may want to include more masonry JavaScript plugins,
            // then I may need this later
            // if ( ! empty( $args['container_id'] ) ) {

            //     $ret .= 'id="' . $args['container_id'] . '-inner" ';

            // }
            // END MASONRY

            $ret .= 'class="row exopite-multifilter-items' . $class_row;
            $ret .= '" data-page="' . get_the_permalink() . '"';
            if ( $args['style'] == 'carousel' ) {
                $ret .= '" data-speed="' . get_the_permalink() . '"';
                $ret .= '" data-autoplay="' . get_the_permalink() . '"';
                $ret .= '" data-pause-on-hover="' . get_the_permalink() . '"';
            }
            $ret .= '>';

            $index = 0;

            if ( $args['except_lenght'] > 0 ) {
                ExopiteSettings::setValue( 'exopite_multifilter_except_lenght', $args['except_lenght'] );
                add_filter( 'excerpt_length', array( $this, 'excerpt_length' ), 999 );
            }

            // Override excerpt more (this will run before theme)
            if ( $args['except_more'] !== '' ) {
                add_filter( 'excerpt_more', function() use ( &$args ) {
                    if ( $args['except_more'] === 'none' ) {
                        return '';
                    }
                    return $args['except_more'];
                });
            }

            add_filter( 'get_the_excerpt', array( $this, 'excerpt' ) );

            while ( $the_query->have_posts() ) {
                $the_query->the_post();

                $post_password_required = post_password_required();

                $article_thumbnail = '';
                $article_content = '';
                $article_body = '';

                // Calculate Bootstrap cols
                //       xs sm md lg
                // 1/row: 1  1  1  1
                // 2/row: 1  1  2  2
                // 3/row: 1  2  2  3
                // 4/row: 1  2  3  4
                // 6/row: 2  3  4  6
                $bootstrap_column_lg = (int)$args['posts_per_row']; // valid: 1, 2, 3, 4, 6
                if ( $bootstrap_column_lg < 1 ) $bootstrap_column_lg = 1;
                if ( $bootstrap_column_lg == 5 ) $bootstrap_column_lg = 4;
                if ( $bootstrap_column_lg > 6 ) $bootstrap_column_lg = 6;

                $bootstrap_column_md = round( ( $bootstrap_column_lg / 4 ) * 3 );
                if ( $bootstrap_column_md == 5 ) $bootstrap_column_md = 4;

                $bootstrap_column_sm = ceil( ( $bootstrap_column_lg / 4 ) * 2 );
                $bootstrap_column_xs = ceil( $bootstrap_column_lg / 4 );

                if ( $args['posts_per_row'] > 1 ) {

                    $classes = 'multi-column';
                    $classes .= ' col-' . ( 12 / $bootstrap_column_xs );
                    $classes .= ' col-sm-' . ( 12 / $bootstrap_column_sm ) . ' col-md-' . (12 / $bootstrap_column_md ) . ' col-lg-' . ( 12 / $bootstrap_column_lg ) . ' multi-column';

                    $thumbnail_size = $args['thumbnail-size-multi-row'];

                } else {

                    $classes = 'col-12 single-column';
                    $thumbnail_size = $args['thumbnail-size-single-row'];

                }

                $classes .= ( $args['no-gap'] ) ? ' no-gap' : '';

                if ( $args['blog_layout'] == 'left' || ( $args['blog_layout'] == 'zigzag' && ( $index % 2 == 0 ) ) ) {
                    $image_class = 'left';
                } elseif ( $args['blog_layout'] == 'right' || ( $args['blog_layout'] == 'zigzag' && ( $index % 2 == 1 ) ) ) {
                    $image_class = 'right';
                } else {
                    $image_class = $args['blog_layout'];
                }

                $link = get_the_permalink();
                $target = '';

                if ( $args['target_override'] ) {

                    $content_to_check = new DOMDocument;
                    $content_to_check->loadHTML( get_the_content() );
                    $content_xpath = new DOMXPath( $content_to_check );

                    foreach ($content_xpath->query('//comment()') as $comment) {

                        if ( preg_match('/exopite-multifilter-external-link/', $comment->textContent ) ) {
                            $link = esc_attr( trim( str_replace( array( 'exopite-multifilter-external-link:', 'exopite-multifilter-external-link' ) , '', $comment->textContent ) ) );
                            $target = ' target="_blank"';
                            break;
                        }

                        if ( preg_match('/exopite-multifilter-internal-link/', $comment->textContent ) ) {
                            $link = esc_attr( trim( str_replace( array( 'exopite-multifilter-internal-link:', 'exopite-multifilter-internal-link' ) , '', $comment->textContent ) ) );
                            break;
                        }

                    }
                    unset( $content_to_check );
                }

                if ( $args['blog_layout'] != 'none' ) {
                    $classes .= ( $image_class == 'left' || $image_class == 'right' ) ? ' image-aside' : '';
                    $classes .= ( $args['blog_layout'] != 'none' ) ? ' has-post-thumbnail' : '';
                    $article_thumbnail = $this->get_thumbnail( get_the_id(), $image_class, $thumbnail_size, $args, $link, $target );
                }

                if ( ( $args['display_title'] || ( $args['except_lenght'] > 0 ) ) && ! $post_password_required ) {

                    $article_content = '<div class="entry-content-container">';
                    if ( count( $args['display_metas'] ) > 0 ) {
                        $no_link = ! empty( $target );
                        $article_content .= '<div class="entry-metas">' . $this->display_metas( $args, get_the_ID(), $no_link ) . '</div>';
                    }
                    if ( $args['display_title'] ) {
                        $article_content .= '<header class="entry-header">';
                        $article_content .= '<h2 class="entry-title"><a href="' . $link . '"' . $target . '>' . get_the_title() . '</a></h2>';

                        $article_content .= '</header>';
                    }
                    if ( $args['except_lenght'] > 0 || $args['except_lenght'] === 'full' ) {
                        $article_content .= '<div class="entry-content">';
                        $article_content .= ( $args['except_lenght'] === 'full' ) ? get_the_content() : get_the_excerpt();
                        $article_content .= '</div>';
                    }
                    $article_content .= '</div>';
                }

                /*
                 * Add ajax-added class to new elements, (css => visibility: hidden;)
                 * removed after items processed by masonry-desandro,
                 * for some reason this keep masonry-desandro align elements more accurate.
                 */
                if ( isset( $args['ajax'] ) && $args['ajax'] && $args['style'] == 'masonry' /*&& $args['masonry_type'] == 'masonry-desandro' */) {
                    $classes .= ' ajax-added';
                }

                $article_wrapper_begin = '<article class="' . $classes . '"><div class="article-container';
                $article_wrapper_begin .= ( $args['style'] === 'equal-height' ) ? ' equal-height' : '';
                $article_wrapper_begin .= '">';

                switch ( $image_class ) {
                    case 'left':
                    case 'top':
                        $article_body = $article_thumbnail . $article_content;
                        break;

                    case 'right':
                        $article_body = $article_content . $article_thumbnail;
                        break;
                }

                $article_wrapper_end = '</div></article>';

                $ret .= $article_wrapper_begin . $article_body . $article_wrapper_end;

                $index++;

            }

            $ret .= '</div>';

            $pagination_orientation = ( $args['pagination'] == 'pagination' ) ? 'text-right' : 'text-center';

            /* Restore original Post Data */
            wp_reset_postdata();
            wp_reset_query();

            if ( $args['pagination'] != 'none' ) {
                $ret .= '<div class="row exopite-multifilter-paginations ' . $pagination_orientation . '">';

                $ret .= $this->get_pagination( $args['page_id'], $the_query->max_num_pages, $args['paged'], $args['pagination'] );

                $ret .= '</div>';
            }

        } else {

            // no posts found
            if ( $args['pagination'] == 'infinite' && $args['paged'] > 1 )  {
                $ret .= '<div class="col-12 text-center no-posts-found">' . esc_attr__( 'Nothing more to load.', 'exopite-multifilter' ) . '</div>';
            } else {
                $ret .= '<div class="col-12 text-center no-posts-found">' . esc_attr__( 'No posts found', 'exopite-multifilter' ) . '</div>';
            }
        }

        return $ret;

    }

    /**
     * Display content from short code
     */
    function exopite_multifilter_shortcode( $atts ) {

        // Get atts and defaults.
        $args = shortcode_atts(
            array(
                'post_type'                 => 'post',
                'posts_per_page'            => 4,
                'posts_per_row'             => 2,                   // 1, 2, 3, 4, 6
                'display_title'             => false,
                'display_filter'            => true,
                'blog_layout'               => 'top',
                'no-gap'                    => false,
                'except_lenght'             => 0,
                'except_more'               => '',
                'pagination'                => 'pagination',        // pagination, readmore, infinite, none
                'multi_selectable'          => true,
                'thumbnail-size-single-row' => 'full',
                'thumbnail-size-multi-row'  => 'large',
                'taxonomies_terms'          => 'category',          // term1, term2, ...
                'update_paged'              => false,               // Do not update page in browser URL bar
                'display_page_number'       => false,               // Show page number between loads in infinite and readmore
                'paged'                     => 1,                   // Set start page number if not already paged
                'effect'                    => 'apollo',
                'search'                    => '',                  // search
                'store_session'             => false,               // store session
                'load_from_url'             => false,               // load from url
                'in_all_taxnomies'          => true,                // positive or negative selection
                'random'                    => false,               // randomize (pagination and search are off)
                // [date, author, commentcount, taxonomy, last-modified] comma separated list
                'display_metas'             => '',
                // only if display_metas has 'taxonomy', taxonomy name to display
                'display_metas_taxonomies'  => '',                  // comma searated list
                'container_id'              => '',
                'container_classes'         => '',                  // comma searated list
                'style'                     => '',                  // empty, equal-height, masonry, carousel
                'masonry_type'              => 'waterfall-kudago',  // waterfall-kudago, masonry-desandro
                'col_min_width'             => 340,                 // (int) only for waterfall-kudago
                'gallery_mode'              => false,               // on thumbnail click, open images self insted of the link of the post/page ("single")
                'archive_mode'              => false,               // deal with archives
                'ajax_mode'                 => true,                // Turn AJAX mode on or off
                'target_override'           => false,               // Override target location. Use <!-- exopite-multifilter-external-link: link or image --> insted of the 'the_perlamink'
                'post_in'                   => '',
                'post_not_in'               => '',
                'date_from'                 => '',                  // iso date: 2001-12-31
                'date_to'                   => '',                  // iso date: 2002-12-31
                'order'                     => 'DESC',
                'orderby'                   => '',
                /*
                 * Slick carousel settings
                 * http://kenwheeler.github.io/slick/
                 */
                'autoplay'                  => true,                // for slick carousel
                'arrows'                    => true,
                'autoplay_speed'            => 3000,
                'infinite'                  => true,
                'speed'                     => 1000,
                'pause_on_hover'            => true,
                'dots'                      => true,
                'adaptive_height'           => false,
                'mobile_first'              => false,
                'slides_per_row'            => 1,
                'slides_to_show'            => 1,
                'slides_to_scroll'          => 1,
                'use_transform'             => true,
                'video'                     => '',
                'video-args'                => 'controls muted',
                'meta_key'                  => '',
                'meta_value'                => '',
            ),
            $atts
        );



        // Add page id and paged.
        $args['page_id'] = get_the_ID();

        // START Deal with archives
        if ( $args['archive_mode'] && ( is_home() || is_archive() ) ) {
            global $wp_query;

            $args['taxonomies_terms'] = '';
            $args['random'] = false;
            $args['search'] = false;
            $args['display_filter'] = false;
            $args['posts_per_page'] = get_option( 'posts_per_page' );

            $args['archive_mode'] = array();
            if ( is_home() ) {
                // https://kellenmace.com/get-blog-posts-page-url-permalink-wordpress/
                $args['page_id'] = ( 'page' === get_option( 'show_on_front' ) ) ? get_permalink( get_option( 'page_for_posts' ) ) : get_home_url();
            }
            if ( is_tag() ) {
                $args['archive_mode']['tag'] = $wp_query->query_vars['tag'];
                $tag = get_term_by('slug', $wp_query->query_vars['tag'], 'post_tag');
                $args['page_id'] = get_tag_link( $tag->term_id );
            }
            if ( is_category() ) {
                $args['archive_mode']['category_name'] = $wp_query->query_vars['category_name'];
                $args['page_id'] = get_category_link( get_category_by_slug( $wp_query->query_vars['category_name'] ) );
            }
            if ( is_date() ) {
                if ( is_year() ) {
                    $args['archive_mode']['year'] = $wp_query->query_vars['year'];
                    $args['page_id'] = get_year_link( $wp_query->query_vars['year'] );
                }
                if ( is_month() ) {
                    $args['archive_mode']['year'] = $wp_query->query_vars['year'];
                    $args['archive_mode']['monthnum'] = $wp_query->query_vars['monthnum'];
                    $args['page_id'] = get_month_link( $wp_query->query_vars['year'], $wp_query->query_vars['monthnum'] );
                }
                if ( is_day() ) {
                    $args['archive_mode']['year'] = $wp_query->query_vars['year'];
                    $args['archive_mode']['monthnum'] = $wp_query->query_vars['monthnum'];
                    $args['archive_mode']['day'] = $wp_query->query_vars['day'];
                    $args['page_id'] = get_day_link( $wp_query->query_vars['year'], $wp_query->query_vars['monthnum'], $wp_query->query_vars['day'] );
                }
            }
            if ( is_author() ) {
                $args['archive_mode']['author_name'] = $wp_query->query_vars['author_name'];
                $args['page_id'] = get_author_posts_url( $wp_query->query_vars['author'] );
            }

        }
        // END Deal with archives

        /*
         * Enqueue scripts and styles only if shortcode is present
         * https://wordpress.stackexchange.com/questions/165754/enqueue-scripts-styles-when-shortcode-is-present/191512#191512
         *
         * Issues:
         * - css load in the footer
         * - check with has_shortcode  has performace issues:
         *   https://wordpress.stackexchange.com/questions/165754/enqueue-scripts-styles-when-shortcode-is-present/165759#165759
         */
        wp_enqueue_style( $this->plugin_name );
        wp_enqueue_style( 'exopite-effects' );

        // ToDo: sanitize data

        if ( $args['style'] == 'carousel' ) {
            $args['ajax_mode']           = false;
            $args['display_filter']      = false;
            $args['pagination']          = 'none';
            $args['search']              = '';
            $args['update_paged']        = false;
            $args['display_page_number'] = false;
            $args['store_session']       = false;
            $args['load_from_url']       = false;
            $args['posts_per_row']       = '1';
            $args['no-gap']              = true;
        }

        if ( $args['ajax_mode'] ) {
            $args['ajax_nonce'] = wp_create_nonce( 'exopite-multifilter-nonce' );
        } elseif ( $args['style'] != 'carousel' ) {
            $args['pagination'] = 'pagination';
        }

        // Do not display filter if it is search
        if ( $args['search'] !== '' || ! $args['ajax_mode'] ) $args['display_filter'] = false;

        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : get_query_var( 'page' );

        $args['paged'] = ( $paged !== 0 ) ? $paged : $args['paged'];

        if ( ! empty( $args['display_metas'] ) ) {
            $args['display_metas'] = explode( ',', preg_replace( '/\s+/', '', $args['display_metas'] ) );

            if ( isset( $args['display_metas'] ) && in_array( 'taxonomy', $args['display_metas'] ) && ! empty( $args['display_metas_taxonomies'] ) ) {
                $args['display_metas_taxonomies'] = explode( ',', preg_replace( '/\s+/', '', $args['display_metas_taxonomies'] ) );
            }
        }

        /*
         * Process terms for taxonomies (if exist)
         * taxonomies_terms="exopite-portfolio-category(suspendisse|tempus), exopite-portfolio-tag"
         */
        // create an array from taxonomies_terms devided by comma.
        $args['taxonomies_terms'] = explode( ',', preg_replace( '/\s+/', '', $args['taxonomies_terms'] ) );

        foreach ( $args['taxonomies_terms'] as $taxonomy ) {
            if ( strpos( $taxonomy, '(') !== false ) {
                $args['display_filter'] = false;
                preg_match('/\((.*?)\)/s', $taxonomy, $matches, PREG_OFFSET_CAPTURE);
                $terms = explode( '|', $matches[1][0] );
                $taxonomy_name = substr( $taxonomy, 0, strpos( $taxonomy, '(' ) );
                $args['taxonomies'][$taxonomy_name] = $terms;
            } else {
                $args['taxonomies'][$taxonomy] = '';
            }

        }

        $args['taxonomies_terms'] = $args['taxonomies'];

        /*
         * - WRAPPER
         */
        $regex_css_identifiers_name = '/^[_A-Za-z][A-Za-z0-9_-]*$/';
        $ret = '<div ';

        // Check wrapper HTML (CSS) ID
        // https://stackoverflow.com/questions/5523974/validate-a-id-name-tokens-in-html-using-php/5523988#5523988
        if ( ! empty( $args['container_id'] ) && preg_match( $regex_css_identifiers_name, $args['container_id'] ) ) {

            $ret .= 'id="' . $args['container_id'] . '" ';

        }

        $ret .= 'class="exopite-multifilter-container';

        // MASONRY
        // If element has id and style is masonry then enqueue masonry JavaScript
        // and add masonry class
        if ( ! empty( $args['container_id'] ) && $args['style'] == 'masonry' ) {

            $ret .= ' masonry';

            wp_enqueue_script( $args['masonry_type'] );

        } else {

            unset( $args['masonry_type'] );

        }
        // END MASONRY

        // Enqueue plugin main JavaScript file
        if ( $args['ajax_mode'] ) {

            wp_enqueue_script( $this->plugin_name );

        } else {

            $ret .= ' ajax-disabled';

        }

        // Check wrapper HTML (CSS) Classes
        if ( ! empty( $args['container_classes'] ) ) {

            // Remove null, 0, '' or false from array.
            // https://stackoverflow.com/questions/8328983/check-whether-an-array-is-empty/8329005#8329005
            //$container_classes = array_filter( explode( ',', preg_replace( '/\s+/', '', $args['container_classes'] ) ) );

            $container_classes = explode( ',', preg_replace( '/\s+/', '', $args['container_classes'] ) );

            // Get only matched (for CSS syntax) class names from given classes
            // https://stackoverflow.com/questions/8627334/how-to-search-in-an-array-with-preg-match/8627354#8627354
            $container_classes_array = preg_grep( $regex_css_identifiers_name, $container_classes );

            if ( is_array( $container_classes_array ) && ! empty( $container_classes_array ) ) {
                $ret .= ' ' . implode( ' ', $container_classes_array );
            }

        }

        $ret .= '" ';


        if ( $args['style'] == 'carousel' ) {

            wp_enqueue_script( 'slick' );
            wp_enqueue_style( 'slick' );
            wp_enqueue_style( 'slick-theme' );

            $carousel_args = array(
                'autoplay'                  => $args['autoplay'],
                'arrows'                    => $args['arrows'],
                'autoplay_speed'            => $args['autoplay_speed'],
                'infinite'                  => $args['infinite'],
                'speed'                     => $args['speed'],
                'pause_on_hover'            => $args['pause_on_hover'],
                'dots'                      => $args['dots'],
                'adaptive_height'           => $args['adaptive_height'],
                'mobile_first'              => $args['mobile_first'],
                'slides_per_row'            => $args['slides_per_row'],
                'slides_to_show'            => $args['slides_to_show'],
                'slides_to_scroll'          => $args['slides_to_scroll'],
                'use_transform'             => $args['use_transform'],
            );

            $ret .= 'data-carousel=\'' . htmlentities( json_encode( $carousel_args ), ENT_QUOTES, 'UTF-8' ) . '\'';

        }

        /**
         * Insert args array to data-ajax for javascript as JSON.
         *
         * http://stackoverflow.com/questions/7322682/best-way-to-store-json-in-an-html-attribute
         */
        if ( $args['ajax_mode'] ) $ret .= 'data-ajax=\'' . htmlentities( json_encode( $args ), ENT_QUOTES, 'UTF-8' ) . '\'';

        $ret .= '>';

        // Display filters
        if ( $args['display_filter'] && $args['display_filter'] !== 'false' && ! $args['random'] ) {

            $ret .= '<div class="exopite-multifilter-filter-wrapper">';
            $ret .= '<div class="exopite-multifilter-filter-reset-search text-right"><span class="exopite-multifilter-filter-reset">' . esc_attr__( 'Reset all', 'exopite-multifilter' ) . '</span>';

            if ( $args['search'] == '' ) $ret .= '<form role="search" method="get" class="exopite-multifilter-search" action="' . esc_url( home_url( '/' ) ) . '"><div class="form-group"><input type="text" class="form-group" placeholder="' . esc_attr__( 'Search…', 'exopite-multifilter' ) . '" name="s" id="" value="' . esc_attr( get_search_query() ) . '" /><span class="form-group-btn"><button class="btn btn-default" type="submit" id="" value="Search"><i class="fa fa-search" aria-hidden="true"></i></button></span></div><!-- /input-group --></form>';

            $ret .= '</div>';
            $ret .= $this->get_filters( $args['post_type'], $args['taxonomies_terms'], true );
            $ret .= '</div>';
        }

        $ret .= $this->get_articles( $args );
        $ret .= '<div class="exopite-multifilter-now-loading"><div class="uil-ripple-css"><div></div><div></div></div></div>';
        $ret .= '</div>';

        return $ret;
    }

    /**
     * AJAX function to query articles
     */
    function exopite_multifilter_get_posts_callback() {

        $ret = '';

        $AJAX = str_replace( '\"', '"', $_POST["json"] );
        $AJAX = json_decode( $AJAX, true );

        if ( wp_verify_nonce( $AJAX['ajax_nonce'], 'exopite-multifilter-nonce' ) ) {

            $AJAX['paged'] = $AJAX['paged'] ? $AJAX['paged'] : 1;

            if ( $AJAX['style'] == 'masonry' /*&& $AJAX['masonry_type'] == 'masonry-desandro'*/ ) $AJAX['ajax'] = true;

            $ret .= $this->get_articles( $AJAX );

            die( $ret );
        }


    }

}
